<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register maintenance settings
function brmedia_register_maintenance_settings() {
    register_setting('brmedia_maintenance_settings', 'brmedia_maintenance_options', 'brmedia_sanitize_maintenance_settings');

    add_settings_section(
        'brmedia_maintenance_section',
        'Maintenance Settings',
        null,
        'brmedia-maintenance'
    );

    add_settings_field(
        'cache_enabled',
        'Enable Cache',
        'brmedia_cache_enabled_callback',
        'brmedia-maintenance',
        'brmedia_maintenance_section'
    );

    add_settings_field(
        'cache_expiration',
        'Cache Expiration (hours)',
        'brmedia_cache_expiration_callback',
        'brmedia-maintenance',
        'brmedia_maintenance_section'
    );

    add_settings_field(
        'cron_enabled',
        'Enable Cron Job',
        'brmedia_cron_enabled_callback',
        'brmedia-maintenance',
        'brmedia_maintenance_section'
    );

    add_settings_field(
        'cron_interval',
        'Cron Interval (hours)',
        'brmedia_cron_interval_callback',
        'brmedia-maintenance',
        'brmedia_maintenance_section'
    );
}
add_action('admin_init', 'brmedia_register_maintenance_settings');

// Sanitize maintenance settings
function brmedia_sanitize_maintenance_settings($input) {
    $sanitized_input = array();
    $sanitized_input['cache_enabled'] = isset($input['cache_enabled']) ? 1 : 0;
    $sanitized_input['cache_expiration'] = isset($input['cache_expiration']) ? absint($input['cache_expiration']) : 24;
    $sanitized_input['cron_enabled'] = isset($input['cron_enabled']) ? 1 : 0;
    $sanitized_input['cron_interval'] = isset($input['cron_interval']) ? absint($input['cron_interval']) : 24;
    return $sanitized_input;
}

// Callback functions
function brmedia_cache_enabled_callback() {
    $options = get_option('brmedia_maintenance_options');
    $cache_enabled = isset($options['cache_enabled']) ? $options['cache_enabled'] : 0;
    echo '<label><input type="checkbox" name="brmedia_maintenance_options[cache_enabled]" value="1" ' . checked(1, $cache_enabled, false) . ' /> Enable caching for player data</label>';
}

function brmedia_cache_expiration_callback() {
    $options = get_option('brmedia_maintenance_options');
    $cache_expiration = isset($options['cache_expiration']) ? $options['cache_expiration'] : 24;
    echo '<input type="number" name="brmedia_maintenance_options[cache_expiration]" value="' . esc_attr($cache_expiration) . '" min="1" />';
}

function brmedia_cron_enabled_callback() {
    $options = get_option('brmedia_maintenance_options');
    $cron_enabled = isset($options['cron_enabled']) ? $options['cron_enabled'] : 0;
    echo '<label><input type="checkbox" name="brmedia_maintenance_options[cron_enabled]" value="1" ' . checked(1, $cron_enabled, false) . ' /> Enable cron job to clear cache</label>';
}

function brmedia_cron_interval_callback() {
    $options = get_option('brmedia_maintenance_options');
    $cron_interval = isset($options['cron_interval']) ? $options['cron_interval'] : 24;
    echo '<input type="number" name="brmedia_maintenance_options[cron_interval]" value="' . esc_attr($cron_interval) . '" min="1" />';
}

// Schedule or unschedule the cron job
function brmedia_schedule_cache_clear() {
    $options = get_option('brmedia_maintenance_options');
    $cron_enabled = isset($options['cron_enabled']) ? $options['cron_enabled'] : 0;
    $interval = isset($options['cron_interval']) ? $options['cron_interval'] * HOUR_IN_SECONDS : 24 * HOUR_IN_SECONDS;

    if ($cron_enabled && !wp_next_scheduled('brmedia_clear_cache')) {
        wp_schedule_event(time(), 'brmedia_custom_interval', 'brmedia_clear_cache');
    } elseif (!$cron_enabled) {
        wp_clear_scheduled_hook('brmedia_clear_cache');
    }
}
add_action('init', 'brmedia_schedule_cache_clear');

// Define the custom interval
add_filter('cron_schedules', 'brmedia_add_custom_cron_interval');
function brmedia_add_custom_cron_interval($schedules) {
    $schedules['brmedia_custom_interval'] = array(
        'interval' => HOUR_IN_SECONDS, // Base interval, adjusted dynamically
        'display' => 'BRMedia Custom Interval'
    );
    return $schedules;
}

