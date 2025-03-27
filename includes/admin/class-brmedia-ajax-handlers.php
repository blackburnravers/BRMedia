<?php
/**
 * BRMedia AJAX Handlers
 * Handles all plugin AJAX operations
 */

class BRMedia_Ajax_Handlers {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Frontend AJAX actions
        add_action('wp_ajax_brmedia_track_play', array($this, 'track_play'));
        add_action('wp_ajax_nopriv_brmedia_track_play', array($this, 'track_play'));
        
        add_action('wp_ajax_brmedia_log_download', array($this, 'log_download'));
        add_action('wp_ajax_nopriv_brmedia_log_download', array($this, 'log_download'));

        // Admin AJAX actions
        add_action('wp_ajax_brmedia_save_timestamps', array($this, 'save_timestamps'));
        add_action('wp_ajax_brmedia_generate_shortcode', array($this, 'generate_shortcode'));
        add_action('wp_ajax_brmedia_get_stats', array($this, 'get_stats'));
    }

    /**
     * Track play count and duration
     */
    public function track_play() {
        check_ajax_referer('brmedia_ajax', 'nonce');

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $duration = isset($_POST['duration']) ? floatval($_POST['duration']) : 0;
        $completed = isset($_POST['completed']) ? boolval($_POST['completed']) : false;

        if (!$post_id) {
            wp_send_json_error(__('Invalid media ID', 'brmedia'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'brmedia_play_stats';

        $data = array(
            'media_id' => $post_id,
            'media_type' => 'music',
            'user_id' => get_current_user_id(),
            'ip_address' => $this->get_client_ip(),
            'play_date' => current_time('mysql'),
            'duration_played' => $duration,
            'completed' => $completed
        );

        $result = $wpdb->insert($table_name, $data);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Failed to log play', 'brmedia'));
        }
    }

    /**
     * Log file downloads
     */
    public function log_download() {
        check_ajax_referer('brmedia_ajax', 'nonce');

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $media_type = isset($_POST['media_type']) ? sanitize_text_field($_POST['media_type']) : 'music';

        if (!$post_id) {
            wp_send_json_error(__('Invalid media ID', 'brmedia'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'brmedia_downloads';

        $data = array(
            'media_id' => $post_id,
            'media_type' => $media_type,
            'user_id' => get_current_user_id(),
            'ip_address' => $this->get_client_ip(),
            'download_date' => current_time('mysql')
        );

        $result = $wpdb->insert($table_name, $data);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Failed to log download', 'brmedia'));
        }
    }

    /**
     * Save track timestamps from metabox
     */
    public function save_timestamps() {
        check_ajax_referer('brmedia_metabox', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $timestamps = isset($_POST['timestamps']) ? $this->sanitize_timestamps($_POST['timestamps']) : array();

        if (!$post_id) {
            wp_send_json_error(__('Invalid post ID', 'brmedia'));
        }

        if (update_post_meta($post_id, 'brmedia_tracklist', $timestamps)) {
            wp_send_json_success(array(
                'message' => __('Timestamps saved', 'brmedia'),
                'timestamps' => $timestamps
            ));
        } else {
            wp_send_json_error(__('Failed to save timestamps', 'brmedia'));
        }
    }

    /**
     * Generate shortcode from admin
     */
    public function generate_shortcode() {
        check_ajax_referer('brmedia_admin', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'music';
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $template = isset($_POST['template']) ? sanitize_text_field($_POST['template']) : 'stylish';
        $autoplay = isset($_POST['autoplay']) ? boolval($_POST['autoplay']) : false;

        if (!$id) {
            wp_send_json_error(__('Invalid media ID', 'brmedia'));
        }

        $shortcode = "[brmedia_{$type} id=\"{$id}\"";
        
        if ($type === 'music') {
            $shortcode .= " template=\"{$template}\"";
        }
        
        if ($autoplay) {
            $shortcode .= ' autoplay="true"';
        }
        
        $shortcode .= "]";

        wp_send_json_success(array(
            'shortcode' => $shortcode
        ));
    }

    /**
     * Get stats for dashboard
     */
    public function get_stats() {
        check_ajax_referer('brmedia_admin', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $timeframe = isset($_POST['timeframe']) ? sanitize_text_field($_POST['timeframe']) : '7days';

        $stats = array(
            'tracks' => $this->count_music_tracks(),
            'videos' => $this->count_videos(),
            'plays' => $this->count_plays($timeframe),
            'downloads' => $this->count_downloads($timeframe),
            'chart_data' => $this->get_chart_data($timeframe)
        );

        wp_send_json_success($stats);
    }

    /**
     * Helper: Sanitize timestamp data
     */
    private function sanitize_timestamps($timestamps) {
        $clean = array();
        
        if (!is_array($timestamps)) {
            return $clean;
        }

        foreach ($timestamps as $ts) {
            $clean[] = array(
                'timestamp' => sanitize_text_field($ts['timestamp']),
                'title' => sanitize_text_field($ts['title'])
            );
        }

        return $clean;
    }

    /**
     * Helper: Get client IP address
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return sanitize_text_field($ip);
    }

    /**
     * Helper: Count music tracks
     */
    private function count_music_tracks() {
        $count = wp_count_posts('brmedia_music');
        return $count->publish;
    }

    /**
     * Helper: Count videos
     */
    private function count_videos() {
        $count = wp_count_posts('brmedia_video');
        return $count->publish;
    }

    /**
     * Helper: Count plays within timeframe
     */
    private function count_plays($timeframe) {
        global $wpdb;
        $table = $wpdb->prefix . 'brmedia_play_stats';
        
        $where = $this->get_timeframe_where($timeframe);
        return $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where");
    }

    /**
     * Helper: Count downloads within timeframe
     */
    private function count_downloads($timeframe) {
        global $wpdb;
        $table = $wpdb->prefix . 'brmedia_downloads';
        
        $where = $this->get_timeframe_where($timeframe);
        return $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where");
    }

    /**
     * Helper: Get timeframe SQL WHERE clause
     */
    private function get_timeframe_where($timeframe) {
        $days = 7;
        
        switch ($timeframe) {
            case '30days':
                $days = 30;
                break;
            case '90days':
                $days = 90;
                break;
            case 'all':
                return '1=1';
        }

        return "play_date >= DATE_SUB(NOW(), INTERVAL $days DAY)";
    }

    /**
     * Helper: Get chart data
     */
    private function get_chart_data($timeframe) {
        global $wpdb;
        $table = $wpdb->prefix . 'brmedia_play_stats';
        $where = $this->get_timeframe_where($timeframe);
        
        return $wpdb->get_results("
            SELECT DATE(play_date) as date, COUNT(*) as plays 
            FROM $table 
            WHERE $where
            GROUP BY DATE(play_date)
            ORDER BY date ASC
        ");
    }
}