<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Update Media Stats (Play / Download)
 */
function brmedia_update_media_stats() {
    // Verify AJAX nonce if added in future
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';

    if (!$post_id || !in_array($action_type, ['play', 'download'])) {
        wp_send_json_error('Invalid parameters.');
    }

    // Retrieve current stat
    $meta_key = $action_type === 'play' ? '_brmedia_total_plays' : '_brmedia_total_downloads';
    $current = (int) get_post_meta($post_id, $meta_key, true);
    update_post_meta($post_id, $meta_key, $current + 1);

    // Update last activity timestamp
    update_post_meta($post_id, '_brmedia_last_activity', current_time('mysql'));

    // Optional Geo Tracking
    $geo_data = brmedia_get_geo_data();
    if ($geo_data && is_array($geo_data)) {
        $existing_logs = get_post_meta($post_id, '_brmedia_geo_logs', true);
        if (!is_array($existing_logs)) {
            $existing_logs = [];
        }

        $geo_data['type'] = $action_type;
        $geo_data['time'] = current_time('mysql');
        $geo_data['ip'] = $_SERVER['REMOTE_ADDR'];

        $existing_logs[] = $geo_data;

        // Keep max 1000 entries
        if (count($existing_logs) > 1000) {
            array_shift($existing_logs);
        }

        update_post_meta($post_id, '_brmedia_geo_logs', $existing_logs);
    }

    wp_send_json_success('Stat updated.');
}
add_action('wp_ajax_brmedia_update_media_stats', 'brmedia_update_media_stats');
add_action('wp_ajax_nopriv_brmedia_update_media_stats', 'brmedia_update_media_stats');

/**
 * Get Geo Data using ip-api.com
 */
function brmedia_get_geo_data() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = wp_remote_get("http://ip-api.com/json/{$ip}?fields=status,country,city,lat,lon,query");

    if (is_wp_error($response)) return false;

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!isset($data['status']) || $data['status'] !== 'success') return false;

    return [
        'country' => $data['country'],
        'city'    => $data['city'],
        'lat'     => $data['lat'],
        'lon'     => $data['lon'],
        'ip'      => $data['query']
    ];
}

/**
 * Example: AJAX Visualizer Ping or Waveform Generator Placeholder
 * Extend this handler to generate waveform data if needed
 */
function brmedia_waveform_generate() {
    check_ajax_referer('brmedia_secure_waveform', 'nonce');

    $audio_url = isset($_POST['audio_url']) ? esc_url_raw($_POST['audio_url']) : '';
    if (!$audio_url) {
        wp_send_json_error('Missing audio URL.');
    }

    // Placeholder logic (integration with WaveSurfer or external processing)
    wp_send_json_success([
        'status' => 'Waveform generation is not implemented.',
        'url' => $audio_url
    ]);
}
add_action('wp_ajax_brmedia_waveform_generate', 'brmedia_waveform_generate');