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
