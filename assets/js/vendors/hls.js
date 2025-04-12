class BRMediaHLSPlayer {
  constructor(videoElement) {
    if (Hls.isSupported()) {
      this.hls = new Hls({
        autoStartLoad: true,
        capLevelToPlayerSize: true,
        debug: false
      });
      this.hls.attachMedia(videoElement);
    }
  }

  loadSource(url) {
    if (this.hls) {
      this.hls.loadSource(url);
    }
  }
}