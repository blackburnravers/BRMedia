<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * BRMedia Player Core Class
 * Handles the rendering logic and output of media players
 */
class BRMedia_Player {

    /**
     * Initialize hooks for shortcodes and template helpers
     */
    public static function init() {
        add_shortcode('brmedia_audio', [__CLASS__, 'render_audio_player']);
        add_shortcode('brmedia_video', [__CLASS__, 'render_video_player']);
        add_shortcode('brmedia_tracklist', [__CLASS__, 'render_tracklist']);
        add_shortcode('brmedia_cover', [__CLASS__, 'render_cover_image']);
        add_shortcode('brmedia_download', [__CLASS__, 'render_download_block']);
    }

    /**
     * Render Audio Player Template
     */
    public static function render_audio_player($atts) {
        $atts = shortcode_atts([
            'id' => '',
            'template' => 'default',
        ], $atts, 'brmedia_audio');

        $post = get_post($atts['id']);
        if (!$post || $post->post_type !== 'brmusic') return 'Invalid audio ID.';

        setup_postdata($post);
        $template_file = BRMEDIA_PLUGIN_DIR . 'includes/audio-' . sanitize_file_name($atts['template']) . '-player.php';

        ob_start();
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            echo 'Audio template not found.';
        }
        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * Render Video Player Template
     */
    public static function render_video_player($atts) {
        $atts = shortcode_atts([
            'id' => '',
            'template' => 'default',
        ], $atts, 'brmedia_video');

        $post = get_post($atts['id']);
        if (!$post || $post->post_type !== 'brvideo') return 'Invalid video ID.';

        setup_postdata($post);
        $template_file = BRMEDIA_PLUGIN_DIR . 'includes/video-' . sanitize_file_name($atts['template']) . '-player.php';

        ob_start();
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            echo 'Video template not found.';
        }
        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * Render standalone tracklist (for use with [brmedia_tracklist id=""])
     */
    public static function render_tracklist($atts) {
        $atts = shortcode_atts([
            'id' => '',
        ], $atts, 'brmedia_tracklist');

        $tracklist = get_post_meta($atts['id'], '_brmedia_tracklist', true);
        if (!$tracklist) return '';

        ob_start();
        echo '<div class="brmedia-tracklist brmedia-detached-tracklist">';
        echo wpautop(esc_html($tracklist));
        echo '</div>';
        return ob_get_clean();
    }

    /**
     * Render standalone cover image (for use with [brmedia_cover id=""])
     */
    public static function render_cover_image($atts) {
        $atts = shortcode_atts([
            'id' => '',
        ], $atts, 'brmedia_cover');

        $img = get_the_post_thumbnail_url($atts['id'], 'full');
        if (!$img) return '';

        return '<div class="brmedia-cover-image-only"><img src="' . esc_url($img) . '" alt="Cover Image"></div>';
    }

    /**
     * Render Download Block Template
     */
    public static function render_download_block($atts) {
        $atts = shortcode_atts([
            'id' => '',
        ], $atts, 'brmedia_download');

        $post = get_post($atts['id']);
        if (!$post || !in_array($post->post_type, ['brmusic', 'brvideo'])) return '';

        $template_file = BRMEDIA_PLUGIN_DIR . 'includes/templates/download-block.php';

        ob_start();
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            echo '<div class="brmedia-error">Download block template not found.</div>';
        }
        return ob_get_clean();
    }
}

BRMedia_Player::init();