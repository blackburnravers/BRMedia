<?php
/**
 * BRMedia Music Shortcode Output
 * Renders selected audio template for music CPT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id     = get_the_ID();
$media_url   = get_post_meta( $post_id, '_brmedia_media_url', true );
$cover_art   = get_post_meta( $post_id, '_brmedia_cover_image', true );
$track_title = get_the_title( $post_id );

// Get template setting
$options         = get_option( 'brmedia_templates' );
$template_style  = $options['template_style'] ?? 'default';

$template_file = BRMEDIA_PATH . 'admin/templates/audio-' . $template_style . '.php';

if ( file_exists( $template_file ) ) {
    include $template_file;
} else {
    echo '<p>' . __( 'Player template not found.', 'brmedia' ) . '</p>';
}