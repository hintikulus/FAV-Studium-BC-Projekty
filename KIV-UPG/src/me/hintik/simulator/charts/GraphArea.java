package me.hintik.simulator.charts;

import java.awt.geom.Point2D;
import java.util.Iterator;

import me.hintik.simulator.Main;
import waterflowsim.Simulator;

public class GraphArea extends Graph {
	
	/** Popisek x-ove souradnicove osy */
	private final static String X_AXIS = "Čas simulace [s]";
	
	/** Popisek y-ove souradniceove osy */
	private final static String Y_AXIS = "ˇPrůměrná výška hladiny vodní plochy v bodě [cm]";

	/** Nazev hodnoty */
	private final static String VALUE_TITLE = "Průměrná výška hladiny vody";
	
	/** pocatecni bod uzemi */
	int point;
	/** sirka uzemi */
	int width;
	/** vyska uzemi */
	int height;
	
	static Point2D modelCoords;
	
	/**
	 * Vytvoreni grafu ukazujici prumernou hodnotu hladiny vody na danem uzemi
	 * @param point pocatecni bod uzemi (levy horni roh)
	 * @param width sirka uzemi
	 * @param height vyska uzemi
	 */
	public GraphArea(int point, int width, int height) {
		super("Vývoj průměrné hladiny vodního toku v závislosti na čase na území ["
			+ (int) (modelCoords = Main.getModelCoord(point)).getX()
			+ "-" 
			+ ((int) modelCoords.getX()+width)
			+";"
			+ (int) modelCoords.getY() 
			+ "-" 
			+ ((int) modelCoords.getY()+height)
			+ "]", X_AXIS, Y_AXIS, VALUE_TITLE);
		
		this.point = point;
		this.width = width;
		this.height = height;
	}
	
	/**
	 * Vlozeni novych dat do grafu
	 */
	public void chartUpdate(double time, double[] data) {
		if(this.data.getItemCount() == 0) 
			initData();
		this.data.add(time, getPrumer(data)*100);
		
	}

	/**
	 * Vlozeni pocatecnich dat do grafu
	 */
	@Override
	public void initData() {
			
		Iterator<Double> timeIterator = Main.simTimeData.iterator();
		for(Iterator<double[]> waterIterator = Main.simWaterData.iterator(); waterIterator.hasNext();) {
			
			double water = getPrumer(waterIterator.next());
			double time = timeIterator.next().doubleValue();
			//System.out.println(time);
			this.data.add(time, water*100);
			
		}
		
	}
	
	/**
	 * Metoda pro vypocet prumerne hodnoty v uzemi grafu
	 * @param data pole vyskovych informaci
	 * @return hodnota prumeru v danem uzemi
	 */
	private double getPrumer(double[] data) {
		double sum = 0;
		double num = 0;
		
		for(int i = 0; i < width; i++) {
			for(int j = 0; j < height; j++) {
				int index = this.point + j*Simulator.getDimension().x + i;
				if(data[index] > 0) {
					sum += data[index];
					num++;
				} 
			}
		}
		
		//System.out.println(sum);
		if(num == 0) return 0.0;
		return sum/num;
	}

}
