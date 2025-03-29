<?php
/**
 * Plugin Name: BRMedia
 * Description: Advanced music and video player plugin for WordPress with tracklists, templates, shortcodes, analytics, sharing, and more.
 * Author: Rhys Cole
 * Version: 1.0.0
 * Text Domain: brmedia
 * Domain Path: /languages
 * Author URI: https://www.blackburnravers.co.uk
 * Plugin URI: https://www.blackburnravers.co.uk/brmedia
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'BRMEDIA_VERSION', '1.0.0' );
define( 'BRMEDIA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BRMEDIA_URL', plugin_dir_url( __FILE__ ) );

// Load translations
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'brmedia', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

// Include core files
require_once BRMEDIA_PATH . 'includes/hooks.php';
require_once BRMEDIA_PATH . 'includes/functions.php';
require_once BRMEDIA_PATH . 'includes/taxonomies.php';
require_once BRMEDIA_PATH . 'includes/template-tags.php';
require_once BRMEDIA_PATH . 'includes/shortcodes.php';
require_once BRMEDIA_PATH . 'includes/upgrades.php';
require_once BRMEDIA_PATH . 'includes/compatibility.php';
require_once BRMEDIA_PATH . 'includes/integrations.php';
require_once BRMEDIA_PATH . 'includes/widgets.php';

// Optional: Custom database setup
register_activation_hook( __FILE__, 'brmedia_activate_plugin' );
function brmedia_activate_plugin() {
    $schema_file = BRMEDIA_PATH . 'db/schema.php';
    if ( file_exists( $schema_file ) ) {
        require_once $schema_file;
    }
    update_option( 'brmedia_db_version', BRMEDIA_VERSION );
}

// Optional: Clean uninstall
register_uninstall_hook( __FILE__, 'brmedia_uninstall_plugin' );
function brmedia_uninstall_plugin() {
    // See uninstall.php file for full cleanup logic
    include plugin_dir_path( __FILE__ ) . 'uninstall.php';
}

// Register admin menu (simplified loader)
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'BRMedia', 'brmedia' ),
        'BRMedia',
        'manage_options',
        'brmedia-dashboard',
        'brmedia_admin_dashboard_page',
        'dashicons-playlist-audio',
        24
    );
});

function brmedia_admin_dashboard_page() {
    do_action( 'brmedia_admin_dashboard' );
}