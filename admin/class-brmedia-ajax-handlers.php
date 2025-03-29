<?php
/**
 * BRMedia AJAX Handlers
 * Centralized AJAX callback manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Ajax_Handlers {

    public function __construct() {
        add_action( 'wp_ajax_brmedia_fetch_track_metadata', array( $this, 'fetch_track_metadata' ) );
        add_action( 'wp_ajax_nopriv_brmedia_fetch_track_metadata', array( $this, 'fetch_track_metadata' ) );

        add_action( 'wp_ajax_brmedia_generate_waveform', array( $this, 'generate_waveform' ) );

        add_action( 'wp_ajax_brmedia_admin_stats', array( $this, 'admin_stats' ) );
    }

    /**
     * AJAX: Fetch metadata for a media file
     */
    public function fetch_track_metadata() {
        check_ajax_referer( 'brmedia_nonce', 'nonce' );

        $attachment_id = absint( $_POST['attachment_id'] ?? 0 );

        if ( ! $attachment_id ) {
            wp_send_json_error( __( 'Invalid media ID.', 'brmedia' ) );
        }

        $file = get_attached_file( $attachment_id );
        if ( ! file_exists( $file ) ) {
            wp_send_json_error( __( 'File not found.', 'brmedia' ) );
        }

        $metadata = array(
            'duration' => wp_read_audio_metadata( $file )['length_formatted'] ?? '0:00',
            'mime'     => get_post_mime_type( $attachment_id ),
            'title'    => get_the_title( $attachment_id ),
            'url'      => wp_get_attachment_url( $attachment_id ),
        );

        wp_send_json_success( $metadata );
    }

    /**
     * AJAX: Generate waveform placeholder (hook for real waveform tools)
     */
    public function generate_waveform() {
        check_ajax_referer( 'brmedia_nonce', 'nonce' );

        $media_url = esc_url_raw( $_POST['media_url'] ?? '' );

        if ( empty( $media_url ) ) {
            wp_send_json_error( __( 'No media URL provided.', 'brmedia' ) );
        }

        // TODO: In production, queue for server-side waveform generation
        $fake_waveform = array_fill(0, 100, rand(1, 100));

        wp_send_json_success( array(
            'waveform' => $fake_waveform,
            'message'  => __( 'Waveform data generated.', 'brmedia' )
        ));
    }

    /**
     * AJAX: Load admin dashboard stats (used by Chart.js)
     */
    public function admin_stats() {
        check_ajax_referer( 'brmedia_admin_nonce', 'nonce' );

        global $wpdb;

        $timeframe = sanitize_text_field( $_POST['timeframe'] ?? '7days' );
        $where = $this->get_timeframe_where( $timeframe );

        $plays = $wpdb->get_results("
            SELECT DATE(play_date) AS date, COUNT(*) AS count
            FROM {$wpdb->prefix}brmedia_play_stats
            WHERE $where
            GROUP BY DATE(play_date)
            ORDER BY date ASC
        ");

        $downloads = $wpdb->get_results("
            SELECT DATE(download_date) AS date, COUNT(*) AS count
            FROM {$wpdb->prefix}brmedia_downloads
            WHERE $where
            GROUP BY DATE(download_date)
            ORDER BY date ASC
        ");

        wp_send_json_success( array(
            'plays'     => $plays,
            'downloads' => $downloads
        ));
    }

    private function get_timeframe_where( $timeframe ) {
        switch ( $timeframe ) {
            case '30days': return "date >= CURDATE() - INTERVAL 30 DAY";
            case '90days': return "date >= CURDATE() - INTERVAL 90 DAY";
            case 'all':    return "1=1";
            default:       return "date >= CURDATE() - INTERVAL 7 DAY";
        }
    }
}

new BRMedia_Ajax_Handlers();