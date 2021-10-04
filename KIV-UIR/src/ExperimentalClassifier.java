import java.io.File;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Scanner;

/**
 * Třída reprezentující vlastní experimentální klasifikátor.
 * Klasifikátor je kombinací minimální vzdálenosti a K-means
 * @author hintik
 *
 */
public class ExperimentalClassifier extends Classifier {
	
	List<SymptomPoint> representatives = new ArrayList<SymptomPoint>();
	
	/**
	 * Trénování modelu jedním číslem.
	 * 
	 * @param trainingFolder složka s trénovacími daty
	 * @param c klasifikační třída, do které daná trénovací data patří
	 * @param s parametrizační algoritmus, který se má použít
	 * @return počet reprezentativních prvků
	 */
	public int trainNumber(File trainingFolder, Class c, Symptom s) {
		
		/*
		 * Načítání dat z trénovací složky a vytvoření příznaků
		 */
		File f = new File(trainingFolder, c.value + "");
		
		File[] data = f.listFiles();
		
		List<SymptomPoint> items = new ArrayList<SymptomPoint>();
		
		for(int i = 0; i < data.length; i++) {
			items.add(new SymptomPoint(s.getSymptom(data[i]), null));
		}
		
		/*
		 * Vytvoření centrálního prvku pomocí MD 
		 */
		
		float[] r = new float[items.get(0).symptom.length];
		
		for(int i = 0; i < items.size(); i++) {
			add(items.get(i).symptom, r);
		}
		
		divide(r, items.size());
		
		/*
		 * Vytvoření reprezentativní prvky každým směrem se vzdáleností 1
		 */

		SymptomPoint[] repre = new SymptomPoint[items.get(0).symptom.length*2];
		
		for(int i = 0; i < items.get(0).symptom.length; i++) {
			float[] symptom = r.clone();
			symptom[i] = symptom[i] + 1;
			repre[i] = new SymptomPoint(symptom, c);
			symptom = r.clone();
			symptom[i] = symptom[i] - 1;
			repre[repre.length-i-1] = new SymptomPoint(symptom, c);		}
		
		
		/*
		 * Pomocí K-means jsou reprezentantní prvky rozmístěny po prostoru dle shluků,
		 * aby se lépe aproximoval tvar třídových shluků v prostoru
		 */
		
		{
			int i = 0;
			while(assignItems(items, repre) && i < 5) {
				System.out.println(i);
				updatePositions(items, repre);
				i++;
			}
		}
		
		int x = 0;
		
		/*
		 * Do finální množiny se dostanou pouze ty reprezentantní prvky, které ve shlukování "vybojovaly" nějaké body.
		 */
		
		for(int i = 0; i < repre.length; i++) {
			if(!Float.isNaN(repre[i].symptom[0])) {
				representatives.add(repre[i]);
				x++;
			}
		}

		return x;
	}
	
	/**
	 * Metoda aktualizuje pozici reprezentativních bodů v vzhledem k příslušnosti jednotlivých bodů
	 * @param points seznam bodů
	 * @param repre seznam reprezentačních bodů
	 */
	private void updatePositions(List<SymptomPoint> points, SymptomPoint[] repre) {
		
		for(SymptomPoint r : repre) {
			Arrays.fill(r.symptom, 0);
		}
		
		int[] counter = new int[repre.length];
		
		for(SymptomPoint s : points) {
			add(s.symptom, repre[s.meta].symptom);
			counter[s.meta]++;
		}
		
		for(int i = 0; i < repre.length; i++) {
			divide(repre[i].symptom, counter[i]);
		}
		
	}
	
	/**
	 * Metoda pro sečtení dvou vektorů
	 * @param from jaký vektor se přičítá
	 * @param to k jakému vyktoru se přičítá
	 */
	private static void add(float[] from, float[] to) {
		for(int i = 0; i < from.length; i++) {
			to[i] += from[i];
		}
	}
	
	/**
	 * Metoda pro dělení hodnot vektoru
	 * @param what jaký vektor se bude dělit
	 * @param with hodnotami jakého vektoru se bude dělit
	 */
	private static void divide(float[] what, int with) {
		for(int i = 0; i < what.length; i++) {
			what[i] /= with;
		}
	}
	
	/**
	 * Metoda 
	 * @param points
	 * @param repre
	 * @return
	 */
	private boolean assignItems(List<SymptomPoint> points, SymptomPoint[] repre) {
		
		boolean changed = false;
		
		for(SymptomPoint s : points) {
			int representant = getNearestRepresentative(s, repre);
			if(s.meta != representant) {
				changed = true;
			}
			
			s.meta = representant; 
		}
		
		return changed;
		
		
	}
	
	/**
	 * Metoda získá nejbližší reprezentační bod k zadananému bodu a vrátí index 
	 * @param s příznakový vektor ke kterému hledáme nejbližší bod
	 * @param repre pole reprezentativních prvků
	 * @return index z pole prvků odkazující na nejbližší bod 
	 */
	private int getNearestRepresentative(SymptomPoint s, SymptomPoint[] repre) {
		float min = Float.MAX_VALUE;
		int index = -1;
		
		for(int i = 0; i < repre.length; i++) {
			float distance = s.distance(repre[i].symptom);

			if(distance < min) {
				min = distance;
				index = i;
			}
		}
		
		return index;
	}
	
	/**
	 * Metoda získá nejbližší reprezentativní bod pro daný syndrom
	 * @param s příznakový vektor ke kterému hledáme nejbližšího reprezentanta
	 * @return reprezentativní bod
	 */
	private SymptomPoint getNearestRepresentativeNumber(float[] s) {
		float min = Float.MAX_VALUE;
		int index = -1;
		
		for(int i = 0; i < representatives.size(); i++) {
			float distance = representatives.get(i).distance(s);
			
			if(distance < min) {
				min = distance;
				index = i;
			}
		}
		
		return representatives.get(index);
	}
	
	/**
	 * Metoda pro trénování všech čísel.
	 */
	public void train(File trainingFolder, Symptom s) {
		Class[] classes = Class.values();
		
		for(Class c : classes) {
			int x = 0;
			do {
				x = trainNumber(trainingFolder, c, s);
			} while(x < 2);
		}
	}
	

	/**
	 * Metoda načte model ze souboru pomocí zadaného scanneru 
	 */
	@Override
	public void load(Scanner scan) {
		representatives.clear();
		
		if(scan.hasNextLine()) scan.nextLine();
		
		while(scan.hasNextLine()) {
			String[] line = scan.nextLine().split(" ");
			Class c = Class.valueOf(line[0]);
			float[] symptom = new float[line.length-1];
			for(int i = 0; i < line.length-1; i++) {
				symptom[i] = Float.parseFloat(line[i+1]);
			}
			
			representatives.add(new SymptomPoint(symptom, c));
		}
		
		scan.close();
	}
	
	/**
	 * Metoda na uložení reprezentace modelu.
	 */
	@Override
	public void save(PrintWriter pw) {
		
		for(SymptomPoint r : representatives) {
			pw.print(r.c + "");

			for(int i = 0; i < r.symptom.length; i++) {
				pw.print(" " + r.symptom[i]);
			}
			
			pw.println();
		}		
		
	}
	
	/**
	 * Metoda na klasifikaci zadaného příznaku
	 */
	@Override
	public Class classify(float[] symptom) {
		
		SymptomPoint r = getNearestRepresentativeNumber(symptom);
		return r.c;
	}

	
}
