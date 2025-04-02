<?php
// Prevent direct access
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// Delete BRMedia plugin options
$options = [
    'brmedia_audio_templates_settings',
    'brmedia_video_templates_settings',
    'brmedia_downloads_settings',
    'brmedia_waveform_settings',
    'brmedia_visualizer_settings',
    'brmedia_general_settings'
];

foreach ($options as $option) {
    delete_option($option);
    delete_site_option($option); // In case of multisite
}

// Delete custom post types: brmusic and brvideo
$custom_post_types = ['brmusic', 'brvideo'];

foreach ($custom_post_types as $post_type) {
    $posts = get_posts([
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any'
    ]);

    foreach ($posts as $post) {
        // Delete custom fields/meta data
        $meta_keys = get_post_custom_keys($post->ID);
        if ($meta_keys) {
            foreach ($meta_keys as $key) {
                delete_post_meta($post->ID, $key);
            }
        }

        // Delete the post itself
        wp_delete_post($post->ID, true);
    }
}

// Clean up any transients or cached stats
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_brmedia_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_brmedia_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'brmedia_stats_%'");

// Remove uploaded media files (optional – use with caution)
function brmedia_delete_attachments_by_post_type($post_type) {
    $posts = get_posts([
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any'
    ]);

    foreach ($posts as $post) {
        $attachments = get_attached_media('', $post->ID);
        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
        }
    }
}
brmedia_delete_attachments_by_post_type('brmusic');
brmedia_delete_attachments_by_post_type('brvideo');