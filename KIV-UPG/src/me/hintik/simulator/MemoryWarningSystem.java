package me.hintik.simulator;

import javax.management.*;
import java.lang.management.*;
import java.util.*;

/**
 * Trida se stara o zavolani posluchace, kdyz program prekroci
 *  urcenou hodnotu volne pameti.
 */
public class MemoryWarningSystem {
	
  private final Collection<Listener> listeners =
      new ArrayList<Listener>();

  public interface Listener {
    public void memoryUsageLow(long usedMemory, long maxMemory);
  }

  public MemoryWarningSystem() {
    MemoryMXBean mbean = ManagementFactory.getMemoryMXBean();
    NotificationEmitter emitter = (NotificationEmitter) mbean;
    emitter.addNotificationListener(new NotificationListener() {
      public void handleNotification(Notification n, Object hb) {
        if (n.getType().equals(
            MemoryNotificationInfo.MEMORY_THRESHOLD_EXCEEDED)) {
          long maxMemory = tenuredGenPool.getUsage().getMax();
          long usedMemory = tenuredGenPool.getUsage().getUsed();
          for (Listener listener : listeners) {
            listener.memoryUsageLow(usedMemory, maxMemory);
          }
        }
      }
    }, null, null);
  }

  public boolean addListener(Listener listener) {
    return listeners.add(listener);
  }

  public boolean removeListener(Listener listener) {
    return listeners.remove(listener);
  }

  private static final MemoryPoolMXBean tenuredGenPool =
      findTenuredGenPool();

  /**
   * Metoda nastavi sledovanou hodnotu
   * @param percentage sledovane procento vyuziti pameti
   */
  public static void setPercentageUsageThreshold(double percentage) {
    if (percentage <= 0.0 || percentage > 1.0) {
      throw new IllegalArgumentException("Toto neni validni hodnota procenta vyuziti");
    }
    
    long maxMemory = tenuredGenPool.getUsage().getMax();
    long warningThreshold = (long) (maxMemory * percentage);
    tenuredGenPool.setUsageThreshold(warningThreshold);
  }

  /**
   * Tenured Space Pool can be determined by it being of type
   * HEAP and by it being possible to set the usage threshold.
   */
  private static MemoryPoolMXBean findTenuredGenPool() {
    for (MemoryPoolMXBean pool :
        ManagementFactory.getMemoryPoolMXBeans()) {
      // I don't know whether this approach is better, or whether
      // we should rather check for the pool name "Tenured Gen"?
      if (pool.getType() == MemoryType.HEAP &&
          pool.isUsageThresholdSupported()) {
        return pool;
      }
    }
    throw new AssertionError("Could not find tenured space");
  }
}
