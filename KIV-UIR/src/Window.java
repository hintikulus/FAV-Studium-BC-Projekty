import javax.swing.BorderFactory;
import javax.swing.Box;
import javax.swing.BoxLayout;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

/**
 * Metoda pro implementaci okenní aplikace
 * @author hintik
 *
 */
public class Window extends JFrame {

	private static final long serialVersionUID = 1L;
	
	Classifier c;
	Symptom s;
	JLabel vysledekLabel;
	
	/**
	 * Konstruktor okna
	 * @param c použitá instance na klasifikaci
	 * @param s použitá instance na tvorbu příznaků
	 */
	public Window(Classifier c, Symptom s) {
		this.c = c;
		this.s = s;
		JButton btn = new JButton("Klasifikovat");
		Drawing d = new Drawing(this);
		this.setTitle("UIR - Semestrální práce - Hinterholzinger");
		this.setLayout(new BorderLayout());
		this.add(d, BorderLayout.CENTER);
		JPanel controlPanel = new JPanel();
		controlPanel.setLayout(new BoxLayout(controlPanel, BoxLayout.Y_AXIS));
		this.add(controlPanel, BorderLayout.EAST);
		controlPanel.add(btn);
		controlPanel.setBorder(BorderFactory.createTitledBorder("Ovládání"));
		controlPanel.add(Box.createRigidArea(new Dimension(0, 30)));
		JLabel vysledekTitleLabel = new JLabel("Výsledek klasifikace:");
		controlPanel.add(vysledekTitleLabel);
		vysledekLabel = new JLabel("-");
		vysledekLabel.setFont(new Font("Arial", Font.PLAIN, 30));
		controlPanel.add(vysledekLabel);
		this.pack();
		
		Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
		
		int x = (int) ((screenSize.getWidth()-this.getWidth())*0.5);
		int y = (int) ((screenSize.getHeight()-this.getHeight())*0.5);
		
		this.setLocation(x, y);
		
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		this.setVisible(true);
		
		btn.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				vysledekLabel.setText(c.classify(s.getSymptom(d.getImage())).value + "");
				
			}
		});
		
		
	}
}
