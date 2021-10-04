import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Scanner;

/**
 * Hlavní třída programu
 * @author hintik
 *
 */
public class Main {
	
	private static SymptomType symptomType; 
	private static ClassifierType classifierType;
	private static Symptom symptom;
	private static Classifier classifier;
	
	/**
	 * Vstupní bod programu
	 * Metoda zkontroluje vstupní parametry a podle nich spustí funkce
	 * @param args
	 */
	public static void main(String[] args) {
		
		if(args.length == 0) {
			setup();
		}
		
		if(args.length == 1) {
			File modelFile = new File(args[0]);
			
			if(!modelFile.exists()) {
				System.out.println("Na uvedeném místě se soubor nenechází");
			}
			
			try {
				load(modelFile);
			} catch (FileNotFoundException e) {
				System.out.println("Chyba při načítání souboru");
				return;
			}
			
			new Window(classifier, symptom);
			
		}
		
		if(args.length == 5) {
			File trainingFolder = new File(args[0]);
			File testingFolder = new File(args[1]);
			symptomType = SymptomType.valueOf(args[2]);
			classifierType = ClassifierType.valueOf(args[3]);
			symptom = SymptomType.getInstance(symptomType);
			classifier = ClassifierType.getInstance(classifierType);
			File modelFile = new File(args[4]);
			
			classifier.train(trainingFolder, symptom);
			
			try {
				save(modelFile, classifier);
			} catch (IOException e) {
				System.out.println("Při ukládání souboru došlo k chybě.");
			}
			

			float accuracy = classifier.test(testingFolder, symptom) * 100;
			System.out.println("Celková úspěšnost: " + accuracy + "%");
			
		}
		
	}
	
	/**
	 * Textové rozhraní pro ovládání aplikace
	 */
	public static void setup() {
			Scanner scan = new Scanner(System.in);
			
			System.out.println("Semestrální práce z UIR");
			System.out.println("Jan Hinterholzinger");
			System.out.println("-----------------------");
			System.out.println("Spuštěno bez parametru");
			System.out.println();
			
			int volba;
			
			do {
				System.out.println("Zvolte mód programu");
				System.out.println(" 1 - vytvoření modelu");
				System.out.println(" 2 - použití modelu");
				System.out.print("> ");
				try {
					volba = Integer.parseInt(scan.nextLine());
					if(volba >= 1 && volba <= 2) {
						break;
					} else {
						throw new IllegalArgumentException();
					}
					
				} catch(Exception e) {
					System.out.println("\nNeznámá volba!\n");
					continue;
				}	
			} while(true);
			
			System.out.println();
			
			if(volba == 2) {
				File f;
				do {
					System.out.println("Zadejte cestu k existujícímu modelu");
					System.out.print("> ");
					
					f = new File(scan.nextLine());
					
					if(!f.exists() || !f.isFile()) {
						System.out.println("\nZadaná cesta nevede k žádnému souboru\n");
						continue;
					} else {
						try {
							load(f);
						} catch (FileNotFoundException e) {
							System.out.println("\nZadaná cesta nevede k žádnému souboru\n");
							continue;
						}
						
						break;
					}
					
				} while(true);
				
				new Window(classifier, symptom);
				
			} else if(volba == 1) {
				File trainingFolderPath;
				File testingFolderPath;
				
				do {
					System.out.println("Zadejte cestu k adresáři s trénovacími daty");
					System.out.print("> ");
					
					trainingFolderPath = new File(scan.nextLine());
					
					if(!trainingFolderPath.isDirectory()) {
						System.out.println("\nZadaná cesta není adresář\n");
						continue;
					} else {
						break;
					}
						
				} while(true); 
				
				System.out.println();
				
				do {
					System.out.println("Zadejte cestu k adresáři s testovacími daty");
					System.out.print("> ");
					
					testingFolderPath = new File(scan.nextLine());
					
					if(!testingFolderPath.isDirectory()) {
						System.out.println("\nZadaná cesta není adresář\n");
						continue;
					} else {
						break;
					}
						
				} while(true);
				
				System.out.println();
				
				do {
					System.out.println("Zadejte parametrizační algoritmus z výběru");
					for(int i = 0; i < SymptomType.values().length; i++) {
						System.out.println((i+1) + " - " + SymptomType.values()[i]);
					}
					System.out.print("> ");
					
					try {
						volba = Integer.parseInt(scan.nextLine());
						
						if(volba >= 1 && volba <= SymptomType.values().length) {
							break;
						} else {
							throw new IllegalArgumentException();
						}
					} catch(Exception e) {
						System.out.println("\nNeznámá volba!\n");
						continue;
					}
					
				} while(true);
				
				symptomType = SymptomType.values()[volba - 1];
				symptom = SymptomType.getInstance(symptomType);
				
				System.out.println();
				
				do {
					System.out.println("Zadejte klasifikační algoritmus z výběru");
					for(int i = 0; i < ClassifierType.values().length; i++) {
						System.out.println((i+1) + " - " + ClassifierType.values()[i]);
					}
					System.out.print("> ");
					
					try {
						volba = Integer.parseInt(scan.nextLine());
						
						if(volba >= 1 && volba <= ClassifierType.values().length) {
							break;
						} else {
							throw new IllegalArgumentException();
						}
					} catch(Exception e) {
						System.out.println("\nNeznámá volba!\n");
						continue;
					}
					
				} while(true);
				
				System.out.println();
				File outputFilePath;
				
				System.out.println("Zadejte cestu pro uložení modelu");
				System.out.print("> ");
				
				outputFilePath = new File(scan.nextLine());
				
				classifierType = ClassifierType.values()[volba - 1];
				classifier = ClassifierType.getInstance(classifierType);
				
				System.out.println("\nUčení...");
				classifier.train(trainingFolderPath, symptom);
				try {
					save(outputFilePath, classifier);
				} catch (IOException e) {
					System.out.println("Při ukládání souboru došlo k chybě.");
					e.printStackTrace();
				}
				
				float accuracy = classifier.test(testingFolderPath, symptom) * 100;
				System.out.println("Celková úspěšnost: " + accuracy + "%");
			}
			
			scan.close();
	}
	
	/**
	 * Metoda pro uložení modelu do souboru
	 * @param f soubor do kterého je model ukládán
	 * @param classifier použitý klasifikátor
	 * @throws IOException
	 */
	public static void save(File f, Classifier classifier) throws IOException {
		System.out.println("Ukládání...");
		
		if(!f.exists()) {
			f.createNewFile();
		}
		
		PrintWriter pw = new PrintWriter(f);
		
		pw.println(symptomType + " " + classifierType);
		classifier.save(pw);
		pw.close();

		System.out.println("Model uložen");
	}
	
	/**
	 * Načítání modelu ze souboru
	 * @param f soubor, kde by se měl nacházet model
	 * @throws FileNotFoundException
	 */
	public static void load(File f) throws FileNotFoundException {
				
		Scanner scan = new Scanner(f);
		
		String[] info = scan.nextLine().split(" ");
		
		symptomType = SymptomType.valueOf(info[0]);
		classifierType = ClassifierType.valueOf(info[1]);
		
		symptom = SymptomType.getInstance(symptomType);
		classifier = ClassifierType.getInstance(classifierType);
		
		classifier.load(scan);
		
		scan.close();
	}
	
	public void run(Classifier c, Symptom s) {
		
	}

}
