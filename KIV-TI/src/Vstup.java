
public enum Vstup {

	TA1		(0, "Teplota latky A prekrocila urcenou teplotu"),
	TB1		(1, "Teplota latky B prekrocila urcenou teplotu"),
	L21		(2, "Hladina latky stoupla nad horni snimac"),
	L10		(3, "Hladina latky klesla pod spodni snimac"),
	A		(4, "Stisknuto tlacitko A"),
	B		(5, "Stisknuto tlacitko B"),
	END		(6, "Konec programu"),
	UNKNOWN_SIGNAL (7, "Neznámý signál");

	private final static int COUNT = 10;
	
	String label;
	int index;
	
	Vstup(int index, String label) {
		this.label = label;
		this.index = index;
	}
	
	public String getLabel() {
		return this.label;
	}
	
	public int getIndex() {
		return this.index;
	}
	
	public static int getCount() {
		return COUNT;
	}
	
}
