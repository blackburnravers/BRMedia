<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

/**
 * Register Audio Template Settings
 */
if (!function_exists('brmedia_register_audio_template_settings')) {
    function brmedia_register_audio_template_settings() {
        $templates = [
            'default'    => 'Default Audio Player',
            'popup'      => 'Popup Audio Player',
            'fullscreen' => 'Fullscreen Audio Player',
            'compact'    => 'Compact Audio Player',
            'modern'     => 'Modern Audio Player',
            'minibar'    => 'Mini Bar Audio Player',
            'playlist'   => 'Playlist Audio Player'
        ];

        foreach ($templates as $slug => $label) {
            register_setting('brmedia_audio_templates_group', "brmedia_audio_{$slug}_settings");

            add_settings_section(
                "brmedia_audio_{$slug}_section",
                $label,
                function () use ($label) {
                    echo "<p>Customize the <strong>{$label}</strong> template settings below.</p>";
                },
                'brmedia_audio_templates'
            );

            // Show Play Button
            add_settings_field(
                "audio_{$slug}_show_play",
                'Show Play Button',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $checked = !empty($options['show_play']) ? 'checked' : '';
                    echo "<input type='checkbox' name='brmedia_audio_{$slug}_settings[show_play]' value='1' {$checked}> Enable";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Play Icon
            add_settings_field(
                "audio_{$slug}_play_icon",
                'Play Icon',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $icon = $options['play_icon'] ?? 'fas fa-play';
                    brmedia_render_icon_picker("brmedia_audio_{$slug}_settings[play_icon]", $icon);
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Control Icon Size
            add_settings_field(
                "audio_{$slug}_control_size",
                'Control Icon Size',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $val = $options['control_size'] ?? '18px';
                    echo "<input type='text' name='brmedia_audio_{$slug}_settings[control_size]' value='" . esc_attr($val) . "' placeholder='e.g., 18px'>";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Control Icon Color
            add_settings_field(
                "audio_{$slug}_control_color",
                'Control Icon Color',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $val = $options['control_color'] ?? '#0073aa';
                    echo "<input type='text' class='brmedia-color-picker' name='brmedia_audio_{$slug}_settings[control_color]' value='" . esc_attr($val) . "'>";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Hover Color
            add_settings_field(
                "audio_{$slug}_hover_color",
                'Control Hover Color',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $val = $options['hover_color'] ?? '#005177';
                    echo "<input type='text' class='brmedia-color-picker' name='brmedia_audio_{$slug}_settings[hover_color]' value='" . esc_attr($val) . "'>";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Plyr Primary Color
            add_settings_field(
                "audio_{$slug}_plyr_color_main",
                'Plyr Primary Color (--plyr-color-main)',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $val = $options['plyr_color_main'] ?? '#1e87f0';
                    echo "<input type='text' class='brmedia-color-picker' name='brmedia_audio_{$slug}_settings[plyr_color_main]' value='" . esc_attr($val) . "'>";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );

            // Plyr Controls Background
            add_settings_field(
                "audio_{$slug}_plyr_controls_bg",
                'Plyr Controls Background (--plyr-audio-controls-background)',
                function () use ($slug) {
                    $options = get_option("brmedia_audio_{$slug}_settings");
                    $val = $options['plyr_controls_bg'] ?? '#ffffff';
                    echo "<input type='text' class='brmedia-color-picker' name='brmedia_audio_{$slug}_settings[plyr_controls_bg]' value='" . esc_attr($val) . "'>";
                },
                'brmedia_audio_templates',
                "brmedia_audio_{$slug}_section"
            );
        }
    }
}
add_action('admin_init', 'brmedia_register_audio_template_settings');

// Icon Picker Modal (only once per page)
if (!defined('BRMEDIA_ICON_PICKER_LOADED')) {
    define('BRMEDIA_ICON_PICKER_LOADED', true);
    include_once plugin_dir_path(__FILE__) . 'icon-picker.php';
}