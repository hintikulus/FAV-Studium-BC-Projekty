import java.util.List;
import java.util.Random;
import java.util.concurrent.locks.ReentrantLock;

/**
 * Class representing workplace and labor relations
 * @author hintik
 *
 */
public class WorkSpace {

	static final char MAP_FULL_CHARACTER = 'x';
	static final char MAP_EMPTY_CHARACTER = ' ';
		
	private Foreman foreman;
	private List<String> map;
	private Lorry lorry;
	private Ferry ferry;
	private Worker[] workers;
	private ReentrantLock lock = new ReentrantLock(true);
	
	private int done = 0;
	
	private static Random rand = new Random(System.currentTimeMillis());
	
	int cWorkers;
	int tWorker;
	int tLorry;
	int capLorry;
	
	/**
	 * The constructor for the workspace, populates the attributes of the instance and starts the action of the foreman.
	 * @param map saved map
	 * @param cWorkers number of workers
	 * @param tWorker the time that will last the extraction of one source
	 * @param lorry capacity 
	 * @param tLorry the time it will take for the lorry to transport the goods from place to place
	 * @param capFerry ferry capacity
	 */
	public WorkSpace(List<String> map, int cWorkers, int tWorker, int capLorry, int tLorry, int capFerry) {

		this.map = map;
		
		this.tWorker = tWorker;
		this.tLorry = tLorry;
		this.cWorkers = cWorkers;
		this.capLorry = capLorry;
		
		this.workers = new Worker[cWorkers];
		this.foreman = new Foreman(this);
		this.setLorry(new Lorry(this));
		this.ferry = new Ferry(capFerry);
		Thread frmn = new Thread(foreman);
		frmn.start();
	}
	
	/**
	 * Method representing loading 1 source to the lorry
	 * @param w Runnable worker that is loading
	 */
	public void load(Worker w) {

		this.lock.lock();
		try {
			// Loading time
			Thread.sleep(1000);
			
			// Transfering sources
			this.getLorry().load(1);
			w.submit(1);
			
			// If the lorry is full, lorry is departing and new apears.
			if(this.getLorry().isFull()) {
				Lorry l = new Lorry(this);
				Thread lorry = new Thread(this.getLorry());
				this.setLorry(l);
				
				lorry.start();
			}
		}
		catch (InterruptedException e) {}
		finally {
			this.lock.unlock();
		}
			
		
	}
	
	/**
	 * Method for adding up the number of goods delivered in total
	 * @param count number of delivered goods
	 */
	public synchronized void done(int count) {
		this.done += count;
	}
	
	/**
	 * Getter for total trasfered goods
	 * @return
	 */
	public int getTotalWorkTransfered() {
		return this.done;
	}
	
	/**
	 * Getter for the foreman of the simulation
	 * @return the foreman of the simulation
	 */
	public Foreman getForeman() {
		return this.foreman;
	}

	/**
	 * Getter for the simulation map
	 * @return the simulation map
	 */
	public List<String> getMap() {
		return map;
	}

	/**
	 * Getter for the ferry
	 * @return ferry
	 */
	public Ferry getFerry() {
		return ferry;
	}

	/**
	 * Getter for lorry
	 * @return lorry
	 */
	public Lorry getLorry() {
		return lorry;
	}

	/**
	 * Setter for lorry
	 * @param lorry lorry
	 */
	public void setLorry(Lorry lorry) {
		this.lorry = lorry;
	}

	/**
	 * Getter for the workers array
	 * @return the workers array
	 */
	public Worker[] getWorkers() {
		return workers;
	}

	/**
	 * Getter for the Random instance
	 * @return Random instance
	 */
	public static Random getRandom() {
		return rand;
	}
	
	
}
