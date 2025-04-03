<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Add Meta Boxes for Music and Video
 */
function brmedia_add_custom_meta_boxes() {
    add_meta_box('brmedia_audio_meta', 'Audio File & Tracklist', 'brmedia_audio_meta_callback', 'brmusic', 'normal', 'high');
    add_meta_box('brmedia_video_meta', 'Video File', 'brmedia_video_meta_callback', 'brvideo', 'normal', 'high');
}
add_action('add_meta_boxes', 'brmedia_add_custom_meta_boxes');

/**
 * Audio Meta Box Callback
 */
function brmedia_audio_meta_callback($post) {
    $audio_url = get_post_meta($post->ID, '_brmedia_audio_file', true);
    $tracklist = get_post_meta($post->ID, '_brmedia_tracklist', true);
    $enable_waveform = get_post_meta($post->ID, '_brmedia_enable_waveform', true);
    $enable_visualizer = get_post_meta($post->ID, '_brmedia_enable_visualizer', true);
    wp_nonce_field('brmedia_audio_meta_save', 'brmedia_audio_meta_nonce');

    echo '<div class="brmedia-meta-box">';

    // Audio File
    echo '<p><label for="brmedia_audio_file"><strong>Audio File URL:</strong></label></p>';
    echo '<input type="text" id="brmedia_audio_file" name="brmedia_audio_file" value="' . esc_attr($audio_url) . '" />';
    echo '<button type="button" class="button brmedia-upload-button" data-target="brmedia_audio_file">Upload Audio</button>';

    // Waveform / Visualizer
    echo '<p><label><input type="checkbox" name="brmedia_enable_waveform" value="1" ' . checked($enable_waveform, 1, false) . '> Enable Waveform</label></p>';
    echo '<p><label><input type="checkbox" name="brmedia_enable_visualizer" value="1" ' . checked($enable_visualizer, 1, false) . '> Enable Visualizer</label></p>';

    // Tracklist
    echo '<p><label for="brmedia_tracklist"><strong>Tracklist:</strong></label></p>';
    echo '<textarea id="brmedia_tracklist" name="brmedia_tracklist" rows="6">' . esc_textarea($tracklist) . '</textarea>';

    echo '</div>';
}

/**
 * Video Meta Box Callback
 */
function brmedia_video_meta_callback($post) {
    $video_url = get_post_meta($post->ID, '_brmedia_video_file', true);
    wp_nonce_field('brmedia_video_meta_save', 'brmedia_video_meta_nonce');

    echo '<div class="brmedia-meta-box">';

    echo '<p><label for="brmedia_video_file"><strong>Video File URL:</strong></label></p>';
    echo '<input type="text" id="brmedia_video_file" name="brmedia_video_file" value="' . esc_attr($video_url) . '" />';
    echo '<button type="button" class="button brmedia-upload-button" data-target="brmedia_video_file">Upload Video</button>';

    echo '</div>';
}

/**
 * Save Meta Box Data
 */
function brmedia_save_custom_meta($post_id) {
    // Verify nonce
    if (isset($_POST['brmedia_audio_meta_nonce']) && !wp_verify_nonce($_POST['brmedia_audio_meta_nonce'], 'brmedia_audio_meta_save')) return;
    if (isset($_POST['brmedia_video_meta_nonce']) && !wp_verify_nonce($_POST['brmedia_video_meta_nonce'], 'brmedia_video_meta_save')) return;

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Check user permission
    if (!current_user_can('edit_post', $post_id)) return;

    // Audio Fields
    if (isset($_POST['brmedia_audio_file'])) {
        update_post_meta($post_id, '_brmedia_audio_file', esc_url_raw($_POST['brmedia_audio_file']));
    }
    if (isset($_POST['brmedia_tracklist'])) {
        update_post_meta($post_id, '_brmedia_tracklist', sanitize_textarea_field($_POST['brmedia_tracklist']));
    }
    update_post_meta($post_id, '_brmedia_enable_waveform', isset($_POST['brmedia_enable_waveform']) ? 1 : 0);
    update_post_meta($post_id, '_brmedia_enable_visualizer', isset($_POST['brmedia_enable_visualizer']) ? 1 : 0);

    // Video Fields
    if (isset($_POST['brmedia_video_file'])) {
        update_post_meta($post_id, '_brmedia_video_file', esc_url_raw($_POST['brmedia_video_file']));
    }
}
add_action('save_post', 'brmedia_save_custom_meta');