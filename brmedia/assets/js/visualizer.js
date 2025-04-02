// BRMedia Visualizer Script

(function ($) {
    $(document).ready(function () {
        const players = document.querySelectorAll('audio.plyr, video.plyr');

        players.forEach(player => {
            if (player.tagName.toLowerCase() !== 'audio') return;

            const container = player.closest('.brmedia-player-container');
            if (!container) return;

            const canvas = document.createElement('canvas');
            canvas.classList.add('brmedia-visualizer');
            canvas.width = container.offsetWidth;
            canvas.height = 80;

            container.insertBefore(canvas, player);

            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const source = audioCtx.createMediaElementSource(player);
            const analyser = audioCtx.createAnalyser();
            const canvasCtx = canvas.getContext('2d');

            analyser.fftSize = 256;
            const bufferLength = analyser.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);

            source.connect(analyser);
            analyser.connect(audioCtx.destination);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let barHeight;
            let x;

            function renderFrame() {
                requestAnimationFrame(renderFrame);
                x = 0;

                analyser.getByteFrequencyData(dataArray);

                canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < bufferLength; i++) {
                    barHeight = dataArray[i];

                    const r = barHeight + 25;
                    const g = 250 * (i / bufferLength);
                    const b = 50;

                    canvasCtx.fillStyle = `rgb(${r},${g},${b})`;
                    canvasCtx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);

                    x += barWidth + 1;
                }
            }

            // Auto-play the visualizer on play
            player.addEventListener('play', () => {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                renderFrame();
            });

            // Resize canvas on window resize
            window.addEventListener('resize', () => {
                canvas.width = container.offsetWidth;
            });
        });
    });
})(jQuery);