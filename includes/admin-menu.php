<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_admin_menu() {
    // Retrieve general settings
    $settings = get_option('brmedia_general_options', []);

    // Add the top-level menu
    add_menu_page(
        'BRMedia',
        'BRMedia',
        'manage_options',
        'brmedia',
        'brmedia_dashboard_page',
        plugins_url('../assets/images/brmedia-icon.png', __FILE__),
        2
    );

    // Add submenu pages in desired order
    add_submenu_page(
        'brmedia',
        'BRMedia Dashboard',
        'Dashboard',
        'manage_options',
        'brmedia',
        'brmedia_dashboard_page'
    );

    add_submenu_page(
        'brmedia',
        'General Settings',
        'General Settings',
        'manage_options',
        'brmedia-general-settings',
        'brmedia_general_settings_page'
    );

    add_submenu_page(
        'brmedia',
        'Template Settings',
        'Template Settings',
        'manage_options',
        'brmedia-template-settings',
        'brmedia_template_settings_page'
    );
    
    add_submenu_page(
        'brmedia',
        '',
        '───────',
        'manage_options',
        'brmedia-separator-1',
        '__return_null' // Does nothing
    );
    
    add_submenu_page(
        'brmedia',
        'Music Tracks',
        'Music Tracks',
        'manage_options',
        'edit.php?post_type=brmedia_track'
    );
    
    if (!empty($settings['addon_video'])) {
        add_submenu_page(
            'brmedia',
            'Video Files',
            'Video Files',
            'manage_options',
            'brmedia-videos',
            'brmedia_videos'
        );
    }
    
    if (!empty($settings['addon_radio'])) {
        add_submenu_page(
            'brmedia',
            'Radio',
            'Radio',
            'manage_options',
            'brmedia-radio',
            'brmedia_radio'
        );
    }
    
    if (!empty($settings['addon_downloads'])) {
        add_submenu_page(
            'brmedia',
            'Downloads',
            'Downloads',
            'manage_options',
            'brmedia-downloads',
            'brmedia_downloads'
        );
    }
    
    if (!empty($settings['addon_chat'])) {
        add_submenu_page(
            'brmedia',
            'Chat',
            'Chat',
            'manage_options',
            'brmedia-chat',
            'brmedia_chat'
        );
    }
    
    if (!empty($settings['addon_gaming'])) {
        add_submenu_page(
            'brmedia',
            'Gaming',
            'Gaming',
            'manage_options',
            'brmedia-gaming',
            'brmedia_gaming'
        );
    }
    
    if (!empty($settings['addon_podcast'])) {
        add_submenu_page(
            'brmedia',
            'Podcasts',
            'Podcasts',
            'manage_options',
            'brmedia-podcasts',
            'brmedia_podcasts'
        );
    }
    
    if (!empty($settings['addon_footbar'])) {
        add_submenu_page(
            'brmedia',
            'Footbar',
            'Footbar',
            'manage_options',
            'brmedia-footbar',
            'brmedia_footbar'
        );
    }
    
    add_submenu_page(
        'brmedia',
        '',
        '───────',
        'manage_options',
        'brmedia-separator-2',
        '__return_null' // Does nothing
    );

    add_submenu_page(
        'brmedia',
        'Music Import Settings',
        'Import Settings',
        'manage_options',
        'brmedia-import-settings',
        'brmedia_import_settings_page'
    );

    add_submenu_page(
        'brmedia',
        'Import Media',
        'Import Media',
        'manage_options',
        'brmedia-import',
        'brmedia_import_page'
    );
    
    add_submenu_page(
        'brmedia',
        '',
        '───────',
        'manage_options',
        'brmedia-separator-3',
        '__return_null' // Does nothing
    );

    add_submenu_page(
        'brmedia',
        'Stats',
        'Stats',
        'manage_options',
        'brmedia-stats',
        'brmedia_stats_page'
    );

    add_submenu_page(
        'brmedia',
        'Shortcodes',
        'Shortcodes',
        'manage_options',
        'brmedia-shortcodes',
        'brmedia_shortcodes_page'
    );

    add_submenu_page(
        'brmedia',
        'Maintenance',
        'Maintenance',
        'manage_options',
        'brmedia-maintenance',
        'brmedia_maintenance_page'
    );
}
add_action('admin_menu', 'brmedia_admin_menu');

// Callback functions for each submenu page
function brmedia_dashboard_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/dashboard.php';
}

function brmedia_general_settings_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/general-settings.php';
}

function brmedia_template_settings_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/template-settings.php';
}

function brmedia_import_settings_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/import-settings.php';
}

function brmedia_import_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/import.php';
}

function brmedia_stats_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/stats.php';
}

function brmedia_shortcodes_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/shortcodes.php';
}

function brmedia_maintenance_page() {
    require_once plugin_dir_path(__FILE__) . '../admin/maintenance.php';
    brmedia_render_maintenance_page(); // Call a new rendering function
}

function brmedia_videos() {
    require_once plugin_dir_path(__FILE__) . '../admin/videos.php';
}

function brmedia_radio() {
    require_once plugin_dir_path(__FILE__) . '../admin/radio.php';
}

function brmedia_downloads() {
    require_once plugin_dir_path(__FILE__) . '../admin/downloads.php';
}

function brmedia_chat() {
    require_once plugin_dir_path(__FILE__) . '../admin/chat.php';
}

function brmedia_gaming() {
    require_once plugin_dir_path(__FILE__) . '../admin/gaming.php';
}

function brmedia_podcasts() {
    require_once plugin_dir_path(__FILE__) . '../admin/podcasts.php';
}

function brmedia_footbar() {
    require_once plugin_dir_path(__FILE__) . '../admin/footbar.php';
}