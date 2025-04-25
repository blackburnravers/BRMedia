<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_maintenance_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Maintenance</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="card-title">Maintenance Settings</h3>
                            <form method="post" action="options.php">
                                <?php
                                settings_fields('brmedia_maintenance_settings');
                                do_settings_sections('brmedia-maintenance');
                                submit_button('Save Changes');
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="card-title">Template Reset</h3>
                            <p>Reset all template settings to their default values.</p>
                            <button id="mass-reset-templates" class="button button-primary">Mass Reset Templates</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="card-title">Waveform Management</h3>
                            <p>Manage cached waveform data for audio files.</p>
                            <button id="mass-delete-waveforms" class="button button-secondary">Mass Delete Waveforms</button>
                            <button id="mass-generate-unprocessed" class="button button-secondary">Generate Unprocessed Waveforms</button>
                            <button id="mass-generate-all" class="button button-secondary">Generate All Waveforms</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var nonce = '<?php echo wp_create_nonce('brmedia_maintenance'); ?>';
            $('#mass-reset-templates').on('click', function() {
                if (confirm('Are you sure you want to reset all templates to default settings?')) {
                    $.post(ajaxurl, {
                        action: 'brmedia_mass_reset_templates',
                        nonce: nonce
                    }, function(response) {
                        if (response.success) {
                            alert('All templates reset to defaults.');
                        } else {
                            alert('Error: ' + response.data);
                        }
                    });
                }
            });
            $('#mass-delete-waveforms').on('click', function() {
                if (confirm('Are you sure you want to delete all cached waveform data?')) {
                    $.post(ajaxurl, {
                        action: 'brmedia_mass_delete_waveforms',
                        nonce: nonce
                    }, function(response) {
                        if (response.success) {
                            alert('All waveforms deleted.');
                        } else {
                            alert('Error: ' + response.data);
                        }
                    });
                }
            });
            $('#mass-generate-unprocessed').on('click', function() {
                if (confirm('Are you sure you want to generate waveforms for unprocessed audio files?')) {
                    $.post(ajaxurl, {
                        action: 'brmedia_mass_generate_unprocessed_waveforms',
                        nonce: nonce
                    }, function(response) {
                        if (response.success) {
                            if (response.data.process_id) {
                                window.location.href = '<?php echo admin_url('admin.php?page=brmedia-waveform-progress&process_id='); ?>' + response.data.process_id;
                            } else {
                                alert('No unprocessed files to generate.');
                            }
                        } else {
                            alert('Error: ' + response.data);
                        }
                    });
                }
            });
            $('#mass-generate-all').on('click', function() {
                if (confirm('Are you sure you want to generate waveforms for all audio files, including regenerating existing ones?')) {
                    $.post(ajaxurl, {
                        action: 'brmedia_mass_generate_all_waveforms',
                        nonce: nonce
                    }, function(response) {
                        if (response.success) {
                            if (response.data.process_id) {
                                window.location.href = '<?php echo admin_url('admin.php?page=brmedia-waveform-progress&process_id='); ?>' + response.data.process_id;
                            } else {
                                alert('No audio files to generate.');
                            }
                        } else {
                            alert('Error: ' + response.data);
                        }
                    });
                }
            });
        });
    </script>
    <?php
}