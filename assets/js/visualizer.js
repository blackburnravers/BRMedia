jQuery(document).ready(function($) {

    $('.brmedia-player.visualizer').each(function () {
        const container = $(this);
        const canvas = container.find('canvas')[0];
        const ctx = canvas.getContext('2d');
        const audio = container.find('audio')[0];

        if (!audio || !ctx) return;

        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioCtx.createAnalyser();
        const source = audioCtx.createMediaElementSource(audio);
        source.connect(analyser);
        analyser.connect(audioCtx.destination);

        analyser.fftSize = 128;
        const bufferLength = analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);

        const width = canvas.width = container.width();
        const height = canvas.height = 100;

        function draw() {
            requestAnimationFrame(draw);

            analyser.getByteFrequencyData(dataArray);

            ctx.clearRect(0, 0, width, height);

            const barWidth = (width / bufferLength);
            let barHeight;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i];
                ctx.fillStyle = 'rgba(58, 123, 213, 0.9)';
                ctx.fillRect(x, height - barHeight / 2, barWidth, barHeight / 2);
                x += barWidth + 1;
            }
        }

        // Trigger visualization on play
        audio.addEventListener('play', () => {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            draw();
        });
    });

});