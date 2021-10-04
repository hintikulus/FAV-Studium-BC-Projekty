package me.hintik.simulator.charts;

import java.awt.Color;

import javax.swing.JFrame;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartPanel;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.plot.XYPlot;
import org.jfree.chart.renderer.xy.XYItemRenderer;
import org.jfree.data.xy.XYSeries;
import org.jfree.data.xy.XYSeriesCollection;

/**
 * Abstraktni trida Graph vytvari obecny graf pro potreby vkladani ruznych dat
 * @author hintik
 *
 */
public abstract class Graph {
	
	/** Popisky grafu*/
	String title, xAxis, yAxis, valueTitle;
	/** Okno grafu */
	public JFrame frame;
	/** Data grafu */
	XYSeries data;
	
	/**
	 * Konstruktor vytvarejici okno grafu
	 * @param title nazev grafu
	 * @param xAxis popisek x-ove souradnicove osy
	 * @param yAxis popisek y-ove souradnicove osy
	 * @param valueTitle popisek nazvu hodnot
	 */
	public Graph(String title, String xAxis, String yAxis, String valueTitle) {
		this.title = title;
		this.xAxis = xAxis;
		this.yAxis = yAxis;
		this.valueTitle = valueTitle;
		
		frame = new JFrame();
		frame.setLocationRelativeTo(null);
		frame.setTitle(title);
		
		JFreeChart chart = makeXYChart();
		
		ChartPanel chartPanel = new ChartPanel(chart);
		frame.add(chartPanel);
		
		frame.pack();
		frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
		frame.setVisible(true);
		
				
	}
	
	/**
	 * Metoda vytvarejici instanci XY grafu
	 * @return XY graf
	 */
	private JFreeChart makeXYChart() {
		XYSeriesCollection dataset = new XYSeriesCollection();

		this.data = new XYSeries(valueTitle);
		
		dataset.addSeries(this.data);
		
		JFreeChart chart = ChartFactory.createXYLineChart(this.title, this.xAxis, this.yAxis, dataset);
		
		XYPlot plot = chart.getXYPlot();
		
		chart.setBackgroundPaint(Color.WHITE);
		plot.setBackgroundPaint(Color.WHITE);
		plot.setDomainGridlinePaint(Color.DARK_GRAY);
		plot.setRangeGridlinePaint(Color.DARK_GRAY);
		
		XYItemRenderer renderer = plot.getRenderer();
		
		renderer.setDefaultItemLabelsVisible(true);
		
		return chart;
	}

	/**
	 * Metoda pro vlozeni pocatecnich dat
	 */
	public abstract void initData();
	
	/**
	 * Metoda pro vlozeni novych dat
	 * @param time hodnota na x-ove souradnici
	 * @param data hodnota na y-ove souradnici
	 */
	public abstract void chartUpdate(double time, double[] data);
}
