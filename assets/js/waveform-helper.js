// BRMedia Waveform Helper - Advanced

(function ($) {
    $(document).ready(function () {
        const waveformPlayers = document.querySelectorAll('.brmedia-waveform-player');

        waveformPlayers.forEach(container => {
            const audioUrl = container.dataset.audio;
            const color = container.dataset.color || '#0073aa';
            const progressColor = container.dataset.progressColor || '#1e87f0';
            const waveHeight = parseInt(container.dataset.waveHeight) || 80;

            if (!audioUrl) return;

            const waveformId = 'waveform-' + Math.floor(Math.random() * 1000000);
            const waveformDiv = document.createElement('div');
            waveformDiv.id = waveformId;
            container.appendChild(waveformDiv);

            const playButton = container.querySelector('.brmedia-waveform-play');
            const pauseIcon = playButton?.dataset.pauseIcon || 'fas fa-pause';
            const playIcon = playButton?.dataset.playIcon || 'fas fa-play';

            const wavesurfer = WaveSurfer.create({
                container: `#${waveformId}`,
                waveColor: color,
                progressColor: progressColor,
                height: waveHeight,
                responsive: true,
                barWidth: 2,
                barRadius: 3,
                cursorColor: '#fff',
                normalize: true,
            });

            wavesurfer.load(audioUrl);

            if (playButton) {
                playButton.addEventListener('click', () => {
                    if (wavesurfer.isPlaying()) {
                        wavesurfer.pause();
                        playButton.innerHTML = `<i class="${playIcon}"></i>`;
                    } else {
                        wavesurfer.play();
                        playButton.innerHTML = `<i class="${pauseIcon}"></i>`;
                    }
                });

                wavesurfer.on('finish', () => {
                    playButton.innerHTML = `<i class="${playIcon}"></i>`;
                });
            }

            // Optional: Trigger events or send tracking
            wavesurfer.on('play', () => {
                const postId = container.dataset.postId;
                if (postId) {
                    $.post(brmediaAjax.ajaxurl, {
                        action: 'brmedia_update_media_stats',
                        post_id: postId,
                        action_type: 'play'
                    });
                }
            });

            wavesurfer.on('ready', () => {
                container.classList.add('brmedia-waveform-ready');
            });
        });
    });
})(jQuery);