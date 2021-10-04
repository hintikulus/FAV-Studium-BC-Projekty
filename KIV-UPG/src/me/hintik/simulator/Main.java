package me.hintik.simulator;

import java.awt.BorderLayout;
import java.awt.geom.Point2D;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import javax.swing.JFrame;
import javax.swing.JOptionPane;

import me.hintik.simulator.charts.Graph;
import me.hintik.simulator.ui.ControlPanel;
import me.hintik.simulator.ui.LegendaPanel;
import me.hintik.simulator.ui.MenuBar;
import waterflowsim.Simulator;

/**
 * Hlavni trida projektu, ktera obsahuje main(String[]) (vstupni bod programu)
 * @author hintik
 *
 */
public class Main {
	
	/**
	 * Vychozi predzvoleny scenar, ktery se zvoli pri nezadani zadneho parametru. Pri zvoleni
	 * nevhodneho parametru se aplikace ukonci.
	 * Netestuje se, zda je cislo scenar v korektnim rozmezi (zda scenar s takovym cislem existuje),
	 * muze tedy vzniknout vyjimka. 
	 */
	private static final int DEFAULT_SCENARIO = 0;
	
	/**
	 * Cas znovuvykresleni okna a skoku simulace
	 */
	public static final int REPAINT_TIME = 100;
	
	/** Ukazatel simulacniho casu */
	public static double simTime = 0;
	
	/** Indikator pozastavene simulace */
	public static boolean isPaused = false;
	
	/** Multiplikator rychlosti "prehravani" simulace */
	public static double speed = 1;

	/** Pocet volani metody saveData() */
	private static int saveCall = 0;
	
	/** Odkaz na instanci mapy vizualizace */
	public static DrawingPanel drawingPanel;
	/** Odkaz na instanci ovladaciho panelu vizualizace */
	public static ControlPanel controlPanel;
	/** Odkaz na instanci legendy vizualizace */
	public static LegendaPanel legendaPanel;
	/** Odkaz na instanci vizualizacni casti vizualizace */
	public static Vizualizace vizualizacePanel;

	/** Okno aplikace */
	public static JFrame frame;
	/** Nabidka okna */
	public static MenuBar menuBar;
	
	/** Seznam pro uchovani informaci o postupu simulacniho casu */
	public static List<Double> simTimeData;
	/** Seznam pro uchovani informaci o postupu simulace */
	public static List<double[]> simWaterData;
	
	/** Seznam otevrenych grafu */
	static ArrayList<Graph> graphs = new ArrayList<Graph>();
	
	/** Indikator povoleni sberu dat */
	static boolean sberDat = true;
	
	/**
	 * Vstupni bod programu, uzivatel zvoli scenar, metoda zalozi okno a spusti simulaci.
	 * Metoda take zaklada Timer, ktery "pravidelne" obnovuje platno a posouva cas simulace. 
	 * @param args - vstupni parametry (cislo scenare)
	 */
	public static void main(String[] args) {
		
		int choose;
		
		if(args.length == 0) {
			choose = DEFAULT_SCENARIO;
		} else {
			try {
				choose = Integer.parseInt(args[0]);
				
				if(choose < 0 || choose >= Simulator.getScenarios().length) {
					System.out.println("Zadany scenar neni k dispozici. Program se ukoncuje.");
					System.exit(0);
				}
			} catch(NumberFormatException e) {
				choose = -1;
			}
			
		}
		
		Simulator.runScenario(choose);
		
		/*
		 * Zalozeni, nastaveni okna a vlozeni panelu
		 */
		
		initGUI();
		
		simTimeData = new LinkedList<Double>();
		simWaterData = new LinkedList<double[]>();
		
		
		/*
		 * Vytvoreni casovace pro "pravidelny" postup simulace a znovuvykreslovani panelu
		 */
		
		Timer timer = new Timer();
				
		long startTime = System.currentTimeMillis();
		timer.scheduleAtFixedRate(new TimerTask() {
			
			@Override
			public void run() {
				if(!isPaused) { 
					double posun = 0.02 * speed; //vypocteni posunu simulace v zavislosti na meritko rychlosti
					saveData(); //ulozeni dat
					Simulator.nextStep(posun); //posun simulace o vypocteny simulacni cas
					simTime += posun; //aktualizace promenne
				}
				
				/*
				 * V pripade nasbirani urceneho poctu zaznamu se spusti redukce dat, aby se
				 * oddalil/zamezil pad aplikace z duvodu nedostatku pameti
				 */
				if(simTimeData.size() >= 500) {
					dataReduction(); 
				}
				
				drawingPanel.time = ((System.currentTimeMillis() - startTime) / 1000.0); //aktualizace realneho casu behu aplikace
				drawingPanel.repaint(); //znovuvykresleni platna mapy
				
				
			}
		}, 0, REPAINT_TIME);
		

		/*
		 * Vytvoreni ochraneho mechanismu, pro zastaveni sberu dat pri 80% pouziti pameti. Pri dosazeni tohoto milniku se uzivateli
		 * zobrazi zprava o ukonceni sberu dat.
		 */
	    MemoryWarningSystem.setPercentageUsageThreshold(0.8);

	    MemoryWarningSystem mws = new MemoryWarningSystem();
	    mws.addListener(new MemoryWarningSystem.Listener() {
	      public void memoryUsageLow(long usedMemory, long maxMemory) {
	        sberDat = false;
	        double percentageUsed = ((double) usedMemory) / maxMemory;
	        System.out.println("percentageUsed = " + percentageUsed);
	        JOptionPane.showMessageDialog(frame, "Od tohoto okamžiku se z důvodu zachování integrity aplikace přestávají uchovávat data.");
	      }
	    });
		
	}
	
