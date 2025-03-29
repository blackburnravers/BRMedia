<?php
/**
 * BRMedia Audio Template – Waveform Style
 * Integrates waveform.js or similar visual for audio playback
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Variables assumed to be passed: $post_id, $media_url, $cover_art, $track_title, $settings
?>

<div class="brmedia-player audio-waveform" data-player-id="<?php echo esc_attr( $post_id ); ?>">
    <?php if ( ! empty( $cover_art ) ) : ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url( $cover_art ); ?>" alt="<?php echo esc_attr( $track_title ); ?>">
        </div>
    <?php endif; ?>

    <div class="brmedia-info">
        <div class="brmedia-title"><?php echo esc_html( $track_title ); ?></div>
    </div>

    <div class="brmedia-waveform-container" id="waveform-<?php echo esc_attr( $post_id ); ?>"></div>

    <div class="brmedia-controls">
        <button class="brmedia-play" data-action="play"><i class="fas fa-play"></i></button>
        <button class="brmedia-pause" data-action="pause"><i class="fas fa-pause"></i></button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof WaveSurfer !== 'undefined') {
            const wavesurfer<?php echo esc_js( $post_id ); ?> = WaveSurfer.create({
                container: '#waveform-<?php echo esc_js( $post_id ); ?>',
                waveColor: '#ccc',
                progressColor: '#3a7bd5',
                height: 60,
                responsive: true,
            });

            wavesurfer<?php echo esc_js( $post_id ); ?>.load('<?php echo esc_url( $media_url ); ?>');

            const container = document.querySelector('[data-player-id="<?php echo esc_js( $post_id ); ?>"]');
            container.querySelector('.brmedia-play').addEventListener('click', () => wavesurfer<?php echo esc_js( $post_id ); ?>.play());
            container.querySelector('.brmedia-pause').addEventListener('click', () => wavesurfer<?php echo esc_js( $post_id ); ?>.pause());
        }
    });
    </script>

    <?php do_action( 'brmedia_after_player_controls', $post_id, $media_url ); ?>
</div>