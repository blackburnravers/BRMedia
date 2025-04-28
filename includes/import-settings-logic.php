<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// For older WordPress versions (pre-4.7)
add_filter('whitelist_options', 'brmedia_whitelist_import_settings');
function brmedia_whitelist_import_settings($whitelist_options) {
    $whitelist_options['brmedia_import_settings'] = array('brmedia_import_settings');
    return $whitelist_options;
}

// For newer WordPress versions (4.7+)
add_filter('allowed_options', 'brmedia_allowed_import_settings');
function brmedia_allowed_import_settings($allowed_options) {
    $allowed_options['brmedia_import_settings'] = array('brmedia_import_settings');
    return $allowed_options;
}

function brmedia_register_import_settings() {
    global $wp_settings_sections;
    // Check if the settings section is already registered
    if (isset($wp_settings_sections['brmedia-import-settings'])) {
        error_log('BRMedia: Import settings already registered');
        return;
    }
    error_log('BRMedia: Registering import settings');
    // Register import settings group
    register_setting('brmedia_import_settings', 'brmedia_import_settings', 'brmedia_sanitize_import_settings');

    $services = [
        'soundcloud' => ['name' => 'SoundCloud', 'icon' => 'soundcloud.svg', 'api_fields' => ['client_id', 'client_secret'], 'color' => '#FF5500'],
        'audiomack' => ['name' => 'Audiomack', 'icon' => 'audiomack.svg', 'api_fields' => ['api_key'], 'color' => '#FFA200'],
        'bandcamp' => ['name' => 'Bandcamp', 'icon' => 'bandcamp.svg', 'api_fields' => [], 'color' => '#629AA9'],
        'reverbnation' => ['name' => 'ReverbNation', 'icon' => 'reverbnation.svg', 'api_fields' => [], 'color' => '#ED36DB'],
        'mixcloud' => ['name' => 'Mixcloud', 'icon' => 'mixcloud.svg', 'api_fields' => ['api_key'], 'color' => '#1D2F5D'],
        'hearthis' => ['name' => 'HearThis.at', 'icon' => 'hearthis.svg', 'api_fields' => ['api_key'], 'color' => '#A12015'],
        'youtube-music' => ['name' => 'YouTube Music', 'icon' => 'youtube-music.svg', 'api_fields' => ['api_key'], 'color' => '#FF0000'],
        'audius' => ['name' => 'Audius', 'icon' => 'audius.svg', 'api_fields' => ['api_key'], 'color' => '#CC0FE0'],
        'house-mixes' => ['name' => 'House-Mixes.com', 'icon' => 'house-mixes.svg', 'api_fields' => [], 'color' => '#000000'],
    ];

    foreach ($services as $key => $service) {
        // Add a section for each service
        add_settings_section(
            'brmedia_import_' . $key,
            '', // No title, handled in card layout
            null, // No callback needed
            'brmedia-import-settings'
        );

        // Add the enable field
        add_settings_field(
            'import_' . $key . '_enabled',
            'Enable',
            'brmedia_import_enabled_callback',
            'brmedia-import-settings',
            'brmedia_import_' . $key,
            ['field' => 'import_' . $key . '_enabled', 'key' => $key, 'label' => 'Enable ' . $service['name']]
        );

        // Add API fields
        foreach ($service['api_fields'] as $field) {
            add_settings_field(
                'import_' . $key . '_' . $field,
                ucfirst(str_replace('_', ' ', $field)),
                'brmedia_import_text_input_callback',
                'brmedia-import-settings',
                'brmedia_import_' . $key,
                ['field' => 'import_' . $key . '_' . $field, 'placeholder' => 'Enter ' . $field, 'key' => $key]
            );
        }
    }

    global $wp_settings_sections;
    error_log('BRMedia: Registered sections for brmedia-import-settings: ' . print_r($wp_settings_sections['brmedia-import-settings'], true));
}
add_action('admin_init', 'brmedia_register_import_settings');

function brmedia_import_section_callback() {
    // This function is no longer used but kept for compatibility
    echo '<p>Configure music import services to enable importing tracks from various platforms.</p>';
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