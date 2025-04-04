<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * BRMedia Advanced Settings Page
 */
function brmedia_settings_page() {
    ?>
    <div class="wrap brmedia-admin-container">
        <h1>Advanced Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('brmedia_settings_group');
            do_settings_sections('brmedia_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register Settings
 */
function brmedia_register_settings() {
    register_setting('brmedia_settings_group', 'brmedia_settings');

    add_settings_section(
        'brmedia_section',
        'Settings',
        function () {
            echo '<p>Basic plugin-wide settings.</p>';
        },
        'brmedia_settings'
    );

    add_settings_field(
        'enable_footer_player',
        'Enable Footer Player',
        'brmedia_checkbox_callback',
        'brmedia_settings',
        'brmedia_section',
        [
            'option_name' => 'brmedia_settings',
            'field_key' => 'enable_footer_player',
        ]
    );

    add_settings_field(
        'enable_popup_player',
        'Enable Popup Player',
        'brmedia_checkbox_callback',
        'brmedia_settings',
        'brmedia_section',
        [
            'option_name' => 'brmedia_settings',
            'field_key' => 'enable_popup_player',
        ]
    );

    add_settings_section(
        'brmedia_advanced_section',
        'Advanced Settings',
        function () {
            echo '<p>Advanced behavior settings for BRMedia player.</p>';
        },
        'brmedia_settings'
    );

    add_settings_field(
        'enable_casting',
        'Enable Casting Support',
        'brmedia_checkbox_callback',
        'brmedia_settings',
        'brmedia_advanced_section',
        [
            'option_name' => 'brmedia_settings',
            'field_key' => 'enable_casting',
        ]
    );
}
add_action('admin_init', 'brmedia_register_settings');

/**
 * Checkbox Callback
 */
function brmedia_checkbox_callback($args) {
    $options = get_option($args['option_name']);
    $checked = isset($options[$args['field_key']]) ? checked(1, $options[$args['field_key']], false) : '';
    echo '<input type="checkbox" name="' . esc_attr($args['option_name']) . '[' . esc_attr($args['field_key']) . ']" value="1" ' . $checked . '>';
}