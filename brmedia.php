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
require_once BRMEDIA_PLUGIN_DIR . 'includes/settings-callbacks.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/post-type.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/admin-menu.php';
require_once BRMEDIA_PLUGIN_DIR . 'includes/enqueue-scripts.php';
// Add other includes as needed (e.g., shortcode.php), but avoid duplicates

// Define services array for import settings
global $services;
$services = [
    'soundcloud' => [
        'name' => 'SoundCloud',
        'color' => '#FF5500',
        'icon' => 'soundcloud.svg',
        'description' => 'A platform for sharing and discovering music.',
        'website' => 'https://soundcloud.com',
        'api' => 'https://developers.soundcloud.com',
        'api_fields' => ['client_id', 'client_secret'],
    ],
    'audiomack' => [
        'name' => 'Audiomack',
        'color' => '#FFA200',
        'icon' => 'audiomack.svg',
        'description' => 'A free music streaming platform for artists.',
        'website' => 'https://www.audiomack.com',
        'api' => 'https://www.audiomack.com/developers',
        'api_fields' => ['api_key'],
    ],
    // Add other services as needed
];

// Activation and deactivation hooks
function brmedia_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'brmedia_activate');

function brmedia_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'brmedia_deactivate');

// Initialize plugin
function brmedia_init() {
    // Add initialization code if needed
}
add_action('init', 'brmedia_init');