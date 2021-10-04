package me.hintik.simulator;

import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.FontMetrics;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.RenderingHints;
import java.awt.Stroke;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.geom.AffineTransform;
import java.awt.geom.Point2D;
import java.awt.geom.Rectangle2D;
import java.awt.geom.RoundRectangle2D;
import java.awt.image.BufferedImage;

import javax.swing.JPanel;

import me.hintik.simulator.charts.GraphArea;
import me.hintik.simulator.charts.GraphPoint;
import waterflowsim.Cell;
import waterflowsim.Simulator;
import waterflowsim.Vector2D;
import waterflowsim.WaterSourceUpdater;

/**
 * Trida se stara o vytvoreni instance JPanelu, na kterou se vykresluje 
 * simulace s dalsimi potrebnymi ukazately.
 * @author hintik
 *
 */
public class DrawingPanel extends JPanel implements MouseListener, MouseMotionListener{
	private static final long serialVersionUID = 1L;

	/**
	 * Cas, ktery ubehl od startu programu 
	 */
	public double time;
	
	/**
	 * Vektor velikosti jedne bunky
	 */
	private Vector2D<Double> delta;
	
	/**
	 * Vektor velikosti modelu
	 */
	public Vector2D<Double> modelDimension;
	
	/**
	 * Potrebny posun a rotace platna pro korektni vykresleni
	 */
	public Vector2D<Double> translation, scale;

	/** Pocet barev pro vykresleni terenu */
	public static final int TERRAIN_COLORS = 8;
	
	/** Extremy vysky terenu*/
	public double minTerrainLevel = Integer.MAX_VALUE, maxTerrainLevel = Integer.MIN_VALUE;

	/** Barva vodni plochy */
	public static final Color WATER_COLOR = new Color(51, 204, 255, 255);
	/** Barva sipky a popisku */
	public static final Color ARROW_COLOR = new Color(31, 64, 104);
	
	/** Hodnota priblizeni */
	public double zoom = 1;
	/** Hodnoty posunu vizualizace */
	public int posunX = 0, posunY = 0;
	
	/** Posledni kliknute misto */
	private Point2D mouseClickedPoint;
	/** Posledni misto, kde se mys nachazela */
	private Point2D mouseMovedPoint;

	/** Aktualne zvoleny nastroj */
	public byte brush = 0;
	
	/**
	 * Konstruktor nastavujici vychozi velikost platna
	 */
	public DrawingPanel() {
		this.setPreferredSize(new Dimension(600, 480));
		init();
		
		addMouseListener(this);
		addMouseMotionListener(this); 
	}
	
	/**
	 * Metoda pro kresleni na platno
	 * Metoda zajisti pripraveni Graphics2D, pocatecni inicializaci stavovych
	 * promennych pro korektni vykresleni, vykresleni simulace
	 *  
	 */
	public void paintComponent(Graphics g) {
		super.paintComponent(g);
		
		Graphics2D g2 = (Graphics2D) g;
		
		g2.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON); //Zapnuti antialiasingu

		
		//g2.drawString(String.format("time = %.2f s", time), 20, 20); //Vypise cas od zahajeni programu
		
		computeModel2WindowTransformation(this.getWidth(), this.getHeight()); //Vypocteni hodnot pro korektni transformaci modelu do okna
		
		drawWaterFlowState(g2); //Vykresleni vizualizace
		
