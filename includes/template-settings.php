<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register template-specific settings
function brmedia_register_template_settings() {
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

    foreach ($templates as $template_key => $template_name) {
        register_setting(
            'brmedia_template_settings_' . $template_key,
            'brmedia_template_options_' . $template_key,
            'brmedia_sanitize_template_settings'
        );

        // General Settings Section
        add_settings_section(
            'brmedia_' . $template_key . '_general',
            'General Settings for ' . $template_name,
            function() use ($template_name) { echo '<p>Customize the general appearance of the ' . esc_html($template_name) . '.</p>'; },
            'brmedia-template-settings-' . $template_key
        );
        add_settings_field($template_key . '_background_color', 'Background Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'background_color']);
        add_settings_field($template_key . '_text_color', 'Text Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'text_color']);
        add_settings_field($template_key . '_icon_color', 'Icon Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'icon_color']);
        add_settings_field($template_key . '_border_color', 'Border Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'border_color']);
        add_settings_field($template_key . '_padding', 'Padding', 'brmedia_text_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'padding', 'placeholder' => 'e.g., 10px']);
        add_settings_field($template_key . '_margin', 'Margin', 'brmedia_text_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_general', ['template' => $template_key, 'field' => 'margin', 'placeholder' => 'e.g., 0 auto']);

        // Controls Section
        add_settings_section(
            'brmedia_' . $template_key . '_controls',
            'Enable Controls',
            function() { echo '<p>Select which controls to display (Play, Pause, Mute, and Unmute are always enabled).</p>'; },
            'brmedia-template-settings-' . $template_key
        );
        add_settings_field($template_key . '_controls', 'Enabled Controls', 'brmedia_controls_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_controls', ['template' => $template_key]);

        // Features Section
        add_settings_section(
            'brmedia_' . $template_key . '_features',
            'Features',
            function() { echo '<p>Enable or disable specific features.</p>'; },
            'brmedia-template-settings-' . $template_key
        );
        add_settings_field($template_key . '_enable_marquee', 'Enable Marquee', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'enable_marquee', 'label' => 'Enable scrolling title text']);
        add_settings_field($template_key . '_marquee_speed', 'Marquee Speed (seconds)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'marquee_speed', 'placeholder' => 'e.g., 10']);
        add_settings_field($template_key . '_marquee_behaviour', 'Marquee Behaviour', 'brmedia_select_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'marquee_behaviour', 'options' => ['scroll' => 'Scroll', 'slide' => 'Slide', 'alternate' => 'Alternate']]);
        add_settings_field($template_key . '_marquee_direction', 'Marquee Direction', 'brmedia_select_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'marquee_direction', 'options' => ['left' => 'Left', 'right' => 'Right']]);
        add_settings_field($template_key . '_marquee_loop', 'Marquee Loop', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'marquee_loop', 'placeholder' => 'e.g., -1 for infinite']);
        add_settings_field($template_key . '_enable_tracklist', 'Enable Tracklist', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'enable_tracklist', 'label' => 'Show tracklist if available']);
        add_settings_field($template_key . '_tracklist_background_color', 'Tracklist Background Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'tracklist_background_color']);
        add_settings_field($template_key . '_tracklist_text_color', 'Tracklist Text Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'tracklist_text_color']);
        add_settings_field($template_key . '_tracklist_time_text_color', 'Tracklist Time Text Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'tracklist_time_text_color']);
        add_settings_field($template_key . '_tracklist_box_size', 'Tracklist Box Size (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'tracklist_box_size', 'placeholder' => 'e.g., 200']);
        add_settings_field($template_key . '_enable_cover_image', 'Enable Cover Image', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'enable_cover_image', 'label' => 'Display cover image']);
        add_settings_field($template_key . '_cover_image_size', 'Cover Image Size (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'cover_image_size', 'placeholder' => 'e.g., 200']);
        add_settings_field($template_key . '_show_artist', 'Show Artist', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'show_artist', 'label' => 'Show artist']);
        add_settings_field($template_key . '_show_bpm', 'Show BPM', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'show_bpm', 'label' => 'Show BPM']);
        add_settings_field($template_key . '_show_key', 'Show Key', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'show_key', 'label' => 'Show key']);
        add_settings_field($template_key . '_show_duration', 'Show Duration', 'brmedia_template_checkbox_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_features', ['template' => $template_key, 'field' => 'show_duration', 'label' => 'Show duration']);

        // Icons Section
        add_settings_section(
            'brmedia_' . $template_key . '_icons',
            'Icon Selection',
            function() { echo '<p>Customize icons for each control.</p>'; },
            'brmedia-template-settings-' . $template_key
        );
        $controls = ['play', 'pause', 'stop', 'volume', 'mute', 'unmute', 'speed', 'rewind', 'fast_forward', 'repeat', 'skip_forward', 'skip_backward', 'share', 'fullscreen', 'cast', 'shuffle', 'tracklist'];
        foreach ($controls as $control) {
            add_settings_field($template_key . '_' . $control . '_icon', ucfirst(str_replace('_', ' ', $control)) . ' Icon', 'brmedia_icon_selector_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_icons', ['template' => $template_key, 'field' => $control . '_icon']);
        }

        // Waveform Styling Section
        add_settings_section(
            'brmedia_' . $template_key . '_waveform',
            'Waveform Styling',
            function() { echo '<p>Adjust the waveform display settings.</p>'; },
            'brmedia-template-settings-' . $template_key
        );
        add_settings_field($template_key . '_wave_color', 'Wave Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'wave_color']);
        add_settings_field($template_key . '_progress_color', 'Progress Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'progress_color']);
        add_settings_field($template_key . '_cursor_color', 'Cursor Color', 'brmedia_color_picker_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'cursor_color']);
        add_settings_field($template_key . '_cursor_width', 'Cursor Width (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'cursor_width', 'placeholder' => 'e.g., 1']);
        add_settings_field($template_key . '_bar_width', 'Bar Width (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'bar_width', 'placeholder' => 'e.g., 2']);
        add_settings_field($template_key . '_bar_gap', 'Bar Gap (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'bar_gap', 'placeholder' => 'e.g., 1']);
        add_settings_field($template_key . '_wave_height', 'Wave Height (px)', 'brmedia_number_input_callback', 'brmedia-template-settings-' . $template_key, 'brmedia_' . $template_key . '_waveform', ['template' => $template_key, 'field' => 'wave_height', 'placeholder' => 'e.g., 100']);
    }
}

// Sanitize template settings
function brmedia_sanitize_template_settings($input) {
    $sanitized_input = [];
    // General Settings
    $sanitized_input['background_color'] = sanitize_hex_color($input['background_color'] ?? '#ffffff');
    $sanitized_input['text_color'] = sanitize_hex_color($input['text_color'] ?? '#000000');
    $sanitized_input['icon_color'] = sanitize_hex_color($input['icon_color'] ?? '#000000');
    $sanitized_input['border_color'] = sanitize_hex_color($input['border_color'] ?? '#000000');
    $sanitized_input['padding'] = sanitize_text_field($input['padding'] ?? '');
    $sanitized_input['margin'] = sanitize_text_field($input['margin'] ?? '');
    // Waveform Settings
    $sanitized_input['wave_color'] = sanitize_hex_color($input['wave_color'] ?? '#888888');
    $sanitized_input['progress_color'] = sanitize_hex_color($input['progress_color'] ?? '#333333');
    $sanitized_input['cursor_color'] = sanitize_hex_color($input['cursor_color'] ?? '#000000');
    $sanitized_input['cursor_width'] = intval($input['cursor_width'] ?? 1);
    $sanitized_input['bar_gap'] = intval($input['bar_gap'] ?? 1);
    $sanitized_input['wave_height'] = intval($input['wave_height'] ?? 100);
    $sanitized_input['bar_width'] = intval($input['bar_width'] ?? 2);
    // Features
    $sanitized_input['enable_marquee'] = isset($input['enable_marquee']) ? 1 : 0;
    $sanitized_input['marquee_speed'] = floatval($input['marquee_speed'] ?? 10);
    $sanitized_input['marquee_behaviour'] = in_array($input['marquee_behaviour'] ?? 'scroll', ['scroll', 'slide', 'alternate']) ? $input['marquee_behaviour'] : 'scroll';
    $sanitized_input['marquee_direction'] = in_array($input['marquee_direction'] ?? 'left', ['left', 'right']) ? $input['marquee_direction'] : 'left';
    $sanitized_input['marquee_loop'] = intval($input['marquee_loop'] ?? -1);
    $sanitized_input['enable_tracklist'] = isset($input['enable_tracklist']) ? 1 : 0;
    $sanitized_input['tracklist_background_color'] = sanitize_hex_color($input['tracklist_background_color'] ?? '#ffffff');
    $sanitized_input['tracklist_text_color'] = sanitize_hex_color($input['tracklist_text_color'] ?? '#000000');
    $sanitized_input['tracklist_time_text_color'] = sanitize_hex_color($input['tracklist_time_text_color'] ?? '#000000');
    $sanitized_input['tracklist_box_size'] = intval($input['tracklist_box_size'] ?? 200);
    $sanitized_input['enable_cover_image'] = isset($input['enable_cover_image']) ? 1 : 0;
    $sanitized_input['cover_image_size'] = intval($input['cover_image_size'] ?? 200);
    $sanitized_input['show_artist'] = isset($input['show_artist']) ? 1 : 0;
    $sanitized_input['show_bpm'] = isset($input['show_bpm']) ? 1 : 0;
    $sanitized_input['show_key'] = isset($input['show_key']) ? 1 : 0;
    $sanitized_input['show_duration'] = isset($input['show_duration']) ? 1 : 0;
    // Controls
    $sanitized_input['controls'] = isset($input['controls']) ? array_map('sanitize_text_field', $input['controls']) : [];
    // Icons
    $controls = ['play', 'pause', 'stop', 'volume', 'mute', 'unmute', 'speed', 'rewind', 'fast_forward', 'repeat', 'skip_forward', 'skip_backward', 'share', 'fullscreen', 'cast', 'shuffle', 'tracklist'];
    foreach ($controls as $control) {
        $sanitized_input[$control . '_icon'] = sanitize_text_field($input[$control . '_icon'] ?? 'fas fa-' . str_replace('_', '-', $control));
    }
    return $sanitized_input;
}