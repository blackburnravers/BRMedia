<?php
/**
 * BRMedia Video Shortcode Output
 * Renders selected video template for video CPT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id      = get_the_ID();
$media_url    = get_post_meta( $post_id, '_brmedia_media_url', true );
$poster_image = get_post_meta( $post_id, '_brmedia_cover_image', true );
$video_title  = get_the_title( $post_id );

// Set fallback poster if not set
$options = get_option( 'brmedia_video' );
if ( empty( $poster_image ) && ! empty( $options['video_poster_fallback'] ) ) {
    $poster_image = esc_url( $options['video_poster_fallback'] );
}

$template_file = BRMEDIA_PATH . 'admin/templates/video-default.php';

if ( file_exists( $template_file ) ) {
    include $template_file;
} else {
    echo '<p>' . __( 'Video player template not found.', 'brmedia' ) . '</p>';
}