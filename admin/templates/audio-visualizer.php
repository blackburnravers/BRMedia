<?php
/**
 * BRMedia Audio Template – Visualizer Style
 * Real-time audio visualization using Web Audio API
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Expected: $post_id, $media_url, $cover_art, $track_title, $settings
?>

<div class="brmedia-player audio-visualizer" data-player-id="<?php echo esc_attr( $post_id ); ?>">
    <?php if ( ! empty( $cover_art ) ) : ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url( $cover_art ); ?>" alt="<?php echo esc_attr( $track_title ); ?>">
        </div>
    <?php endif; ?>

    <div class="brmedia-info">
        <div class="brmedia-title"><?php echo esc_html( $track_title ); ?></div>
    </div>

    <div class="brmedia-visualizer-wrapper">
        <canvas id="visualizer-<?php echo esc_attr( $post_id ); ?>" height="100"></canvas>
    </div>

    <div class="brmedia-controls">
        <audio id="audio-<?php echo esc_attr( $post_id ); ?>" controls preload="none">
            <source src="<?php echo esc_url( $media_url ); ?>" type="audio/mpeg">
            <?php _e( 'Your browser does not support the audio element.', 'brmedia' ); ?>
        </audio>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const audio = document.getElementById('audio-<?php echo esc_js( $post_id ); ?>');
        const canvas = document.getElementById('visualizer-<?php echo esc_js( $post_id ); ?>');
        const ctx = canvas.getContext('2d');

        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = audioCtx.createAnalyser();
        analyser.fftSize = 256;

        const source = audioCtx.createMediaElementSource(audio);
        source.connect(analyser);
        analyser.connect(audioCtx.destination);

        const bufferLength = analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);

        function draw() {
            requestAnimationFrame(draw);

            analyser.getByteFrequencyData(dataArray);

            ctx.fillStyle = '#111';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const barWidth = (canvas.width / bufferLength) * 2.5;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                const barHeight = dataArray[i];
                ctx.fillStyle = 'rgb(' + (barHeight+50) + ',50,150)';
                ctx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);
                x += barWidth + 1;
            }
        }

        audio.addEventListener('play', () => {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            draw();
        });
    });
    </script>

    <?php do_action( 'brmedia_after_player_controls', $post_id, $media_url ); ?>
</div>