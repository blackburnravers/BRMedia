<?php
/**
 * BRMedia Template Tags
 * Helper functions for use inside themes and templates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output the full player by post ID
 *
 * @param int $post_id
 */
function brmedia_the_player( $post_id ) {
    echo brmedia_get_player( $post_id );
}

/**
 * Return the player HTML (audio or video)
 *
 * @param int $post_id
 * @return string
 */
function brmedia_get_player( $post_id ) {
    $post_type = get_post_type( $post_id );

    if ( ! in_array( $post_type, array( 'brmedia_music', 'brmedia_video' ) ) ) {
        return '';
    }

    ob_start();
    setup_postdata( get_post( $post_id ) );

    if ( $post_type === 'brmedia_music' ) {
        include BRMEDIA_PATH . 'templates/shortcode-output/music-player.php';
    } elseif ( $post_type === 'brmedia_video' ) {
        include BRMEDIA_PATH . 'templates/shortcode-output/video-player.php';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * Get the cover image URL
 *
 * @param int $post_id
 * @return string
 */
function brmedia_the_cover_image( $post_id ) {
    echo esc_url( brmedia_get_cover_image( $post_id ) );
}

/**
 * Get the tracklist as HTML
 *
 * @param int $post_id
 */
function brmedia_the_tracklist( $post_id ) {
    $tracklist = brmedia_get_tracklist( $post_id );

    if ( ! empty( $tracklist ) ) {
        echo '<ul class="brmedia-tracklist">';
        foreach ( $tracklist as $item ) {
            echo '<li><strong>' . esc_html( $item['time'] ) . '</strong> - ' . esc_html( $item['title'] ) . '</li>';
        }
        echo '</ul>';
    }
}