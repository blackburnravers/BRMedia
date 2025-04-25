<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Hook into attachment creation to flag audio files for waveform processing
function brmedia_flag_audio_attachment($attachment_id) {
    $mime_type = get_post_mime_type($attachment_id);
    if (strpos($mime_type, 'audio/') === 0) {
        update_post_meta($attachment_id, '_waveform_generated', false);
    }
}
add_action('add_attachment', 'brmedia_flag_audio_attachment');

// Clean up waveform data when attachment is deleted
function brmedia_cleanup_waveform($attachment_id) {
    $waveform_path = get_post_meta($attachment_id, '_waveform_json', true);
    if ($waveform_path && file_exists($waveform_path)) {
        unlink($waveform_path);
    }
    delete_post_meta($attachment_id, '_waveform_json');
    delete_post_meta($attachment_id, '_waveform_generated');
}
add_action('delete_attachment', 'brmedia_cleanup_waveform');

// Start waveform generation process
function brmedia_start_waveform_generation($file_ids) {
    $process_id = uniqid('waveform_');
    $progress = array_fill_keys($file_ids, 'pending');
    set_transient("brmedia_waveform_progress_$process_id", $progress, DAY_IN_SECONDS);
    foreach ($file_ids as $file_id) {
        wp_schedule_single_event(time(), 'brmedia_generate_waveform', [$file_id, $process_id]);
    }
    return $process_id;
}

// Placeholder for waveform generation (client-side handled)
function brmedia_generate_waveform($file_id, $process_id) {
    $progress = get_transient("brmedia_waveform_progress_$process_id");
    if ($progress && isset($progress[$file_id])) {
        $progress[$file_id] = 'completed'; // Actual generation is client-side
        set_transient("brmedia_waveform_progress_$process_id", $progress, DAY_IN_SECONDS);
    }
}
add_action('brmedia_generate_waveform', 'brmedia_generate_waveform', 10, 2);