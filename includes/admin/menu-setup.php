<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Admin Menu Setup for BRMedia Plugin
 */

function brmedia_register_admin_menu() {

    // Main BRMedia menu
    add_menu_page(
        __('BRMedia', 'brmedia'),
        __('BRMedia', 'brmedia'),
        'manage_options',
        'brmedia-dashboard',
        'brmedia_dashboard_page',
        'dashicons-format-audio', // Can be replaced with your custom icon via CSS
        3
    );

    // Dashboard page
    add_submenu_page(
        'brmedia-dashboard',
        __('Dashboard', 'brmedia'),
        __('Dashboard', 'brmedia'),
        'manage_options',
        'brmedia-dashboard',
        'brmedia_dashboard_page'
    );

    // Settings page with internal tabs (General, Widgets, SEO, Sharing, etc.)
    add_submenu_page(
        'brmedia-dashboard',
        __('Settings', 'brmedia'),
        __('Settings', 'brmedia'),
        'manage_options',
        'brmedia-settings',
        'brmedia_settings_page'
    );
    
    // Music Section
    add_submenu_page(
        'brmedia-dashboard',
        'Manage Music Entries',
        'Music',
        'manage_options',
        'edit.php?post_type=brmusic'
    );

    add_submenu_page(
        'brmedia-dashboard',
        'Add New Music',
        'Add New Music',
        'manage_options',
        'post-new.php?post_type=brmusic'
    );

    add_submenu_page(
        'brmedia-dashboard',
        'Music Categories & Tags',
        'Categories & Tags',
        'manage_options',
        'edit-tags.php?taxonomy=category&post_type=brmusic'
    );

    // Video Section
    add_submenu_page(
        'brmedia-dashboard',
        'Manage Video Entries',
        'Video',
        'manage_options',
        'edit.php?post_type=brvideo'
    );
    
    add_submenu_page(
        'brmedia-dashboard',
        'Add New Video',
        'Add New Video',
        'manage_options',
        'post-new.php?post_type=brvideo'
    );
    
    add_submenu_page(
        'brmedia-dashboard',
        'Video Categories & Tags',
        'Categories & Tags',
        'manage_options',
        'edit-tags.php?taxonomy=category&post_type=brvideo'
    );

    // Templates settings
    add_submenu_page(
        'brmedia-dashboard',
        __('Templates', 'brmedia'),
        __('Templates', 'brmedia'),
        'manage_options',
        'brmedia-templates',
        'brmedia_render_templates_settings'
    );

    // Statistics
    add_submenu_page(
        'brmedia-dashboard',
        __('Statistics', 'brmedia'),
        __('Statistics', 'brmedia'),
        'manage_options',
        'brmedia-statistics',
        'brmedia_statistics_page'
    );

    // Downloads & Buttons
    add_submenu_page(
        'brmedia-dashboard',
        __('Downloads', 'brmedia'),
        __('Downloads', 'brmedia'),
        'manage_options',
        'brmedia-downloads',
        'brmedia_render_downloads_settings_page'
    );

    // Shortcodes Manager
add_submenu_page(
  'brmedia-dashboard',
  __('Shortcodes Manager', 'brmedia'),
  __('Shortcodes Manager', 'brmedia'),
  'manage_options',
  'brmedia-shortcodes',
  'brmedia_shortcodes_page'
);

    // Hide CPT menus from top-level if needed (optional)
    remove_menu_page('edit.php?post_type=music');
    remove_menu_page('edit.php?post_type=video');
}
add_action('admin_menu', 'brmedia_register_admin_menu');

// Include callback files (assuming each page has its own file)
require_once plugin_dir_path(__FILE__) . 'dashboard.php';
require_once plugin_dir_path(__FILE__) . 'settings.php';
require_once plugin_dir_path(__FILE__) . 'templates-settings.php';
require_once plugin_dir_path(__FILE__) . 'statistics.php';
require_once plugin_dir_path(__FILE__) . 'downloads-settings.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes-manager.php';