<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add meta boxes
function brmedia_add_meta_boxes() {
    add_meta_box(
        'brmedia_metadata',
        'Track Metadata',
        'brmedia_metadata_callback',
        'brmedia_track',
        'side',
        'default'
    );
    add_meta_box(
        'brmedia_audio_upload',
        'Upload Audio File',
        'brmedia_audio_upload_callback',
        'brmedia_track',
        'side',
        'default'
    );
    add_meta_box(
        'brmedia_tracklist',
        'Tracklist',
        'brmedia_tracklist_callback',
        'brmedia_track',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'brmedia_add_meta_boxes');

// Metadata callback
function brmedia_metadata_callback($post) {
    wp_nonce_field('brmedia_save_metadata', 'brmedia_metadata_nonce');
    $artist = get_post_meta($post->ID, 'artist', true);
    $album = get_post_meta($post->ID, 'album', true);
    $bpm = get_post_meta($post->ID, 'bpm', true);
    $key = get_post_meta($post->ID, 'key', true);
    ?>
    <p><label for="artist">Artist:</label></p>
    <input type="text" id="artist" name="artist" value="<?php echo esc_attr($artist); ?>" />
    <p><label for="album">Album:</label></p>
    <input type="text" id="album" name="album" value="<?php echo esc_attr($album); ?>" />
    <p><label for="bpm">BPM:</label></p>
    <input type="text" id="bpm" name="bpm" value="<?php echo esc_attr($bpm); ?>" />
    <p><label for="key">Key:</label></p>
    <input type="text" id="key" name="key" value="<?php echo esc_attr($key); ?>" />
    <?php
}

// Audio upload callback
function brmedia_audio_upload_callback($post) {
    wp_nonce_field('brmedia_save_audio', 'brmedia_audio_nonce');
    $audio_url = get_post_meta($post->ID, 'audio_file', true);
    ?>
    <p><label for="audio_file">Audio File URL:</label></p>
    <input type="text" id="audio_file" name="audio_file" value="<?php echo esc_url($audio_url); ?>" />
    <input type="button" class="button" value="Upload Audio" id="upload_audio_button" />
    <script>
        jQuery(document).ready(function($) {
            var frame;
            var $input = $('#audio_file');
            $('#upload_audio_button').click(function(e) {
                e.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Select or Upload Audio',
                    button: { text: 'Use this audio' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                });
                frame.open();
            });
        });
    </script>
    <?php
}

// Tracklist callback
function brmedia_tracklist_callback($post) {
    wp_nonce_field('brmedia_save_tracklist', 'brmedia_tracklist_nonce');
    $tracklist = get_post_meta($post->ID, 'tracklist', true);
    ?>
    <p><label for="tracklist">Tracklist (one per line or upload .txt file):</label></p>
    <textarea id="tracklist" name="tracklist" rows="10" style="width:100%;"><?php echo esc_textarea($tracklist); ?></textarea>
    <input type="button" class="button" value="Upload Tracklist" id="upload_tracklist_button" />
    <script>
        jQuery(document).ready(function($) {
            var frame;
            var $textarea = $('#tracklist');
            $('#upload_tracklist_button').click(function(e) {
                e.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Select or Upload Tracklist (.txt)',
                    button: { text: 'Use this file' },
                    multiple: false,
                    library: { type: 'text/plain' }
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $.get(attachment.url, function(data) {
                        $textarea.val(data);
                    });
                });
                frame.open();
            });
        });
    </script>
    <?php
}

// Save meta data
function brmedia_save_meta_data($post_id) {
    if (!isset($_POST['brmedia_metadata_nonce']) || !wp_verify_nonce($_POST['brmedia_metadata_nonce'], 'brmedia_save_metadata')) {
        return;
    }
    if (isset($_POST['artist'])) {
        update_post_meta($post_id, 'artist', sanitize_text_field($_POST['artist']));
    }
    if (isset($_POST['album'])) {
        update_post_meta($post_id, 'album', sanitize_text_field($_POST['album']));
    }
    if (isset($_POST['bpm'])) {
        update_post_meta($post_id, 'bpm', sanitize_text_field($_POST['bpm']));
    }
    if (isset($_POST['key'])) {
        update_post_meta($post_id, 'key', sanitize_text_field($_POST['key']));
    }
    if (isset($_POST['audio_file'])) {
        update_post_meta($post_id, 'audio_file', esc_url_raw($_POST['audio_file']));
    }
    if (isset($_POST['tracklist'])) {
        update_post_meta($post_id, 'tracklist', sanitize_textarea_field($_POST['tracklist']));
    }
}
add_action('save_post', 'brmedia_save_meta_data');