<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register general settings
function brmedia_register_general_settings() {
    // Define templates array (shared with template settings)
    $templates = [
        'template-1' => 'Classic Audio Player',
        'template-2' => 'Advanced Media Player',
        'template-3' => 'Minimalist Waveform Player',
        'template-4' => 'Customizable Audio Interface',
        'template-5' => 'Compact Media Controls',
        'template-6' => 'Modern Card-Style Player',
        'template-7' => 'Full-Width Audio Visualizer',
        'template-fullscreen' => 'Fullscreen Immersive Player'
    ];

    // Register settings group
    register_setting('brmedia_general_settings', 'brmedia_general_options', 'brmedia_sanitize_general_settings');

    // Section 1: BRMedia Addons
    add_settings_section(
        'brmedia_addon_section',
        'BRMedia Addons',
        'brmedia_addon_section_callback',
        'brmedia-general-settings'
    );

    $addon_features = [
        'video' => ['name' => 'BRMedia Video', 'description' => 'Enable video playback with advanced streaming features.'],
        'podcast' => ['name' => 'BRMedia Podcast', 'description' => 'Manage and play podcasts seamlessly.'],
        'radio' => ['name' => 'BRMedia Radio', 'description' => 'Stream live radio stations with customization options.'],
        'gaming' => ['name' => 'BRMedia Gaming', 'description' => 'Integrate gaming media and controls.'],
        'chat' => ['name' => 'BRMedia Chat', 'description' => 'Enable chat functionality for media interaction.'],
        'downloads' => ['name' => 'BRMedia Downloads', 'description' => 'Manage downloadable media content.'],
        'footbar' => ['name' => 'BRMedia Footbar', 'description' => 'Enable sticky footbar player synced with all media.']
    ];

    foreach ($addon_features as $key => $addon) {
        add_settings_field(
            'addon_' . $key,
            esc_html($addon['name']),
            'brmedia_checkbox_callback',
            'brmedia-general-settings',
            'brmedia_addon_section',
            ['field' => 'addon_' . $key, 'label' => 'Enable ' . $addon['name'], 'description' => $addon['description']]
        );
    }

    // Section 2: Player Settings
    add_settings_section(
        'brmedia_template_section',
        'Player Settings',
        'brmedia_template_section_callback',
        'brmedia-general-settings'
    );
    add_settings_field('default_volume', 'Default Volume', 'brmedia_default_volume_callback', 'brmedia-general-settings', 'brmedia_template_section');
    add_settings_field('default_template', 'Default Template', 'brmedia_default_template_callback', 'brmedia-general-settings', 'brmedia_template_section');
    add_settings_field('rewind_length', 'Rewind Length (seconds)', 'brmedia_number_input_callback', 'brmedia-general-settings', 'brmedia_template_section', ['field' => 'rewind_length', 'placeholder' => 'e.g., 22']);
    add_settings_field('fast_forward_length', 'Fast Forward Length (seconds)', 'brmedia_number_input_callback', 'brmedia-general-settings', 'brmedia_template_section', ['field' => 'fast_forward_length', 'placeholder' => 'e.g., 22']);

    // Section 3: Cast Settings
    add_settings_section('brmedia_cast_section', 'Cast Settings', 'brmedia_cast_section_callback', 'brmedia-general-settings');
    add_settings_field('casting_enabled', 'Enable Casting', 'brmedia_casting_enabled_callback', 'brmedia-general-settings', 'brmedia_cast_section');
    add_settings_field('cast_api_key', 'Cast API Key', 'brmedia_text_input_callback', 'brmedia-general-settings', 'brmedia_cast_section', ['field' => 'cast_api_key', 'placeholder' => 'Enter your Cast API Key']);
    add_settings_field('cast_device_id', 'Cast Device ID', 'brmedia_text_input_callback', 'brmedia-general-settings', 'brmedia_cast_section', ['field' => 'cast_device_id', 'placeholder' => 'Enter your Cast Device ID']);

    // Section 4: Meta Settings
    add_settings_section('brmedia_meta_section', 'Meta Settings', 'brmedia_meta_section_callback', 'brmedia-general-settings');
    add_settings_field('meta_tags_enabled', 'Enable Meta Tags', 'brmedia_checkbox_callback', 'brmedia-general-settings', 'brmedia_meta_section', ['field' => 'meta_tags_enabled', 'label' => 'Enable Meta Tags']);
    add_settings_field('custom_meta_description', 'Custom Meta Description', 'brmedia_text_input_callback', 'brmedia-general-settings', 'brmedia_meta_section', ['field' => 'custom_meta_description', 'placeholder' => 'Enter custom meta description']);

    // Section 5: Advanced SEO Settings
    add_settings_section('brmedia_seo_section', 'Advanced SEO Settings', 'brmedia_seo_section_callback', 'brmedia-general-settings');
    add_settings_field('seo_enabled', 'Enable SEO Features', 'brmedia_seo_enabled_callback', 'brmedia-general-settings', 'brmedia_seo_section');
    add_settings_field('seo_og_tags', 'Enable Open Graph Tags', 'brmedia_checkbox_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_og_tags', 'label' => 'Enable Open Graph Tags']);
    add_settings_field('seo_twitter_cards', 'Enable Twitter Cards', 'brmedia_checkbox_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_twitter_cards', 'label' => 'Enable Twitter Cards']);
    add_settings_field('seo_canonical_url', 'Enable Canonical URLs', 'brmedia_checkbox_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_canonical_url', 'label' => 'Enable Canonical URLs']);
    add_settings_field('seo_noindex', 'Noindex Media Pages', 'brmedia_checkbox_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_noindex', 'label' => 'Prevent search engines from indexing media pages']);
    add_settings_field('seo_keywords', 'Default SEO Keywords', 'brmedia_text_input_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_keywords', 'placeholder' => 'Enter default keywords (comma-separated)']);
    add_settings_field('seo_author', 'Default Author Name', 'brmedia_text_input_callback', 'brmedia-general-settings', 'brmedia_seo_section', ['field' => 'seo_author', 'placeholder' => 'Enter default author name for meta tags']);
}

