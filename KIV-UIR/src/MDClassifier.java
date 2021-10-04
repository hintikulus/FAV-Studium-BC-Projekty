import java.io.File;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

/**
 * Třída pro reprezentaci klasifikátoru nejmenší vzdálenosti
 * @author hintik
 *
 */
public class MDClassifier extends Classifier {
	
	List<SymptomPoint> SymptomPoints = new ArrayList<SymptomPoint>();
	
	/**
	 * Trénování modelu jedním číslem.
	 * 
	 * @param trainingFolder složka s trénovacími daty
	 * @param c klasifikační třída, do které daná trénovací data patří
	 * @param s parametrizační algoritmus, který se má použít
	 * @return počet reprezentativních prvků
	 */
	public void trainNumber(File trainingFolder, Class c, Symptom s) {
		
		/*
		 * Načítání dat z trénovací složky a vytvoření příznaků
		 */
		File f = new File(trainingFolder, c.value + "");
		
		File[] data = f.listFiles();
		
		List<float[]> items = new ArrayList<float[]>();
		
		for(int i = 0; i < data.length; i++) {
			items.add(s.getSymptom(data[i]));
		}
		
		/*
		 * Vytvoření centrálního prvku pomocí MD 
		 */
		
		float[] SymptomPoint = new float[items.get(0).length];
		
		for(float[] d : items) {
			add(d, SymptomPoint);
		}
		
		float max = 0;
		for(int i = 0; i < SymptomPoint.length; i++) {
			if(SymptomPoint[i] > max) {
				max = SymptomPoint[i];
			}
		}
		
		divide(SymptomPoint, items.size());
		
		SymptomPoint r = new SymptomPoint(SymptomPoint, c);
		SymptomPoints.add(r);
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
			what[i] = what[i] / with;
		}
	}
	
	/**
	 * Metoda získá nejbližší příznak k zadanému příznaku. 
	 * @param s příznak, ke kterému hledáme nejbližší příznak
	 * @return nejbližší příznak k zadanému příznaku
	 */
	private SymptomPoint getNearestSymptomPointNumber(float[] s) {
		float min = Float.MAX_VALUE;
		int index = -1;
		
		for(int i = 0; i < SymptomPoints.size(); i++) {
			float distance = SymptomPoints.get(i).distance(s);
			
			if(distance < min) {
				min = distance;
				index = i;
			}
		}
		
		return SymptomPoints.get(index);
	}
	
	/**
	 * Metoda natrénuje model s daty v zadané trénovací složce
	 */
	public void train(File trainingFolder, Symptom s) {
		Class[] classes = Class.values();
		
		for(Class c : classes) {
			trainNumber(trainingFolder, c, s);
		}
	}
	

	/**
	 * Metoda načte model ze souboru pomocí zadaného scanneru 
	 */
	@Override
	public void load(Scanner scan) {
		SymptomPoints.clear();

		if(scan.hasNextLine()) scan.nextLine();
		
		while(scan.hasNextLine()) {
			String[] line = scan.nextLine().split(" ");
			Class c = Class.valueOf(line[0]);
			float[] symptom = new float[line.length-1];
			for(int i = 0; i < line.length-1; i++) {
				symptom[i] = Float.parseFloat(line[i+1]);
			}
			
			SymptomPoints.add(new SymptomPoint(symptom, c));
		}
		
		scan.close();
		
	}
	
	/**
	 * Metoda na uložení reprezentace modelu.
	 */
	@Override
	public void save(PrintWriter pw) {
		
		for(SymptomPoint r : SymptomPoints) {
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
		
		SymptomPoint r = getNearestSymptomPointNumber(symptom);
		return r.c;
	}

	
}
