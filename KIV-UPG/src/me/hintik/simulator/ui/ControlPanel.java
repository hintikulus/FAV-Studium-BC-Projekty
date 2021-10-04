package me.hintik.simulator.ui;

import java.awt.BorderLayout;
import java.awt.Dimension;
import java.awt.FlowLayout;
import java.awt.GridLayout;
import java.awt.Insets;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;
import java.text.DecimalFormat;

import javax.swing.BorderFactory;
import javax.swing.BoxLayout;
import javax.swing.ButtonGroup;
import javax.swing.JButton;
import javax.swing.JPanel;
import javax.swing.JRadioButton;
import javax.swing.JSlider;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;

import me.hintik.simulator.Main;

/**
 * Trida se stara o vytvoreni ovladacich prvku simulace a vizualizace
 * @author hintik
 *
 */
public class ControlPanel extends JPanel {

	private static final long serialVersionUID = 1L;

	/** Format ciselnych hodnot */
	DecimalFormat f = new DecimalFormat("0.0");
	
	/** Tlacitko ovladaciho panelu */
	JButton btnMoveLeft, btnMoveUp, btnMoveRight, btnMoveDown, btnMoveReset,
		btnZoomIn, btnZoomOut,
		btnSpeedUp, btnSpeedDown, btnPlayPause
		;
	
	/** Posouvac priblizeni */
	JSlider sliderZoom;
	
	/** Panely obsazene v kontorlim panelu */
	JPanel navigation, zoom, speed, brushes;
	
	/** Skupina tlacitek */
	ButtonGroup btnGroupBrush;
	/** Radio tlacitka pro vyber nastroje */
	JRadioButton radioBrushSelect, radioBrushMove;
	
	/** Konstruktor pro vytvoreni kontrolniho panelu, inicializaci komponent a rozvrzeni komponent */
	public ControlPanel() {
		this.setPreferredSize(new Dimension(130, 480));
		this.setLayout(new BoxLayout(this, BoxLayout.Y_AXIS));
		
		initialize();
		initListeners();
		
		sliderZoom.setMajorTickSpacing(10);
		sliderZoom.setMinorTickSpacing(5);
		sliderZoom.setPaintTicks(true);
		sliderZoom.setPaintLabels(false);
		
		JPanel navCross = new JPanel();
		navCross.setLayout(new GridLayout(3,3));

		Insets insets = new Insets(5,5,5,5);
		
		btnMoveUp.setMargin(insets);
		btnMoveLeft.setMargin(insets);
		btnMoveReset.setMargin(insets);
		btnMoveRight.setMargin(insets);
		btnMoveDown.setMargin(insets);
		
		btnSpeedDown.setMargin(insets);
		btnSpeedUp.setMargin(insets);
		btnPlayPause.setMargin(insets);
		
		navCross.add(new JPanel());
		navCross.add(btnMoveUp);
		navCross.add(new JPanel());
		navCross.add(btnMoveLeft);
		navCross.add(btnMoveReset);
		navCross.add(btnMoveRight);
		navCross.add(new JPanel());
		navCross.add(btnMoveDown);
		
		JPanel zoomComponents = new JPanel(new BorderLayout());
		JPanel zoomIn = new JPanel(new FlowLayout(FlowLayout.CENTER));
		zoomIn.add(btnZoomIn);
		zoomComponents.add(zoomIn, BorderLayout.NORTH);
		JPanel zoomOut = new JPanel(new FlowLayout(FlowLayout.CENTER));
		zoomOut.add(btnZoomOut);
		zoomComponents.add(zoomOut, BorderLayout.SOUTH);
		zoomComponents.add(sliderZoom, BorderLayout.CENTER);
		
		JPanel speedButtons = new JPanel(new GridLayout(1,3));
		speedButtons.add(btnSpeedDown);
		speedButtons.add(btnPlayPause);
		speedButtons.add(btnSpeedUp);
		
		
		JPanel speedComponents = new JPanel(new BorderLayout());
		speedComponents.add(speedButtons, BorderLayout.CENTER);
		
		JPanel brushesComponents = new JPanel(new GridLayout(2,1));
		brushesComponents.add(radioBrushSelect);
		brushesComponents.add(radioBrushMove);
		

		navigation = new JPanel(new BorderLayout());
		navigation.setBorder(BorderFactory.createTitledBorder("Navigace"));
		navigation.add(navCross, BorderLayout.CENTER);
		Dimension navDimension = new Dimension(130, 150);
		navigation.setMinimumSize(navDimension);
		navigation.setMaximumSize(navDimension);
		navigation.setPreferredSize(navDimension);
		
		zoom = new JPanel(new BorderLayout());
		zoom.setBorder(BorderFactory.createTitledBorder("Zoom"));
		zoom.add(zoomComponents, BorderLayout.CENTER);
		
		speed = new JPanel(new BorderLayout());
		speed.setBorder(BorderFactory.createTitledBorder("Rychlost"));
		speed.add(speedComponents, BorderLayout.CENTER);
		Dimension speedDimension = new Dimension(130, 55);
		speed.setMinimumSize(speedDimension);
		speed.setMaximumSize(speedDimension);
		speed.setPreferredSize(speedDimension);
		
		brushes = new JPanel(new BorderLayout());
		brushes.setBorder(BorderFactory.createTitledBorder("Nástroje"));
		brushes.add(brushesComponents, BorderLayout.SOUTH);
		Dimension brushesDimension = new Dimension(130, 71);
		brushes.setMinimumSize(brushesDimension);
		brushes.setMaximumSize(brushesDimension);
		brushes.setPreferredSize(brushesDimension);
		
		this.add(navigation);
		this.add(zoom);
		this.add(speed);
		this.add(brushes);
		
	}
	
