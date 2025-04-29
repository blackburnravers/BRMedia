<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

class BRMedia_Import {
    private $services = [
        'soundcloud' => ['name' => 'SoundCloud', 'icon' => 'soundcloud.svg', 'color' => '#FF5500'],
        'audiomack' => ['name' => 'Audiomack', 'icon' => 'audiomack.svg', 'color' => '#FFA200'],
        'bandcamp' => ['name' => 'Bandcamp', 'icon' => 'bandcamp.svg', 'color' => '#629AA9'],
        'reverbnation' => ['name' => 'ReverbNation', 'icon' => 'reverbnation.svg', 'color' => '#ED36DB'],
        'mixcloud' => ['name' => 'Mixcloud', 'icon' => 'mixcloud.svg', 'color' => '#1D2F5D'],
        'hearthis' => ['name' => 'HearThis.at', 'icon' => 'hearthis.svg', 'color' => '#A12015'],
        'youtube-music' => ['name' => 'YouTube Music', 'icon' => 'youtube-music.svg', 'color' => '#FF0000'],
        'audius' => ['name' => 'Audius', 'icon' => 'audius.svg', 'color' => '#CC0FE0'],
        'house-mixes' => ['name' => 'House-Mixes.com', 'icon' => 'house-mixes.svg', 'color' => '#000000'], 
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('wp_ajax_brmedia_import_track', [$this, 'handle_import']);
    }

    public function add_menu() {
        add_submenu_page(
            'brmedia',
            'Import Media',
            'Import Media',
            'manage_options',
            'brmedia-import',
            [$this, 'import_page']
        );
    }

    public function import_page() {
    $options = get_option('brmedia_import_settings', []);
    error_log('BRMedia Import Settings: ' . print_r($options, true)); // Add this line
    $enabled_services = [];
    foreach ($this->services as $key => $service) {
        if (!empty($options['import_' . $key . '_enabled'])) {
            $enabled_services[$key] = $service;
        }
    }
        $icon_dir = plugin_dir_url(__FILE__) . '../assets/icons/';
        ?>
        <div class="container-fluid mt-4">
            <h1 class="mb-4">Import Media</h1>
            <?php if (empty($enabled_services)): ?>
                <div class="alert alert-warning">
                    No media import services are enabled. Please configure them in <a href="<?php echo admin_url('admin.php?page=brmedia-import-settings'); ?>">Import Settings</a>.
                </div>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h3 class="card-title">Import Tracks</h3>
                                <form id="import-form">
                                    <div class="mb-3">
                                        <label for="service-select" class="form-label">Select Service</label>
                                        <select class="form-select" id="service-select" name="service">
                                            <?php foreach ($enabled_services as $key => $service): ?>
                                                <option value="<?php echo esc_attr($key); ?>" data-icon="<?php echo esc_url($icon_dir . $service['icon']); ?>" data-color="<?php echo esc_attr($service['color']); ?>">
                                                    <?php echo esc_html($service['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="track-urls" class="form-label">Track URLs (one per line)</label>
                                        <textarea class="form-control" id="track-urls" name="track_urls" rows="5" placeholder="e.g., https://soundcloud.com/artist/track1&#10;https://soundcloud.com/artist/track2" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg">Import Tracks</button>
                                </form>
                                <div id="import-status" class="mt-3"></div>
                                <div id="import-results" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <style>
            #import-status .alert, #import-results .card {
                margin-bottom: 10px;
            }
            #import-results .card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        </style>
        <script>
        jQuery(document).ready(function($) {
            $('#service-select').select2({
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    var icon = $(data.element).data('icon');
                    return $('<span><img src="' + icon + '" style="width: 20px; height: 20px; margin-right: 10px;" />' + data.text + '</span>');
                },
                templateSelection: function(data) {
                    if (!data.id) return data.text;
                    var icon = $(data.element).data('icon');
                    return $('<span><img src="' + icon + '" style="width: 20px; height: 20px; margin-right: 10px;" />' + data.text + '</span>');
                }
            });

            $('#import-form').on('submit', function(e) {
                e.preventDefault();
                var $status = $('#import-status');
                var $results = $('#import-results');
                $status.html('<div class="alert alert-info">Preparing to import...</div>');
                $results.empty();

                var service = $('#service-select').val();
                var urls = $('#track-urls').val().split('\n').filter(url => url.trim() !== '');
                var serviceColor = $('#service-select option:selected').data('color');
                var importedCount = 0;

                function importNextTrack(index) {
                    if (index >= urls.length) {
                        $status.html('<div class="alert alert-success">Import completed! Imported ' + importedCount + ' out of ' + urls.length + ' tracks.</div>');
                        return;
                    }

                    var trackUrl = urls[index].trim();
                    $status.html('<div class="alert alert-info">Importing track ' + (index + 1) + ' of ' + urls.length + ': ' + trackUrl + '</div>');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'brmedia_import_track',
                            service: service,
                            track_url: trackUrl
                        },
                        success: function(response) {
                            if (response.success) {
                                importedCount++;
                                var resultCard = '<div class="card shadow-sm border-0">' +
                                    '<div class="card-header" style="background-color: ' + serviceColor + '; color: white;">' +
                                        '<span>Imported: ' + response.data.title + '</span>' +
                                        '<a href="' + response.data.edit_link + '" class="btn btn-sm btn-light">View Draft</a>' +
                                    '</div>' +
                                    '<div class="card-body">' +
                                        '<p><strong>Artist:</strong> ' + response.data.artist + '</p>' +
                                        '<p><strong>URL:</strong> ' + trackUrl + '</p>' +
                                    '</div>' +
                                '</div>';
                                $results.append(resultCard);
                            } else {
                                var errorCard = '<div class="card shadow-sm border-0">' +
                                    '<div class="card-header bg-danger text-white">' +
                                        'Failed to Import' +
                                    '</div>' +
                                    '<div class="card-body">' +
                                        '<p><strong>URL:</strong> ' + trackUrl + '</p>' +
                                        '<p><strong>Error:</strong> ' + response.data.message + '</p>' +
                                    '</div>' +
                                '</div>';
                                $results.append(errorCard);
                            }
                            importNextTrack(index + 1);
                        },
                        error: function() {
                            var errorCard = '<div class="card shadow-sm border-0">' +
                                '<div class="card-header bg-danger text-white">' +
                                    'Failed to Import' +
                                '</div>' +
                                '<div class="card-body">' +
                                    '<p><strong>URL:</strong> ' + trackUrl + '</p>' +
                                    '<p><strong>Error:</strong> An error occurred. Please try again.</p>' +
                                '</div>' +
                            '</div>';
                            $results.append(errorCard);
                            importNextTrack(index + 1);
                        }
                    });
                }

                importNextTrack(0);
            });
        });
        </script>
        <?php
    }

    public function handle_import() {
        $service = sanitize_text_field($_POST['service']);
        $track_url = esc_url_raw($_POST['track_url']);
        
        // Placeholder for import logic
        // This would use API calls or web scraping to fetch track data
        $result = [
            'title' => 'Sample Track',
            'artist' => 'Sample Artist',
            'post_id' => 123,
            'edit_link' => admin_url('post.php?post=123&action=edit')
        ];

        if ($result) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error(['message' => 'Failed to import track from ' . $service]);
        }
    }
}

new BRMedia_Import();