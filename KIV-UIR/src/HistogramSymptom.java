import java.awt.image.BufferedImage;

/**
 * Třída pro tvorbu příznaků typu HISTOGRAM.
 * @author hintik
 *
 */
public class HistogramSymptom extends Symptom {

	/**
	 * Metoda na základně vstupního obrázku vytvoří příznakový vektor, kdy sečte hodnoty
	 * v každém řádku a tyto součty jsou uloženy do výsledného vektoru. 
	 */
	@Override
	float[] getSymptom(BufferedImage img) {
		
		float[] imgData = new float[img.getHeight()];
		int[] imgD = new int[img.getWidth() * img.getHeight()];
		img.getRGB(0, 0, img.getWidth(), img.getHeight(), imgD, 0, img.getWidth());

		for(int i = 0; i < img.getHeight(); i++) {
			for(int j = 0;  j < img.getWidth(); j++) {
				float c = (float) ((imgD[i*img.getHeight()+j] & 0x000000FF)/255.0);
				imgData[i] += c;
			}
		}
		
		for(int i = 0; i < imgData.length; i++) {
			imgData[i] = (float) (imgData[i] / img.getWidth());
		}
		return imgData;
	}
}
