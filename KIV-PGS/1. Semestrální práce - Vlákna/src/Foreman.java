import java.util.LinkedList;
import java.util.Queue;
import java.util.concurrent.CountDownLatch;

/**
 * The class takes care of the redistribution of work to the workers and the final departure of the lorry.
 * @author hintik
 *
 */
public class Foreman implements Runnable {

	private Queue<Integer> blocks;
	private WorkSpace workSpace;
	private int sum = 0;
	private CountDownLatch l;
	
	/**
	 * Constructor of the class. Preparation of variables.
	 * @param workSpace
	 */
	public Foreman(WorkSpace workSpace) {
		this.blocks = new LinkedList<Integer>();
		this.workSpace = workSpace;
		this.l = new CountDownLatch(this.workSpace.cWorkers);
	}
	
	/**
	 * Method for analyze workspace map to find all available sources and blocks.
	 */
	public void analyze() {
		
		for(String s : this.workSpace.getMap()) {
			int croftNumber = 0; 
			for(int i = 0; i < s.length(); i++) {
				if(s.charAt(i) == WorkSpace.MAP_FULL_CHARACTER) {
					croftNumber++;
				} else if(s.charAt(i) == WorkSpace.MAP_EMPTY_CHARACTER) {
					if(croftNumber > 0) {
						blocks.add(croftNumber);
						this.sum += croftNumber;
						croftNumber = 0;
					}
				}
			}
			
			if(croftNumber > 0) {
				blocks.add(croftNumber);
				this.sum += croftNumber;
			}
			
		}
		
		Writer.print(this, this.blocks.size() + " blocks " + this.sum + " sources");
		System.out.println("The Foreman found " + this.sum + " sources in " + this.blocks.size() + " blocks");
	}
	
	/**
	 * Thread workflow. The thread analyzes the map, creates a certain number of workers,
	 * and finally instructs the departure of the last truck.
	 */
	public void run() {
		
		this.analyze();
		
		for(int i = 0; i < this.workSpace.cWorkers; i++) {
			this.workSpace.getWorkers()[i] = new Worker(this.workSpace);
			new Thread(this.workSpace.getWorkers()[i]).start();
		}
		
		//Waiting to all workers to finish the work
		try {
			l.await();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		//If there is something in the truck, the truck leaves
		if(this.workSpace.getLorry().getLoaded() > 0) {
			new Thread(this.workSpace.getLorry()).start();
		}
	
		
	}
	
	/**
	 * Method for work distribution
	 * @return number of resources in the block, 0 if there is no work left
	 */
	public synchronized int giveWork() {
		if(blocks.isEmpty()) return 0;
		return blocks.poll();
	}
	
	/**
	 * Getter for number of found sources on the map.
	 * @return number of sources on the map
	 */
	public int getFoundSources() {
		return this.sum;
	}
	
	/**
	 * Getter for latch
	 * @return latch to wait until all workers are done
	 */
	public CountDownLatch getLatch() {
		return this.l;
	}
	

}
