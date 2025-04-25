// BRMedia custom scripts
document.addEventListener('DOMContentLoaded', function() {
    // Set default volume and initialize WaveSurfer.js for all players
    const players = document.querySelectorAll('.brmedia-player audio');
    players.forEach(function(player, index) {
        // Create a unique container for the waveform
        const container = document.createElement('div');
        container.id = 'wavesurfer-player-' + index;
        player.parentNode.insertBefore(container, player.nextSibling);
        
        // Initialize WaveSurfer.js
        const wavesurfer = WaveSurfer.create({
            container: '#' + container.id,
            waveColor: 'violet',
            progressColor: 'purple',
            height: 100,
            barWidth: 2,
            responsive: true,
            mediaElement: player,
            backend: 'MediaElement' // Ensures compatibility with <audio> elements
        });

        // Apply default volume from settings
        if (typeof brmediaSettings !== 'undefined' && brmediaSettings.defaultVolume) {
            wavesurfer.setVolume(brmediaSettings.defaultVolume);
        }

        // Cache waveform data on first load
        wavesurfer.on('ready', function() {
            const audioUrl = player.src;
            const waveformJson = player.dataset.waveform;
            if (!waveformJson) {
                const peaks = wavesurfer.exportPeaks(1024);
                const formData = new URLSearchParams();
                formData.append('action', 'brmedia_save_waveform');
                formData.append('nonce', brmediaSettings.nonce);
                formData.append('audio_url', audioUrl);
                formData.append('peak_data', JSON.stringify(peaks));
                fetch(brmediaSettings.ajaxurl, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
            }
        });
    });
});