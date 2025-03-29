<?php
/**
 * BRMedia Audio Template – Default Style
 * Core audio player structure with cover, title, and controls
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Expecting the following variables to be available:
// $post_id, $media_url, $cover_art, $track_title, $settings
?>

<div class="brmedia-player audio-default" data-player-id="<?php echo esc_attr( $post_id ); ?>">
    <?php if ( ! empty( $cover_art ) ) : ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url( $cover_art ); ?>" alt="<?php echo esc_attr( $track_title ); ?>">
        </div>
    <?php endif; ?>

    <div class="brmedia-info">
        <div class="brmedia-title"><?php echo esc_html( $track_title ); ?></div>
    </div>

    <div class="brmedia-controls">
        <audio class="brmedia-audio-element" controls preload="none">
            <source src="<?php echo esc_url( $media_url ); ?>" type="audio/mpeg">
            <?php _e( 'Your browser does not support the audio element.', 'brmedia' ); ?>
        </audio>
    </div>

    <?php do_action( 'brmedia_after_player_controls', $post_id, $media_url ); ?>
</div>