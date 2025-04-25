<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Get the services array
global $services;

?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">Music Import Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('brmedia_import_settings');
        global $wp_settings_sections, $wp_settings_fields;
        $page = 'brmedia-import-settings';
        
        if (!isset($wp_settings_sections[$page])) {
            echo '<p>No settings sections found.</p>';
            return;
        }

        echo '<div class="row">'; // Start grid layout

        // Loop through services instead of sections directly
        foreach ($services as $key => $service) {
            $section_id = 'brmedia_import_' . $key; // Construct expected section ID
            if (isset($wp_settings_sections[$page][$section_id])) {
                $icon_url = plugins_url('assets/icons/' . $service['icon'], BRMEDIA_PLUGIN_FILE);
                $bg_color = $service['color'];
                $text_color = (hexdec(substr($bg_color, 1, 2)) + hexdec(substr($bg_color, 3, 2)) + hexdec(substr($bg_color, 5, 2)) > 382) ? '#000000' : '#FFFFFF';

                // Start column for card
                echo '<div class="col-12 col-md-4 mb-4">';
                echo '<div class="card shadow-sm border-0">';
                echo '<div class="card-header" style="background-color: ' . esc_attr($bg_color) . '; color: ' . esc_attr($text_color) . ';">';
                echo '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($service['name']) . '" style="width: 50px; height: 50px; margin-right: 10px;">';
                echo '<h5 style="display: inline-block; color: ' . esc_attr($bg_color) . ';">' . esc_html($service['name']) . '</h5>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<p>' . esc_html($service['description']) . '</p>';
                echo '<a href="' . esc_url($service['website']) . '" target="_blank" class="btn btn-primary btn-sm">Brand Website</a><br>';
                if (!empty($service['api'])) {
                    echo '<a href="' . esc_url($service['api']) . '" target="_blank" class="btn btn-secondary btn-sm mt-2">API Documentation</a><br>';
                }
                echo '<table class="form-table mt-3">';
                foreach ((array) $wp_settings_fields[$page][$section_id] as $field) {
                    $field_key = $field['args']['key'] ?? '';
                    $field_bg_color = $bg_color; // Use service color for field rows
                    $field_text_color = $text_color;
                    echo '<tr style="background-color: ' . esc_attr($field_bg_color) . '; color: ' . esc_attr($field_text_color) . ';">';
                    if (!empty($field['args']['label_for'])) {
                        echo '<th scope="row"><label for="' . esc_attr($field['args']['label_for']) . '" style="color: ' . esc_attr($field_text_color) . ';">' . esc_html($field['title']) . '</label></th>';
                    } else {
                        echo '<th scope="row" style="color: ' . esc_attr($field_text_color) . ';">' . esc_html($field['title']) . '</th>';
                    }
                    echo '<td>';
                    call_user_func($field['callback'], $field['args']);
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
                echo '</div>';
                echo '</div>'; // End column
            }
        }

        echo '</div>'; // End row

        submit_button('Save Changes', 'primary btn-lg');
        ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.toggle-service').on('change', function() {
        var service = $(this).data('service');
        $('tr[class*="api-field-' + service + '"]').toggle(this.checked);
    });

    $('.toggle-service').each(function() {
        var service = $(this).data('service');
        $('tr[class*="api-field-' + service + '"]').toggle(this.checked);
    });
});
</script>