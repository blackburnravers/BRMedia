<?php
/**
 * BRMedia Schema Upgrades
 * Handle DB upgrades on plugin update
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function brmedia_upgrade_schema( $current_version ) {
    $stored_version = get_option( 'brmedia_db_version', '0.0.0' );

    if ( version_compare( $stored_version, $current_version, '<' ) ) {
        brmedia_install_schema();
        update_option( 'brmedia_db_version', $current_version );
    }
}