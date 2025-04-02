<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Register BRMedia admin menu
 */
function brmedia_register_admin_menu() {
    add_menu_page(
    'BRMedia Dashboard',
    'BRMedia',
    'manage_options',
    'brmedia-dashboard',
    'brmedia_dashboard_page', // this function should echo dashboard content
    BRMEDIA_URL . 'assets/images/brmedia-icon.png'
);

    // General Settings
    add_submenu_page('brmedia-dashboard', 'Settings', 'Settings', 'manage_options', 'brmedia-settings', 'brmedia_settings_page');

    // Music Section
    add_submenu_page('brmedia-dashboard', 'Manage Music', 'Manage Music', 'manage_options', 'edit.php?post_type=brmusic');
    add_submenu_page('brmedia-dashboard', 'Add New Music', 'Add New Music', 'manage_options', 'post-new.php?post_type=brmusic');
    add_submenu_page('brmedia-dashboard', 'Music Categories & Tags', 'Music Categories & Tags', 'manage_options', 'edit-tags.php?taxonomy=brmusic_category&post_type=brmusic');

    // Video Section
    add_submenu_page('brmedia-dashboard', 'Manage Video', 'Manage Video', 'manage_options', 'edit.php?post_type=brvideo');
    add_submenu_page('brmedia-dashboard', 'Add New Video', 'Add New Video', 'manage_options', 'post-new.php?post_type=brvideo');
    add_submenu_page('brmedia-dashboard', 'Video Categories & Tags', 'Video Categories & Tags', 'manage_options', 'edit-tags.php?taxonomy=brvideo_category&post_type=brvideo');

    // Templates
    add_submenu_page('brmedia-dashboard', 'Templates', 'Templates', 'manage_options', 'brmedia-templates', 'brmedia_templates_page');

    // Shortcodes
    add_submenu_page('brmedia-dashboard', 'Shortcodes Manager', 'Shortcodes Manager', 'manage_options', 'brmedia-shortcodes', 'brmedia_shortcodes_page');

    // Statistics
    add_submenu_page('brmedia-dashboard', 'Statistics & Analytics', 'Statistics & Analytics', 'manage_options', 'brmedia-statistics', 'brmedia_statistics_page');

    // Downloads
    add_submenu_page('brmedia-dashboard', 'Downloads & Buttons', 'Downloads & Buttons', 'manage_options', 'brmedia-downloads-settings', 'brmedia_downloads_settings_page');

    // Widgets (inside Settings tab)
    add_submenu_page(null, 'Widgets Settings', 'Widgets Settings', 'manage_options', 'brmedia-widgets-settings', 'brmedia_widgets_settings_page');

    // SEO & Sharing (inside Settings tab)
    add_submenu_page(null, 'SEO & Sharing Settings', 'SEO & Sharing Settings', 'manage_options', 'brmedia-seo-settings', 'brmedia_seo_settings_page');
}
add_action('admin_menu', 'brmedia_register_admin_menu');
