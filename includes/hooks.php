<?php
/**
 * BRMedia Hooks
 * Central place for loading plugin functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Menu
require_once BRMEDIA_PATH . 'admin/class-brmedia-admin-menu.php';

// Register custom post types
require_once BRMEDIA_PATH . 'includes/post-types.php';

// Register metaboxes
require_once BRMEDIA_PATH . 'admin/class-brmedia-metaboxes.php';

// Media metadata helpers
require_once BRMEDIA_PATH . 'admin/class-brmedia-media-metadata.php';

// AJAX handlers (frontend + admin)
require_once BRMEDIA_PATH . 'admin/class-brmedia-ajax-handlers.php';

// Shortcodes + shortcode manager
require_once BRMEDIA_PATH . 'admin/class-brmedia-shortcodes.php';
require_once BRMEDIA_PATH . 'admin/class-brmedia-shortcode-manager.php';

// Admin dashboard
require_once BRMEDIA_PATH . 'admin/class-brmedia-dashboard.php';

// Social sharing (frontend)
require_once BRMEDIA_PATH . 'admin/class-brmedia-social-sharing.php';

// Admin settings API
require_once BRMEDIA_PATH . 'admin/class-brmedia-settings-api.php';
require_once BRMEDIA_PATH . 'admin/class-brmedia-settings.php';

// Core functions
require_once BRMEDIA_PATH . 'includes/functions.php';

// Enqueue frontend assets
add_action( 'wp_enqueue_scripts', 'brmedia_enqueue_frontend_assets' );
function brmedia_enqueue_frontend_assets() {
    wp_enqueue_style( 'brmedia-frontend', BRMEDIA_URL . 'assets/css/frontend.css', array(), BRMEDIA_VERSION );
    wp_enqueue_script( 'brmedia-js', BRMEDIA_URL . 'assets/js/brmedia.js', array('jquery'), BRMEDIA_VERSION, true );

    wp_localize_script( 'brmedia-js', 'brmedia_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'brmedia_nonce' )
    ) );
}

// Enqueue admin assets
add_action( 'admin_enqueue_scripts', 'brmedia_enqueue_admin_assets' );
function brmedia_enqueue_admin_assets( $hook ) {
    // Load for BRMedia settings + post editor
    if (
        strpos( $hook, 'brmedia' ) !== false || 
        in_array( $hook, ['post.php', 'post-new.php'] )
    ) {
        wp_enqueue_style( 'brmedia-admin', BRMEDIA_URL . 'assets/css/admin.css', array(), BRMEDIA_VERSION );
        wp_enqueue_script( 'brmedia-admin', BRMEDIA_URL . 'assets/js/admin-media.js', array('jquery'), BRMEDIA_VERSION, true );
        wp_enqueue_media(); // <- This is what enables the media uploader
    }
}