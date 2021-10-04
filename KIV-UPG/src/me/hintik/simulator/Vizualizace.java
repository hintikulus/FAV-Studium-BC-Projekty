package me.hintik.simulator;

import java.awt.BorderLayout;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.image.BufferedImage;
import java.awt.print.PageFormat;
import java.awt.print.Printable;
import java.awt.print.PrinterException;

import javax.swing.JPanel;

import me.hintik.simulator.ui.LegendaPanel;

/**
 * Trida zajistuje spojeni legendy se samotnou mapou vizualizace predevsim pro tisk
 * @author hintik
 *
 */
public class Vizualizace extends JPanel implements Printable {

	private static final long serialVersionUID = 1L;
	
	/** Instance mapy vizualizace */
	public static DrawingPanel drawingPanel;
	/** Instance panelu obsahujici legendu */
	public static LegendaPanel legendaPanel;
	
	/**
	 * Vytvoreni instance panelu vizualizace s inicializaci promennych
	 */
	public Vizualizace() {
		this.setLayout(new BorderLayout());

		drawingPanel = new DrawingPanel();
		legendaPanel = new LegendaPanel();
		

		this.add(legendaPanel, BorderLayout.WEST);
		this.add(drawingPanel, BorderLayout.CENTER);
		
	}

	/**
	 * Metoda pripravi obraz platna na tisk
	 */
	@Override
	public int print(Graphics g, PageFormat pageFormat, int pageIndex) throws PrinterException {
		if(pageIndex > 0) {
			return NO_SUCH_PAGE;
		}
		
		Graphics2D g2 = (Graphics2D) g;
		double scaleX = pageFormat.getWidth()/this.getWidth();
		double scaleY = pageFormat.getHeight()/this.getHeight();
		
		double scale = Math.min(scaleX, scaleY);
		
		g2.scale(scale, scale);
		
		
		
		g2.translate((pageFormat.getWidth()-this.getWidth()*scale)/2, (pageFormat.getHeight()-this.getHeight()*scale)/2);
		BufferedImage bufImage = new BufferedImage(this.getWidth(), this.getHeight(), BufferedImage.TYPE_4BYTE_ABGR);
		this.paint(bufImage.createGraphics());
		g2.drawImage(bufImage, null, 0, 0);
		return 0;
	}
	
	
}
