<?php
/**
 * BRMedia Global Functions
 * Shared helpers used throughout the plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Format time from seconds to hh:mm:ss
 */
function brmedia_format_duration( $seconds ) {
    $hours = floor( $seconds / 3600 );
    $minutes = floor( ( $seconds % 3600 ) / 60 );
    $secs = $seconds % 60;

    if ( $hours > 0 ) {
        return sprintf( "%02d:%02d:%02d", $hours, $minutes, $secs );
    } else {
        return sprintf( "%02d:%02d", $minutes, $secs );
    }
}

/**
 * Get media cover image URL
 */
function brmedia_get_cover_image( $post_id ) {
    $url = get_post_meta( $post_id, '_brmedia_cover_image', true );
    if ( ! $url ) {
        $url = get_the_post_thumbnail_url( $post_id, 'medium' );
    }
    return $url;
}

/**
 * Get media URL (audio or video)
 */
function brmedia_get_media_url( $post_id ) {
    $url = get_post_meta( $post_id, '_brmedia_media_url', true );
    if ( ! $url && has_post_thumbnail( $post_id ) ) {
        $url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
    }
    return $url;
}

/**
 * Check if download is enabled
 */
function brmedia_download_enabled( $post_id ) {
    return get_post_meta( $post_id, '_brmedia_enable_download', true ) === '1';
}

/**
 * Get tracklist formatted as array
 */
function brmedia_get_tracklist( $post_id ) {
    $raw = get_post_meta( $post_id, '_brmedia_tracklist', true );
    $lines = explode( "\n", $raw );
    $parsed = array();

    foreach ( $lines as $line ) {
        if ( preg_match( '/^(\d{1,2}:\d{2}(?::\d{2})?)\s+(.*)$/', trim( $line ), $matches ) ) {
            $parsed[] = array(
                'time' => trim( $matches[1] ),
                'title' => trim( $matches[2] )
            );
        }
    }

    return $parsed;
}

/**
 * Get current user's IP address
 */
function brmedia_get_user_ip() {
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        return sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        return sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
    } else {
        return sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
    }
}

/**
 * Output accessible shortcode
 */
function brmedia_shortcode_output( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0
    ), $atts );

    $post_id = absint( $atts['id'] );
    if ( ! $post_id || ! get_post( $post_id ) ) return '';

    ob_start();

    $post_type = get_post_type( $post_id );
    setup_postdata( get_post( $post_id ) );

    if ( $post_type === 'brmedia_music' ) {
        include BRMEDIA_PATH . 'templates/shortcode-output/music-player.php';
    } elseif ( $post_type === 'brmedia_video' ) {
        include BRMEDIA_PATH . 'templates/shortcode-output/video-player.php';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'brmedia', 'brmedia_shortcode_output' );