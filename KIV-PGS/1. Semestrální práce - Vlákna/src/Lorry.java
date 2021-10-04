import java.util.concurrent.BrokenBarrierException;

/**
 * Class representing a lorry carrying goods
 * @author hintik
 *
 */
public class Lorry implements Runnable {

	private static int counter = 0;
	
	private long time;
	private int number;
	private int capacity;
	private int loaded;
	private WorkSpace workSpace;
	
	/**
	 * Constructor fills the attributes
	 */
	public Lorry(WorkSpace workSpace) {
		this.workSpace = workSpace;
		this.capacity = workSpace.capLorry;
		this.loaded = 0;
		this.number = getCount();
		time = System.currentTimeMillis(); 
	}
	
	/**
	 * Getter to get the number of loaded goods
	 * @return
	 */
	public int getLoaded() {
		return this.loaded;
	}
	
	/**
	 * Getter for information on whether the lorry is full
	 * @return truck is full?
	 */
	public boolean isFull() {
		return this.loaded >= this.capacity;
	}
	
	
	public synchronized void load(int number) {
		this.loaded += number;
	}
	
	/**
	 * Getter for the thread number
	 * @return thread number
	 */
	public int getNumber() {
		return this.number;
	}

	/**
	 * Getter for the thread number initialization
	 * @return thread number
	 */
	private static int getCount() {
		return counter++;
	}
	
	/**
	 * Executing thread code
	 */
	public void run() {
		double time = (System.currentTimeMillis() - this.time)/(double)1000; 
		Writer.print(this, "full " + time + "s");
		this.time = System.currentTimeMillis();
		
		try {
			Thread.sleep(WorkSpace.getRandom().nextInt(this.workSpace.tLorry-1)+1);
			
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		try {
			this.workSpace.getFerry().getSemaphore().acquire();
		} catch (InterruptedException e) {
			e.printStackTrace();
		}
		
		time = (System.currentTimeMillis() - this.time)/(double)1000; 
		Writer.print(this, "arrived_ferry " + time + "s");
		
		try {
			this.workSpace.getFerry().getLoadingBarrier().await();
		} catch (InterruptedException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (BrokenBarrierException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		this.time = System.currentTimeMillis();
		
		try {
			Thread.sleep(WorkSpace.getRandom().nextInt(this.workSpace.tLorry)+1);
			
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		time = (System.currentTimeMillis() - this.time)/(double)1000; 
		Writer.print(this, "finished " + time + "s");
		
		this.workSpace.done(this.loaded);
		
		if(this.workSpace.getTotalWorkTransfered() == this.workSpace.getForeman().getFoundSources()) {
			Writer.getInstance().close();
			for(Worker worker : this.workSpace.getWorkers()) {
				System.out.println("WORKER-" + worker.getNumber() + ": " + worker.getTotalWork() + " sources");
			}
			
			System.out.println("Delivered in total: " + this.workSpace.getTotalWorkTransfered());

		}
		
	}
}
