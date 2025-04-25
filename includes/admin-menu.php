<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_admin_menu() {
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
        'Waveform Generator',
        'Waveform Generator',
        'manage_options',
        'brmedia-waveform-generator',
        'brmedia_waveform_generator_page'
    );

    add_submenu_page(
        'brmedia',
        'Waveform Progress',
        'Waveform Progress',
        'manage_options',
        'brmedia-waveform-progress',
        'brmedia_waveform_progress_page'
    );
    
    add_submenu_page(
    'brmedia',
    '',
    '───────',
    'manage_options',
    'brmedia-separator-4',
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