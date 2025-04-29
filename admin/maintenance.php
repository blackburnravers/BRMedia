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
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Handle the Reset All Templates button click
            $('#reset-all-templates').on('click', function(e) {
                e.preventDefault();
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to reset all templates to their default settings? This action cannot be undone.')) {
                    // Perform AJAX request
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'brmedia_reset_all_templates',
                            nonce: '<?php echo wp_create_nonce('brmedia_reset_all_templates'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('All templates have been reset to defaults.');
                                location.reload(); // Refresh the page to reflect changes
                            } else {
                                alert('Error: ' + (response.data || 'Unknown error occurred.'));
                            }
                        },
                        error: function() {
                            alert('An error occurred while resetting the templates. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
    <?php
}

/**
 * AJAX handler to reset all templates
 */
function brmedia_reset_all_templates() {
    // Verify nonce for security
    check_ajax_referer('brmedia_reset_all_templates', 'nonce');
    
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    
    // Placeholder for reset logic (replace with your actual reset code)
    // Example: Reset options to defaults
    $reset_success = true; // Assume success for now; update this based on your logic
    
    if ($reset_success) {
        wp_send_json_success('Templates reset successfully.');
    } else {
        wp_send_json_error('Failed to reset templates.');
    }
}
add_action('wp_ajax_brmedia_reset_all_templates', 'brmedia_reset_all_templates');