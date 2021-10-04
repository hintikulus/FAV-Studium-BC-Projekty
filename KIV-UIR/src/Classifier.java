import java.io.File;
import java.io.PrintWriter;
import java.util.Scanner;

/**
 * Abstraktní metoda pro klasifikátory
 * @author hintik
 *
 */
public abstract class Classifier {

	/**
	 * Metoda určená pro načítání modelů do programu
	 * @param scan instance Scanneru který čte ze souboru
	 */
	public abstract void load(Scanner scan);
	
	/**
	 * Metoda uloží model do souboru pomocí daného PrintWriteru
	 * @param pw PrintWriter pro ukládání do souboru
	 */
	public abstract void save(PrintWriter pw); 
	
	/**
	 * Metoda pro klasifikaci příznakového vektoru
	 * @param symptom příznakový vektor
	 * @return klasifikovaná třída
	 */
	public abstract Class classify(float[] symptom);
	
	/**
	 * Spuštění testování modelu na základě způsobu tvorby příznaků
	 * @param testingDataFile složka s testovacími daty
	 * @param s instance třídy pro tvorbu příznaku
	 * @return úspěšnost
	 */
	public float test(File testingDataFile, Symptom s) {
		float result = 0;
		int counter = 0;
		int[] counters = new int[Class.values().length];
		int[] results = new int[Class.values().length];
		Class[] classes = Class.values();
		
		for(Class c : Class.values()) {
			System.out.println("Testování...");
			File[] files = new File(testingDataFile, c.value + "").listFiles();
			for(File f : files) {
				counter++;
				counters[c.value]++;
				if(classify(s.getSymptom(f)) == c) {
					results[c.value]++;
					result++;
				}
			}
		}
		
		System.out.println("Přesnost podle čísel: ");
		for(int i = 0; i < classes.length; i++) {
			System.out.format("%s: %.3f%%\n", classes[i].value, (results[i]/(double) counters[i])*100);
		}
		return result/counter;
	}
	
	/**
	 * Metoda pro natrénování modelu zadanými daty na základě způsoby tvorby příznaků
	 * @param trainingFileFolder složka s trénovacími daty
	 * @param s instance třídy pro tvorbu příznaků
	 */
	public abstract void train(File trainingFileFolder, Symptom s);
}
