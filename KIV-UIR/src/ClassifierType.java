/**
 * Výčtový typ dostupných klasifikátorů
 * @author hintik
 *
 */
public enum ClassifierType {
	KNN("Metoda k nejbližších sousedů"),
	MD("Metoda minimální vzdálenosti"),
	EXPERIMENTAL("Experimentální KMeans metoda");
	
	String name;
	
	private ClassifierType(String name) {
		this.name = name;
	}
	
	/**
	 * Získání instance zadaného klasifikátoru
	 * @param type typ klasifikátoru
	 * @return instance klasifikátoru
	 */
	public static Classifier getInstance(ClassifierType type) {
		switch(type) {
		case KNN:
			return new KNNClassifier();
		case MD:
			return new MDClassifier();
		case EXPERIMENTAL:
			return new ExperimentalClassifier();
		}
		
		return null;
		
	}
}
