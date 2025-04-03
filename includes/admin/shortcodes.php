<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Register BRMedia Shortcodes
 */
function brmedia_register_shortcodes() {
    add_shortcode('brmedia_audio', 'brmedia_audio_shortcode');
    add_shortcode('brmedia_video', 'brmedia_video_shortcode');
    add_shortcode('brmedia_tracklist', 'brmedia_tracklist_shortcode');
    add_shortcode('brmedia_cover', 'brmedia_cover_shortcode');
    add_shortcode('brmedia_download', 'brmedia_download_button_shortcode');
}
add_action('init', 'brmedia_register_shortcodes');

/**
 * Audio Shortcode Callback
 */
function brmedia_audio_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => '',
        'template' => 'default',
    ], $atts, 'brmedia_audio');

    if (empty($atts['id'])) return 'Audio ID is required.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'brmusic') return 'Invalid audio ID.';

    setup_postdata($post);

    $template = sanitize_title($atts['template']);
    $template_file = BRMEDIA_PLUGIN_DIR . 'includes/' . 'audio-' . $template . '-player.php';

    if (!file_exists($template_file)) {
        wp_reset_postdata();
        return '<div class="brmedia-error">Audio player template not found: ' . esc_html($template) . '</div>';
    }

    ob_start();
    include $template_file;
    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * Video Shortcode Callback
 */
function brmedia_video_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => '',
        'template' => 'default',
    ], $atts, 'brmedia_video');

    if (empty($atts['id'])) return 'Video ID is required.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'brvideo') return 'Invalid video ID.';

    setup_postdata($post);

    $template = sanitize_title($atts['template']);
    $template_file = BRMEDIA_PLUGIN_DIR . 'includes/' . 'video-' . $template . '-player.php';

    if (!file_exists($template_file)) {
        wp_reset_postdata();
        return '<div class="brmedia-error">Video player template not found: ' . esc_html($template) . '</div>';
    }

    ob_start();
    include $template_file;
    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * Tracklist Shortcode
 */
function brmedia_tracklist_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => ''
    ], $atts, 'brmedia_tracklist');

    if (empty($atts['id'])) return 'Audio ID required.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'brmusic') return 'Invalid audio ID.';

    $tracklist = get_post_meta($post->ID, '_brmedia_tracklist', true);
    if (empty($tracklist)) return 'No tracklist found.';

    ob_start();
    echo '<div class="brmedia-tracklist-standalone"><pre>' . esc_html($tracklist) . '</pre></div>';
    return ob_get_clean();
}

/**
 * Cover Image Shortcode
 */
function brmedia_cover_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => ''
    ], $atts, 'brmedia_cover');

    if (empty($atts['id'])) return 'Audio ID required.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'brmusic') return 'Invalid audio ID.';

    $cover_image = get_the_post_thumbnail_url($post->ID, 'full');
    if (!$cover_image) return 'No cover image found.';

    return '<div class="brmedia-cover-standalone"><img src="' . esc_url($cover_image) . '" alt="' . esc_attr(get_the_title($post)) . '" /></div>';
}

/**
 * Download Button Shortcode
 */
function brmedia_download_button_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => '',
        'label' => 'Download',
        'icon' => 'fas fa-download',
    ], $atts, 'brmedia_download');

    if (empty($atts['id'])) return 'Audio ID required.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'brmusic') return 'Invalid audio ID.';

    $download_url = get_post_meta($post->ID, '_brmedia_audio_file', true);
    if (empty($download_url)) return 'No audio file found.';

    return '<a class="brmedia-download-btn" href="' . esc_url($download_url) . '" download data-post-id="' . esc_attr($post->ID) . '"><i class="' . esc_attr($atts['icon']) . '"></i> ' . esc_html($atts['label']) . '</a>';
}