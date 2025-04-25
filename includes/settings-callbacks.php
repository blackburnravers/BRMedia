<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Callback functions
function brmedia_number_input_callback($args) {
    $template = isset($args['template']) ? $args['template'] : '';
    $field = $args['field'];
    $options = get_option($template ? 'brmedia_template_options_' . $template : 'brmedia_general_options');
    $value = isset($options[$field]) ? $options[$field] : '';
    $placeholder = $args['placeholder'] ?? '';
    $name = $template ? 'brmedia_template_options_' . esc_attr($template) : 'brmedia_general_options';
    echo '<input type="number" class="form-control" name="' . $name . '[' . esc_attr($field) . ']" id="' . esc_attr($field) . '_' . esc_attr($template) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($placeholder) . '" min="-1" step="1" />';
}

function brmedia_select_callback($args) {
    $template = isset($args['template']) ? $args['template'] : '';
    $field = $args['field'];
    $options = get_option($template ? 'brmedia_template_options_' . $template : 'brmedia_general_options');
    $value = isset($options[$field]) ? $options[$field] : '';
    $select_options = $args['options'] ?? [];
    $name = $template ? 'brmedia_template_options_' . esc_attr($template) : 'brmedia_general_options';
    echo '<select class="form-control" name="' . $name . '[' . esc_attr($field) . ']" id="' . esc_attr($field) . '_' . esc_attr($template) . '">';
    foreach ($select_options as $key => $label) {
        echo '<option value="' . esc_attr($key) . '" ' . (selected($value, $key, false)) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
}

function brmedia_template_checkbox_callback($args) {
    $template = $args['template'];
    $field = $args['field'];
    $options = get_option('brmedia_template_options_' . $template);
    $value = isset($options[$field]) ? $options[$field] : 0;
    echo '<input type="checkbox" name="brmedia_template_options_' . esc_attr($template) . '[' . esc_attr($field) . ']" id="' . esc_attr($field) . '_' . esc_attr($template) . '" value="1" ' . checked(1, $value, false) . ' />';
    echo '<label for="' . esc_attr($field) . '_' . esc_attr($template) . '" style="margin-left: 5px;">' . esc_html($args['label']) . '</label>';
}

function brmedia_addon_section_callback() {
    echo '<p>Manage BRMedia addons to enhance your media player functionality.</p>';
}

function brmedia_template_section_callback() {
    echo '<p>Configure default settings for your media player templates.</p>';
}

function brmedia_cast_section_callback() {
    echo '<p style="margin-bottom: 15px;">Configure casting options to enable seamless media streaming to supported devices such as Chromecast or smart TVs. This section allows you to enable casting functionality, input your Cast API Key for authentication, and specify a Cast Device ID for targeting specific devices. Proper configuration enhances user experience by allowing media playback on external screens or speakers.</p>';
}

function brmedia_meta_section_callback() {
    echo '<p style="margin-bottom: 15px;">Manage meta settings to control the metadata displayed on your media pages. This section includes options to enable meta tags and customize the meta description, improving search engine visibility and providing a summary of your content to users and search engines. A well-crafted meta description can boost click-through rates from search results.</p>';
}

function brmedia_seo_section_callback() {
    echo '<p style="margin-bottom: 15px;">Optimize your media pages for search engines with advanced SEO options. This section allows you to enable SEO features, configure Open Graph tags for social media sharing, enable Twitter Cards for enhanced tweet embeds, set canonical URLs to avoid duplicate content issues, prevent indexing of media pages with noindex, and define default SEO keywords and author names for enhanced discoverability.</p>';
}

function brmedia_checkbox_callback_greyed($args) {
    echo '<input type="checkbox" disabled /> ' . esc_html($args['label']) . ' (Coming Soon)';
}

function brmedia_default_volume_callback() {
    echo '<input type="number" name="brmedia_general_options[default_volume]" value="' . esc_attr(get_option('brmedia_general_options')['default_volume'] ?? 50) . '" min="0" max="100" />';
}

function brmedia_default_template_callback() {
    $options = get_option('brmedia_general_options');
    $selected = $options['default_template'] ?? 'template-1';
    $templates = [
        'template-1' => 'Classic Audio Player',
        'template-2' => 'Advanced Media Player',
        'template-3' => 'Minimalist Waveform Player',
        'template-4' => 'Customizable Audio Interface',
        'template-5' => 'Compact Media Controls',
        'template-6' => 'Modern Card-Style Player',
        'template-7' => 'Full-Width Audio Visualizer'
    ];
    echo '<select name="brmedia_general_options[default_template]">';
    foreach ($templates as $key => $label) {
        echo '<option value="' . esc_attr($key) . '" ' . selected($selected, $key, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">Select the default template for BRMedia (Fullscreen Immersive Player excluded).</p>';
}

function brmedia_casting_enabled_callback() {
    echo '<input type="checkbox" name="brmedia_general_options[casting_enabled]" value="1" ' . checked(1, get_option('brmedia_general_options')['casting_enabled'] ?? 0, false) . ' />';
}

function brmedia_seo_enabled_callback() {
    echo '<input type="checkbox" name="brmedia_general_options[seo_enabled]" value="1" ' . checked(1, get_option('brmedia_general_options')['seo_enabled'] ?? 0, false) . ' />';
}

function brmedia_checkbox_callback($args) {
    $options = get_option('brmedia_general_options');
    $value = $options[$args['field']] ?? 0;
    echo '<input type="checkbox" name="brmedia_general_options[' . esc_attr($args['field']) . ']" value="1" ' . checked(1, $value, false) . ' /> ' . esc_html($args['label']);
    if (!empty($args['description'])) {
        echo '<p class="description">' . esc_html($args['description']) . '</p>';
    }
}

function brmedia_text_input_callback($args) {
    $template = isset($args['template']) ? $args['template'] : '';
    $options = get_option($template ? 'brmedia_template_options_' . $template : 'brmedia_general_options');
    $value = $options[$args['field']] ?? '';
    $name = $template ? 'brmedia_template_options_' . esc_attr($template) : 'brmedia_general_options';
    echo '<input type="text" class="form-control" name="' . $name . '[' . esc_attr($args['field']) . ']" id="' . esc_attr($args['field']) . '_' . esc_attr($template) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($args['placeholder']) . '" />';
    if ($args['field'] === 'cast_api_key') {
        echo '<p class="description">Enter your Cast API Key to enable casting to supported devices.</p>';
    } elseif ($args['field'] === 'cast_device_id') {
        echo '<p class="description">Enter your Cast Device ID for specific device targeting.</p>';
    }
}

function brmedia_color_picker_callback($args) {
    $template = $args['template'];
    $field = $args['field'];
    $options = get_option('brmedia_template_options_' . $template);
    $value = $options[$field] ?? '';
    echo '<div class="color-picker-wrapper">';
    echo '<input type="color" class="color-picker" name="brmedia_template_options_' . esc_attr($template) . '[' . esc_attr($field) . ']" id="' . esc_attr($field) . '_' . esc_attr($template) . '" value="' . esc_attr($value) . '" />';
    if ($value) {
        echo '<input type="text" class="hex-code" value="' . esc_attr($value) . '" readonly />';
    }
    echo '</div>';
}

function brmedia_controls_callback($args) {
    $template = $args['template'];
    $options = get_option('brmedia_template_options_' . $template);
    $selected_controls = $options['controls'] ?? [];
    $controls = ['stop', 'volume', 'speed', 'rewind', 'fast_forward', 'repeat', 'skip_forward', 'skip_backward', 'share', 'fullscreen', 'cast', 'shuffle', 'tracklist'];
    echo '<p style="margin-bottom: 10px;">Select which additional controls to display (Play, Pause, Mute, and Unmute are always enabled).</p>';
    foreach ($controls as $control) {
        $checked = in_array($control, $selected_controls) ? 'checked' : '';
        echo '<div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">';
        echo '<input type="checkbox" name="brmedia_template_options_' . esc_attr($template) . '[controls][]" id="control_' . esc_attr($control) . '_template_' . esc_attr($template) . '" value="' . esc_attr($control) . '" ' . $checked . '>';
        echo '<label for="control_' . esc_attr($control) . '_template_' . esc_attr($template) . '" style="margin: 0;">' . ucfirst(str_replace('_', ' ', $control)) . '</label>';
        echo '</div>';
    }
}

function brmedia_icon_selector_callback($args) {
    $template = $args['template'];
    $field = $args['field'];
    $options = get_option('brmedia_template_options_' . $template);
    $value = $options[$field] ?? '';
    $icons = brmedia_get_media_icons(); // Assumes this function exists elsewhere
    echo '<select class="icon-picker" name="brmedia_template_options_' . esc_attr($template) . '[' . esc_attr($field) . ']" id="' . esc_attr($field) . '_' . esc_attr($template) . '">';
    foreach ($icons as $class => $label) {
        echo '<option value="' . esc_attr($class) . '" ' . selected($value, $class, false) . '>' . esc_html($label) . ' (' . $class . ')</option>';
    }
    echo '</select>';
}

function brmedia_import_enabled_callback($args) {
    $options = get_option('brmedia_import_settings');
    $field = $args['field'];
    $value = isset($options[$field]) ? $options[$field] : 0;
    echo '<input type="checkbox" id="' . esc_attr($field) . '" name="brmedia_import_settings[' . esc_attr($field) . ']" value="1" ' . checked(1, $value, false) . ' class="toggle-service" data-service="' . esc_attr($args['key']) . '" />';
}