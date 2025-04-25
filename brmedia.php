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
require_once BRMEDIA_PLUGIN_DIR . 'admin/waveform-generator.php';
require_once BRMEDIA_PLUGIN_DIR . 'admin/waveform-progress.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/waveform.php';

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
        'color' => '#00A4A4',
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
        'color' => '#00C4B4',
        'icon' => 'hearthis.png',
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
    'drooble' => [
        'name' => 'Drooble',
        'color' => '#00AEEF',
        'icon' => 'drooble.svg',
        'description' => 'offline to be removed',
        'website' => '',
        'api' => '',
    ],
    'house-mixes' => [
        'name' => 'House-Mixes.com',
        'color' => '#2E2E2E',
        'icon' => 'house-mixes.png',
        'description' => 'A platform for house music mixes.',
        'website' => 'https://www.house-mixes.com',
        'api' => '',
    ],
];

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
    error_log('BRMedia enqueue hook: ' . $hook);
    if (strpos($hook, 'brmedia') !== false) {
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
        wp_enqueue_style('brmedia-admin-css', BRMEDIA_PLUGIN_URL . 'assets/css/admin.css', array('bootstrap'), '1.1');
        wp_enqueue_script('brmedia-admin-js', BRMEDIA_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'bootstrap'), '1.0', true);
        if ($hook === 'brmedia_page_brmedia-stats') {
            wp_enqueue_script('apexcharts', 'https://cdn.jsdelivr.net/npm/apexcharts', array(), '3.35.0', true);
        }
    }
}
add_action('admin_enqueue_scripts', 'brmedia_enqueue_admin_assets');

// Initialize plugin
// Removed text domain loading since no languages/ folder