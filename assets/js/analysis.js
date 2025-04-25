document.addEventListener('DOMContentLoaded', function() {
    const analyzeBtn = document.getElementById('analyze-btn');
    const resultDiv = document.getElementById('analysis-result');
    if (analyzeBtn) {
        analyzeBtn.addEventListener('click', async function() {
            resultDiv.innerHTML = 'Analyzing...';
            try {
                // Placeholder for Essentia.js analysis (simplified)
                // In practice, use Essentia.js APIs as per documentation
                const audioUrl = brmedia.audio_url;
                // Example values (replace with actual Essentia.js calls)
                const bpm = 120; // Simulate BPM detection
                const key = 'C Major'; // Simulate key detection

                resultDiv.innerHTML = `BPM: ${bpm}, Key: ${key} <button id="save-analysis">Save</button>`;
                document.getElementById('save-analysis').addEventListener('click', function() {
                    jQuery.ajax({
                        url: brmedia.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'brmedia_save_analysis',
                            post_id: brmedia.post_id,
                            bpm: bpm,
                            key: key,
                            nonce: brmedia.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Analysis saved successfully!');
                            } else {
                                alert('Failed to save analysis.');
                            }
                        },
                        error: function() {
                            alert('Error occurred during save.');
                        }
                    });
                });
            } catch (error) {
                resultDiv.innerHTML = 'Analysis failed.';
            }
        });
    }
});