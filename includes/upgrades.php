<?php
/**
 * BRMedia Upgrade Handler
 * Run database or option updates when plugin version changes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'plugins_loaded', 'brmedia_run_upgrades', 20 );

function brmedia_run_upgrades() {
    $installed_version = get_option( 'brmedia_db_version', '1.0.0' );
    $current_version   = defined( 'BRMEDIA_VERSION' ) ? BRMEDIA_VERSION : '1.0.0';

    if ( version_compare( $installed_version, $current_version, '<' ) ) {

        // Example upgrade: add new default options if missing
        if ( version_compare( $installed_version, '1.1.0', '<' ) ) {
            $video_settings = get_option( 'brmedia_video', array() );

            if ( ! isset( $video_settings['video_poster_fallback'] ) ) {
                $video_settings['video_poster_fallback'] = '';
                update_option( 'brmedia_video', $video_settings );
            }
        }

        // Example: trigger db/schema-upgrades.php manually if needed
        if ( file_exists( BRMEDIA_PATH . 'db/schema-upgrades.php' ) ) {
            include_once BRMEDIA_PATH . 'db/schema-upgrades.php';
        }

        // Update the stored version
        update_option( 'brmedia_db_version', $current_version );
    }
}