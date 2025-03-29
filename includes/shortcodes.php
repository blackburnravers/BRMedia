<?php
/**
 * BRMedia Shortcodes
 * Register additional shortcodes beyond the core player
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Example: [brmedia_tracklist id="123"]
add_shortcode( 'brmedia_tracklist', 'brmedia_tracklist_shortcode' );

function brmedia_tracklist_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
    ), $atts );

    $post_id = absint( $atts['id'] );
    if ( ! $post_id ) return '';

    $tracklist = brmedia_get_tracklist( $post_id );
    if ( empty( $tracklist ) ) return '';

    ob_start();
    echo '<ul class="brmedia-tracklist">';
    foreach ( $tracklist as $track ) {
        echo '<li><strong>' . esc_html( $track['time'] ) . '</strong> - ' . esc_html( $track['title'] ) . '</li>';
    }
    echo '</ul>';
    return ob_get_clean();
}

// Example: [brmedia_download id="123"]
add_shortcode( 'brmedia_download', 'brmedia_download_button_shortcode' );

function brmedia_download_button_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
        'label' => __( 'Download Track', 'brmedia' )
    ), $atts );

    $post_id = absint( $atts['id'] );
    if ( ! $post_id || ! brmedia_download_enabled( $post_id ) ) return '';

    $media_url = brmedia_get_media_url( $post_id );
    if ( ! $media_url ) return '';

    return sprintf(
        '<a href="%s" class="brmedia-download-btn" download><i class="fas fa-download"></i> %s</a>',
        esc_url( $media_url ),
        esc_html( $atts['label'] )
    );
}