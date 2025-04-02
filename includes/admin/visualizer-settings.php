<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Add Visualizer Settings Section
 */
function brmedia_add_visualizer_settings_section() {
    add_settings_section(
        'brmedia_visualizer_settings_section',
        'Visualizer Settings',
        'brmedia_visualizer_settings_section_callback',
        'brmedia-settings'
    );

    $fields = [
        'enable_visualizer'        => 'Enable Visualizer',
        'visualizer_type'          => 'Visualizer Type',
        'bar_color'                => 'Bar Color',
        'bar_width'                => 'Bar Width (px)',
        'bar_gap'                  => 'Bar Gap (px)',
        'bar_count'                => 'Bar Count',
        'mirror_effect'            => 'Mirror Bars',
        'show_peak_indicator'      => 'Show Peak Indicator',
        'peak_color'               => 'Peak Color',
        'use_smooth_animation'     => 'Enable Smooth Animation'
    ];

    foreach ($fields as $key => $label) {
        add_settings_field(
            "brmedia_{$key}",
            $label,
            "brmedia_visualizer_field_{$key}",
            'brmedia-settings',
            'brmedia_visualizer_settings_section'
        );
    }

    register_setting('brmedia_settings_group', 'brmedia_visualizer_settings');
}
add_action('admin_init', 'brmedia_add_visualizer_settings_section');

/**
 * Section Callback
 */
function brmedia_visualizer_settings_section_callback() {
    echo '<p>Customize how your real-time audio visualizer looks and behaves across your BRMedia players.</p>';
}

/**
 * Enable Visualizer Checkbox
 */
function brmedia_visualizer_field_enable_visualizer() {
    $options = get_option('brmedia_visualizer_settings');
    $checked = !empty($options['enable_visualizer']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_visualizer_settings[enable_visualizer]" value="1" ' . $checked . '> Enable animated visualizer';
}

/**
 * Visualizer Type Dropdown
 */
function brmedia_visualizer_field_visualizer_type() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['visualizer_type']) ? $options['visualizer_type'] : 'bars';

    $types = [
        'bars' => 'Bars',
        'circle' => 'Circular',
        'waveform' => 'Waveform-Style',
        'dots' => 'Dots'
    ];

    echo '<select name="brmedia_visualizer_settings[visualizer_type]">';
    foreach ($types as $key => $label) {
        $selected = ($value === $key) ? 'selected' : '';
        echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
}

/**
 * Bar Color Picker
 */
function brmedia_visualizer_field_bar_color() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['bar_color']) ? $options['bar_color'] : '#00c4ff';
    echo '<input type="text" class="brmedia-color-picker" name="brmedia_visualizer_settings[bar_color]" value="' . esc_attr($value) . '">';
}

/**
 * Peak Color Picker
 */
function brmedia_visualizer_field_peak_color() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['peak_color']) ? $options['peak_color'] : '#ff5252';
    echo '<input type="text" class="brmedia-color-picker" name="brmedia_visualizer_settings[peak_color]" value="' . esc_attr($value) . '">';
}

/**
 * Bar Width Input
 */
function brmedia_visualizer_field_bar_width() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['bar_width']) ? intval($options['bar_width']) : 3;
    echo '<input type="number" name="brmedia_visualizer_settings[bar_width]" value="' . esc_attr($value) . '" min="1" max="10">';
}

/**
 * Bar Gap Input
 */
function brmedia_visualizer_field_bar_gap() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['bar_gap']) ? intval($options['bar_gap']) : 1;
    echo '<input type="number" name="brmedia_visualizer_settings[bar_gap]" value="' . esc_attr($value) . '" min="0" max="10">';
}

/**
 * Bar Count Input
 */
function brmedia_visualizer_field_bar_count() {
    $options = get_option('brmedia_visualizer_settings');
    $value = isset($options['bar_count']) ? intval($options['bar_count']) : 64;
    echo '<input type="number" name="brmedia_visualizer_settings[bar_count]" value="' . esc_attr($value) . '" min="16" max="256">';
}

/**
 * Mirror Effect Checkbox
 */
function brmedia_visualizer_field_mirror_effect() {
    $options = get_option('brmedia_visualizer_settings');
    $checked = !empty($options['mirror_effect']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_visualizer_settings[mirror_effect]" value="1" ' . $checked . '> Mirror bars top and bottom';
}

/**
 * Show Peak Indicator
 */
function brmedia_visualizer_field_show_peak_indicator() {
    $options = get_option('brmedia_visualizer_settings');
    $checked = !empty($options['show_peak_indicator']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_visualizer_settings[show_peak_indicator]" value="1" ' . $checked . '> Show visual peak indicators';
}

/**
 * Smooth Animation Toggle
 */
function brmedia_visualizer_field_use_smooth_animation() {
    $options = get_option('brmedia_visualizer_settings');
    $checked = !empty($options['use_smooth_animation']) ? 'checked' : '';
    echo '<input type="checkbox" name="brmedia_visualizer_settings[use_smooth_animation]" value="1" ' . $checked . '> Enable smoothing between frames';
}