<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include settings files
require_once __DIR__ . '/settings-callbacks.php';
require_once __DIR__ . '/general-settings.php';
require_once __DIR__ . '/template-settings.php';
require_once __DIR__ . '/import-settings-logic.php';

// Register all settings
function brmedia_register_settings() {
    brmedia_register_general_settings();
    brmedia_register_template_settings();
    brmedia_register_import_settings();
}
add_action('admin_init', 'brmedia_register_settings');