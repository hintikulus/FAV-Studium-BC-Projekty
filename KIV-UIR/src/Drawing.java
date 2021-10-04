import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.RenderingHints;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.geom.AffineTransform;
import java.awt.image.BufferedImage;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPopupMenu;
import javax.swing.event.MouseInputListener;

/**
 * Třída pro implementaci kreslícího plátna
 * @author hintik
 *
 */
public class Drawing extends JPanel {
	
	private static final long serialVersionUID = 1L;
	
	public static final int MARGIN = 10;
	public static final int IMG_SIZE = 28;

	public static final int WHITE_COLOR = getColor(255, 255, 255);
	public static final int BLACK_COLOR = getColor(0, 0, 0);
	
	BufferedImage img;
	
	Window parent;
	
	boolean clicked = false;
	double scale = 1;
	
	/**
	 * Konstruktor plátna
	 * @param parent okno, ke kterému plátno přísluší
	 */
	public Drawing(Window parent) {
		
		this.parent = parent;
		
		this.setPreferredSize(new Dimension(300,300));
		
		img = new BufferedImage(IMG_SIZE, IMG_SIZE, BufferedImage.TYPE_4BYTE_ABGR);
		img.getGraphics().setColor(Color.white);
		img.getGraphics().fillRect(0, 0, img.getWidth(), img.getHeight());
		
		addMouseListener(new MouseAction());
		addMouseMotionListener(new MouseAction());
		
	}
	
	/**
	 * Metoda pro kreslení na plátno
	 */
	public void paint(Graphics g) {
		Graphics2D g2 = (Graphics2D) g;
		
		g2.setColor(Color.LIGHT_GRAY);
		g2.fillRect(0, 0, this.getWidth(), this.getHeight());
		
		int size;
		
		if(this.getHeight() > this.getWidth()) {
			size = this.getWidth();
		} else {
			size = this.getHeight();
		}
		
		g2.translate(MARGIN, MARGIN);
		AffineTransform transform = g2.getTransform();

		int realSize = size-2*MARGIN;
		
		scale = realSize/(double)(IMG_SIZE);
		
		g2.scale(scale, scale);
		
		
		g.drawImage(img, 0, 0, null);

		
		g2.setTransform(transform);
		
		g.setColor(Color.BLACK);
		g.drawRect(0, 0, realSize, realSize);
		
	}
	
	/**
	 * Vymazání plátna (vyplnění bílou barvou)
	 */
	public void clearPainting() {

		for(int x = 0; x < IMG_SIZE; x++) {
			for(int y = 0; y < IMG_SIZE; y++) {
				img.setRGB(x, y, WHITE_COLOR);
			}
		}
		
		repaint();
	}
	
	/**
	 * Získání barvy v podobě celého čísla
	 * @param r složka červené barvy
	 * @param g složka zelené barvy
	 * @param b složka modré barvy
	 * @return celočíselná reprezentace barvy
	 */
	public static int getColor(int r, int g, int b) {
		return getColor(r, g, b, 255);
	}
	
	/**
	 * Získání barvy v podobě celého čísla
	 * @param r složka červené barvy
	 * @param g složka zelené barvy
	 * @param b složka modré barvy
	 * @param a složka průhlednosti
	 * @return celočíselná reprezentace barvy
	 */
	public static int getColor(int r, int g, int b, int a) {
		a = (a << 24) & 0xFF000000;
		r = (r << 16) & 0x00FF0000;
	    g = (g << 8) & 0x0000FF00;
	    b = b & 0x000000FF;

	    return a | r | g | b;
	}
	
	/**
	 * Nakreslení oválu jako "štětcem".
	 * @param x x-ová souřadnice bodu
	 * @param y y-ová souřadnice bodu
	 */
	public void paint(int x, int y) {
		if(x >= 0 & x < img.getWidth() && y >= 0 && y < img.getHeight()) {
			Graphics g = img.getGraphics();
			
			Graphics2D g2 = (Graphics2D) g;
			g2.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);
			
			g2.scale(0.25, 0.25);
			
			g.setColor(Color.BLACK);
			g.fillOval(4*(x)-1, 4*(y)-3, 6, 6);
			g.drawOval(4*(x)-1, 4*(y)-3, 6, 6);
	
			repaint();
		}
	}
	
	/**
	 * Transformační funkce pro převod koordináru z plátna na obrázek
	 * @param coord souřadnice plátna
	 * @return souřadnice v obrázku
	 */
	public int transformCoordinate(int coord) {
		return (int) ((coord-MARGIN)/scale);
	}
	
	/**
	 * Metoda pro získání nakresleného obrázku
	 * @return nakreslený obrázek
	 */
	public BufferedImage getImage() {
		int[] data = new int[img.getWidth()*img.getHeight()];
		this.img.getRGB(0, 0, img.getWidth(), img.getHeight(), data, 0, img.getWidth());
		
		BufferedImage img = new BufferedImage(this.img.getWidth(), this.img.getHeight(), BufferedImage.TYPE_4BYTE_ABGR);
		
		for(int i = 0; i < data.length; i++) {
			data[i] = 0xFFFFFFFF - (data[i] & 0x00FFFFFF); // inverze barev
		}
		
		img.setRGB(0, 0, img.getWidth(), img.getHeight(), data, 0, img.getWidth());
		return img;
	}
	
	/**
	 * Třída pro obsluhu událostí tvořené myší
	 * @author hintik
	 *
	 */
	class MouseAction implements MouseInputListener {

		@Override
		public void mouseClicked(MouseEvent e) {
			if(e.getButton() == MouseEvent.BUTTON1) {
				paint(transformCoordinate(e.getX()), transformCoordinate(e.getY()));
			}
		}

		@Override
		public void mousePressed(MouseEvent e) {
			if(e.getButton() == MouseEvent.BUTTON1) {
				clicked = true;
			}
			
			if(e.getButton() == MouseEvent.BUTTON3) {
				doPop(e);
			}
		}

		@Override
		public void mouseReleased(MouseEvent e) {
			clicked = false;
			
		}

		@Override
		public void mouseEntered(MouseEvent e) {}

		@Override
		public void mouseExited(MouseEvent e) {}

		@Override
		public void mouseDragged(MouseEvent e) {
			if(clicked) {
				paint(transformCoordinate(e.getX()), transformCoordinate(e.getY()));;
			}
			
		}

		@Override
		public void mouseMoved(MouseEvent e) {}
		
	    private void doPop(MouseEvent e) {
	        ContextMenu menu = new ContextMenu();
	        menu.show(e.getComponent(), e.getX(), e.getY());
	    }
		
	}
	
	/**
	 * Kontextové menu pro aktivaci funkcí
	 * @author hintik
	 *
	 */
	class ContextMenu extends JPopupMenu {	
		private static final long serialVersionUID = 1L;
		
		JMenuItem clearPainting = new JMenuItem("Smazat plátno");
		
		/**
		 * Konstruktor kontextového meny
		 */
		public ContextMenu() {
			
			ButtonListener listener = new ButtonListener();
			
			clearPainting.addActionListener(listener);
			
			this.add(clearPainting);
		}
		
		/**
		 * Třída pro obsluhu tlačítkových událostí v kontextovém menu
		 * @author hintik
		 *
		 */
		class ButtonListener implements ActionListener {

			/**
			 * Obsluha události kliknutí na tlačítka
			 */
			@Override
			public void actionPerformed(ActionEvent e) {
				
				if(e.getSource() == clearPainting) {
					clearPainting();
					return;
				}
			}

			
		}
	}
	
}
