
public enum Vystup {

	CA1		(0, "Zapnout cerpadlo latky A"),
	CB1		(1, "Zapnout cerpadlo latky B"),
	V0		(2, "Zavrit vystupni ventil"),
	CA0TOP1 (3, "Vypnout cerpadlo latky A a zapnout topeni"),
	CB0TOP1 (4, "Vypnout cerpadlo latky B a zapnout topení"),
	TOP0V1 	(5, "Vypnout topeni a otevrit vystupni ventil");
	
	
	private final static int COUNT = 6;
	
	String label;
	int index;
	
	Vystup(int index, String label) {
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
