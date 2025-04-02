<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Add Downloads Settings Page
 */
function brmedia_add_downloads_settings_page() {
    add_submenu_page(
        'brmedia-dashboard',
        'Downloads & Buttons',
        'Downloads & Buttons',
        'manage_options',
        'brmedia-downloads',
        'brmedia_render_downloads_settings_page'
    );
}
add_action('admin_menu', 'brmedia_add_downloads_settings_page');

/**
 * Render Downloads Settings Page
 */
function brmedia_render_downloads_settings_page() {
    ?>
    <div class="wrap brmedia-admin-container">
        <h1>BRMedia Download Button Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('brmedia_downloads_settings_group');
            do_settings_sections('brmedia_downloads');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register Download Button Settings
 */
function brmedia_register_downloads_settings() {
    register_setting('brmedia_downloads_settings_group', 'brmedia_downloads_settings');

    add_settings_section(
        'brmedia_downloads_section',
        'Download Button Style',
        function () {
            echo '<p>Customize the appearance and functionality of your download buttons.</p>';
        },
        'brmedia_downloads'
    );

    // Icon Selector
    add_settings_field(
        'download_icon',
        'Button Icon',
        'brmedia_icon_selector_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_icon',
            'option_group' => 'brmedia_downloads_settings',
            'description' => 'Choose a Font Awesome icon for the download button.'
        ]
    );

    // Icon Size
    add_settings_field(
        'download_icon_size',
        'Icon Size (px)',
        'brmedia_text_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_icon_size',
            'option_group' => 'brmedia_downloads_settings',
            'type' => 'number'
        ]
    );

    // Icon Color
    add_settings_field(
        'download_icon_color',
        'Icon Color',
        'brmedia_color_picker_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_icon_color',
            'option_group' => 'brmedia_downloads_settings'
        ]
    );

    // Hover Color
    add_settings_field(
        'download_hover_color',
        'Hover Color',
        'brmedia_color_picker_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_hover_color',
            'option_group' => 'brmedia_downloads_settings'
        ]
    );

    // Background Color
    add_settings_field(
        'download_background_color',
        'Background Color',
        'brmedia_color_picker_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_background_color',
            'option_group' => 'brmedia_downloads_settings'
        ]
    );

    // Button Label
    add_settings_field(
        'download_button_text',
        'Button Label',
        'brmedia_text_field',
        'brmedia_downloads',
        'brmedia_downloads_section',
        [
            'label_for' => 'download_button_text',
            'option_group' => 'brmedia_downloads_settings'
        ]
    );
}
add_action('admin_init', 'brmedia_register_downloads_settings');

/**
 * Icon Selector Preview Field
 */
function brmedia_icon_selector_field($args) {
    $options = get_option($args['option_group']);
    $value = isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '';

    // Sample icon list (extendable)
    $icons = [
        'fas fa-download',
        'fas fa-arrow-circle-down',
        'fas fa-file-download',
        'fas fa-cloud-download-alt',
        'fas fa-music',
        'fas fa-play-circle',
        'fas fa-file-audio',
        'fas fa-file-alt',
        'fas fa-compact-disc'
    ];

    echo '<div class="brmedia-icon-preview">';
    foreach ($icons as $icon) {
        $selected = ($value === $icon) ? 'selected' : '';
        echo '<label style="margin-right:10px;display:inline-block;">';
        echo '<input type="radio" name="' . esc_attr($args['option_group']) . '[' . esc_attr($args['label_for']) . ']" value="' . esc_attr($icon) . '" ' . checked($value, $icon, false) . '>';
        echo '<i class="' . esc_attr($icon) . '" style="font-size:24px;margin-left:5px;"></i>';
        echo '</label>';
    }
    echo '</div>';
    if (!empty($args['description'])) {
        echo '<p class="description">' . esc_html($args['description']) . '</p>';
    }
}

/**
 * Color Picker Field
 */
function brmedia_color_picker_field($args) {
    $options = get_option($args['option_group']);
    $value = isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '';
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['option_group']) . '[' . esc_attr($args['label_for']) . ']" class="brmedia-color-picker" value="' . $value . '" />';
}

// Generic Text Field
function brmedia_text_field($args) {
    $options = get_option($args['option_group']);
    $type = isset($args['type']) ? $args['type'] : 'text';
    $value = isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : '';
    echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['option_group']) . '[' . esc_attr($args['label_for']) . ']" value="' . $value . '" />';
}

// Icon Picker Modal (only once per page)
if (!defined('BRMEDIA_ICON_PICKER_LOADED')) {
    define('BRMEDIA_ICON_PICKER_LOADED', true);
    include_once plugin_dir_path(__FILE__) . 'icon-picker.php';
}