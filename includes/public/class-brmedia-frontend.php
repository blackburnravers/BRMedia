<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * BRMedia Frontend Class
 * Handles public-facing assets and frontend enhancements
 */
class BRMedia_Frontend {

    /**
     * Initialize public hooks
     */
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_frontend_assets']);
        add_action('wp_footer', [__CLASS__, 'render_footer_player']);
    }

    /**
     * Enqueue all frontend CSS/JS
     */
    public static function enqueue_frontend_assets() {
        // Plyr (CDN)
        wp_enqueue_style('plyr', 'https://cdn.plyr.io/3.7.8/plyr.css', [], null);
        wp_enqueue_script('plyr', 'https://cdn.plyr.io/3.7.8/plyr.js', ['jquery'], null, true);

        // FontAwesome (CDN)
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], null);

        // Custom Styles
        wp_enqueue_style('brmedia-frontend-css', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia-frontend.css', [], BRMEDIA_VERSION);
        wp_enqueue_style('brmedia-core-css', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia-styles.css', [], BRMEDIA_VERSION);

        // Core JS
        wp_enqueue_script('brmedia-frontend-js', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia-frontend.js', ['jquery', 'plyr'], BRMEDIA_VERSION, true);
        wp_enqueue_script('brmedia-scripts', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia-scripts.js', ['jquery', 'plyr'], BRMEDIA_VERSION, true);

        // Optional: Visualizer & Casting
        wp_enqueue_script('brmedia-visualizer', BRMEDIA_PLUGIN_URL . 'assets/js/visualizer.js', ['jquery'], BRMEDIA_VERSION, true);
        wp_enqueue_script('brmedia-casting', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia-casting.js', ['jquery'], BRMEDIA_VERSION, true);

        // Localized Ajax
        wp_localize_script('brmedia-scripts', 'brmediaAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('brmedia_nonce')
        ]);
    }

    /**
     * Render footer player if enabled (to be expanded later)
     */
    public static function render_footer_player() {
        $enabled = get_option('brmedia_footer_player_enabled');
        if (!$enabled) return;

        echo '<div id="brmedia-footer-player">';
        echo '<p class="brmedia-footer-placeholder">[BRMedia Footer Player Placeholder]</p>';
        echo '</div>';
    }
}

BRMedia_Frontend::init();