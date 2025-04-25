# analyze_audio.py
import sys
import essentia.standard as es

# Get audio file path from command line argument
audio_file = sys.argv[1]

# Load audio
audio = es.MonoLoader(filename=audio_file)()

# Detect BPM
rhythm_extractor = es.RhythmExtractor2013()
bpm, _, _, _, _ = rhythm_extractor(audio)

# Detect Key
key_extractor = es.KeyExtractor()
key, scale, _ = key_extractor(audio)

# Output results
print(f"BPM: {bpm}")
print(f"Key: {key} {scale}")