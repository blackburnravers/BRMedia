<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// Delete options
delete_option('brmedia_general');
delete_option('brmedia_templates');
delete_option('brmedia_controls');
delete_option('brmedia_stats');

// Remove database tables
global $wpdb;
$tables = [
    'brmedia_stats',
    'brmedia_events'
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table}");
}

// Clear scheduled events
wp_clear_scheduled_hook('brmedia_daily_cleanup');