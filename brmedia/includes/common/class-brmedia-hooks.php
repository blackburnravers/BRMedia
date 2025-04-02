<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Class BRMedia_Hooks
 * Handles all WordPress hooks for BRMedia.
 */
class BRMedia_Hooks {

    public static function init() {
        // Custom Post Types
        add_action('init', [__CLASS__, 'register_custom_post_types']);

        // Admin Assets
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);

        // Frontend Assets
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_frontend_assets']);

        // AJAX handlers
        add_action('wp_ajax_brmedia_update_media_stats', [__CLASS__, 'ajax_update_media_stats']);
        add_action('wp_ajax_nopriv_brmedia_update_media_stats', [__CLASS__, 'ajax_update_media_stats']);
    }

    /**
     * Register BRMedia Custom Post Types
     */
    public static function register_custom_post_types() {
        $supports = ['title', 'editor', 'thumbnail'];

        register_post_type('brmusic', [
            'label' => 'Music',
            'public' => true,
            'menu_icon' => 'dashicons-format-audio',
            'supports' => $supports,
            'has_archive' => true,
            'rewrite' => ['slug' => 'music'],
            'show_in_rest' => true
        ]);

        register_post_type('brvideo', [
            'label' => 'Video',
            'public' => true,
            'menu_icon' => 'dashicons-video-alt3',
            'supports' => $supports,
            'has_archive' => true,
            'rewrite' => ['slug' => 'video'],
            'show_in_rest' => true
        ]);
    }

    /**
     * Enqueue admin scripts and styles
     */
    public static function enqueue_admin_assets($hook) {
        if (strpos($hook, 'brmedia') !== false || get_post_type() === 'brmusic' || get_post_type() === 'brvideo') {
            wp_enqueue_style('brmedia-admin', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia-admin.css', [], BRMEDIA_VERSION);
            wp_enqueue_script('brmedia-admin', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia-admin.js', ['jquery'], BRMEDIA_VERSION, true);
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');
        }
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public static function enqueue_frontend_assets() {
        wp_enqueue_style('plyr', 'https://cdn.plyr.io/3.7.8/plyr.css');
        wp_enqueue_script('plyr', 'https://cdn.plyr.io/3.7.8/plyr.js', ['jquery'], null, true);

        wp_enqueue_style('brmedia-frontend', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia-frontend.css', [], BRMEDIA_VERSION);
        wp_enqueue_script('brmedia-frontend', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia-frontend.js', ['jquery', 'plyr'], BRMEDIA_VERSION, true);

        wp_localize_script('brmedia-frontend', 'brmediaAjax', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
    }

    /**
     * Handle media stat updates (plays/downloads)
     */
    public static function ajax_update_media_stats() {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';

        if (!$post_id || !in_array($type, ['play', 'download'])) {
            wp_send_json_error('Invalid request.');
        }

        $key = $type === 'play' ? '_brmedia_play_count' : '_brmedia_download_count';
        $count = (int) get_post_meta($post_id, $key, true);
        update_post_meta($post_id, $key, $count + 1);

        wp_send_json_success(['count' => $count + 1]);
    }
}

// Initialize all hooks
BRMedia_Hooks::init();