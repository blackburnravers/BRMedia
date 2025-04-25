<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register all settings
function brmedia_register_settings() {
    global $services;

    // Register the import settings option group
    register_setting(
        'brmedia_import_settings', // Option group
        'brmedia_import_settings', // Option name
        'brmedia_sanitize_import_settings' // Sanitization callback (from settings-callbacks.php)
    );

    // Add sections and fields for each service
    foreach ($services as $key => $service) {
        // Add a section for the service
        add_settings_section(
            'brmedia_import_' . $key,
            $service['name'],
            '__return_null', // No section description needed
            'brmedia-import-settings' // Menu slug
        );

        // Add "Enable" checkbox field
        add_settings_field(
            'import_' . $key . '_enabled',
            'Enable ' . $service['name'],
            'brmedia_import_enabled_callback', // Callback from settings-callbacks.php
            'brmedia-import-settings',
            'brmedia_import_' . $key,
            ['field' => 'import_' . $key . '_enabled', 'key' => $key]
        );

        // Add API fields (e.g., Client ID, Client Secret)
        if (isset($service['api_fields'])) {
            foreach ($service['api_fields'] as $field) {
                add_settings_field(
                    'import_' . $key . '_' . $field,
                    ucfirst(str_replace('_', ' ', $field)),
                    'brmedia_text_input_callback', // Callback from settings-callbacks.php
                    'brmedia-import-settings',
                    'brmedia_import_' . $key,
                    ['field' => 'import_' . $key . '_' . $field, 'placeholder' => 'Enter ' . $field]
                );
            }
        }
    }
}
add_action('admin_init', 'brmedia_register_settings');