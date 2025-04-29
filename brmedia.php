<?php
/*
Plugin Name: BRMedia
Description: Advanced music player for WordPress with custom post type, admin control panel, and multiple templates.
Version: 1.1
Author: Rhys Cole
Author URI: https://www.blackburnravers.co.uk
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BRMEDIA_PLUGIN_FILE', __FILE__);
define('BRMEDIA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BRMEDIA_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once BRMEDIA_PLUGIN_DIR . 'includes/settings.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/general-settings.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/template-settings.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/settings-callbacks.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/post-type.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/admin-menu.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/shortcode.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/enqueue-scripts.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/audio-metadata.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/maintenance.php';
require_once BRMEDIA_PLUGIN_DIR . 'admin/maintenance.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/stats.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/import-settings-logic.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/icons.php';

// Define services array for import settings
$services = [
    'soundcloud' => [
        'name' => 'SoundCloud',
        'color' => '#FF5500',
        'icon' => 'soundcloud.svg',
        'description' => 'A platform for sharing and discovering music.',
        'website' => 'https://www.soundcloud.com',
        'api' => 'https://developers.soundcloud.com/docs/api/',
    ],
    'audiomack' => [
        'name' => 'Audiomack',
        'color' => '#FFA200',
        'icon' => 'audiomack.svg',
        'description' => 'A free music streaming platform for artists.',
        'website' => 'https://www.audiomack.com/developers',
        'api' => 'https://www.audiomack.com/developers',
    ],
    'bandcamp' => [
        'name' => 'Bandcamp',
        'color' => '#629AA9',
        'icon' => 'bandcamp.svg',
        'description' => 'A platform for artists to sell music directly.',
        'website' => 'https://bandcamp.com',
        'api' => 'https://bandcamp.com/developer_documentation',
    ],
    'reverbnation' => [
        'name' => 'ReverbNation',
        'color' => '#ED36DB',
        'icon' => 'reverbnation.svg',
        'description' => 'A platform for musicians to promote and connect.',
        'website' => 'https://www.reverbnation.com',
        'api' => 'https://www.reverbnation.com/api',
    ],
    'mixcloud' => [
        'name' => 'Mixcloud',
        'color' => '#1D2F5D',
        'icon' => 'mixcloud.svg',
        'description' => 'A platform for DJ mixes and podcasts.',
        'website' => 'https://www.mixcloud.com',
        'api' => 'https://www.mixcloud.com/developers/',
    ],
    'hearthis' => [
        'name' => 'HearThis.at',
        'color' => '#A12015',
        'icon' => 'hearthis.svg',
        'description' => 'A platform for independent musicians to share.',
        'website' => 'https://hearthis.at',
        'api' => '',
    ],
    'youtube-music' => [
        'name' => 'YouTube Music',
        'color' => '#FF0000',
        'icon' => 'youtube-music.svg',
        'description' => 'A music streaming service with videos.',
        'website' => 'https://music.youtube.com',
        'api' => 'https://developers.google.com/youtube/v3',
    ],
    'audius' => [
        'name' => 'Audius',
        'color' => '#CC0FE0',
        'icon' => 'audius.svg',
        'description' => 'A decentralized music streaming platform.',
        'website' => 'https://audius.org',
        'api' => 'https://docs.audius.co/',
    ],
    'house-mixes' => [
        'name' => 'House-Mixes.com',
        'color' => '#000000',
        'icon' => 'house-mixes.svg',
        'description' => 'A platform for multi genre music mixes.',
        'website' => 'https://www.house-mixes.com',
        'api' => '',
    ],
];

// Define features for settings
$features = [
    'video' => 'BRMedia Video',
    'podcast' => 'BRMedia Podcast',
    'radio' => 'BRMedia Radio',
    'gaming' => 'BRMedia Gaming',
    'chat' => 'BRMedia Chat',
    'downloads' => 'BRMedia Downloads',
    'footbar' => 'BRMedia Footbar',
];

// Register feature settings
function brmedia_register_feature_settings() {
    register_setting('brmedia_settings', 'brmedia_settings', 'brmedia_sanitize_feature_settings');
    
    add_settings_section(
        'brmedia_features',
        'Feature Settings',
        null,
        'brmedia-settings'
    );
    
    global $features;
    foreach ($features as $key => $label) {
        add_settings_field(
            'enable_' . $key,
            'Enable ' . $label,
            'brmedia_feature_checkbox_callback',
            'brmedia-settings',
            'brmedia_features',
            ['field' => 'enable_' . $key]
        );
    }
}
add_action('admin_init', 'brmedia_register_feature_settings');

function brmedia_sanitize_feature_settings($input) {
    $sanitized = [];
    global $features;
    foreach ($features as $key => $label) {
        $sanitized['enable_' . $key] = isset($input['enable_' . $key]) ? 1 : 0;
    }
    return $sanitized;
}

function brmedia_feature_checkbox_callback($args) {
    $field = $args['field'];
    $options = get_option('brmedia_settings', []);
    $checked = !empty($options[$field]) ? 'checked' : '';
    echo '<input type="checkbox" id="' . esc_attr($field) . '" name="brmedia_settings[' . esc_attr($field) . ']" value="1" ' . $checked . ' />';
}

// Activation hook
function brmedia_activate() {
    brmedia_register_post_type();
    flush_rewrite_rules();
    if (get_option('brmedia_cache_enabled', false)) {
        brmedia_schedule_cache_clear();
    }
    brmedia_create_stats_table();
}
register_activation_hook(__FILE__, 'brmedia_activate');

// Deactivation hook
function brmedia_deactivate() {
    flush_rewrite_rules();
    wp_clear_scheduled_hook('brmedia_clear_cache');
}
register_deactivation_hook(__FILE__, 'brmedia_deactivate');

// Create stats table
function brmedia_create_stats_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'brmedia_stats';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        track_id mediumint(9) NOT NULL,
        action varchar(50) NOT NULL,
        timestamp datetime NOT NULL,
        duration_played int(11) DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Enqueue admin assets
function brmedia_enqueue_admin_assets($hook) {
    error_log('BRMedia enqueue hook: ' . $hook); // Useful for debugging
    // Check if the current hook is related to BRMedia pages
    if (strpos($hook, 'brmedia') !== false || in_array($hook, [
        'toplevel_page_brmedia', // Dashboard
        'brmedia_page_brmedia-general-settings',
        'brmedia_page_brmedia-template-settings',
        'brmedia_page_brmedia-stats',
        'brmedia_page_brmedia-shortcodes',
        'brmedia_page_brmedia-maintenance',
        'brmedia_page_brmedia-import-settings',
        'brmedia_page_brmedia-import'
    ])) {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
        wp_enqueue_style('brmedia-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
        wp_enqueue_style('brmedia-admin-css', BRMEDIA_PLUGIN_URL . 'assets/css/admin.css', array('bootstrap'), '1.1');
        wp_enqueue_script('brmedia-admin-js', BRMEDIA_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'bootstrap'), '1.0', true);
        if ($hook === 'brmedia_page_brmedia-stats') {
            wp_enqueue_script('apexcharts', 'https://cdn.jsdelivr.net/npm/apexcharts', array(), '3.35.0', true);
        }
        // Localize script with AJAX URL and nonces
        wp_localize_script('brmedia-admin-js', 'brmedia_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'maintenance_nonce' => wp_create_nonce('brmedia_maintenance'),
            'template_reset_nonce' => wp_create_nonce('brmedia_template_reset')
        ));
    }
}
add_action('admin_enqueue_scripts', 'brmedia_enqueue_admin_assets');