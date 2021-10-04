import java.util.Scanner;

public class Simulation {
	
	Stav stav;
	Vstup vstup;
	
	Stav[][] prech_tab;
	Vystup[][] vyst_tab;
	
	Scanner scan;
			
	public Simulation() {
		scan = new Scanner(System.in);
	}
	
	public void run() {
		inic_tab();
		inic_stav();
		
		System.out.println("Aktualni stav: " + stav.getLabel());
		
		vstup();
		
		while(vstup != Vstup.END) {
			if(vstup == Vstup.UNKNOWN_SIGNAL) {
				System.out.println("Signal nebyl rozpoznan.");
				vstup();
				continue;
			}
			
			vystup(vyst_tab[stav.getIndex()][vstup.getIndex()]);
			if(stav != prech_tab[stav.getIndex()][vstup.getIndex()]) {
				System.out.println("Prechod do stavu: "
						+ prech_tab[stav.getIndex()][vstup.getIndex()].getLabel());
			} else {
				System.out.println("Proces zustava ve stejnem stavu.");
			}
			stav = prech_tab[stav.getIndex()][vstup.getIndex()];
			
			vstup();
		}
		
	}
	
	
	public void inic_tab() {
		
		this.prech_tab = new Stav[][] {
			{Stav.NOP, 		Stav.NOP, 		Stav.NOP, 		Stav.NOP, 	Stav.TAF, 		Stav.TBF},
			{Stav.TAF, 		Stav.TAF, 		Stav.TAH, 		Stav.TAF, 	Stav.TAF, 		Stav.TAF},
			{Stav.DRA, 		Stav.TAH, 		Stav.TAH, 		Stav.TAH, 	Stav.TAH, 		Stav.TAH},
			{Stav.TBF, 		Stav.TBF, 		Stav.TBH, 		Stav.TBF, 	Stav.TBF, 		Stav.TBF},
			{Stav.TBH, 		Stav.DRA, 		Stav.TBH, 		Stav.TBH, 	Stav.TBH, 		Stav.TBH},
			{Stav.DRA, 		Stav.DRA, 		Stav.DRA, 		Stav.NOP, 	Stav.DRA, 		Stav.DRA}
		};
		
		this.vyst_tab = new Vystup[][] {
			{null, 			null, 			null, 				null, 		Vystup.CA1, 	Vystup.CB1},
			{null, 			null, 			Vystup.CA0TOP1, 	null, 		null, 			null},
			{Vystup.TOP0V1,	null,			null,				null, 		null, 			null},
			{null,			null,			Vystup.CB0TOP1, 	null, 		null,			null},
			{null,			Vystup.TOP0V1, 	null,				null,		null,			null},
			{null,			null, 			null, 				Vystup.V0, 	null, 			null}
		};
	}
	
	public void inic_stav() {
		stav = Stav.NOP;
	}

	
	
	public void vstup() {
		System.out.print("\nSimulujte vstup: ");
		String input = scan.nextLine();
		
		if(input.equalsIgnoreCase("A")) {
			vstup = Vstup.A;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("B")) {
			vstup = Vstup.B;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("TA1")) {
			vstup = Vstup.TA1;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("TB1")) {
			vstup = Vstup.TB1;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("L21")) {
			vstup = Vstup.L21;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("L10")) {
			vstup = Vstup.L10;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		if(input.equalsIgnoreCase("QUIT")) {
			vstup = Vstup.END;
			System.out.println("Vstup: " + vstup.label);
			return;
		}
		
		vstup = Vstup.UNKNOWN_SIGNAL;
		
	}
	
	public void vystup(Vystup vystup) {
		if(vystup == null) return;
		System.out.println("Vystup: " + vystup.label);
	}

}
