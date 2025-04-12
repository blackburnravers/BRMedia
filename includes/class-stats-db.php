<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Stats_DB {

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$wpdb->prefix}brmedia_stats (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            track_id BIGINT UNSIGNED NOT NULL,
            user_ip VARCHAR(45) NOT NULL,
            user_agent TEXT NOT NULL,
            device_type VARCHAR(20) NOT NULL,
            os VARCHAR(50) NOT NULL,
            location VARCHAR(100) NOT NULL,
            event_type VARCHAR(20) NOT NULL,
            duration FLOAT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY track_id (track_id),
            KEY event_type (event_type)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function record_play($track_id, $data) {
        global $wpdb;

        return $wpdb->insert(
            "{$wpdb->prefix}brmedia_stats",
            [
                'track_id' => $track_id,
                'user_ip' => $data['user_ip'],
                'user_agent' => $data['user_agent'],
                'device_type' => $this->detect_device(),
                'os' => $this->detect_os(),
                'location' => $data['location'] ?? '',
                'event_type' => $data['event'],
                'duration' => $data['duration'] ?? 0,
                'created_at' => current_time('mysql')
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s']
        );
    }

    private function detect_device() {
        $detect = new Mobile_Detect();
        if ($detect->isMobile()) return 'mobile';
        if ($detect->isTablet()) return 'tablet';
        return 'desktop';
    }

    private function detect_os() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (stripos($user_agent, 'Windows') !== false) return 'Windows';
        if (stripos($user_agent, 'Mac') !== false) return 'macOS';
        if (stripos($user_agent, 'Linux') !== false) return 'Linux';
        if (stripos($user_agent, 'Android') !== false) return 'Android';
        if (stripos($user_agent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    public function get_total_plays($days = 7) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}brmedia_stats
             WHERE event_type = 'start' AND created_at >= %s",
            date('Y-m-d', strtotime("-{$days} days"))
        ));
    }

    public function get_avg_duration($days = 7) {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(duration) FROM {$wpdb->prefix}brmedia_stats
             WHERE event_type = 'end' AND created_at >= %s",
            date('Y-m-d', strtotime("-{$days} days"))
        ));
    }
}