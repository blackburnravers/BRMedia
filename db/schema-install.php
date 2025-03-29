<?php
/**
 * BRMedia Database Schema Installer
 * Creates custom tables for stats tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function brmedia_install_schema() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $play_table = $wpdb->prefix . 'brmedia_play_stats';
    $download_table = $wpdb->prefix . 'brmedia_downloads';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "
        CREATE TABLE $play_table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            media_id BIGINT UNSIGNED NOT NULL,
            play_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_ip VARCHAR(100),
            PRIMARY KEY (id),
            KEY media_id (media_id)
        ) $charset_collate;

        CREATE TABLE $download_table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            media_id BIGINT UNSIGNED NOT NULL,
            download_url TEXT,
            download_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            user_ip VARCHAR(100),
            PRIMARY KEY (id),
            KEY media_id (media_id)
        ) $charset_collate;
    ";

    dbDelta( $sql );
}