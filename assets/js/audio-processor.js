class BRMediaAudioProcessor {
  constructor() {
    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    this.analyser = this.audioContext.createAnalyser();
    this.analyser.fftSize = 2048;
    this.bufferLength = this.analyser.frequencyBinCount;
    this.dataArray = new Uint8Array(this.bufferLength);
  }

  async processFile(file) {
    try {
      const buffer = await file.arrayBuffer();
      const audioBuffer = await this.audioContext.decodeAudioData(buffer);
      
      const source = this.audioContext.createBufferSource();
      source.buffer = audioBuffer;
      source.connect(this.analyser);
      this.analyser.connect(this.audioContext.destination);
      
      return {
        duration: audioBuffer.duration,
        sampleRate: audioBuffer.sampleRate,
        analyze: () => this.analyze()
      };
    } catch (error) {
      throw new Error(`Audio processing failed: ${error.message}`);
    }
  }

  analyze() {
    this.analyser.getByteTimeDomainData(this.dataArray);
    return {
      waveform: Array.from(this.dataArray),
      frequencies: this.getFrequencyData()
    };
  }

  getFrequencyData() {
    const freqData = new Uint8Array(this.bufferLength);
    this.analyser.getByteFrequencyData(freqData);
    return Array.from(freqData);
  }
}

// Web Worker implementation
class BRMediaAudioWorker {
  constructor() {
    this.worker = new Worker('assets/js/workers/audio-processor.worker.js');
  }

  analyze(file) {
    return new Promise((resolve, reject) => {
      this.worker.onmessage = e => {
        if (e.data.error) reject(e.data.error);
        resolve(e.data.result);
      };
      
      this.worker.postMessage({ file });
    });
  }
}