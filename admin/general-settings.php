<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>BRMedia General Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('brmedia_general_settings');
        
        // Get all sections for the 'brmedia-general-settings' page
        global $wp_settings_sections, $wp_settings_fields;
        $page = 'brmedia-general-settings';
        
        if (!isset($wp_settings_sections[$page])) {
            echo '<p>No settings sections found.</p>';
            return;
        }

        foreach ((array) $wp_settings_sections[$page] as $section_id => $section) {
            echo '<div class="card mb-4 brmedia-section-card" id="' . esc_attr($section_id) . '">';
            echo '<div class="card-body">';
            echo '<h2 class="card-title">' . esc_html($section['title']) . '</h2>';
            
            // Render the section callback (description)
            if (!empty($section['callback'])) {
                call_user_func($section['callback'], $section);
            }
            
            // Render the fields for this section
            if (!isset($wp_settings_fields[$page][$section_id])) {
                continue;
            }
            
            echo '<table class="form-table">';
            foreach ((array) $wp_settings_fields[$page][$section_id] as $field) {
                echo '<tr>';
                if (!empty($field['args']['label_for'])) {
                    echo '<th scope="row"><label for="' . esc_attr($field['args']['label_for']) . '">' . esc_html($field['title']) . '</label></th>';
                } else {
                    echo '<th scope="row">' . esc_html($field['title']) . '</th>';
                }
                echo '<td>';
                call_user_func($field['callback'], $field['args']);
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            echo '</div>';
            echo '</div>';
        }
        ?>
        <?php submit_button(); ?>
    </form>
</div>