		drawBrushOutline(g2);
		
	}
	
	/**
	 * Metoda provede prvotni inicializaci pomocnych promennych
	 */
	private void init() {
		for(int i = 0; i < Simulator.getData().length; i++) {
			Cell cell = Simulator.getData()[i];
			
			if(cell.getTerrainLevel() > maxTerrainLevel) maxTerrainLevel = cell.getTerrainLevel();
			if(cell.getTerrainLevel() < minTerrainLevel) minTerrainLevel = cell.getTerrainLevel();
		}
		
	}
	
	/**
	 * Metoda vykresli celkovy aktualni stav krajiny.
	 * @param g
	 */
	private void drawWaterFlowState(Graphics2D g) {
		//Vykresleni terenu
		drawTerrain(g);
		
		//Vykresleni vodnich ploch s popisky	
		drawWaterLayer(g);
	}
	
	/**
	 * Metoda vykresli teren podle ziskanych nadmorskych vysek.
	 * @param g
	 */
	private void drawTerrain(Graphics2D g) {
		BufferedImage img = new BufferedImage(
				Simulator.getDimension().x,
				Simulator.getDimension().y,
				BufferedImage.TYPE_4BYTE_ABGR
			);
			
			int[] rgbArray = new int[Simulator.getDimension().x*Simulator.getDimension().y]; //Vytvoreni pole pro bitmapovy obrazek
			
			//Pole prochazejici vsechny bunky dat a testujici, zda se v dane bunce nachazi voda 
			for(int i = 0; i < rgbArray.length; i++) {
				Cell cell = Simulator.getData()[i];
				
				rgbArray[i] = RGBAToInteger(getColorByHeight(cell.getTerrainLevel()));
			}
			
			//Vlozeni pole barevych informaci do bitmapoveho obrazku
			img.setRGB(
				0,
				0,
				Simulator.getDimension().x,
				Simulator.getDimension().y,
				rgbArray,
				0,
				Simulator.getDimension().x
			);
			
			
			AffineTransform puv_transform = g.getTransform(); //Ulozeni aktualni transformace pro pozdejsi obnoveni

			//Ziskani umisteni reprezentace modelu v bode [0;0] (levy horni bod) - pro zarovnani na stred
			Point2D point = model2window(new Point2D.Double(posunX, posunY));
			g.translate(point.getX(), point.getY());
			g.scale(scale.x, scale.y); //Zmena meritka pro vyplneni platna
			
			if(Main.menuBar.MIShowTerrain.isSelected()) {
				g.drawImage(img, null, 0, 0); //Vykresleni obrazku na platno
			}
			
			g.setTransform(puv_transform); //Obnoveni puvodni transformace

		
	}
	
	/**
	 * Metoda ze ziskanych dat vytvori bitmapovy obrazek obsahujici lokaci vod. Obrazek se dle potreby
	 * posune a zmeni meritko v zavislosti na velikosti jedne bunky a velikosti obrazovky.
	 * Obrazek se vykresli na platno a obnovi puvodni nastaveni transformace.
	 * Vykresli se vodni zdroje.
	 * @param g
	 */
	private void drawWaterLayer(Graphics2D g) {
		
		//Vytvoreni bitmapoveho obrazku
		BufferedImage img = new BufferedImage(
			Simulator.getDimension().x,
			Simulator.getDimension().y,
			BufferedImage.TYPE_4BYTE_ABGR
		);
		
		int[] rgbArray = new int[Simulator.getDimension().x*Simulator.getDimension().y]; //Vytvoreni pole pro bitmapovy obrazek
		
		int color_water = RGBAToInteger(WATER_COLOR); //Vypocteni barvy vody
		
		//Pole prochazejici vsechny bunky dat a testujici, zda se v dane bunce nachazi voda 
		for(int i = 0; i < rgbArray.length; i++) {
			Cell cell = Simulator.getData()[i];
			
			if(!cell.isDry()) {
				rgbArray[i] = color_water;
			}
		}
		
		//Vlozeni pole barevych informaci do bitmapoveho obrazku
		img.setRGB(
			0,
			0,
			Simulator.getDimension().x,
			Simulator.getDimension().y,
			rgbArray,
			0,
			Simulator.getDimension().x
		);
		
		
		AffineTransform puv_transform = g.getTransform(); //Ulozeni aktualni transformace pro pozdejsi obnoveni

		//Ziskani umisteni reprezentace modelu v bode [0;0] (levy horni bod) - pro zarovnani na stred
		Point2D point = model2window(new Point2D.Double(posunX, posunY));
		g.translate(point.getX(), point.getY());
		g.scale(scale.x, scale.y); //Zmena meritka pro vyplneni platna
		
		if(Main.menuBar.MIShowWater.isSelected()) {
			g.drawImage(img, null, 0, 0); //Vykresleni obrazku na platno
		}
		
		g.setTransform(puv_transform); //Obnoveni puvodni transformace

		if(Main.menuBar.MIShowMarkers.isSelected()) {
			drawWaterSources(g);
		}
		
		
	}
	
	/**
	 * Metoda vykresli na platno vsechny vodni zdroje s reprezentaci dodatecnych informaci.
	 * @param g
	 */
	private void drawWaterSources(Graphics2D g) {
		
		//Cyklus prochazi vsechny prvky pole obsahujici zroje vodnich toku 
		for(WaterSourceUpdater wsu : Simulator.getWaterSources()) {
			Cell source = Simulator.getData()[wsu.getIndex()]; //ziskani bunky zdroje
			
			int x = wsu.getIndex()%(Simulator.getDimension().x); //vypocteni x-ove souradnice zdroje vody v modelu
			int y = (wsu.getIndex()-x)/Simulator.getDimension().x; //vypocteni y-ove souradnice zdroje vody v modelu

			Point2D point = new Point2D.Double((double) x+posunX, (double) y+posunY);
			point = model2window(point); //ziskani pozice zdroje v souradnicich panelu
			
			/*
			 * Vykresleni bodu zdroje (Pro testovaci ucely)
				g.setColor(new Color(0, 255, 0));
				g.fillOval( (int) point.getX()-5, (int) point.getY()-5, 10, 10);
			*/
			
			Vector2D<Double> smer_toku = new Vector2D<Double>(-source.getGradient().x, source.getGradient().y); //vypocitani smeru toku vody pro vykresleni do panelu 
			
			drawWaterFlowLabel(point, smer_toku, wsu.getName(), g); //vykresleni sipky a nazvu 
			
		}
		
		
	}

	/**
	 * Metoda vykresli jednotlivy popisek vodniho zdroje.
	 * @param position pozice zdroje
	 * @param dirFlow smer gradientu terenu
	 * @param name nazev vodniho toku
	 * @param g graficky kontext
	 */
	private void drawWaterFlowLabel(Point2D position, Vector2D<Double> dirFlow, String name, Graphics2D g) {
		final double SIPKA_SIZE = 50; //Velikost sipky v pixelech
		double POSUN_X = 0; //Velikost posunu sipky po ose X v pixelech
		double POSUN_Y = 0; //Velikost posunu sipky po ose Y v pixelech
		final int TEXT_SIZE = (int) (SIPKA_SIZE/3); //Velikost pisma popisku
		final double POSUN_TEXTU = -15; //Velikost posunu textu
		
		/* Vypocet realneho smeru sipky (zavislost na velikosti bunek), protoze na sipku se
		 * nevztahuje scale, ktery je uplatnen u roztahovani bitmapoveho obrazku (aby popisek a sipka nebyly deformovany)
		 */
		dirFlow.x = dirFlow.x.doubleValue()*(Math.abs(delta.x.doubleValue()));
		dirFlow.y = dirFlow.y.doubleValue()*Math.abs(delta.y.doubleValue());
		
		//Vypocitani pozice sipky (pozice zroje + posun)
		
			if(translation.x < SIPKA_SIZE && this.getWidth() > 200) {
				if(dirFlow.x <= 0 && position.getX() < translation.x + SIPKA_SIZE) {
					POSUN_X += SIPKA_SIZE/2 - POSUN_X;
				} else if(dirFlow.x >= 0 && position.getX() > this.getWidth()-translation.x-SIPKA_SIZE) {
					POSUN_X += -SIPKA_SIZE/2 - POSUN_X;
				}
				
				if(position.getX() < translation.x + SIPKA_SIZE) {
					POSUN_X += SIPKA_SIZE/2;
				} else if(position.getX() > this.getWidth()-translation.x-SIPKA_SIZE) {
					POSUN_X += SIPKA_SIZE/2;
				}
				
			}
			
			if(translation.y < SIPKA_SIZE && this.getHeight() > 200) {
				if(dirFlow.y >= 0 && position.getY() < translation.y + SIPKA_SIZE) {
					POSUN_Y += SIPKA_SIZE/2 - POSUN_Y;
				} else if(dirFlow.y <= 0 && position.getY() > this.getHeight()-translation.y-SIPKA_SIZE) {
					POSUN_Y += -SIPKA_SIZE/2 - POSUN_Y;
				}
				
				if(position.getY() < translation.y + SIPKA_SIZE) {
					POSUN_Y += SIPKA_SIZE/2;
				} else if(position.getY() > this.getHeight()-translation.y-SIPKA_SIZE) {
					POSUN_Y += -SIPKA_SIZE/2;
				}
				
			}
		
		
		position = new Point2D.Double(position.getX()+POSUN_X, position.getY()+POSUN_Y); 
		
		AffineTransform puv_transform = g.getTransform();
		
		/*
		 * Vykresleni sipky 
		 */
		
		g.translate(position.getX(), position.getY()); //pozicovani stredu sour. systemu platna pozici zacatku sipky
		g.rotate(Math.atan2(-dirFlow.y.doubleValue(), dirFlow.x.doubleValue())); //rotace sipky na pozadovany smer
		
		g.setColor(ARROW_COLOR);
		drawArrow(g, SIPKA_SIZE); //vykresleni sipky
		
		/*
		 * Vykresleni nazvu vodniho toku
		 */
		
		
		Font font = new Font("Arial", Font.BOLD, TEXT_SIZE);
		g.setFont(font);
		FontMetrics fm = g.getFontMetrics(); //FontMetrics pro zjisteni skutecne sirky textu
		
		double scale = 1;
		
		if(fm.stringWidth(name) > SIPKA_SIZE) {
			scale = (SIPKA_SIZE)/fm.stringWidth(name);
		}
		
		double posun = 0;
		//Podminka zajistujici spravne vykresleni textu (nemuze byt vzhuru nohama)
		if(dirFlow.x < 0) {
			g.rotate(Math.PI);
			posun -= SIPKA_SIZE;
		} else {
			posun = 0;
		}
		
		
		//Vykresleni popisku, ktery je zarovnan na stred sipky
		g.translate(posun, 0);
		g.scale(scale, scale);
		
		if(scale >= 1) {
			g.translate((SIPKA_SIZE-fm.stringWidth(name))/2, 0);
		}
		
		g.setColor(Color.white);
		
		//g.fillRoundRect(-5, POSUN_TEXTU-fm.getHeight(), fm.stringWidth(name)+15, fm.getHeight()+10,15,15);
		
		
		Rectangle2D bounds = fm.getStringBounds(name, g);
		
		RoundRectangle2D rect = new RoundRectangle2D.Double(-2.5 + bounds.getX(), -2.5+POSUN_TEXTU + bounds.getY(), bounds.getBounds().width+5, bounds.getBounds().height+5, 15, 15);
		
		g.fill(rect);
		
		g.setColor(ARROW_COLOR);
		g.drawString(name, 0, (int) POSUN_TEXTU);
		
		
		g.setTransform(puv_transform);
		
	
	}
	
	/**
	 * Metoda vypocita a inicializuje stavove promenne pro velikost modelu a velikost bunky;
	 */
	void computeModelDimensions() {
		this.delta = new Vector2D<Double>(Math.abs(Simulator.getDelta().x), Math.abs(Simulator.getDelta().y));
		
		Vector2D<Integer> dimension = Simulator.getDimension();
		
		modelDimension = new Vector2D<Double>(dimension.x*delta.x, dimension.y*delta.y); //Nasobim velikosti bunek pro kompenzaci nectvercovych bunek a zjisteni celkovych realnych rozmeru.
	}
	
	/**
	 * Metoda vypocitava a inicializuje stavove promenne pro vhodnou transformaci do existujiciho okna a korektni
	 * vykresleni. 
	 * @param width
	 * @param height
	 */
	void computeModel2WindowTransformation(int width, int height) {
		computeModelDimensions(); //Vypocteni velikosti modelu
		
		//Vypocteni vhodnou zmenu merizka
		double scaleX = (double)width/(modelDimension.x);
		double scaleY = (double)height/(modelDimension.y);
		double scale = Math.min(scaleX, scaleY)*zoom;
		this.scale = new Vector2D<Double>(scale*this.delta.x, scale*this.delta.y);
		
		//Vypocteni posunu modelu na stred panelu
		double translateX = (((double) width - modelDimension.x*scale)/2);
		double translateY = (((double) height - modelDimension.y*scale)/2);
		
		//Vlozeni translace do vektoru
		this.translation = new Vector2D<Double>(translateX, translateY);
		
	}
	
	/**
	 * Metoda prepocita souradnice bodu modelu do souradnicoveho systemu panelu
	 * @param m bod v modelovem souradnicovem systemu
	 * @return
	 */
	Point2D model2window(Point2D m) {
		double x = translation.x + (m.getX()*scale.x);
		double y = translation.y + (m.getY()*scale.y);
		return new Point2D.Double(x, y);
	}
	
	Point2D window2model(Point2D m) {
		Point2D point = model2window(new Point2D.Double(posunX, posunY));
		double x = (m.getX()/scale.x) - point.getX()/scale.x;
		double y = (m.getY()/scale.y) - point.getY()/scale.y;
		
		return new Point2D.Double(x, y);
	}
	
	/**
	 * Metoda vykresli vodorovnou (pri standardni rotaci) sipku o zadane velikosti
	 * @param g graficky kontext
	 * @param size velikost sipky v pixelech
	 */
	private void drawArrow(Graphics2D g, double size) {
		final double SIPKA_DELKA = size/4; //Delka zakonceni
		final double SIPKA_UHEL = size/10; //Velikost roztahnuti
		final int STROKE_SIZE = (int) (size/20); //Tloustka cary 
		Stroke puv_stroke = g.getStroke();
		double Ax = size;		
		double Bx = Ax - SIPKA_DELKA;
		
		int[] valuesX = new int[] {(int) Ax, (int) Bx, (int) Bx};
		int[] valuesY = new int[] {0, (int) SIPKA_UHEL, (int) -SIPKA_UHEL};
		
		g.setStroke(new BasicStroke(STROKE_SIZE));
		g.fillPolygon(valuesX, valuesY, 3);
		g.drawLine(0, 0, (int) Bx, 0);
		
		g.setStroke(puv_stroke);
	}
	
	/**
	 * Metoda prevadi jednotlive barevne slozky do celociselneho dekadickeho tvaru
	 * @param red cervena
	 * @param green zelena
	 * @param blue modra
	 * @param alpha pruhlednost
	 * @return reprezentaci barvy v celociselnem dekadickem tvaru
	 */
	private int RGBAToInteger(int red, int green, int blue, int alpha) {
		return (alpha << 24) | (red << 16) | (green << 8) | blue;
	}
	
	/**
	 * Metoda prevadi jednotlive barevne slozky do celociselneho dekadickeho tvaru
	 * @param c instance barvy
	 * @return reprezentaci barvy v celociselnem dekadickem tvaru
	 */
	private int RGBAToInteger(Color c) {
		return RGBAToInteger(c.getRed(), c.getGreen(), c.getBlue(), c.getAlpha());
	}
		
	/**
	 * Metoda vypocita barvu bodu na zaklade vysky terenu v bode a minimalni a maximalni 
	 * vysky ve scenari  
	 * @param height vyska bodu v terenu
	 * @return prislusna barevna interpretace vysky v terenu
	 */
	public Color getColorByHeight(double height) {
		double size = maxTerrainLevel - minTerrainLevel;
		height = height - minTerrainLevel;
		double value = (height/size)*255;
		
		double step = 255/(TERRAIN_COLORS);
		
		int color = (int) (value/step);
		
		return getColorByValue((int) (color * step));
		
	}
	
	/**
	 * Metoda vrati barvu z prechodu
	 * @param value postup v prechodu od 0 do 255
	 * @return barvu nachazejici se na uvedenem miste
	 */
	public Color getColorByValue(int value) {
		int R = (0 + value*2);
		int G = (511 - value*2);
		int B = 0;
		
		if(R > 255) R = 255;
		if(G > 255) G = 255;
		if(B > 255) B = 255;
		if(R < 0) R = 0;
		if(G < 0) G = 0;
		if(B < 0) B = 0;
		

		return new Color(R, G, B);
	}
	
	/**
	 * Metoda vykresluje hranice oznacovane casti.
	 * @param g2 graficky kontext
	 */
	private void drawBrushOutline(Graphics2D g2) {
		if(mouseClickedPoint != null && mouseMovedPoint != null && brush == 0) {
			int minX, minY, maxX, maxY;
			
			if(mouseClickedPoint.getX() > mouseMovedPoint.getX()) {
				maxX = (int) mouseClickedPoint.getX();
				minX = (int) mouseMovedPoint.getX();
			} else {
				maxX = (int) mouseMovedPoint.getX();
				minX = (int) mouseClickedPoint.getX();
			}
			
			if(mouseClickedPoint.getY() > mouseMovedPoint.getY()) {
				maxY = (int) mouseClickedPoint.getY();
				minY = (int) mouseMovedPoint.getY();
			} else {
				maxY = (int) mouseMovedPoint.getY();
				minY = (int) mouseClickedPoint.getY();
			}
			
			g2.drawRect(minX, minY, maxX-minX, maxY-minY);
		}
	}
	
	/**
	 * Metoda zajistuje prevod souradnic uzemi pro zvoleni spravnych dat pro
	 * vykresleni grafu. Nakonec v pripade validnich dat se vytvori novy graf.
	 * @param point1 souradnice stisku tlacitka mysi
	 * @param point2 souradnice uvolneni tlacitka mysi
	 */
	private void areaSelected(Point2D point1, Point2D point2) {

		point1 = window2model(point1);
		point2 = window2model(point2);
		
		int x1 = (int) point1.getX();
		int y1 = (int) point1.getY();
		int x2 = (int) point2.getX();
		int y2 = (int) point2.getY();
		
		int xx1 = Math.min(x1, x2);
		int yy1 = Math.min(y1, y2);
		int xx2 = Math.max(x1, x2);
		int yy2 = Math.max(y1, y2);
		
		if(point1.getX() < 0)
			x1 = 0;
		else if(point1.getX() >= Simulator.getDimension().x)
			x1 = Simulator.getDimension().x - 1;
	
		if(point2.getX() < 0)
			x2 = 0;
		else if(point2.getX() >= Simulator.getDimension().x)
			x2 = Simulator.getDimension().x - 1;

		if(point1.getY() < 0)
			y1 = 0;
		else if(point1.getY() >= Simulator.getDimension().y)
			y1 = Simulator.getDimension().y - 1;
	
		if(point2.getY() < 0)
			y2 = 0;
		else if(point2.getY() >= Simulator.getDimension().y)
			y2 = Simulator.getDimension().y - 1;
		
		int x = Math.min(x1, x2);
		int y = Math.min(y1, y2);
		
		int w = Math.max(x1, x2) - x + 1;
		int h = Math.max(y1, y2) - y + 1;
		
		if((xx1 < 0 && xx2 < 0) || (xx1 > Simulator.getDimension().x && xx2 > Simulator.getDimension().x))  {
			return;
		}
		if((yy1 < 0 && yy2 < 0) || (yy1 > Simulator.getDimension().y && yy2 > Simulator.getDimension().y))  {
			return;
		}
		
		
		//System.out.println("Uzemi vybrano");
		//System.out.println("[" + x + ";" + y + "]" + "W = " + w + " H = " + h);
		
		int bod = Main.getArrayIndex(x, y);
		
		Main.graphs.add(new GraphArea(bod, w, h));
	}
	
	

	/**
	 * Metoda posunu mysi
	 */
	public void mouseMoved(MouseEvent e) {}
	
	/**
	 * Metoda presunu pomoci mysi
	 */
	@Override
	public void mouseDragged(MouseEvent e) {

		Main.drawingPanel.repaint();
		mouseMovedPoint = e.getPoint();
		
		if(brush == 1 || e.getModifiers() == MouseEvent.BUTTON3_MASK) {
			try {
				posunX += (mouseMovedPoint.getX()-mouseClickedPoint.getX())/(scale.x);
				posunY += (mouseMovedPoint.getY()-mouseClickedPoint.getY())/(scale.x);
				mouseClickedPoint = e.getPoint();
			} catch(NullPointerException ex) {
				mouseClickedPoint = e.getPoint();
			}
		}
	}
	
	/**
	 * Metoda udalosti uvolneni tlacitka mysi
	 */
	@Override
	public void mouseReleased(MouseEvent e) {
		try {
			if(e.getPoint().getX() != mouseClickedPoint.getX() && e.getPoint().getY() != mouseClickedPoint.getY() && brush == 0) {
				
				areaSelected(mouseClickedPoint, e.getPoint());
			}
		} catch(NullPointerException ex) {
			
		}
		mouseClickedPoint = null;
		mouseMovedPoint = null;
		
	}
	
	/**
	 * Metoda udalosti stiskunti tlacitka mysi
	 */
	@Override
	public void mousePressed(MouseEvent e) {
		mouseClickedPoint = e.getPoint();
		
	}
	
	/**
	 * Metoda udalosti vystupu kurzuru mysi z panelu
	 */
	public void mouseExited(MouseEvent e) {}
	
	/**
	 * Metoda udalosti vstupu kurzuru mysi do panelu
	 */
	public void mouseEntered(MouseEvent e) {}
	
	/**
	 * Metoda udalosti kliknuti tlacitka mysi
	 */
	@Override
	public void mouseClicked(MouseEvent e) {
		if(e.getModifiers() == MouseEvent.BUTTON1_MASK && brush == 0) {
			Point2D point = window2model(e.getPoint());
			//System.out.println("Velikost: " + Simulator.getDimension().x + " " + Simulator.getDimension().y);
			//System.out.println("Bod vybran " + (int) point.getX() + " " + (int) point.getY());
			
			if(point.getX() <= Simulator.getDimension().x && point.getY() <= Simulator.getDimension().y && point.getX() >= 0 && point.getY() >= 0) {
				int bod = ((int) point.getX() + (int) point.getY() * Simulator.getDimension().x);
				Main.graphs.add(new GraphPoint(bod));
			}
		}
		
	}
	
}
