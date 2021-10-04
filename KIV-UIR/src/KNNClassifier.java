import java.io.File;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Scanner;

/**
 * Třída reprezentující funkčnost KNN klasifikátoru
 * @author hintik
 *
 */
public class KNNClassifier extends Classifier {

	ArrayList<SymptomPoint> points = new ArrayList<SymptomPoint>();

	/**
	 * Metoda načte model ze souboru pomocí zadaného scanneru 
	 */
	@Override
	public void load(Scanner scan) {
		
		points.clear();

		if(scan.hasNextLine()) scan.nextLine();
		
		while(scan.hasNextLine()) {
			String[] line = scan.nextLine().split(" ");
			Class c = Class.valueOf(line[0]);
			float[] symptom = new float[line.length-1];
			for(int i = 0; i < line.length-1; i++) {
				symptom[i] = Float.parseFloat(line[i+1]);
			}
			
			points.add(new SymptomPoint(symptom, c));
		}
		
		scan.close();
	}
	
	/**
	 * Metoda na uložení reprezentace modelu.
	 */
	@Override
	public void save(PrintWriter pw) {

		for(SymptomPoint p : points) {
			pw.print(p.c + "");

			for(int i = 0; i < p.symptom.length; i++) {
				pw.print(" " + p.symptom[i]);
			}
			
			pw.println();
		}
		
	}

	/**
	 * Metoda na klasifikaci zadaného příznaku
	 */
	@Override
	public Class classify(float[] symptom) {

		Collections.sort(points, new Comparator<SymptomPoint>() {
			
			@Override
			public int compare(SymptomPoint p1, SymptomPoint p2) {
				
				float sub = (p1.distance(symptom) - p2.distance(symptom));
				
				if(sub == 0) {
					return 0;
				}
				
				if(sub > 0) {
					return 1;
				}
				
				return -1;
				
			} 
		});

		int k = 5;
		
		Class[] classes = Class.values();
		int[] classCounter = new int[classes.length];
		
		for(int i = 0; i < k; i++) {
			classCounter[points.get(i).c.value]++;
		}
		
		int max = 0;
		int index = 0;
		for(int i = 0; i < classCounter.length; i++) {
			if(classCounter[i] > max) {
				max = classCounter[i];
				index = i;
			}
		}
		
		return classes[index]; 
		
	}

	/**
	 * Metoda pro trénování všech čísel.
	 */
	@Override
	public void train(File trainingFileFolder, Symptom s) {
		Class[] classes = Class.values();
		
		for(int i = 0; i < classes.length; i++) {
			File folder = new File(trainingFileFolder, classes[i].value + "");
			File[] files = folder.listFiles();
			
			for(File f : files) {
				float[] symptom = s.getSymptom(f);
				points.add(new SymptomPoint(symptom, classes[i]));
			}

			System.out.println("Done: " + classes[i]);
		} 
		
	}

}
