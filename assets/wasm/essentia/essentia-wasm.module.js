let essentiaInstance;

async function initEssentia() {
  const response = await fetch('essentia-wasm.wasm');
  const wasmBuffer = await response.arrayBuffer();
  
  const essentia = new Essentia({
    wasmBinary: wasmBuffer,
    memory: new WebAssembly.Memory({ initial: 256 })
  });

  essentiaInstance = essentia;
  return essentia;
}

export function getEssentia() {
  if (!essentiaInstance) {
    throw new Error('Essentia.js not initialized');
  }
  return essentiaInstance;
}