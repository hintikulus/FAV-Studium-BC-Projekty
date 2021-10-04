/**
 * Třída typu přepravka pro reprezentaci příznaku společně
 * s přiřazenou třídou a případně s dodatečnými daty
 * @author hintik
 *
 */
public class SymptomPoint {

	float[] symptom;
	Class c;
	int meta = -1;
	
	/**
	 * Konstruktor pro vytvoření instrance
	 * @param symptom příznakový vektor
	 * @param c klasifikační třída
	 */
	public SymptomPoint(float[] symptom, Class c) {
		this.c = c;
		this.symptom = symptom;
	}
	
	/**
	 * Vypočtení euklidovské vzdálenosti k zadanému vektoru
	 * @param symptom vektor
	 * @return vypočítaná euklidovská vzdálenost mezi vektory
	 */
	public float distance(float[] symptom) {
		return Symptom.distance(this.symptom, symptom);
	}
}