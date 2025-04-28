<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Renders the Maintenance admin page
 */
function brmedia_render_maintenance_page() {
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
                <!-- Master Reset Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="card-title">Reset All Templates</h3>
                            <p>Click below to reset all template settings to defaults.</p>
                            <button id="reset-all-templates" class="button button-error">Reset All Templates</button>
                            <p style="color: red;">Warning: This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}