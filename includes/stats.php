<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// AJAX handler for logging stats
add_action('wp_ajax_brmedia_log_event', 'brmedia_log_event');
add_action('wp_ajax_nopriv_brmedia_log_event', 'brmedia_log_event');

function brmedia_log_event() {
    global $wpdb;
    $track_id = intval($_POST['track_id']);
    $action = sanitize_text_field($_POST['action_type']);
    $duration_played = isset($_POST['duration_played']) ? intval($_POST['duration_played']) : null;

    $table_name = $wpdb->prefix . 'brmedia_stats';
    $wpdb->insert($table_name, [
        'track_id' => $track_id,
        'action' => $action,
        'timestamp' => current_time('mysql'),
        'duration_played' => $duration_played
    ]);
    wp_die();
}

// Functions to retrieve stats
function brmedia_get_total_plays() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'brmedia_stats';
    return $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE action = 'play'");
}

function brmedia_get_average_play_duration() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'brmedia_stats';
    return $wpdb->get_var("SELECT AVG(duration_played) FROM $table_name WHERE action = 'play' AND duration_played IS NOT NULL");
}

function brmedia_get_top_tracks($limit = 5) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'brmedia_stats';
    $posts_table = $wpdb->prefix . 'posts';
    return $wpdb->get_results("
        SELECT p.post_title, COUNT(s.id) as play_count
        FROM $table_name s
        JOIN $posts_table p ON s.track_id = p.ID
        WHERE s.action = 'play'
        GROUP BY s.track_id
        ORDER BY play_count DESC
        LIMIT $limit
    ", ARRAY_A);
}

function brmedia_get_shared_tracks($limit = 5) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'brmedia_stats';
    $posts_table = $wpdb->prefix . 'posts';
    return $wpdb->get_results("
        SELECT p.post_title, COUNT(s.id) as share_count
        FROM $table_name s
        JOIN $posts_table p ON s.track_id = p.ID
        WHERE s.action = 'share'
        GROUP BY s.track_id
        ORDER BY share_count DESC
        LIMIT $limit
    ", ARRAY_A);
}