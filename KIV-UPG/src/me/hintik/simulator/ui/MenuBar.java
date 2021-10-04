package me.hintik.simulator.ui;

import java.awt.BorderLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;
import java.awt.print.PrinterException;
import java.awt.print.PrinterJob;

import javax.swing.JCheckBoxMenuItem;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;

import me.hintik.simulator.Main;

/**
 * Trida se stara o vytvoreni nabidky do okna vizualizace
 * @author hintik
 *
 */
public class MenuBar extends JMenuBar implements ItemListener  {

	private static final long serialVersionUID = 1L;
	
	/** Zaskrtavaci tlacitka pro nabidku okna*/
	public JCheckBoxMenuItem MIControlPanel, MIShowLegenda, MIShowMarkers, MIShowTerrain, MIShowWater;
	/** Tlacitka pro nabidku okna */
	public JMenuItem MIPrint, MIExit;
	
	/**
	 * Konstruktor pro inicializaci a rozvrzeni
	 */
	public MenuBar() {
		
		JMenu menuSoubor = new JMenu("Soubor");
			MIPrint = new JMenuItem("Tisk");
			menuSoubor.add(MIPrint);
			MIExit = new JMenuItem("Zavřít");
			menuSoubor.add(MIExit);
		this.add(menuSoubor);
		JMenu menuOkno = new JMenu("Okno");
			MIControlPanel = new JCheckBoxMenuItem("Ovladaci panel");
			MIControlPanel.setSelected(true);
			menuOkno.add(MIControlPanel);
			MIShowLegenda = new JCheckBoxMenuItem("Legenda");
			MIShowLegenda.setSelected(true);
			menuOkno.add(MIShowLegenda);
		this.add(menuOkno);
		JMenu menuMapa = new JMenu("Mapa");
			MIShowMarkers = new JCheckBoxMenuItem("Znacky");
			MIShowMarkers.setSelected(true);
			menuMapa.add(MIShowMarkers);
			MIShowTerrain = new JCheckBoxMenuItem("Teren");
			MIShowTerrain.setSelected(true);
			menuMapa.add(MIShowTerrain);
			MIShowWater = new JCheckBoxMenuItem("Voda");
			MIShowWater.setSelected(true);
			menuMapa.add(MIShowWater);
		this.add(menuMapa);
	
		MIShowMarkers.addItemListener(this);
		MIShowTerrain.addItemListener(this);
		MIShowWater.addItemListener(this);
		

		initListeners();
	}
	

	/**
	 * Inicializace a nastaveni posluchacu pro jednotlive polozky nabidky
	 */
	private void initListeners() {

		
		MIControlPanel.addItemListener(new ItemListener() {
			
			@Override
			public void itemStateChanged(ItemEvent e) {
				if(e.getStateChange() == ItemEvent.SELECTED) {
					Main.frame.add(Main.controlPanel, BorderLayout.EAST);
				} else if(e.getStateChange() == ItemEvent.DESELECTED) {
					Main.frame.remove(Main.controlPanel);
				}
				
				Main.frame.validate();
			}
		});
		MIShowLegenda.addItemListener(new ItemListener() {
			
			@Override
			public void itemStateChanged(ItemEvent e) {
				if(e.getStateChange() == ItemEvent.SELECTED) {
					Main.vizualizacePanel.add(Main.legendaPanel, BorderLayout.WEST);
				} else if(e.getStateChange() == ItemEvent.DESELECTED) {
					Main.vizualizacePanel.remove(Main.legendaPanel);
				}
				
				Main.vizualizacePanel.validate();
			}
		});

		
        MIPrint.addActionListener(new ActionListener() {
            
            @Override
            public void actionPerformed(ActionEvent e) {
                PrinterJob job = PrinterJob.getPrinterJob();
                job.setPrintable(Main.vizualizacePanel);
                job.setJobName("Vizualizace vodniho toku");
                boolean doPrint = job.printDialog();
                if (doPrint) {
                    try {
                        job.print();
                    } catch (PrinterException e1) {
                        JOptionPane.showMessageDialog(Main.frame, "Vyskytla se chyba při tisku.");
                    }
                }
            }
        });
        
        MIExit.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				System.exit(0);
				
			}
		});
	}
	
	/**
	 * Metoda pro udalost zmeneni stavu tlacitka, kdy aktualizuje vizualizaci 
	 */
	@Override
	public void itemStateChanged(ItemEvent e) {
		if(e.getStateChange() == ItemEvent.SELECTED || e.getStateChange() == ItemEvent.DESELECTED) {
			Main.drawingPanel.repaint();
		}
		
	}
}