// Cron job callback to clear cache
function brmedia_clear_cache() {
    $options = get_option('brmedia_maintenance_options');
    $cache_expiration = isset($options['cache_expiration']) ? $options['cache_expiration'] * HOUR_IN_SECONDS : 24 * HOUR_IN_SECONDS;
    // Placeholder: Implement cache clearing logic here
    // Example: delete_transient('brmedia_cache_key');
    if (false) {
        // Log or notify if cache clearing fails
    }
}
add_action('brmedia_clear_cache', 'brmedia_clear_cache');

// Update cron schedule when settings change
add_action('update_option_brmedia_maintenance_options', 'brmedia_schedule_cache_clear', 10, 2);

// Handle mass reset of all templates
function brmedia_mass_reset_templates() {
    check_ajax_referer('brmedia_maintenance', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $templates = [
        'template-1', 'template-2', 'template-3', 'template-4',
        'template-5', 'template-6', 'template-7', 'template-fullscreen'
    ];
    $default_options = brmedia_get_default_template_options();
    foreach ($templates as $template) {
        update_option('brmedia_template_options_' . $template, $default_options);
    }
    wp_send_json_success('All templates reset to defaults.');
}
add_action('wp_ajax_brmedia_mass_reset_templates', 'brmedia_mass_reset_templates');

// Handle mass deletion of waveforms
function brmedia_mass_delete_waveforms() {
    check_ajax_referer('brmedia_maintenance', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $audio_files = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'audio',
        'posts_per_page' => -1,
    ]);
    $upload_dir = wp_upload_dir();
    $waveform_dir = $upload_dir['basedir'] . '/waveforms/';
    foreach ($audio_files as $file) {
        $waveform_path = get_post_meta($file->ID, '_waveform_json', true);
        if ($waveform_path && file_exists($waveform_path)) {
            unlink($waveform_path);
        }
        delete_post_meta($file->ID, '_waveform_json');
        delete_post_meta($file->ID, '_waveform_generated');
    }
    if (is_dir($waveform_dir)) {
        $files = glob($waveform_dir . '*.json');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    wp_send_json_success('All waveforms deleted.');
}
add_action('wp_ajax_brmedia_mass_delete_waveforms', 'brmedia_mass_delete_waveforms');

// Handle mass generation of unprocessed waveforms
function brmedia_mass_generate_unprocessed_waveforms() {
    check_ajax_referer('brmedia_maintenance', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $audio_files = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'audio',
        'posts_per_page' => -1,
    ]);
    $file_ids = array_filter(wp_list_pluck($audio_files, 'ID'), function($id) {
        return !get_post_meta($id, '_waveform_generated', true);
    });
    if (!empty($file_ids)) {
        $process_id = brmedia_start_waveform_generation($file_ids);
        wp_send_json_success(['process_id' => $process_id]);
    } else {
        wp_send_json_success('No unprocessed files to generate.');
    }
}
add_action('wp_ajax_brmedia_mass_generate_unprocessed_waveforms', 'brmedia_mass_generate_unprocessed_waveforms');

// Handle mass generation of all waveforms
function brmedia_mass_generate_all_waveforms() {
    check_ajax_referer('brmedia_maintenance', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $audio_files = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'audio',
        'posts_per_page' => -1,
    ]);
    $file_ids = wp_list_pluck($audio_files, 'ID');
    if (!empty($file_ids)) {
        $process_id = brmedia_start_waveform_generation($file_ids);
        wp_send_json_success(['process_id' => $process_id]);
    } else {
        wp_send_json_success('No audio files to generate.');
    }
}
add_action('wp_ajax_brmedia_mass_generate_all_waveforms', 'brmedia_mass_generate_all_waveforms');

// Save waveform data from client-side generation
function brmedia_save_waveform() {
    check_ajax_referer('brmedia_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $audio_url = sanitize_text_field($_POST['audio_url']);
    $peak_data = json_decode(stripslashes($_POST['peak_data']), true);
    $attachment_id = attachment_url_to_postid($audio_url);
    if ($attachment_id && $peak_data) {
        $upload_dir = wp_upload_dir();
        $waveform_dir = $upload_dir['basedir'] . '/waveforms/';
        wp_mkdir_p($waveform_dir);
        $json_path = $waveform_dir . $attachment_id . '.json';
        file_put_contents($json_path, json_encode(['data' => $peak_data]));
        update_post_meta($attachment_id, '_waveform_json', $json_path);
        update_post_meta($attachment_id, '_waveform_generated', true);
        wp_send_json_success('Waveform saved.');
    } else {
        wp_send_json_error('Invalid audio URL or peak data.');
    }
}
add_action('wp_ajax_brmedia_save_waveform', 'brmedia_save_waveform');