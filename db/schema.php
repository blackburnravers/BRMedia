<?php
/**
 * BRMedia Schema Setup
 * Creates custom DB tables on plugin activation
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;

// Include WordPress upgrade functions
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$charset_collate = $wpdb->get_charset_collate();
$prefix = $wpdb->prefix;

// 1. Play Stats Table
$play_stats = "{$prefix}brmedia_play_stats";
$sql_play_stats = "CREATE TABLE $play_stats (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    media_id BIGINT(20) UNSIGNED NOT NULL,
    user_ip VARCHAR(100) DEFAULT '',
    play_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX (media_id),
    INDEX (play_date)
) $charset_collate;";

// 2. Downloads Table
$downloads = "{$prefix}brmedia_downloads";
$sql_downloads = "CREATE TABLE $downloads (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    media_id BIGINT(20) UNSIGNED NOT NULL,
    user_ip VARCHAR(100) DEFAULT '',
    download_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX (media_id),
    INDEX (download_date)
) $charset_collate;";

// 3. Chat Messages Table
$chat = "{$prefix}brmedia_chat";
$sql_chat = "CREATE TABLE $chat (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    post_id BIGINT(20) UNSIGNED NOT NULL,
    user_id BIGINT(20) UNSIGNED DEFAULT 0,
    username VARCHAR(100) DEFAULT '',
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX (post_id),
    INDEX (created_at)
) $charset_collate;";

// Run all queries
dbDelta( $sql_play_stats );
dbDelta( $sql_downloads );
dbDelta( $sql_chat );