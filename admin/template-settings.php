<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Get selected template
$selected_template = isset($_GET['template']) ? sanitize_text_field($_GET['template']) : 'template-1';
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">Templates</h1>
    <form method="get" action="">
        <input type="hidden" name="page" value="brmedia-template-settings">
        <select name="template" id="template-select" class="form-select mb-4" onchange="this.form.submit()">
            <?php
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
            foreach ($templates as $key => $label) {
                echo '<option value="' . esc_attr($key) . '" ' . selected($selected_template, $key, false) . '>' . esc_html($label) . '</option>';
            }
            ?>
        </select>
    </form>

    <form method="post" action="options.php">
        <?php settings_fields('brmedia_template_settings_' . $selected_template); ?>
        <div class="row">
            <!-- General Settings -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title">General Settings</h3>
                        <p>Customize the general appearance of the template.</p>
                        <?php
                        $options = get_option('brmedia_template_options_' . $selected_template);
                        $bg_color = $options['background_color'] ?? '';
                        $text_color = $options['text_color'] ?? '';
                        $icon_color = $options['icon_color'] ?? '';
                        $border_color = $options['border_color'] ?? '';
                        $icon_background_shape = $options['icon_background_shape'] ?? 'none';
                        $icon_background_color = $options['icon_background_color'] ?? '#ffffff';
                        ?>
                        <div class="mb-3">
                            <label for="text_color_<?php echo esc_attr($selected_template); ?>">Text Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[text_color]" id="text_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($text_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($text_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="background_color_<?php echo esc_attr($selected_template); ?>">Background Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[background_color]" id="background_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($bg_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($bg_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="border_color_<?php echo esc_attr($selected_template); ?>">Border Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[border_color]" id="border_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($border_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($border_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="icon_color_<?php echo esc_attr($selected_template); ?>">Icon Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[icon_color]" id="icon_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($icon_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($icon_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="icon_background_shape_<?php echo esc_attr($selected_template); ?>">Icon Background Shape</label>
                            <select class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[icon_background_shape]" id="icon_background_shape_<?php echo esc_attr($selected_template); ?>">
                                <?php
                                $shapes = ['none' => 'None', 'square' => 'Square', 'circle' => 'Circle', 'rounded' => 'Rounded'];
                                foreach ($shapes as $key => $label) {
                                    echo '<option value="' . esc_attr($key) . '" ' . selected($icon_background_shape, $key, false) . '>' . esc_html($label) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="icon_background_color_<?php echo esc_attr($selected_template); ?>">Icon Background Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[icon_background_color]" id="icon_background_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($icon_background_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($icon_background_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="padding_<?php echo esc_attr($selected_template); ?>">Padding</label>
                            <input type="text" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[padding]" id="padding_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['padding'] ?? ''); ?>" placeholder="e.g., 10px">
                        </div>
                        <div class="mb-3">
                            <label for="margin_<?php echo esc_attr($selected_template); ?>">Margin</label>
                            <input type="text" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[margin]" id="margin_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['margin'] ?? ''); ?>" placeholder="e.g., 0 auto">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enable Controls -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title">Enable Controls</h3>
                        <p>Select which controls to display (Play, Pause, Mute, and Unmute are always enabled).</p>
                        <div class="mb-3">
                            <label>Enabled Controls</label>
                            <?php
                            $controls = ['stop', 'volume', 'speed', 'rewind', 'fast_forward', 'repeat', 'skip_forward', 'skip_backward', 'share', 'fullscreen', 'cast', 'shuffle', 'tracklist'];
                            $selected_controls = $options['controls'] ?? [];
                            foreach ($controls as $control) {
                                $checked = in_array($control, $selected_controls) ? 'checked' : '';
                                echo '<div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">';
                                echo '<input type="checkbox" name="brmedia_template_options_' . esc_attr($selected_template) . '[controls][]" id="control_' . esc_attr($control) . '_template_' . esc_attr($selected_template) . '" value="' . esc_attr($control) . '" ' . $checked . '>';
                                echo '<label for="control_' . esc_attr($control) . '_template_' . esc_attr($selected_template) . '" style="margin: 0;">' . ucfirst(str_replace('_', ' ', $control)) . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title">Features</h3>
                        <p>Enable or disable specific features.</p>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[enable_marquee]" id="enable_marquee_<?php echo esc_attr($selected_template); ?>" <?php checked($options['enable_marquee'] ?? 0, 1); ?> value="1">
                            <label for="enable_marquee_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Enable scrolling title text</label>
                        </div>
                        <div id="marquee_settings_<?php echo esc_attr($selected_template); ?>" style="display: none;">
                            <div class="mb-3">
                                <label for="marquee_speed_<?php echo esc_attr($selected_template); ?>">Marquee Speed (seconds)</label>
                                <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[marquee_speed]" id="marquee_speed_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['marquee_speed'] ?? '10'); ?>" step="0.1">
                            </div>
                            <div class="mb-3">
                                <label for="marquee_behaviour_<?php echo esc_attr($selected_template); ?>">Marquee Behaviour</label>
                                <select class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[marquee_behaviour]" id="marquee_behaviour_<?php echo esc_attr($selected_template); ?>">
                                    <?php
                                    $behaviours = ['scroll' => 'Scroll', 'slide' => 'Slide', 'alternate' => 'Alternate'];
                                    $selected_behaviour = $options['marquee_behaviour'] ?? 'scroll';
                                    foreach ($behaviours as $key => $label) {
                                        echo '<option value="' . esc_attr($key) . '" ' . selected($selected_behaviour, $key, false) . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="marquee_direction_<?php echo esc_attr($selected_template); ?>">Marquee Direction</label>
                                <select class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[marquee_direction]" id="marquee_direction_<?php echo esc_attr($selected_template); ?>">
                                    <?php
                                    $directions = ['left' => 'Left', 'right' => 'Right'];
                                    $selected_direction = $options['marquee_direction'] ?? 'left';
                                    foreach ($directions as $key => $label) {
                                        echo '<option value="' . esc_attr($key) . '" ' . selected($selected_direction, $key, false) . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="marquee_loop_<?php echo esc_attr($selected_template); ?>">Marquee Loop</label>
                                <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[marquee_loop]" id="marquee_loop_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['marquee_loop'] ?? '-1'); ?>" placeholder="e.g., -1 for infinite">
                            </div>
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[enable_tracklist]" id="enable_tracklist_<?php echo esc_attr($selected_template); ?>" <?php checked($options['enable_tracklist'] ?? 0, 1); ?> value="1">
                            <label for="enable_tracklist_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Enable Tracklist</label>
                        </div>
                        <div class="mb-3" id="tracklist_background_color_<?php echo esc_attr($selected_template); ?>">
                            <label for="tracklist_background_color_<?php echo esc_attr($selected_template); ?>">Tracklist Background Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[tracklist_background_color]" id="tracklist_background_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['tracklist_background_color'] ?? '#ffffff'); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($options['tracklist_background_color'] ?? '#ffffff'); ?>">
                            </div>
                        </div>
                        <div class="mb-3" id="tracklist_text_color_<?php echo esc_attr($selected_template); ?>">
                            <label for="tracklist_text_color_<?php echo esc_attr($selected_template); ?>">Tracklist Text Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[tracklist_text_color]" id="tracklist_text_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['tracklist_text_color'] ?? '#000000'); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($options['tracklist_text_color'] ?? '#000000'); ?>">
                            </div>
                        </div>
                        <div class="mb-3" id="tracklist_time_text_color_<?php echo esc_attr($selected_template); ?>">
                            <label for="tracklist_time_text_color_<?php echo esc_attr($selected_template); ?>">Tracklist Time Text Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[tracklist_time_text_color]" id="tracklist_time_text_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['tracklist_time_text_color'] ?? '#000000'); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($options['tracklist_time_text_color'] ?? '#000000'); ?>">
                            </div>
                        </div>
                        <div class="mb-3" id="tracklist_box_size_<?php echo esc_attr($selected_template); ?>">
                            <label for="tracklist_box_size_<?php echo esc_attr($selected_template); ?>">Tracklist Box Size (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[tracklist_box_size]" id="tracklist_box_size_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['tracklist_box_size'] ?? '300'); ?>" placeholder="e.g., 300">
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[enable_cover_image]" id="enable_cover_image_<?php echo esc_attr($selected_template); ?>" <?php checked($options['enable_cover_image'] ?? 0, 1); ?> value="1">
                            <label for="enable_cover_image_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Enable Cover Image</label>
                        </div>
                        <div class="mb-3" id="cover_image_size_<?php echo esc_attr($selected_template); ?>">
                            <label for="cover_image_size_<?php echo esc_attr($selected_template); ?>">Cover Image Size (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[cover_image_size]" id="cover_image_size_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($options['cover_image_size'] ?? '200'); ?>" placeholder="e.g., 200">
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[show_artist]" id="show_artist_<?php echo esc_attr($selected_template); ?>" <?php checked($options['show_artist'] ?? 0, 1); ?> value="1">
                            <label for="show_artist_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Show Artist</label>
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[show_bpm]" id="show_bpm_<?php echo esc_attr($selected_template); ?>" <?php checked($options['show_bpm'] ?? 0, 1); ?> value="1">
                            <label for="show_bpm_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Show BPM</label>
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[show_key]" id="show_key_<?php echo esc_attr($selected_template); ?>" <?php checked($options['show_key'] ?? 0, 1); ?> value="1">
                            <label for="show_key_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Show Key</label>
                        </div>
                        <div class="mb-3" style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[show_duration]" id="show_duration_<?php echo esc_attr($selected_template); ?>" <?php checked($options['show_duration'] ?? 0, 1); ?> value="1">
                            <label for="show_duration_<?php echo esc_attr($selected_template); ?>" style="margin: 0;">Show Duration</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Icon Selection -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title">Icon Selection</h3>
                        <p>Customize icons for each control.</p>
                        <?php
                        $controls = ['play', 'pause', 'stop', 'volume', 'mute', 'unmute', 'speed', 'rewind', 'fast_forward', 'repeat', 'skip_forward', 'skip_backward', 'share', 'fullscreen', 'cast', 'shuffle', 'tracklist'];
                        $icons = brmedia_get_media_icons();
                        foreach ($controls as $control) {
                            $field = $control . '_icon';
                            $value = $options[$field] ?? '';
                            ?>
                            <div class="mb-3">
                                <label for="<?php echo esc_attr($field); ?>_<?php echo esc_attr($selected_template); ?>"><?php echo ucfirst(str_replace('_', ' ', $control)); ?> Icon</label>
                                <select class="icon-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[<?php echo esc_attr($field); ?>]" id="<?php echo esc_attr($field); ?>_<?php echo esc_attr($selected_template); ?>">
                                    <?php foreach ($icons as $class => $label) : ?>
                                        <option value="<?php echo esc_attr($class); ?>" <?php selected($value, $class); ?>><?php echo esc_html($label) . ' (' . $class . ')'; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Waveform Styling -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="card-title">Waveform Styling</h3>
                        <p>Adjust the waveform display settings.</p>
                        <?php
                        $wave_color = $options['wave_color'] ?? '';
                        $progress_color = $options['progress_color'] ?? '';
                        $cursor_color = $options['cursor_color'] ?? '';
                        $cursor_width = $options['cursor_width'] ?? '1';
                        $bar_width = $options['bar_width'] ?? '';
                        $bar_gap = $options['bar_gap'] ?? '1';
                        $wave_height = $options['wave_height'] ?? '';
                        ?>
                        <div class="mb-3">
                            <label for="wave_color_<?php echo esc_attr($selected_template); ?>">Wave Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[wave_color]" id="wave_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($wave_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($wave_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="progress_color_<?php echo esc_attr($selected_template); ?>">Progress Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[progress_color]" id="progress_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($progress_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($progress_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cursor_color_<?php echo esc_attr($selected_template); ?>">Cursor Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="color-picker" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[cursor_color]" id="cursor_color_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($cursor_color); ?>">
                                <input type="text" class="hex-code" value="<?php echo esc_attr($cursor_color); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cursor_width_<?php echo esc_attr($selected_template); ?>">Cursor Width (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[cursor_width]" id="cursor_width_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($cursor_width); ?>" placeholder="e.g., 1">
                        </div>
                        <div class="mb-3">
                            <label for="bar_width_<?php echo esc_attr($selected_template); ?>">Bar Width (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[bar_width]" id="bar_width_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($bar_width); ?>" placeholder="e.g., 2">
                        </div>
                        <div class="mb-3">
                            <label for="bar_gap_<?php echo esc_attr($selected_template); ?>">Bar Gap (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[bar_gap]" id="bar_gap_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($bar_gap); ?>" placeholder="e.g., 1">
                        </div>
                        <div class="mb-3">
                            <label for="wave_height_<?php echo esc_attr($selected_template); ?>">Wave Height (px)</label>
                            <input type="number" class="form-control" name="brmedia_template_options_<?php echo esc_attr($selected_template); ?>[wave_height]" id="wave_height_<?php echo esc_attr($selected_template); ?>" value="<?php echo esc_attr($wave_height); ?>" placeholder="e.g., 100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php submit_button('Save Changes', 'primary btn-lg'); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Sync color picker with hex code
    $('.color-picker').on('input', function() {
        var hexCodeInput = $(this).next('.hex-code');
        hexCodeInput.val(this.value);
    });
    $('.hex-code').on('change', function() {
        var colorPicker = $(this).prev('.color-picker');
        colorPicker.val(this.value);
    });

    // Initialize Select2 for icon pickers with icon preview (requires Select2 library)
    $('.icon-picker').select2({
        templateResult: function(data) {
            if (!data.id) return data.text;
            return $('<span><i class="' + data.id + '"></i> ' + data.text + '</span>');
        },
        templateSelection: function(data) {
            if (!data.id) return data.text;
            return $('<span><i class="' + data.id + '"></i> ' + data.text + '</span>');
        }
    });

    // Toggle marquee settings visibility
    function toggleMarqueeSettings() {
        var marqueeChecked = $('#enable_marquee_<?php echo esc_js($selected_template); ?>').is(':checked');
        if (marqueeChecked) {
            $('#marquee_settings_<?php echo esc_js($selected_template); ?>').show();
        } else {
            $('#marquee_settings_<?php echo esc_js($selected_template); ?>').hide();
        }
    }
    $('#enable_marquee_<?php echo esc_js($selected_template); ?>').on('change', toggleMarqueeSettings);
    toggleMarqueeSettings();

    // Toggle tracklist settings visibility
    function toggleTracklistSettings() {
        var tracklistChecked = $('#enable_tracklist_<?php echo esc_js($selected_template); ?>').is(':checked');
        if (tracklistChecked) {
            $('#tracklist_background_color_<?php echo esc_js($selected_template); ?>').show();
            $('#tracklist_text_color_<?php echo esc_js($selected_template); ?>').show();
            $('#tracklist_time_text_color_<?php echo esc_js($selected_template); ?>').show();
            $('#tracklist_box_size_<?php echo esc_js($selected_template); ?>').show();
        } else {
            $('#tracklist_background_color_<?php echo esc_js($selected_template); ?>').hide();
            $('#tracklist_text_color_<?php echo esc_js($selected_template); ?>').hide();
            $('#tracklist_time_text_color_<?php echo esc_js($selected_template); ?>').hide();
            $('#tracklist_box_size_<?php echo esc_js($selected_template); ?>').hide();
        }
    }
    $('#enable_tracklist_<?php echo esc_js($selected_template); ?>').on('change', toggleTracklistSettings);
    toggleTracklistSettings();

    // Toggle cover image size visibility
    function toggleCoverImageSettings() {
        var coverImageChecked = $('#enable_cover_image_<?php echo esc_js($selected_template); ?>').is(':checked');
        if (coverImageChecked) {
            $('#cover_image_size_<?php echo esc_js($selected_template); ?>').show();
        } else {
            $('#cover_image_size_<?php echo esc_js($selected_template); ?>').hide();
        }
    }
    $('#enable_cover_image_<?php echo esc_js($selected_template); ?>').on('change', toggleCoverImageSettings);
    toggleCoverImageSettings();
});
</script>