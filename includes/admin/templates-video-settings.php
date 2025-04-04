<?php
if (!defined('ABSPATH')) exit;

function brmedia_video_templates_content() {
    echo '<form method="post" action="options.php">';
    settings_fields('brmedia_video_templates_group');
    do_settings_sections('brmedia_video_templates');
    submit_button();
    echo '</form>';
}

if (!function_exists('brmedia_register_video_template_settings')) {
    function brmedia_register_video_template_settings() {
        $templates = [
            'default'    => 'Default Video Player',
            'popup'      => 'Popup Video Player',
            'fullscreen' => 'Fullscreen Video Player',
            'modern'     => 'Modern Video Player'
        ];

        $controls = [
            'skip'       => 'Skip',
            'seek'       => 'Seek',
            'speed'      => 'Speed',
            'cast'       => 'Cast',
            'popup'      => 'Popup',
            'fullscreen' => 'Fullscreen',
            'social'     => 'Social Sharing'
        ];

        foreach ($templates as $slug => $label) {
            register_setting('brmedia_video_templates_group', "brmedia_video_{$slug}_settings");

            add_settings_section(
                "brmedia_video_{$slug}_section",
                $label,
                function () use ($label) {
                    echo "<p>Customize the <strong>{$label}</strong> video template below.</p>";
                    echo "<p><strong>Note:</strong> Play/Pause and Mute/Unmute are always active.</p>";
                },
                'brmedia_video_templates'
            );

            foreach ($controls as $key => $label_txt) {
                add_settings_field(
                    "video_{$slug}_show_{$key}",
                    "Enable {$label_txt} Control",
                    function () use ($slug, $key) {
                        $options = get_option("brmedia_video_{$slug}_settings");
                        $checked = !empty($options["show_{$key}"]) ? 'checked' : '';
                        echo "<input type='checkbox' name='brmedia_video_{$slug}_settings[show_{$key}]' value='1' {$checked}> Enable";
                    },
                    'brmedia_video_templates',
                    "brmedia_video_{$slug}_section"
                );

                add_settings_field(
                    "video_{$slug}_{$key}_icon",
                    "{$label_txt} Icon",
                    function () use ($slug, $key) {
                        $options = get_option("brmedia_video_{$slug}_settings");
                        $icon = $options["{$key}_icon"] ?? 'fas fa-forward';
                        brmedia_render_icon_picker("brmedia_video_{$slug}_settings[{$key}_icon]", $icon);
                    },
                    'brmedia_video_templates',
                    "brmedia_video_{$slug}_section"
                );

                add_settings_field(
                    "video_{$slug}_{$key}_size",
                    "{$label_txt} Icon Size",
                    function () use ($slug, $key) {
                        $options = get_option("brmedia_video_{$slug}_settings");
                        $val = $options["{$key}_size"] ?? '20px';
                        echo "<input type='text' name='brmedia_video_{$slug}_settings[{$key}_size]' value='" . esc_attr($val) . "' placeholder='e.g., 20px'>";
                    },
                    'brmedia_video_templates',
                    "brmedia_video_{$slug}_section"
                );

                add_settings_field(
                    "video_{$slug}_{$key}_color",
                    "{$label_txt} Icon Color",
                    function () use ($slug, $key) {
                        $options = get_option("brmedia_video_{$slug}_settings");
                        $val = $options["{$key}_color"] ?? '#0073aa';
                        echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[{$key}_color]' value='" . esc_attr($val) . "'>";
                    },
                    'brmedia_video_templates',
                    "brmedia_video_{$slug}_section"
                );

                add_settings_field(
                    "video_{$slug}_{$key}_hover",
                    "{$label_txt} Hover Color",
                    function () use ($slug, $key) {
                        $options = get_option("brmedia_video_{$slug}_settings");
                        $val = $options["{$key}_hover"] ?? '#005177';
                        echo "<input type='text' class='brmedia-color-picker' name='brmedia_video_{$slug}_settings[{$key}_hover]' value='" . esc_attr($val) . "'>";
                    },
                    'brmedia_video_templates',
                    "brmedia_video_{$slug}_section"
                );
            }

            // Plyr color styling
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
}
add_action('admin_init', 'brmedia_register_video_template_settings');

// Load icon picker once
if (!defined('BRMEDIA_ICON_PICKER_LOADED')) {
    define('BRMEDIA_ICON_PICKER_LOADED', true);
    include_once plugin_dir_path(__FILE__) . 'icon-picker.php';
}