	/**
	 * Inicializace grafickeho rozhrani okna
	 */
	private static void initGUI() {
		frame = new JFrame();
		frame.setTitle("Jan Hinterholzinger (A19B0052P)");
		
		menuBar = new MenuBar();
		frame.setJMenuBar(menuBar);
		
		vizualizacePanel = new Vizualizace();
		drawingPanel = Vizualizace.drawingPanel;
		legendaPanel = Vizualizace.legendaPanel;
		controlPanel = new ControlPanel();
		
		frame.setLayout(new BorderLayout());
		
		frame.add(vizualizacePanel, BorderLayout.CENTER);
		frame.add(controlPanel, BorderLayout.EAST);
		
		frame.pack();
		
		
		frame.setLocationRelativeTo(null);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		
		frame.setVisible(true);
	}
	
	/**
	 * Ulozeni kazdy desaty soubor dat
	 */
	private static void saveData() {
		if(!sberDat) {
			return;
		}
		
			if(saveCall != 0) {
				saveCall=++saveCall%10;
				return;
			}
		
		simTimeData.add(simTime);
		
		double[] waterLevel = new double[Simulator.getData().length];
		
		for(int i = 0; i < Simulator.getData().length; i++) {
			waterLevel[i] = Simulator.getData()[i].getWaterLevel();
		}
		
		simWaterData.add(waterLevel);

		for(Iterator<Graph> graphIterator = graphs.iterator(); graphIterator.hasNext();) {
			Graph graph = graphIterator.next();
			
			if(!graph.frame.isVisible()) {
				graphIterator.remove();
			} else {
				graph.chartUpdate(simTime, waterLevel);
			}
		}
		
		saveCall++;
	}
	
	/**
	 * Metoda prepocte index pole s daty na souradnice bodu v matici modelu
	 * @param index index bodu v jednorozmernem poli dat
	 * @return souradnice bodu v modelu
	 */
	public static Point2D getModelCoord(int index) {
		int x = index%(Simulator.getDimension().x); //vypocteni x-ove souradnice zdroje vody v modelu
		int y = (index-x)/Simulator.getDimension().x; //vypocteni y-ove souradnice zdroje vody v modelu

		return new Point2D.Double((int) x, (int) y);
		
	}
	
	/**
	 * Metoda vypocita pozici bodu v jednorozmernem poli dat
	 * @param point souradnice v modelu
	 * @return index v jednorozmernem poli
	 */
	public static int getArrayIndex(Point2D point) {
		return getArrayIndex(point.getX(), point.getY());
	}
	
	/**
	 * Metoda vypocita pozici bodu v jednorozmernem poli dat
	 * @param x x-ova souradnice modelu
	 * @param y y-ova souradnice modelu
	 * @return index v jednorozmernem polid
	 */
	public static int getArrayIndex(double x, double y) {
		return ((int) x + (int) y * Simulator.getDimension().x);
	}
	
	
	/**
	 * Metoda provede v zaznamech dat redukci
	 */
	private static void dataReduction() {
		
		
		Iterator<Double> iteratorTime = simTimeData.listIterator(0);
		
		for(Iterator<double[]> iteratorWater = simWaterData.listIterator(0); iteratorWater.hasNext();) {
			iteratorWater.next();
			iteratorTime.next();
			if(iteratorWater.hasNext()) {
				iteratorWater.next();
				iteratorTime.next();
				iteratorWater.remove();
				iteratorTime.remove();
			}
		}
		
		System.gc();
	}
	
	
}
