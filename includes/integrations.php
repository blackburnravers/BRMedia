<?php
/**
 * BRMedia External Integrations
 * Handles optional integrations like YouTube, Shoutcast, Icecast, Facebook, etc.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'brmedia_load_integrations' );

function brmedia_load_integrations() {
    // Placeholder for initializing integrations in the future
    // Useful for loading APIs or authenticating services
}

/**
 * Example: YouTube embed generator
 */
function brmedia_get_youtube_embed( $video_id, $width = '100%', $height = '360' ) {
    if ( empty( $video_id ) ) return '';
    return sprintf(
        '<iframe width="%s" height="%s" src="https://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe>',
        esc_attr( $width ),
        esc_attr( $height ),
        esc_attr( $video_id )
    );
}

/**
 * Example: Shoutcast stream checker (simple HEAD request)
 */
function brmedia_check_shoutcast_stream( $stream_url ) {
    $response = wp_remote_head( $stream_url, array( 'timeout' => 5 ) );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    $status = wp_remote_retrieve_response_code( $response );
    return ( $status >= 200 && $status < 400 );
}

/**
 * Future Placeholder: Facebook or Icecast logic
 */
function brmedia_get_facebook_embed( $video_url ) {
    // To-do: Facebook video/player embed support
    return '<p>' . __( 'Facebook embed coming soon.', 'brmedia' ) . '</p>';
}