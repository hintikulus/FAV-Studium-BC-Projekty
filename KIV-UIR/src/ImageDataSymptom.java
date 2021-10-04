import java.awt.image.BufferedImage;

/**
 * Třída pro tvorbu příznaků z obrázku
 * @author hintik
 *
 */
public class ImageDataSymptom extends Symptom {

	@Override
	/**
	 * Metoda vytvoří příznakový vektor z obrázku tak,
	 * že jednotlivé šedotónové pixely ukládá za sebe.
	 */
	float[] getSymptom(BufferedImage img) {

		float[] imgData = new float[img.getWidth() * img.getHeight()];
		int[] imgD = new int[img.getWidth() * img.getHeight()];
		
		img.getRGB(0, 0, img.getWidth(), img.getHeight(), imgD, 0, img.getWidth());
		
		for(int i = 0; i < imgD.length; i++) {
			float c = (float) ((imgD[i] & 0x000000FF)/255.0);
			imgData[i] = (float) (c/255.0);
		}
		
		return imgData;
	}

}