// Sanitize general settings
function brmedia_sanitize_general_settings($input) {
    $sanitized = [];
    $sanitized['default_volume'] = intval($input['default_volume'] ?? 50);
    $sanitized['default_template'] = sanitize_text_field($input['default_template'] ?? 'template-1');
    $sanitized['rewind_length'] = floatval($input['rewind_length'] ?? 22);
    $sanitized['fast_forward_length'] = floatval($input['fast_forward_length'] ?? 22);
    $sanitized['casting_enabled'] = isset($input['casting_enabled']) ? 1 : 0;
    $sanitized['cast_api_key'] = sanitize_text_field($input['cast_api_key'] ?? '');
    $sanitized['cast_device_id'] = sanitize_text_field($input['cast_device_id'] ?? '');
    $sanitized['meta_tags_enabled'] = isset($input['meta_tags_enabled']) ? 1 : 0;
    $sanitized['custom_meta_description'] = sanitize_text_field($input['custom_meta_description'] ?? '');
    $sanitized['seo_enabled'] = isset($input['seo_enabled']) ? 1 : 0;
    $sanitized['seo_og_tags'] = isset($input['seo_og_tags']) ? 1 : 0;
    $sanitized['seo_twitter_cards'] = isset($input['seo_twitter_cards']) ? 1 : 0;
    $sanitized['seo_canonical_url'] = isset($input['seo_canonical_url']) ? 1 : 0;
    $sanitized['seo_noindex'] = isset($input['seo_noindex']) ? 1 : 0;
    $sanitized['seo_keywords'] = sanitize_text_field($input['seo_keywords'] ?? '');
    $sanitized['seo_author'] = sanitize_text_field($input['seo_author'] ?? '');
    $addons = ['video', 'podcast', 'radio', 'gaming', 'chat', 'downloads', 'footbar'];
    foreach ($addons as $addon) {
        $sanitized['addon_' . $addon] = isset($input['addon_' . $addon]) ? 1 : 0;
    }
    return $sanitized;
}