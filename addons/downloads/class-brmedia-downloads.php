<?php
/**
 * BRMedia Downloads Addon
 * Adds download support to media entries
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Downloads_Addon {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Add download buttons to media output
        add_filter( 'brmedia_after_player_controls', array( $this, 'render_download_button' ), 10, 2 );

        // Handle download link
        add_action( 'init', array( $this, 'process_download_request' ) );

        // Log download stats
        add_action( 'brmedia_file_downloaded', array( $this, 'log_download' ), 10, 2 );
    }

    public function render_download_button( $post_id, $media_url ) {
        $enabled = get_post_meta( $post_id, 'brmedia_enable_download', true );
        if ( $enabled !== '1' || empty( $media_url ) ) return;

        $download_url = add_query_arg( array(
            'brmedia_download' => $post_id,
            'nonce' => wp_create_nonce( 'brmedia_download_' . $post_id )
        ), home_url( '/' ) );

        echo '<a href="' . esc_url( $download_url ) . '" class="brmedia-download-button" download>
                <i class="fas fa-download"></i> ' . __( 'Download', 'brmedia' ) . '
              </a>';
    }

    public function process_download_request() {
        if ( ! isset( $_GET['brmedia_download'] ) || ! isset( $_GET['nonce'] ) ) return;

        $post_id = intval( $_GET['brmedia_download'] );
        $nonce = $_GET['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'brmedia_download_' . $post_id ) ) {
            wp_die( __( 'Invalid download request.', 'brmedia' ) );
        }

        $media_url = get_post_meta( $post_id, 'brmedia_media_url', true );
        if ( empty( $media_url ) ) {
            wp_die( __( 'File not found.', 'brmedia' ) );
        }

        do_action( 'brmedia_file_downloaded', $post_id, $media_url );

        wp_redirect( esc_url_raw( $media_url ) );
        exit;
    }

    public function log_download( $post_id, $media_url ) {
        global $wpdb;

        $table = $wpdb->prefix . 'brmedia_downloads';

        $wpdb->insert( $table, array(
            'media_id' => $post_id,
            'download_url' => esc_url_raw( $media_url ),
            'download_date' => current_time( 'mysql' ),
            'user_ip' => $_SERVER['REMOTE_ADDR']
        ) );
    }
}

BRMedia_Downloads_Addon::instance();