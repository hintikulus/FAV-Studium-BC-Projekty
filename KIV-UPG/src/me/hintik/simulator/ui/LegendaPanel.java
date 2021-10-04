package me.hintik.simulator.ui;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.FlowLayout;
import java.awt.Font;
import java.awt.GridLayout;
import java.awt.Label;

import javax.swing.BorderFactory;
import javax.swing.BoxLayout;
import javax.swing.JLabel;
import javax.swing.JPanel;


import me.hintik.simulator.DrawingPanel;
import me.hintik.simulator.Vizualizace;

/**
 * Trida starajici se o vykresleni legendy v panelu
 * @author hintik
 *
 */
public class LegendaPanel extends JPanel {

	private static final long serialVersionUID = 1L;
	
	/** Font pouzity pro nadpis panelu */
	private static final Font HEADLINE_FONT = new Font("Arial", Font.BOLD, 20);
	/** Font pouzity na popisky legendy */
	private static final Font TEXT_FONT = new Font("Arial", Font.BOLD, 16);
	/** Barva pouzita na legendu vysky terenu*/
	private static final Color TEXT_COLOR = new Color(20, 20, 20);
	
	/** Skok, ktery udava mezeru mezi jednotlivymi barvami*/
	private double gradient_step;

	/**
	 * Konstruktor incializace promenych a vlozeni do panelu
	 */
	public LegendaPanel() {
		this.setPreferredSize(new Dimension(130, 480));
		
		this.setLayout(new BoxLayout(this, BoxLayout.Y_AXIS));;
		
		JPanel titlePanel = new JPanel();
		Dimension titleDimension = new Dimension(130, 34);
		titlePanel.setMinimumSize(titleDimension);
		titlePanel.setMaximumSize(titleDimension);
		titlePanel.setSize(titleDimension);
		JLabel title = new JLabel("Legenda");
		title.setAlignmentX(Label.CENTER_ALIGNMENT);
		title.setFont(HEADLINE_FONT);
		titlePanel.add(title);
		titlePanel.setLayout(new FlowLayout(FlowLayout.CENTER));
		
		this.add(titlePanel, BorderLayout.NORTH);
		
		JPanel terrainHeight = new JPanel();
		terrainHeight.setBorder(BorderFactory.createTitledBorder("Výška terénu"));
		terrainHeight.setLayout(new GridLayout(DrawingPanel.TERRAIN_COLORS, 1));
		
		double terrain_height = Vizualizace.drawingPanel.maxTerrainLevel - Vizualizace.drawingPanel.minTerrainLevel;
		gradient_step = terrain_height/(DrawingPanel.TERRAIN_COLORS);
		
		for(int i = 1; i < DrawingPanel.TERRAIN_COLORS+1; i++) {
			
			int x = 0;
			if(i == DrawingPanel.TERRAIN_COLORS) x = 1; 
			double vyska = Vizualizace.drawingPanel.minTerrainLevel + (i-x)*gradient_step;
			boolean posledni = i==DrawingPanel.TERRAIN_COLORS;
			JPanel barva = drawHeightColor(vyska, posledni);
			terrainHeight.add(barva);
			
		}
		
		this.add(terrainHeight);
		
		JPanel others = new JPanel();
		others.setBorder(BorderFactory.createTitledBorder("Ostatní"));
		others.setLayout(new GridLayout(2, 1));
		
		others.add(getLegendaItem("Voda", DrawingPanel.WATER_COLOR, Color.WHITE));
		others.add(getLegendaItem("Značky", DrawingPanel.ARROW_COLOR, Color.WHITE));

		
		this.add(others);
		
	}
	
	/**
	 * Metoda vytvori položku do legendy pro vysku terenu
	 * @param height vyska terenu
	 * @param last informace, zda se jedna o posledni prvek
	 * @return polozka do legendy
	 */
	private JPanel drawHeightColor(double height, boolean last) {
		String text = "";
		Color color;
		
		if(last) {
			text = "> ";
			color = Vizualizace.drawingPanel.getColorByHeight(height+gradient_step);
		} else {
			text = "< ";
			color = Vizualizace.drawingPanel.getColorByHeight(height);
		}
		
		
		text += (int) height + " m";
		
		return getLegendaItem(text, color, TEXT_COLOR);
	}
	
	/**
	 * Metoda vytvari polozku do legendy
	 * @param text popisek polozky
	 * @param barva barva pozadi polozky
	 * @param textColor barva textu popisku polozky
	 * @return
	 */
	private JPanel getLegendaItem(String text, Color barva, Color textColor) {

		JPanel panel = new JPanel(new GridLayout(0,1));
		JLabel label = new JLabel(text);
		panel.setBackground(barva);
		label.setFont(TEXT_FONT);
		label.setForeground(textColor);
		label.setHorizontalAlignment(JLabel.CENTER);
		panel.add(label);
		
		return panel;
	}
	
}
