<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Class BRMedia_Utils
 * Utility methods used throughout BRMedia plugin.
 */
class BRMedia_Utils {

    /**
     * Get sanitized option with fallback.
     */
    public static function get_option($key, $default = '') {
        $options = get_option($key, []);
        return is_array($options) ? $options : $default;
    }

    /**
     * Get a single setting field from grouped options.
     */
    public static function get_setting($group, $field, $fallback = null) {
        $settings = get_option($group, []);
        return isset($settings[$field]) ? sanitize_text_field($settings[$field]) : $fallback;
    }

    /**
     * Get all available Font Awesome icons (subset for selection).
     */
    public static function get_fontawesome_icons() {
        return [
            'fas fa-play', 'fas fa-pause', 'fas fa-stop', 'fas fa-step-forward', 'fas fa-step-backward',
            'fas fa-volume-up', 'fas fa-volume-mute', 'fas fa-random', 'fas fa-redo', 'fas fa-expand',
            'fas fa-compress', 'fas fa-download', 'fas fa-music', 'fas fa-headphones', 'fas fa-eject',
            'fas fa-cogs', 'fas fa-equals', 'fas fa-microphone', 'fas fa-broadcast-tower',
            'fas fa-wave-square', 'fas fa-sliders-h', 'fas fa-chart-bar', 'fas fa-cloud-download-alt'
        ];
    }

    /**
     * Convert seconds to HH:MM:SS format.
     */
    public static function format_duration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Generate HTML <option> elements for a select dropdown.
     */
    public static function generate_options($options = [], $selected = '') {
        $html = '';
        foreach ($options as $key => $label) {
            $isSelected = ($selected == $key) ? 'selected' : '';
            $html .= '<option value="' . esc_attr($key) . '" ' . $isSelected . '>' . esc_html($label) . '</option>';
        }
        return $html;
    }

    /**
     * Render FontAwesome icon preview box
     */
    public static function render_icon_picker($name, $selected_icon = '') {
        $icons = self::get_fontawesome_icons();
        echo '<div class="brmedia-icon-picker">';
        foreach ($icons as $icon) {
            $selected = ($icon === $selected_icon) ? 'brmedia-icon-selected' : '';
            echo '<label class="brmedia-icon-option ' . esc_attr($selected) . '">';
            echo '<input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($icon) . '" ' . checked($icon, $selected_icon, false) . '>';
            echo '<i class="' . esc_attr($icon) . '"></i>';
            echo '</label>';
        }
        echo '</div>';
    }

    /**
     * Render media upload field with WP media uploader integration
     */
    public static function render_upload_field($id, $value = '', $button_text = 'Upload') {
        echo '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_url($value) . '" class="regular-text">';
        echo ' <button type="button" class="button brmedia-upload-button" data-target="' . esc_attr($id) . '">' . esc_html($button_text) . '</button>';
    }

    /**
     * Get post meta helper with default fallback
     */
    public static function get_meta($post_id, $key, $default = '') {
        $value = get_post_meta($post_id, $key, true);
        return !empty($value) ? $value : $default;
    }

    /**
     * Render toggle checkbox
     */
    public static function render_toggle($name, $checked = false) {
        echo '<label class="brmedia-switch">';
        echo '<input type="checkbox" name="' . esc_attr($name) . '" ' . checked($checked, true, false) . '>';
        echo '<span class="brmedia-slider round"></span>';
        echo '</label>';
    }
}