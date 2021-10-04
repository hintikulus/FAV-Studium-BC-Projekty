import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Scanner;

/**
 * The main class of the project
 * @author hintik
 *
 */
public class Main {

	/** Simulation start time */
	static long startTime;
	
	/**
	 * Entry point of the program
	 * @param args arguments from the console
	 */
	public static void main(String[] args) {
		
		
		if(args.length != 14) {
			System.out.println("Incorrect number of arguments entered");
			return;
		}
	
		String inputFilePath = "";
		String outputFilePath = "";
		int tWorker = 100, cWorker = 3, capLorry = 10, tLorry = 100, capFerry = 10;
		
		boolean[] arguments = new boolean[7];
		Arrays.fill(arguments, false);
		
		for(int i = 0; i < args.length*0.5 ; i++) {
			if(args[i*2].equalsIgnoreCase("-i")) {
				inputFilePath = args[i*2+1];
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-o")) {
				outputFilePath = args[i*2+1];
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-cWorker")) {
				cWorker = Integer.parseInt(args[i*2+1]);
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-tWorker")) {
				tWorker = Integer.parseInt(args[i*2+1]);
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-capLorry")) {
				capLorry = Integer.parseInt(args[i*2+1]);
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-tLorry")) {
				tLorry = Integer.parseInt(args[i*2+1]);
				arguments[i] = true;
				continue;
			}
			if(args[i*2].equalsIgnoreCase("-capFerry")) {
				capFerry = Integer.parseInt(args[i*2+1]);
				arguments[i] = true;
				continue;
			}
		}
		
		boolean allArgumentsInserted = true;
		
		for(boolean a : arguments) {
			allArgumentsInserted &= a;
		}
			
		
		if(!allArgumentsInserted) {
			System.out.println("Input data was not entered correctly");
			return;
		}
		startTime = System.currentTimeMillis();
		
		File outputFile = new File("output.txt");
		if(!outputFile.exists()) {
			try {
				outputFile.createNewFile();
			} catch (IOException e) {

				System.out.println("File output error");
				return;
			}
		}
		
		try {
			Writer.setInstance(new PrintWriter(outputFile));
		} catch (FileNotFoundException e) {
			System.out.println("File output error");
			return;
		}
		
		File inputFile = new File(inputFilePath);
		if(!inputFile.exists()) {
			System.out.println("Input file does not exist.");
			return;
		}
		
		Scanner scan;
		try {
			scan = new Scanner(inputFile);
		} catch (FileNotFoundException e) {
			System.out.println("Input file error.");
			return;
		}
		List<String> input = new ArrayList<String>();
		while(scan.hasNextLine()) {
			input.add(scan.nextLine());
		}
		
		scan.close();
		
		System.out.println("Vstupní parametry:");
		System.out.println("Vstup: " + inputFilePath);
		System.out.println("Výstup: " + outputFilePath);
		System.out.println("cWorker: " + cWorker);
		System.out.println("tWorker: " + tWorker);
		System.out.println("capLorry: " + capLorry);
		System.out.println("tLorry: " + tLorry);
		System.out.println("capFerry: " + capFerry);
		
		new WorkSpace(input, cWorker, tWorker, capLorry, tLorry, capFerry);
		
	}
}
