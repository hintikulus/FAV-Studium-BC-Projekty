package me.hintik.simulator.charts;

import java.awt.geom.Point2D;
import java.util.Iterator;

import me.hintik.simulator.Main;


public class GraphPoint extends Graph {

	/** Popisek x-ove souradnicove osy */
	private final static String X_AXIS = "Čas simulace [s]";
	
	/** Popisek y-ove souradnicove osy */
	private final static String Y_AXIS = "Výška hladiny vodní plochy v bodě [cm]";

	/** Nazev zobrazovane hodnoty */
	private final static String VALUE_TITLE = "Výška hladina vody";
	
	/** Index bodu zdroje informaci v poli dat */
	int point;

	/** Bod zdroje informaci v modelu */
	static Point2D modelCoords;
	
	public GraphPoint(int point) {
		super("Vývoj hladiny vodního toku v závislosti na čase v bodě ["
		+ (int) (modelCoords = Main.getModelCoord(point)).getX() 
		+ ";"
		+ (int) modelCoords.getY() 
		+ "]", X_AXIS, Y_AXIS, VALUE_TITLE);
		
		this.point = point;
		
				
	}
	
	/**
	 * Metoda pro pridani novych dat do grafu
	 */
	public void chartUpdate(double time, double[] data) {
		if(this.data.getItemCount() == 0) initData();
		double water = data[point];
		if(water < 0) water = 0;
		this.data.add(time, water*100);
		
	}

	/**
	 * Metoda pro pridani pocatecnich dat do grafu
	 */
	@Override
	public void initData() {
		
		Iterator<Double> timeIterator = Main.simTimeData.iterator();
		for(Iterator<double[]> waterIterator = Main.simWaterData.iterator(); waterIterator.hasNext();) {
			
			double water = waterIterator.next()[this.point];
			
			if(water < 0) water = 0;
			
			double time = timeIterator.next().doubleValue();
			
			this.data.add(time, water*100);
			
		}
	}
}
