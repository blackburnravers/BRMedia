// Web Worker for audio processing
importScripts('vendors/essentia.js');

self.onmessage = async (e) => {
  const { audioData } = e.data;
  
  try {
    const essentia = new Essentia(EssentiaWASM);
    const features = {
      bpm: essentia.RhythmExtractor(audioData).bpm,
      key: essentia.KeyExtractor(audioData).key
    };
    
    self.postMessage({ result: features });
  } catch (error) {
    self.postMessage({ error: error.message });
  }
};