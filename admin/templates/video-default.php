<?php
/**
 * BRMedia Video Template – Default Style
 * Simple and clean video player template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Expected variables: $post_id, $media_url, $poster_image, $video_title, $settings
?>

<div class="brmedia-player video-default" data-player-id="<?php echo esc_attr( $post_id ); ?>">
    <div class="brmedia-video-container">
        <video width="100%" height="auto" controls poster="<?php echo esc_url( $poster_image ); ?>">
            <source src="<?php echo esc_url( $media_url ); ?>" type="video/mp4">
            <?php _e( 'Your browser does not support the video element.', 'brmedia' ); ?>
        </video>
    </div>

    <div class="brmedia-video-info">
        <div class="brmedia-title"><?php echo esc_html( $video_title ); ?></div>
    </div>

    <?php do_action( 'brmedia_after_player_controls', $post_id, $media_url ); ?>
</div>