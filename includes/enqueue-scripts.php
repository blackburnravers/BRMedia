<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue scripts and styles for frontend
function brmedia_enqueue_scripts() {
    // Enqueue WaveSurfer.js for waveforms and audio playback
    wp_enqueue_script('wavesurfer-js', 'https://unpkg.com/wavesurfer.js', array(), null, true);
    
    // Enqueue Font Awesome 6.7.2 for icons
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
    
    // Enqueue custom plugin CSS
    wp_enqueue_style('brmedia-css', BRMEDIA_PLUGIN_URL . 'assets/css/brmedia.css', array(), '1.1');
    
    // Enqueue custom plugin JS
    wp_enqueue_script('brmedia-js', BRMEDIA_PLUGIN_URL . 'assets/js/brmedia.js', array('jquery', 'wavesurfer-js'), '1.1', true);
    
    // Retrieve the default volume from plugin settings and localize it for the frontend
    $general_options = get_option('brmedia_general_options');
    $default_volume = isset($general_options['default_volume']) ? $general_options['default_volume'] / 100 : 0.5;
    
    // Localize settings for frontend scripts
    wp_localize_script('brmedia-js', 'brmediaSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('brmedia_nonce'),
        'defaultVolume' => $default_volume,
        'defaultTemplate' => $general_options['default_template'] ?? 'classic-player'
    ));
}
add_action('wp_enqueue_scripts', 'brmedia_enqueue_scripts');

// Enqueue styles and scripts for admin
function brmedia_enqueue_admin_assets($hook) {
    // Check if we're on a BRMedia admin page
    if (strpos($hook, 'brmedia') !== false || in_array($hook, [
        'toplevel_page_brmedia',
        'brmedia_page_brmedia-import-settings',
    ])) {
        // Enqueue Bootstrap CSS and JS
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
        
        // Enqueue Font Awesome for icons in admin
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
        
        // Enqueue Select2 for dropdowns
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
        
        // Enqueue custom admin CSS
        wp_enqueue_style('brmedia-admin-css', BRMEDIA_PLUGIN_URL . 'assets/css/admin.css', array('bootstrap'), '1.1');
        
        // Enqueue custom admin JS
        wp_enqueue_script('brmedia-admin-js', BRMEDIA_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'bootstrap'), '1.0', true);
        
        // Enqueue WaveSurfer.js for admin waveform features
        wp_enqueue_script('wavesurfer-js', 'https://unpkg.com/wavesurfer.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'brmedia_enqueue_admin_assets');