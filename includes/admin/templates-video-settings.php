<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Register Video Template Settings
 */
function brmedia_register_video_template_settings() {
    $templates = [
        'default'   => 'Default Video Player',
        'popup'     => 'Popup Video Player',
        'fullscreen'=> 'Fullscreen Video Player',
        'modern'    => 'Modern Video Player'
    ];

    foreach ($templates as $slug => $label) {
        register_setting('brmedia_video_templates_group', "brmedia_video_{$slug}_settings");

        add_settings_section(
            "brmedia_video_{$slug}_section",
            $label,
            function () use ($label) {
                echo "<p>Customize the <strong>{$label}</strong> template settings below.</p>";
            },
            'brmedia_video_templates'
        );

        // Show Play Toggle
        add_settings_field(
            "video_{$slug}_show_play",
            'Show Play Button',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $checked = !empty($options['show_play']) ? 'checked' : '';
                echo "<input type='checkbox' name='brmedia_video_{$slug}_settings[show_play]' value='1' {$checked}> Enable";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        // Play Icon
        add_settings_field(
            "video_{$slug}_play_icon",
            'Play Icon',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $icon = $options['play_icon'] ?? 'fas fa-play';
                brmedia_render_icon_picker("brmedia_video_{$slug}_settings[play_icon]", $icon);
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        // Icon Size
        add_settings_field(
            "video_{$slug}_control_size",
            'Control Icon Size',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $val = $options['control_size'] ?? '20px';
                echo "<input type='text' name='brmedia_video_{$slug}_settings[control_size]' value='" . esc_attr($val) . "' placeholder='e.g., 20px'>";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        // Icon Color
        add_settings_field(
            "video_{$slug}_control_color",
            'Control Icon Color',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $val = $options['control_color'] ?? '#0073aa';
                echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[control_color]' value='" . esc_attr($val) . "'>";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        // Hover Color
        add_settings_field(
            "video_{$slug}_hover_color",
            'Control Hover Color',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $val = $options['hover_color'] ?? '#005177';
                echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[hover_color]' value='" . esc_attr($val) . "'>";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        // Plyr CSS Variables
        add_settings_field(
            "video_{$slug}_plyr_color_main",
            'Plyr Primary Color (--plyr-color-main)',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $val = $options['plyr_color_main'] ?? '#1e87f0';
                echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[plyr_color_main]' value='" . esc_attr($val) . "'>";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );

        add_settings_field(
            "video_{$slug}_plyr_controls_bg",
            'Plyr Controls Background (--plyr-video-controls-background)',
            function () use ($slug) {
                $options = get_option("brmedia_video_{$slug}_settings");
                $val = $options['plyr_controls_bg'] ?? '#ffffff';
                echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[plyr_controls_bg]' value='" . esc_attr($val) . "'>";
            },
            'brmedia_video_templates',
            "brmedia_video_{$slug}_section"
        );
    }
}
add_action('admin_init', 'brmedia_register_video_template_settings');

// Icon Picker Modal (only once per page)
if (!defined('BRMEDIA_ICON_PICKER_LOADED')) {
    define('BRMEDIA_ICON_PICKER_LOADED', true);
    include_once plugin_dir_path(__FILE__) . 'icon-picker.php';
}