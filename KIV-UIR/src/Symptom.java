import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.util.Random;

import javax.imageio.ImageIO;

/**
 * Abstraktní třída reprezentující strukturu instance na vytváření příznaků
 * @author hintik
 *
 */
public abstract class Symptom {

	static Random rand = new Random(System.currentTimeMillis());
	
	/**
	 * Metoda pro získání příznakového vektoru z obrázku uloženém v souboru daného cestou
	 * @param imgFilePath cesta k souboru obrázku
	 * @return příznakový vektor
	 */
	float[] getSymptom(String imgFilePath) {
		File f = new File(imgFilePath);
		if(!f.exists()) {
			return null;
			
		}
		
		try {
			return getSymptom(ImageIO.read(f));
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		return null;
	}
	
	/**
	 * Metoda pro získání příznakového vektoru z obrázku uloženém v zadaném souboru
	 * @param imgFile soubor obrázku
	 * @return příznakový vektor
	 */
	float[] getSymptom(File imgFile) {
		
		try {
			return getSymptom(ImageIO.read(imgFile));
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return null;
		
	}
	
	/**
	 * Metoda pro získání příznakového vektoru z obrázku
	 * @param img vstupní obrázek
	 * @return příznakový vektor
	 */
	abstract float[] getSymptom(BufferedImage img);
	
	/**
	 * Vypočítání euklidovacké vzdálenosti mezi dvěma vektory	
	 * @param vector1
	 * @param vector2
	 * @return euklidovská vzdálenost
	 */
	public static float distance(float[] vector1, float[] vector2) {
		float sum = 0;
		
		for(int i = 0; i < vector1.length; i++) {
			float sub = Math.abs(vector1[i] - vector2[i]);
			sum += sub * sub;
		}
		
		return (float) Math.sqrt(sum);
	}
	
	/**
	 * Metoda pro vygenerování náhodného příznaku
	 * @param width velikost generovaného vektoru
	 * @return vygenerovaný příznak
	 */
	public static float[] getRandomSymptom(int width) {
		float[] result = new float[width];
		
		for(int i = 0; i < result.length; i++) {
			result[i] = (float) rand.nextFloat();
		}
		
		return result;
	}
}