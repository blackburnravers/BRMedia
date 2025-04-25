<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_register_import_settings() {
    // Register import settings group
    register_setting('brmedia_import_settings', 'brmedia_import_settings', 'brmedia_sanitize_import_settings');

    // Section: Music Import Services
    add_settings_section(
        'brmedia_import_section',
        'Music Import Services',
        'brmedia_import_section_callback',
        'brmedia-import-settings'
    );

    $services = [
        'soundcloud' => ['name' => 'SoundCloud', 'icon' => 'soundcloud.svg', 'api_fields' => ['client_id', 'client_secret'], 'color' => '#FF5500'],
        'audiomack' => ['name' => 'Audiomack', 'icon' => 'audiomack.svg', 'api_fields' => ['api_key'], 'color' => '#FFA200'],
        'bandcamp' => ['name' => 'Bandcamp', 'icon' => 'bandcamp.svg', 'api_fields' => [], 'color' => '#629AA9'],
        'reverbnation' => ['name' => 'ReverbNation', 'icon' => 'reverbnation.svg', 'api_fields' => [], 'color' => '#00A4A4'],
        'mixcloud' => ['name' => 'Mixcloud', 'icon' => 'mixcloud.svg', 'api_fields' => ['api_key'], 'color' => '#1D2F5D'],
        'hearthis' => ['name' => 'HearThis.at', 'icon' => 'hearthis.png', 'api_fields' => ['api_key'], 'color' => '#00C4B4'],
        'youtube-music' => ['name' => 'YouTube Music', 'icon' => 'youtube-music.svg', 'api_fields' => ['api_key'], 'color' => '#FF0000'],
        'audius' => ['name' => 'Audius', 'icon' => 'audius.svg', 'api_fields' => ['api_key'], 'color' => '#CC0FE0'],
        'drooble' => ['name' => 'Drooble', 'icon' => 'drooble.svg', 'api_fields' => [], 'color' => '#00AEEF'],
        'house-mixes' => ['name' => 'House-Mixes.com', 'icon' => 'house-mixes.png', 'api_fields' => [], 'color' => '#2E2E2E'],
    ];

    foreach ($services as $key => $service) {
        add_settings_field(
            'import_' . $key . '_enabled',
            '<img src="' . esc_url(BRMEDIA_PLUGIN_URL . 'assets/icons/' . $service['icon']) . '" alt="' . esc_attr($service['name']) . '" />' . esc_html($service['name']),
            'brmedia_import_enabled_callback',
            'brmedia-import-settings',
            'brmedia_import_section',
            ['field' => 'import_' . $key . '_enabled', 'key' => $key, 'label' => 'Enable ' . $service['name']]
        );

        foreach ($service['api_fields'] as $field) {
            add_settings_field(
                'import_' . $key . '_' . $field,
                ucfirst(str_replace('_', ' ', $field)),
                'brmedia_text_input_callback',
                'brmedia-import-settings',
                'brmedia_import_section',
                ['field' => 'import_' . $key . '_' . $field, 'placeholder' => 'Enter ' . $field, 'key' => $key]
            );
        }
    }
}
add_action('admin_init', 'brmedia_register_import_settings');

function brmedia_import_section_callback() {
    echo '<p>Configure music import services to enable importing tracks from various platforms. These settings will allow you to enable or disable services and input API credentials where required.</p>';
}

function brmedia_sanitize_import_settings($input) {
    $sanitized = [];
    $services = ['soundcloud', 'audiomack', 'bandcamp', 'reverbnation', 'mixcloud', 'hearthis', 'youtube-music', 'audius', 'drooble', 'house-mixes'];
    $api_fields = [
        'soundcloud' => ['client_id', 'client_secret'],
        'audiomack' => ['api_key'],
        'mixcloud' => ['api_key'],
        'hearthis' => ['api_key'],
        'youtube-music' => ['api_key'],
        'audius' => ['api_key'],
    ];

    foreach ($services as $service) {
        $enabled_field = 'import_' . $service . '_enabled';
        $sanitized[$enabled_field] = isset($input[$enabled_field]) ? 1 : 0;
        if (isset($api_fields[$service])) {
            foreach ($api_fields[$service] as $field) {
                $sanitized['import_' . $service . '_' . $field] = sanitize_text_field($input['import_' . $service . '_' . $field] ?? '');
            }
        }
    }
    return $sanitized;
}