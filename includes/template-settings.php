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

// Handle reset of single template via AJAX
function brmedia_reset_template() {
    // Log the AJAX request for debugging
    error_log('BRMedia: Reset template AJAX request received');

    // Verify nonce
    if (!check_ajax_referer('brmedia_template_reset', 'nonce', false)) {
        error_log('BRMedia: Nonce verification failed');
        wp_send_json_error('Invalid nonce');
        return;
    }

    // Check user permissions
    if (!current_user_can('manage_options')) {
        error_log('BRMedia: User lacks manage_options capability');
        wp_send_json_error('Permission denied');
        return;
    }

    // Get and sanitize template
    $template = isset($_POST['template']) ? sanitize_text_field($_POST['template']) : '';
    if (empty($template)) {
        error_log('BRMedia: No template specified');
        wp_send_json_error('No template specified');
        return;
    }

    // Reset template options
    $default_options = brmedia_get_default_template_options();
    update_option('brmedia_template_options_' . $template, $default_options);

    // Log success and send response
    error_log('BRMedia: Template ' . $template . ' reset successfully');
    wp_send_json_success('Template ' . esc_html($template) . ' reset to defaults');
}
add_action('wp_ajax_brmedia_reset_template', 'brmedia_reset_template');