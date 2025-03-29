<?php
/**
 * BRMedia Statistics Addon
 * Displays detailed statistics for media playback and downloads
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Statistics_Addon {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Add admin menu item
        add_action( 'admin_menu', array( $this, 'register_admin_page' ) );

        // Enqueue scripts for admin stats page
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // AJAX handler for loading chart data
        add_action( 'wp_ajax_brmedia_load_stats_chart', array( $this, 'ajax_load_chart_data' ) );
    }

    public function register_admin_page() {
        add_submenu_page(
            'brmedia-settings',
            __( 'Statistics', 'brmedia' ),
            __( 'Statistics', 'brmedia' ),
            'manage_options',
            'brmedia-statistics',
            array( $this, 'render_statistics_page' )
        );
    }

    public function enqueue_admin_assets( $hook ) {
        if ( $hook !== 'brmedia_page_brmedia-statistics' ) return;

        wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '4.4.1', true );
        wp_enqueue_script( 'brmedia-stats', BRMEDIA_URL . 'addons/statistics/assets/statistics.js', array('jquery', 'chartjs'), BRMEDIA_VERSION, true );
        wp_localize_script( 'brmedia-stats', 'BRMediaStats', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'brmedia_stats_nonce' ),
        ));
        wp_enqueue_style( 'brmedia-stats-style', BRMEDIA_URL . 'addons/statistics/assets/statistics.css', array(), BRMEDIA_VERSION );
    }

    public function render_statistics_page() {
        ?>
        <div class="wrap brmedia-statistics-page">
            <h1><i class="fas fa-chart-line"></i> <?php _e( 'BRMedia Statistics', 'brmedia' ); ?></h1>

            <div class="brmedia-stats-controls">
                <label for="brmedia-stats-timeframe"><?php _e( 'Select Timeframe:', 'brmedia' ); ?></label>
                <select id="brmedia-stats-timeframe">
                    <option value="7days"><?php _e( 'Last 7 Days', 'brmedia' ); ?></option>
                    <option value="30days"><?php _e( 'Last 30 Days', 'brmedia' ); ?></option>
                    <option value="90days"><?php _e( 'Last 90 Days', 'brmedia' ); ?></option>
                    <option value="all"><?php _e( 'All Time', 'brmedia' ); ?></option>
                </select>
            </div>

            <canvas id="brmedia-stats-chart" width="100%" height="400"></canvas>

            <div id="brmedia-stats-summary" class="brmedia-stats-summary">
                <div class="stat-block">
                    <strong id="stat-total-plays">0</strong>
                    <span><?php _e( 'Total Plays', 'brmedia' ); ?></span>
                </div>
                <div class="stat-block">
                    <strong id="stat-total-downloads">0</strong>
                    <span><?php _e( 'Total Downloads', 'brmedia' ); ?></span>
                </div>
                <div class="stat-block">
                    <strong id="stat-total-uploads">0</strong>
                    <span><?php _e( 'Total Media Uploads', 'brmedia' ); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    public function ajax_load_chart_data() {
        check_ajax_referer( 'brmedia_stats_nonce', 'nonce' );

        global $wpdb;

        $timeframe = isset( $_POST['timeframe'] ) ? sanitize_text_field( $_POST['timeframe'] ) : '7days';

        $where_clause = $this->get_timeframe_where( $timeframe );

        $plays = $wpdb->get_results("
            SELECT DATE(play_date) as date, COUNT(*) as count
            FROM {$wpdb->prefix}brmedia_play_stats
            WHERE $where_clause
            GROUP BY DATE(play_date)
            ORDER BY date ASC
        ");

        $downloads = $wpdb->get_results("
            SELECT DATE(download_date) as date, COUNT(*) as count
            FROM {$wpdb->prefix}brmedia_downloads
            WHERE $where_clause
            GROUP BY DATE(download_date)
            ORDER BY date ASC
        ");

        $uploads = $wpdb->get_var("
            SELECT COUNT(ID)
            FROM {$wpdb->posts}
            WHERE post_type IN ('brmedia_music', 'brmedia_video') AND post_status = 'publish'
        ");

        wp_send_json_success( array(
            'plays'     => $plays,
            'downloads' => $downloads,
            'uploads'   => intval( $uploads )
        ));
    }

    private function get_timeframe_where( $timeframe ) {
        switch ( $timeframe ) {
            case '30days':
                return "date >= CURDATE() - INTERVAL 30 DAY";
            case '90days':
                return "date >= CURDATE() - INTERVAL 90 DAY";
            case 'all':
                return "1=1";
            case '7days':
            default:
                return "date >= CURDATE() - INTERVAL 7 DAY";
        }
    }
}

BRMedia_Statistics_Addon::instance();