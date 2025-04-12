<?php
class BRMedia_Analytics_Handler {
    public function __construct() {
        add_action('wp_ajax_brmedia_track_event', [$this, 'handle_tracking']);
        add_action('wp_ajax_nopriv_brmedia_track_event', [$this, 'handle_tracking']);
    }

    public function handle_tracking() {
        check_ajax_referer('wp_rest', '_wpnonce');
        
        $event_type = sanitize_key($_POST['event_type']);
        $event_data = $this->sanitize_event_data($_POST['event_data']);
        
        // Store in database
        $this->store_event($event_type, $event_data);
        
        wp_send_json_success();
    }

    private function sanitize_event_data($data) {
        return array_map('sanitize_text_field', (array)$data);
    }

    private function store_event($type, $data) {
        global $wpdb;
        $wpdb->insert("{$wpdb->prefix}brmedia_events", [
            'event_type' => $type,
            'event_data' => maybe_serialize($data),
            'created_at' => current_time('mysql')
        ]);
    }
}
new BRMedia_Analytics_Handler();