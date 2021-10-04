
public enum Stav {
	NOP 		(0, "Zarizeni neni v provozu"),
	TAF 		(1, "Napousteni latky A"),
	TBF			(3, "Napousteni latky B"),
	TAH			(2, "Ohrev latky A"),
	TBH			(4, "Ohrev latky B"),
	DRA	(5, "Vypousteni nadrze");
	
	private final static int COUNT = 6;
	
	private String label;
	private int index;
	
	Stav(int index, String label) {
		this.index = index;
		this.label = label;
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
