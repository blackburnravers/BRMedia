<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue scripts and styles for frontend
function brmedia_enqueue_scripts() {
    // Enqueue WaveSurfer.js for the audio player
    wp_enqueue_script('wavesurfer-js', 'https://unpkg.com/wavesurfer.js', array(), null, true);
    
    // Enqueue Font Awesome 6.7.2 for icons
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
    
    // Enqueue custom plugin CSS
    wp_enqueue_style('brmedia-css', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia.css', array(), '1.1');
    
    // Retrieve the default volume from plugin settings and localize it for the frontend
    $general_options = get_option('brmedia_general_options');
    $default_volume = isset($general_options['default_volume']) ? $general_options['default_volume'] / 100 : 0.5;
    
    // Localize settings for frontend scripts
    wp_localize_script('wavesurfer-js', 'brmediaSettings', array(
        'defaultVolume' => $general_options['default_volume'] ?? 0.5,
        'defaultTemplate' => $general_options['default_template'] ?? 'classic-player'
    ));
}
add_action('wp_enqueue_scripts', 'brmedia_enqueue_scripts');