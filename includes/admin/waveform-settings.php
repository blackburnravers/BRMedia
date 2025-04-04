<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Add Waveform Settings Section
 */
function brmedia_add_waveform_settings_section() {
    add_settings_section(
        'brmedia_waveform_settings_section',
        'Waveform Settings',
        'brmedia_waveform_settings_section_callback',
        'brmedia-settings'
    );

    $fields = [
        'enable_waveform'         => 'Enable Waveform Visualizer',
        'waveform_color'          => 'Waveform Color',
        'waveform_progress_color' => 'Progress Color',
        'waveform_height'         => 'Waveform Height (px)',
        'waveform_bar_width'      => 'Bar Width (px)',
        'waveform_bar_gap'        => 'Bar Gap (px)',
        'waveform_backend'        => 'Use WebAudio Backend'
    ];

    foreach ($fields as $key => $label) {
        add_settings_field(
            "brmedia_{$key}",
            $label,
            "brmedia_waveform_field_{$key}",
            'brmedia-settings',
            'brmedia_waveform_settings_section'
        );
    }

    register_setting('brmedia_settings_group', 'brmedia_waveform_settings');
}
add_action('admin_init', 'brmedia_add_waveform_settings_section');

/**
 * Section Callback
 */
function brmedia_waveform_settings_section_callback() {
    echo '<p>Configure the visual appearance and behavior of waveform visualizers for audio players.</p>';
}

/**
 * Enable Checkbox Field
 */
function brmedia_waveform_field_enable_waveform() {
    $options = get_option('brmedia_waveform_settings');
    $checked = !empty($options['enable_waveform']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_waveform_settings[enable_waveform]" value="1" ' . $checked . '> Enable waveform visualization';
}

/**
 * Waveform Color Picker
 */
function brmedia_waveform_field_waveform_color() {
    $options = get_option('brmedia_waveform_settings');
    $value = isset($options['waveform_color']) ? $options['waveform_color'] : '#cccccc';
    echo '<input type="text" class="brmedia-color-picker" name="brmedia_waveform_settings[waveform_color]" value="' . esc_attr($value) . '">';
}

/**
 * Progress Color Picker
 */
function brmedia_waveform_field_waveform_progress_color() {
    $options = get_option('brmedia_waveform_settings');
    $value = isset($options['waveform_progress_color']) ? $options['waveform_progress_color'] : '#0073aa';
    echo '<input type="text" class="brmedia-color-picker" name="brmedia_waveform_settings[waveform_progress_color]" value="' . esc_attr($value) . '">';
}

/**
 * Waveform Height Input
 */
function brmedia_waveform_field_waveform_height() {
    $options = get_option('brmedia_waveform_settings');
    $value = isset($options['waveform_height']) ? intval($options['waveform_height']) : 100;
    echo '<input type="number" name="brmedia_waveform_settings[waveform_height]" value="' . esc_attr($value) . '" min="20" max="500">';
}

/**
 * Bar Width Input
 */
function brmedia_waveform_field_waveform_bar_width() {
    $options = get_option('brmedia_waveform_settings');
    $value = isset($options['waveform_bar_width']) ? intval($options['waveform_bar_width']) : 2;
    echo '<input type="number" name="brmedia_waveform_settings[waveform_bar_width]" value="' . esc_attr($value) . '" min="1" max="10">';
}

/**
 * Bar Gap Input
 */
function brmedia_waveform_field_waveform_bar_gap() {
    $options = get_option('brmedia_waveform_settings');
    $value = isset($options['waveform_bar_gap']) ? intval($options['waveform_bar_gap']) : 1;
    echo '<input type="number" name="brmedia_waveform_settings[waveform_bar_gap]" value="' . esc_attr($value) . '" min="0" max="10">';
}

/**
 * Backend Toggle
 */
function brmedia_waveform_field_waveform_backend() {
    $options = get_option('brmedia_waveform_settings');
    $checked = !empty($options['waveform_backend']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_waveform_settings[waveform_backend]" value="1" ' . $checked . '> Use WebAudio backend (higher accuracy)';
}