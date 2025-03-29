<?php
/**
 * BRMedia Uninstall Cleanup
 * This file is triggered when the plugin is deleted
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Delete custom options
delete_option( 'brmedia_general' );
delete_option( 'brmedia_audio' );
delete_option( 'brmedia_video' );
delete_option( 'brmedia_templates' );
delete_option( 'brmedia_social' );
delete_option( 'brmedia_db_version' );

// Delete custom tables
$tables = array(
    $wpdb->prefix . 'brmedia_play_stats',
    $wpdb->prefix . 'brmedia_downloads',
    $wpdb->prefix . 'brmedia_share_stats'
);

foreach ( $tables as $table ) {
    $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

// Optionally: delete post meta (for media URLs, metadata, etc.)
$meta_keys = array(
    '_brmedia_media_url',
    '_brmedia_cover_image',
    '_brmedia_enable_download',
    '_brmedia_tracklist',
    '_brmedia_bpm',
    '_brmedia_key',
    '_brmedia_tags',
    '_brmedia_genre',
    '_brmedia_mood',
    '_brmedia_duration'
);

foreach ( $meta_keys as $key ) {
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->postmeta} WHERE meta_key = %s",
            $key
        )
    );
}