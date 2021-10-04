
/**
 * Výčtový typ pro typy reprezentace obrázku
 * @author hintik
 *
 */
public enum SymptomType {
	DATA("Data obrázku"), HISTOGRAM("Histogram podle řádků");
	
	String name;
	
	private SymptomType(String name) {
		this.name = name;	
	}
	
	/**
	 * Metoda vytvoří a vrátí instanci třídy pro vytváření příznakových vektorů
	 * @param type
	 * @return
	 */
	public static Symptom getInstance(SymptomType type) {
		switch(type) {
		case DATA:
			return new ImageDataSymptom();
		case HISTOGRAM:
			return new HistogramSymptom();
		}
		
		return null;
	}
}