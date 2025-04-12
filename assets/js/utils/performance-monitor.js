class PerformanceMonitor {
  constructor() {
    this.metrics = {
      loadTime: 0,
      memoryUsage: 0,
      fps: 0
    };
    
    this.startMonitoring();
  }

  startMonitoring() {
    // Load time tracking
    this.metrics.loadTime = performance.now();
    
    // Memory monitoring
    if ('memory' in performance) {
      setInterval(() => {
        this.metrics.memoryUsage = performance.memory.usedJSHeapSize;
      }, 5000);
    }

    // FPS tracking
    let frameCount = 0;
    setInterval(() => {
      this.metrics.fps = frameCount;
      frameCount = 0;
    }, 1000);

    const loop = () => {
      frameCount++;
      requestAnimationFrame(loop);
    };
    loop();
  }
}