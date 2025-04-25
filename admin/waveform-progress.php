<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_waveform_progress_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get process ID from URL
    $process_id = isset($_GET['process_id']) ? sanitize_text_field($_GET['process_id']) : '';
    if (!$process_id) {
        echo '<div class="error"><p>Invalid process ID.</p></div>';
        return;
    }

    // Add admin notice for navigation
    add_action('admin_notices', function() use ($process_id) {
        $progress = get_transient("brmedia_waveform_progress_$process_id");
        if ($progress) {
            $all_done = !in_array('pending', $progress) && !in_array('processing', $progress);
            $message = $all_done ? 'Waveform generation completed.' : 'Waveform generation is in progress. <a href="' . admin_url('admin.php?page=brmedia-waveform-progress&process_id=' . $process_id) . '">View Progress</a>';
            echo '<div class="notice notice-info is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    });

    // Start output
    ?>
    <div class="wrap">
        <h1>Waveform Generation Progress</h1>
        <div id="progress-list">
            <p>Loading progress...</p>
        </div>
        <button id="cancel-process" class="button">Cancel</button>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var processId = '<?php echo esc_js($process_id); ?>';
            var nonce = '<?php echo wp_create_nonce('brmedia_waveform_progress'); ?>';

            function updateProgress() {
                $.post(ajaxurl, {
                    action: 'brmedia_get_waveform_progress',
                    process_id: processId,
                    nonce: nonce
                }, function(response) {
                    if (response.success) {
                        var progress = response.data.progress;
                        var html = '<table class="wp-list-table widefat fixed striped"><thead><tr><th>File ID</th><th>Status</th></tr></thead><tbody>';
                        for (var fileId in progress) {
                            html += '<tr><td>' + fileId + '</td><td>' + progress[fileId] + '</td></tr>';
                        }
                        html += '</tbody></table>';
                        $('#progress-list').html(html);
                        var allDone = Object.values(progress).every(function(s) { return s === 'completed' || s === 'failed' || s === 'cancelled'; });
                        if (!allDone) {
                            setTimeout(updateProgress, 5000);
                        } else {
                            alert('Waveform generation completed or cancelled.');
                        }
                    } else {
                        $('#progress-list').html('<p>Error: ' + response.data + '</p>');
                    }
                });
            }

            updateProgress();

            $('#cancel-process').on('click', function() {
                if (confirm('Are you sure you want to cancel waveform generation?')) {
                    $.post(ajaxurl, {
                        action: 'brmedia_cancel_waveform_generation',
                        process_id: processId,
                        nonce: nonce
                    }, function(response) {
                        if (response.success) {
                            alert('Process cancelled.');
                            updateProgress();
                        } else {
                            alert('Error cancelling process: ' + response.data);
                        }
                    });
                }
            });
        });
    </script>
    <?php
}

// AJAX handler to cancel generation
function brmedia_cancel_waveform_generation() {
    check_ajax_referer('brmedia_waveform_progress', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied.');
    }
    $process_id = sanitize_text_field($_POST['process_id']);
    $progress = get_transient("brmedia_waveform_progress_$process_id");
    if ($progress) {
        foreach ($progress as $file_id => $status) {
            if ($status === 'pending' || $status === 'processing') {
                $progress[$file_id] = 'cancelled';
            }
        }
        set_transient("brmedia_waveform_progress_$process_id", $progress, DAY_IN_SECONDS);
        wp_clear_scheduled_hook('brmedia_generate_waveform', [$file_id, $process_id]);
    }
    wp_send_json_success();
}
add_action('wp_ajax_brmedia_cancel_waveform_generation', 'brmedia_cancel_waveform_generation');