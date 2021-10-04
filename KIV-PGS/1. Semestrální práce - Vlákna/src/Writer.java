import java.io.PrintWriter;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;

/**
 * Class that organize output to file.
 * @author hintik
 *
 */
public class Writer {
	
	private static PrintWriter w;
	static SimpleDateFormat sdf = new SimpleDateFormat("HH:mm:ss.SSS");
	
	/**
	 * Timezone settings
	 */
	static {
		sdf.setTimeZone(TimeZone.getTimeZone("GMT+0"));
	}
	
	/**
	 * Setter for PrintWriter
	 * @param w instance of PrintWriter
	 */
	public static void setInstance(PrintWriter w) {
		Writer.w = w;
	}
	
	/**
	 * Getter for instance of PrintWriter
	 * @return instance of PrintWriter
	 */
	public static PrintWriter getInstance() {
		return Writer.w;
	}
	
	/**
	 * Method for string representation of time since app launch 
	 * @return string representation of time since app launch
	 */
	private static String getTime() {
		return sdf.format(new Date(System.currentTimeMillis()-Main.startTime));
	}
	
	/**
	 * Method that prints line to the file
	 * @param role label of thread role
	 * @param number the thread number with the given role
	 * @param message information message that is printed
	 */
	public static synchronized void print(String role, int number, String message) {
		
		Writer.w.format("%s %s %d %s\n", getTime(), role, number, message);
		Writer.w.flush();
	}
	
	/**
	 * Method for printing a message from a thread 
	 * @param f Runnable Foreman
	 * @param message message that is printed
	 */
	public static void print(Foreman f, String message) {
		print("FOREMAN", 0, message);
	}
	
	/**
	 * Method for printing a message from a thread 
	 * @param w Runnable Worker
	 * @param message message that is printed
	 */
	public static void print(Worker w, String message) {
		print("WORKER", w.getNumber(), message);
	}
	
	/**
	 * Method for printing a message from a thread 
	 * @param l Runnable Lorry
	 * @param message message that is printed
	 */
	public static void print(Lorry l, String message) {
		print("LORRY", l.getNumber(), message);
	}
	
	/**
	 * Method for printing a message from a thread 
	 * @param f Runnable Ferry
	 * @param message message that is printed
	 */
	public static void print(Ferry f, String message) {
		print("FERRY", 0, message);
	}
	
	
}
