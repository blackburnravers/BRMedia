<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

// Define extended services array with descriptions, websites, and API links
$services = [
    'soundcloud' => [
        'name' => 'SoundCloud',
        'color' => '#FF5500',
        'icon' => 'soundcloud.svg',
        'description' => 'A platform for sharing and discovering music.',
        'website' => 'https://soundcloud.com',
        'api' => 'https://developers.soundcloud.com',
    ],
    'audiomack' => [
        'name' => 'Audiomack',
        'color' => '#FFA200',
        'icon' => 'audiomack.svg',
        'description' => 'A free music streaming platform for artists.',
        'website' => 'https://www.audiomack.com',
        'api' => 'https://www.audiomack.com/developers',
    ],
    'bandcamp' => [
        'name' => 'Bandcamp',
        'color' => '#629AA9',
        'icon' => 'bandcamp.svg',
        'description' => 'A platform for artists to sell music directly.',
        'website' => 'https://bandcamp.com',
        'api' => 'https://bandcamp.com/developer_documentation',
    ],
    'reverbnation' => [
        'name' => 'ReverbNation',
        'color' => '#00A4A4',
        'icon' => 'reverbnation.svg',
        'description' => 'A platform for musicians to promote and connect.',
        'website' => 'https://www.reverbnation.com',
        'api' => 'https://www.reverbnation.com/api',
    ],
    'mixcloud' => [
        'name' => 'Mixcloud',
        'color' => '#1D2F5D',
        'icon' => 'mixcloud.svg',
        'description' => 'A platform for DJ mixes and podcasts.',
        'website' => 'https://www.mixcloud.com',
        'api' => 'https://www.mixcloud.com/developers/',
    ],
    'hearthis' => [
        'name' => 'HearThis.at',
        'color' => '#00C4B4',
        'icon' => 'hearthis.png',
        'description' => 'A platform for independent musicians to share.',
        'website' => 'https://hearthis.at',
        'api' => '',
    ],
    'youtube-music' => [
        'name' => 'YouTube Music',
        'color' => '#FF0000',
        'icon' => 'youtube-music.svg',
        'description' => 'A music streaming service with videos.',
        'website' => 'https://music.youtube.com',
        'api' => 'https://developers.google.com/youtube/v3',
    ],
    'audius' => [
        'name' => 'Audius',
        'color' => '#CC0FE0',
        'icon' => 'audius.svg',
        'description' => 'A decentralized music streaming platform.',
        'website' => 'https://audius.co',
        'api' => 'https://docs.audius.co/',
    ],
    'drooble' => [
        'name' => 'Drooble',
        'color' => '#00AEEF',
        'icon' => 'drooble.svg', // Corrected from 'dribbble.svg' assuming a typo
        'description' => 'A social network for musicians to collaborate.',
        'website' => 'https://www.drooble.com',
        'api' => '',
    ],
    'house-mixes' => [
        'name' => 'House-Mixes.com',
        'color' => '#2E2E2E',
        'icon' => 'house-mixes.png',
        'description' => 'A platform for house music mixes.',
        'website' => 'https://www.house-mixes.com',
        'api' => '',
    ],
];
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">Music Import Settings</h1>
    <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-gears fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">General Settings</h5>
                    <p class="card-text">Manage all your main settings from in here.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-general-settings'); ?>" class="btn btn-danger">Go to Settings</a>
                </div>
            </div>
        </div>
    
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
                echo '<div class="container-fluid mt-4">'; // Adjusted for full width on mobile
                echo '<div class="card shadow-sm border-0">';
                echo '<div class="card-header" style="background-color: ' . esc_attr($bg_color) . '; color: ' . esc_attr($text_color) . ';">';
                echo '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($service['name']) . '" style="width: 50px; height: 50px; margin-right: 10px;">';
                echo '<h5 style="display: inline-block;">' . esc_html($service['name']) . '</h5>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<p>' . esc_html($service['description']) . '</p>';
                echo '<a href="' . esc_url($service['website']) . '" target="_blank">Brand Website</a><br>';
                if (!empty($service['api'])) {
                    echo '<a href="' . esc_url($service['api']) . '" target="_blank">API Documentation</a><br>';
                }
                echo '<table class="form-table">';
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