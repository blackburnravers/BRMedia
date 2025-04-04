<?php
if (!defined('ABSPATH')) exit;

/**
 * Renders the icon picker field with modal trigger and input.
 */
function brmedia_render_icon_picker($field_name, $selected_icon = 'fas fa-play') {
    echo "<div class='brmedia-icon-picker-wrapper'>";
    echo "<input type='text' class='brmedia-icon-input' name='" . esc_attr($field_name) . "' value='" . esc_attr($selected_icon) . "' placeholder='e.g., fas fa-play'>";
    echo "<button type='button' class='button brmedia-open-icon-picker'>Select Icon</button>";
    echo "<div class='brmedia-icon-modal' style='display:none;'>";
    echo "<input type='text' class='brmedia-icon-search' placeholder='Search icons...'>";
    echo "<div class='brmedia-icon-grid'></div>";
    echo "</div></div>";
}

/**
 * Enqueue styles and scripts for admin icon picker.
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
    wp_enqueue_style('brmedia-icon-picker', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/css/icon-picker.css');
    wp_enqueue_script('brmedia-icon-picker', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/js/icon-picker.js', ['jquery'], null, true);
});