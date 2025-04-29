<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include getID3 library
require_once BRMEDIA_PLUGIN_DIR . 'lib/getid3/getid3.php';

// Function to extract audio metadata using getID3
function brmedia_get_audio_metadata($audio_file_path) {
    $metadata = array(
        'duration' => '',
        'bitrate' => '',
        'sample_rate' => '',
        'error' => ''
    );

    // Check if the file exists and is accessible
    if (!file_exists($audio_file_path)) {
        $metadata['error'] = 'Audio file not found.';
        return $metadata;
    }

    // Initialize getID3
    $getID3 = new getID3;

    // Analyze the audio file
    try {
        $file_info = $getID3->analyze($audio_file_path);

        // Check for errors in analysis
        if (isset($file_info['error'])) {
            $metadata['error'] = implode(', ', $file_info['error']);
            return $metadata;
        }

        // Extract duration
        if (isset($file_info['playtime_string'])) {
            $metadata['duration'] = $file_info['playtime_string']; // e.g., "3:45"
        } elseif (isset($file_info['playtime_seconds'])) {
            $seconds = $file_info['playtime_seconds'];
            $metadata['duration'] = gmdate('i:s', $seconds); // Format as mm:ss
        }

        // Extract bitrate
        if (isset($file_info['audio']['bitrate'])) {
            $metadata['bitrate'] = round($file_info['audio']['bitrate'] / 1000) . ' kbps'; // Convert to kbps
        }

        // Extract sample rate
        if (isset($file_info['audio']['sample_rate'])) {
            $metadata['sample_rate'] = $file_info['audio']['sample_rate'] . ' Hz';
        }

    } catch (Exception $e) {
        $metadata['error'] = 'Failed to analyze audio file: ' . $e->getMessage();
    }

    return $metadata;
}