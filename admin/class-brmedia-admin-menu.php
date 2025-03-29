<?php
/**
 * BRMedia Admin Menu
 * Registers the full admin menu structure for the plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Admin_Menu {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
    }

    public function register_admin_menu() {

        // Top-level menu
        add_menu_page(
            __( 'BRMedia', 'brmedia' ),
            'BRMedia',
            'manage_options',
            'brmedia-dashboard',
            array( $this, 'render_dashboard' ),
            'dashicons-playlist-audio',
            3
        );

        // Dashboard
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Dashboard', 'brmedia' ),
            __( 'Dashboard', 'brmedia' ),
            'manage_options',
            'brmedia-dashboard',
            array( $this, 'render_dashboard' )
        );

        // Settings
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Settings', 'brmedia' ),
            __( 'Settings', 'brmedia' ),
            'manage_options',
            'brmedia-settings',
            array( $this, 'render_settings' )
        );

        // Divider - Music Section
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Music', 'brmedia' ),
            '─ ' . __( 'Music', 'brmedia' ),
            'read',
            'separator-music',
            '__return_null'
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'All Tracks', 'brmedia' ),
            __( 'All Tracks', 'brmedia' ),
            'edit_posts',
            'edit.php?post_type=brmedia_music',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Add New', 'brmedia' ),
            __( 'Add New', 'brmedia' ),
            'edit_posts',
            'post-new.php?post_type=brmedia_music',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Genres', 'brmedia' ),
            __( 'Genres', 'brmedia' ),
            'manage_categories',
            'edit-tags.php?taxonomy=brmedia_genre&post_type=brmedia_music',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Tags', 'brmedia' ),
            __( 'Tags', 'brmedia' ),
            'manage_categories',
            'edit-tags.php?taxonomy=brmedia_tag&post_type=brmedia_music',
            ''
        );

        // Divider - Video Section
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Video', 'brmedia' ),
            '─ ' . __( 'Video', 'brmedia' ),
            'read',
            'separator-video',
            '__return_null'
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'All Videos', 'brmedia' ),
            __( 'All Videos', 'brmedia' ),
            'edit_posts',
            'edit.php?post_type=brmedia_video',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Add New', 'brmedia' ),
            __( 'Add New', 'brmedia' ),
            'edit_posts',
            'post-new.php?post_type=brmedia_video',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Categories', 'brmedia' ),
            __( 'Categories', 'brmedia' ),
            'manage_categories',
            'edit-tags.php?taxonomy=brmedia_video_category&post_type=brmedia_video',
            ''
        );

        add_submenu_page(
            'brmedia-dashboard',
            __( 'Tags', 'brmedia' ),
            __( 'Tags', 'brmedia' ),
            'manage_categories',
            'edit-tags.php?taxonomy=brmedia_video_tag&post_type=brmedia_video',
            ''
        );

        // Shortcodes
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Shortcodes', 'brmedia' ),
            __( 'Shortcodes', 'brmedia' ),
            'manage_options',
            'brmedia-shortcodes',
            array( $this, 'render_shortcodes' )
        );

        // Stats
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Stats', 'brmedia' ),
            __( 'Stats', 'brmedia' ),
            'manage_options',
            'brmedia-stats',
            array( $this, 'render_stats' )
        );
    }

    public function render_dashboard() {
        echo '<h1>' . esc_html__( 'Dashboard Placeholder', 'brmedia' ) . '</h1>';
    }

    public function render_settings() {
        echo '<h1>' . esc_html__( 'Settings Placeholder', 'brmedia' ) . '</h1>';
    }

    public function render_shortcodes() {
        echo '<h1>' . esc_html__( 'Shortcodes Placeholder', 'brmedia' ) . '</h1>';
    }

    public function render_stats() {
        echo '<h1>' . esc_html__( 'Stats Placeholder', 'brmedia' ) . '</h1>';
    }
}

new BRMedia_Admin_Menu();