<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

function brmedia_templates_page() {
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

    echo '<div class="wrap brmedia-admin">';
    echo '<h1>BRMedia Templates</h1>';

    echo '<nav class="nav-tab-wrapper">';
    echo '<a href="?page=brmedia-templates&tab=general" class="nav-tab ' . ($active_tab === 'general' ? 'nav-tab-active' : '') . '">General Settings</a>';
    echo '<a href="?page=brmedia-templates&tab=audio" class="nav-tab ' . ($active_tab === 'audio' ? 'nav-tab-active' : '') . '">Audio Templates</a>';
    echo '<a href="?page=brmedia-templates&tab=video" class="nav-tab ' . ($active_tab === 'video' ? 'nav-tab-active' : '') . '">Video Templates</a>';
    echo '</nav>';

    echo '<div class="brmedia-tab-content">';

    if ($active_tab === 'general') {
        echo '<form method="post" action="options.php">';
        settings_fields('brmedia_general_templates_group');
        do_settings_sections('brmedia_general_templates');
        submit_button();
        echo '</form>';
    }

    if ($active_tab === 'audio') {
        require_once plugin_dir_path(__FILE__) . 'templates-audio-settings.php';
        if (function_exists('brmedia_audio_templates_content')) {
            brmedia_audio_templates_content();
        }
    }

    if ($active_tab === 'video') {
        require_once plugin_dir_path(__FILE__) . 'templates-video-settings.php';
        if (function_exists('brmedia_video_templates_content')) {
            brmedia_video_templates_content();
        }
    }

    echo '</div>';
    echo '</div>';
}

function brmedia_register_general_template_settings() {
    register_setting('brmedia_general_templates_group', 'brmedia_general_settings');

    add_settings_section(
        'brmedia_general_section',
        'Global Player Settings',
        function () {
            echo '<p>Control default behavior and player preferences for audio and video templates.</p>';
        },
        'brmedia_general_templates'
    );

    // Get saved options
    $options = get_option('brmedia_general_settings', []);

    // Default Audio Player (not popup/fullscreen)
    add_settings_field(
        'default_audio_template',
        'Default Audio Template',
        function () use ($options) {
            $val = $options['default_audio_template'] ?? 'default';
            echo '<select name="brmedia_general_settings[default_audio_template]">';
            foreach (['default', 'modern', 'compact', 'minibar', 'playlist'] as $tpl) {
                echo '<option value="' . esc_attr($tpl) . '" ' . selected($val, $tpl, false) . '>' . ucfirst($tpl) . '</option>';
            }
            echo '</select>';
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );

    // Default Video Player (not popup/fullscreen)
    add_settings_field(
        'default_video_template',
        'Default Video Template',
        function () use ($options) {
            $val = $options['default_video_template'] ?? 'default';
            echo '<select name="brmedia_general_settings[default_video_template]">';
            foreach (['default', 'modern'] as $tpl) {
                echo '<option value="' . esc_attr($tpl) . '" ' . selected($val, $tpl, false) . '>' . ucfirst($tpl) . '</option>';
            }
            echo '</select>';
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );

    // Social Sharing Links
    add_settings_field(
        'social_links',
        'Enable Social Sharing Buttons',
        function () use ($options) {
            $socials = ['facebook' => 'Facebook', 'x' => 'X (Twitter)', 'instagram' => 'Instagram', 'youtube' => 'YouTube'];
            foreach ($socials as $key => $label) {
                $checked = !empty($options['social_links'][$key]) ? 'checked' : '';
                echo "<label style='display:inline-block; margin-right:15px;'><input type='checkbox' name='brmedia_general_settings[social_links][{$key}]' value='1' {$checked}> {$label}</label>";
            }
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );

    // Autoplay toggle
    add_settings_field(
        'autoplay',
        'Autoplay Media',
        function () use ($options) {
            $checked = !empty($options['autoplay']) ? 'checked' : '';
            echo "<input type='checkbox' name='brmedia_general_settings[autoplay]' value='1' {$checked}> Enable";
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );

    // Footer Player toggle
    add_settings_field(
        'footer_player',
        'Footer Player',
        function () use ($options) {
            $checked = !empty($options['footer_player']) ? 'checked' : '';
            echo "<input type='checkbox' name='brmedia_general_settings[footer_player]' value='1' {$checked}> Enable";
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );

    // Volume Level
    add_settings_field(
        'volume_level',
        'Default Volume Level',
        function () use ($options) {
            $val = $options['volume_level'] ?? '0.8';
            echo "<input type='number' step='0.1' min='0' max='1' name='brmedia_general_settings[volume_level]' value='" . esc_attr($val) . "'> (0 to 1)";
        },
        'brmedia_general_templates',
        'brmedia_general_section'
    );
}
add_action('admin_init', 'brmedia_register_general_template_settings');