	/**
	 * Metoda aktualizuje informaci o aktualnim priblizeni/oddaleni
	 */
	public void updateZoomInformation() {
		zoom.setBorder(BorderFactory.createTitledBorder("Zoom (" + f.format(Main.drawingPanel.zoom) + "x)"));
	}
	
	/**
	 * Metoda aktualizuje hodnoty v posouvaci
	 */
	public void updateZoomSliderValue() {
		sliderZoom.setValue(toSliderValue(Main.drawingPanel.zoom));
	}
	
	/**
	 * Metoda prevede hodnotu priblizeni na hodnotu slideru
	 * @param zoom hodnota priblizeni
	 * @return hodnota slideru
	 */
	private int toSliderValue(double zoom) {
		return (int) (zoom*9.6);
	}
	
	/**
	 * Inicializace tlacitek a slideru
	 */
	private void initialize() {
		btnMoveLeft = new JButton("◄");
		btnMoveUp = new JButton("▲");
		btnMoveRight = new JButton("►");
		btnMoveDown = new JButton("▼");
		btnMoveReset = new JButton("•");
		
		btnZoomIn = new JButton("▲");
		btnZoomOut = new JButton("▼");
		
		btnPlayPause = new JButton("▍ ▍");
		btnSpeedDown = new JButton("◄◄");
		btnSpeedUp = new JButton("►►");
		
		sliderZoom = new JSlider(JSlider.VERTICAL, 1, 96, 6);
		
		btnGroupBrush = new ButtonGroup();
		radioBrushSelect = new JRadioButton("Výběr");
		radioBrushMove = new JRadioButton("Pohyb");
		radioBrushSelect.setSelected(true);
		btnGroupBrush.add(radioBrushMove);
		btnGroupBrush.add(radioBrushSelect);
		
	}
	
	/**
	 * Metoda pripravy posluchaze na udalosti
	 */
	private void initListeners() {
		sliderZoom.addChangeListener(new ChangeListener() {
			
			@Override
			public void stateChanged(ChangeEvent e) {
				Main.drawingPanel.zoom = (sliderZoom.getValue()/10.0) +0.4;
				updateZoomInformation();
				Main.drawingPanel.repaint();
			}
		});
		
		btnMoveLeft.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				Main.drawingPanel.posunX += 10;
				Main.drawingPanel.repaint();
				
			}
		});
		
		btnMoveUp.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				Main.drawingPanel.posunY += 10;
				Main.drawingPanel.repaint();
				
			}
		});
		
		btnMoveRight.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				
				Main.drawingPanel.posunX -= 10;
				Main.drawingPanel.repaint();
				
			}
		});
		
		btnMoveDown.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				Main.drawingPanel.posunY -= 10;
				Main.controlPanel.repaint();
			}
		});
		
		btnMoveReset.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				Main.drawingPanel.posunX = 0;
				Main.drawingPanel.posunY = 0;
				Main.drawingPanel.repaint();
				
			}
		});
		
		btnZoomIn.addActionListener(new ActionListener() {
					
			@Override
			public void actionPerformed(ActionEvent e) {
				if(Main.drawingPanel.zoom+0.1 < 10) {
					sliderZoom.setValue(sliderZoom.getValue()+1);
				} else {
					sliderZoom.setValue(96);
				}
				updateZoomInformation();
				//updateZoomSliderValue();
				Main.drawingPanel.repaint();
				
				
			}
		});
		
		btnZoomOut.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				if(Main.drawingPanel.zoom-0.1 > 0.5) {
					sliderZoom.setValue(sliderZoom.getValue()-1);
				} else {
					sliderZoom.setValue(1);
				}
				updateZoomInformation();
				//updateZoomSliderValue();
				Main.drawingPanel.repaint();
				
			}
		});
		
		btnSpeedUp.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				if(Main.speed < 3) {
					Main.speed = (double) Math.round((Main.speed+0.2)*10) * 0.1;
					speed.setBorder(BorderFactory.createTitledBorder("Rychlost (" + f.format(Main.speed) + "x)"));
				}
				
			}
		});
		
		btnSpeedDown.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				if(Double.compare(Main.speed, 0.2) > 0) {
					Main.speed = (double) Math.round((Main.speed-0.2)*10) * 0.1;
					speed.setBorder(BorderFactory.createTitledBorder("Rychlost (" + f.format(Main.speed) + "x)"));
				}
				
			}
		});
		
		btnPlayPause.addActionListener(new ActionListener() {
			
			@Override
			public void actionPerformed(ActionEvent e) {
				Main.isPaused = !Main.isPaused;
				
				Main.drawingPanel.repaint();
				
				if(Main.isPaused) {
					btnPlayPause.setText("►");
				} else {
					btnPlayPause.setText("▍ ▍");
				}
			}
		});

		radioBrushSelect.addItemListener(new ItemListener() {
			
			@Override
			public void itemStateChanged(ItemEvent e) {
				if(e.getStateChange() == ItemEvent.SELECTED) {
					Main.drawingPanel.brush = 0;
				}
				
			}
		});
		
		radioBrushMove.addItemListener(new ItemListener() {
			
			@Override
			public void itemStateChanged(ItemEvent e) {
				if(e.getStateChange() == ItemEvent.SELECTED) {
					Main.drawingPanel.brush = 1;
				}
				
			}
		});
		
	}
	
}
