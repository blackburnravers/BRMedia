<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Stats {

    private $db;

    public function __construct() {
        $this->db = new BRMedia_Stats_DB();
        add_action('brmedia_play_start', [$this, 'track_play_start']);
        add_action('brmedia_play_end', [$this, 'track_play_end'], 10, 2);
    }

    public function track_play_start($track_id) {
        $this->db->record_play($track_id, [
            'event' => 'start',
            'user_ip' => $this->get_user_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }

    public function track_play_end($track_id, $duration) {
        $this->db->record_play($track_id, [
            'event' => 'end',
            'duration' => $duration,
            'location' => $this->get_geolocation()
        ]);
    }

    public function get_aggregate_stats($range = 7) {
        return [
            'total_plays' => $this->db->get_total_plays($range),
            'avg_duration' => $this->db->get_avg_duration($range),
            'device_distribution' => $this->db->get_device_stats($range),
            'geo_distribution' => $this->db->get_geo_data($range)
        ];
    }

    private function get_user_ip() {
        return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ?: '';
    }

    private function get_geolocation() {
        // Implement GeoIP lookup
        return 'Not implemented';
    }

    public function generate_report($args) {
        return $this->db->query_report($args);
    }
}