<?php
/**
 * Plugin Name: BRMedia
 * Plugin URI: https://blackburnravers.co.uk
 * Description: Powerful media player plugin with audio/video templates, waveform/visualizer, stats, and more.
 * Version: 1.0
 * Author: Rhys Cole
 * Author URI: https://blackburnravers.co.uk
 * License: GPL2
 */

if (!defined('ABSPATH')) exit;

// Define constants
define('BRMEDIA_VERSION', '1.0');
define('BRMEDIA_PATH', plugin_dir_path(__FILE__));
define('BRMEDIA_URL', plugin_dir_url(__FILE__));
define('BRMEDIA_ASSETS', BRMEDIA_URL . 'assets/');
define('BRMEDIA_ADMIN', BRMEDIA_PATH . 'includes/admin/');
define('BRMEDIA_PUBLIC', BRMEDIA_PATH . 'includes/public/');
define('BRMEDIA_COMMON', BRMEDIA_PATH . 'includes/common/');
define('BRMEDIA_TEMPLATES', BRMEDIA_PATH . 'templates/');
define('BRMEDIA_PLUGIN_FILE', __FILE__);

if (!defined('BRMEDIA_PLUGIN_URL')) {
    define('BRMEDIA_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Load core plugin files
require_once BRMEDIA_COMMON . 'class-brmedia-utils.php';
require_once BRMEDIA_COMMON . 'class-brmedia-hooks.php';
require_once BRMEDIA_PUBLIC . 'class-brmedia-player.php';
require_once BRMEDIA_PUBLIC . 'class-brmedia-frontend.php';

// Load admin files
if (is_admin()) {
    require_once BRMEDIA_ADMIN . 'dashboard.php';
    require_once BRMEDIA_ADMIN . 'menu-setup.php';
    require_once BRMEDIA_ADMIN . 'settings.php';
    require_once BRMEDIA_ADMIN . 'templates-settings.php';
    require_once BRMEDIA_ADMIN . 'templates-audio-settings.php';
    require_once BRMEDIA_ADMIN . 'templates-video-settings.php';
    require_once BRMEDIA_ADMIN . 'waveform-settings.php';
    require_once BRMEDIA_ADMIN . 'visualizer-settings.php';
    require_once BRMEDIA_ADMIN . 'downloads-settings.php';
    require_once BRMEDIA_ADMIN . 'shortcodes.php';
    require_once BRMEDIA_ADMIN . 'statistics.php';
    require_once BRMEDIA_ADMIN . 'meta-boxes.php';
    require_once BRMEDIA_ADMIN . 'widgets.php';
    require_once BRMEDIA_ADMIN . 'icon-picker.php';
}

// Load AJAX handler
require_once plugin_dir_path(__FILE__) . 'ajax.php';

// Frontend scripts and styles
function brmedia_enqueue_scripts() {
    wp_enqueue_style('brmedia-frontend', BRMEDIA_ASSETS . 'css/brmedia-frontend.css', [], BRMEDIA_VERSION);
    wp_enqueue_script('brmedia-scripts', BRMEDIA_ASSETS . 'js/brmedia-scripts.js', ['jquery'], BRMEDIA_VERSION, true);
    wp_enqueue_script('brmedia-frontend', BRMEDIA_ASSETS . 'js/brmedia-frontend.js', ['jquery'], BRMEDIA_VERSION, true);
    wp_enqueue_script('brmedia-casting', BRMEDIA_ASSETS . 'js/brmedia-casting.js', [], BRMEDIA_VERSION, true);
    wp_enqueue_script('brmedia-footer-player', BRMEDIA_ASSETS . 'js/brmedia-footer-player.js', [], BRMEDIA_VERSION, true);
    wp_enqueue_script('visualizer', BRMEDIA_ASSETS . 'js/visualizer.js', [], BRMEDIA_VERSION, true);
    wp_enqueue_script('waveform-helper', BRMEDIA_ASSETS . 'js/waveform-helper.js', [], BRMEDIA_VERSION, true);
}
add_action('wp_enqueue_scripts', 'brmedia_enqueue_scripts');

// Admin scripts and styles
function brmedia_enqueue_admin_assets($hook) {
    wp_enqueue_style('brmedia-admin', BRMEDIA_ASSETS . 'css/brmedia-admin.css', [], BRMEDIA_VERSION);
    wp_enqueue_script('brmedia-admin', BRMEDIA_ASSETS . 'js/brmedia-admin.js', ['jquery'], BRMEDIA_VERSION, true);
    wp_enqueue_script('brmedia-analytics', BRMEDIA_ASSETS . 'js/brmedia-analytics.js', ['chart.js'], BRMEDIA_VERSION, true);
    wp_enqueue_script('fontawesome-preview', BRMEDIA_ASSETS . 'js/fontawesome-preview.js', [], BRMEDIA_VERSION, true);
}
add_action('admin_enqueue_scripts', 'brmedia_enqueue_admin_assets');

// Dashboard icon
function brmedia_custom_menu_icon() {
    echo '<style>#adminmenu .toplevel_page_brmedia-dashboard .wp-menu-image img { width: 20px; height: 20px; }</style>';
}
add_action('admin_head', 'brmedia_custom_menu_icon');

// Hide CPT duplicates
function brmedia_remove_cpt_duplicates() {
    remove_menu_page('edit.php?post_type=brmusic');
    remove_menu_page('edit.php?post_type=brvideo');
}
add_action('admin_menu', 'brmedia_remove_cpt_duplicates', 999);