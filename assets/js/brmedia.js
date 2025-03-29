document.addEventListener('DOMContentLoaded', function () {
    const players = document.querySelectorAll('.brmedia-player');

    players.forEach(player => {
        const audio = player.querySelector('audio');
        const playBtn = player.querySelector('[data-action="play"]');
        const pauseBtn = player.querySelector('[data-action="pause"]');

        if (!audio) return;

        // Basic controls
        if (playBtn) {
            playBtn.addEventListener('click', () => {
                pauseAllOthers(audio);
                audio.play();
            });
        }

        if (pauseBtn) {
            pauseBtn.addEventListener('click', () => {
                audio.pause();
            });
        }

        // Optional: auto-pause others
        audio.addEventListener('play', () => {
            pauseAllOthers(audio);
        });

        // Optional: log play event (for AJAX stats tracking)
        audio.addEventListener('play', () => {
            const mediaId = player.getAttribute('data-player-id');
            if (mediaId) {
                fetch(brmedia_vars.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `action=brmedia_log_play&media_id=${mediaId}&nonce=${brmedia_vars.nonce}`
                });
            }
        });
    });

    function pauseAllOthers(current) {
        document.querySelectorAll('audio').forEach(audio => {
            if (audio !== current) {
                audio.pause();
            }
        });
    }
});