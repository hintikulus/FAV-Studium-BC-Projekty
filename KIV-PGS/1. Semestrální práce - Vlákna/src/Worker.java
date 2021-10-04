/**
 * Class representing a worker
 * @author hintik
 *
 */
public class Worker implements Runnable {

	private static int counter = 0;
	
	private int number;
	private WorkSpace workSpace;
	private int workToDo;
	private int workDone;
	private int workSum;
	
	/**
	 * Constructor fills the attributes
	 * @param workSpace assigned workplace
	 */
	Worker(WorkSpace workSpace) {
		this.workDone = 0;
		this.workToDo = 0;
		this.workSum = 0;
		this.workSpace = workSpace;
		this.number = getCount();
	}
	
	/**
	 * Executing thread code
	 */
	public void run() {
		int work;
		while((work = this.workSpace.getForeman().giveWork()) != 0) {
			this.workToDo = work;
			this.workSum += work;
			
			long time = System.currentTimeMillis();
			while(workToDo != 0) {
				long time2 = System.currentTimeMillis();
				try {
					Thread.sleep(WorkSpace.getRandom().nextInt(this.workSpace.tWorker)+1);
					workToDo--;
					workDone++;
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
				double mineTime = (System.currentTimeMillis() - time2)/(double)1000; 
				Writer.print(this, "mined_source " + mineTime + "s");
			}
			double mineTime = (System.currentTimeMillis() - time)/(double)1000; 
			Writer.print(this, "mined_block " + mineTime + "s");
			
			while(workDone != 0) {
				
				this.workSpace.load(this);
			}
			
		}
		
		this.workSpace.getForeman().getLatch().countDown();
	}
	
	/**
	 * Getter for the thread number
	 * @return thread number
	 */
	public int getNumber() {
		return this.number;
	}
	
	/**
	 * Mark work as done
	 * @param value how much work is done
	 */
	public void submit(int value) {
		this.workDone -= value;
	}
	
	/**
	 * Getter to find out how much work was assigned to the worker
	 * @return total assigned work to the worker
	 */
	public int getTotalWork() {
		return this.workSum;
	}

	/**
	 * Getter for the thread number initialization
	 * @return thread number
	 */
	private static int getCount() {
		return counter++;
	}
}
