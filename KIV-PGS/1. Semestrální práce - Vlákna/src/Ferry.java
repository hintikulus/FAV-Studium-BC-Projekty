import java.util.concurrent.CyclicBarrier;
import java.util.concurrent.Semaphore;

/**
 * Class representing the ferry
 * @author hintik
 *
 */
public class Ferry implements Runnable {
	
	private CyclicBarrier loading;
	private Semaphore semaphore;
	
	private long time;
	private int capacity;
	
	/**
	 * The ferry constructor fills in the attributes
	 * @param capFerry
	 */
	public Ferry(int capFerry) {
		this.loading = new CyclicBarrier(capFerry, this);
		
		this.semaphore = new Semaphore(capFerry, true);
		this.capacity = capFerry;
		time = System.currentTimeMillis();
	}
	
	/**
	 * Executing thread code
	 */
	public void run() {

		double waitingTime = (System.currentTimeMillis() - time)/(double)1000; 
		Writer.print(this, "leaving " + waitingTime + "s");
		System.out.println("The ferry is leaving");
		
		// Simulation of the jurney....
		
		// Arriving
		
		// Simulation of the jurney back
		
		// Arriving
		

		this.time = System.currentTimeMillis();
		semaphore.release(this.capacity);
		
	}

	/**
	 * Getter for a barrier that prevents the ferry from exiting
	 * @return barrier
	 */
	public CyclicBarrier getLoadingBarrier() {
		return this.loading;
	}
	
	/**
	 * Getter for Semaphore dividing the entry permit into a ferry
	 * @return
	 */
	public Semaphore getSemaphore() {
		return this.semaphore;
	}

}
