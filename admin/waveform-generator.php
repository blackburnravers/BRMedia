<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function brmedia_waveform_generator_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get all audio attachments
    $audio_files = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'audio',
        'posts_per_page' => -1,
    ]);

    // Handle form submission for mass processing
    if (isset($_POST['process_selected']) || isset($_POST['process_all']) || isset($_POST['process_unprocessed'])) {
        check_admin_referer('brmedia_waveform_generator');
        $selected_files = isset($_POST['selected_files']) ? array_map('intval', $_POST['selected_files']) : [];
        if (isset($_POST['process_all'])) {
            $selected_files = wp_list_pluck($audio_files, 'ID');
        } elseif (isset($_POST['process_unprocessed'])) {
            $selected_files = array_filter(wp_list_pluck($audio_files, 'ID'), function($id) {
                return !get_post_meta($id, '_waveform_generated', true);
            });
        }
        if (!empty($selected_files)) {
            // Start waveform generation process
            $process_id = brmedia_start_waveform_generation($selected_files);
            // Redirect to progress page
            wp_redirect(admin_url('admin.php?page=brmedia-waveform-progress&process_id=' . $process_id));
            exit;
        }
    }

    // Start output
    ?>
    <div class="wrap">
        <h1>Waveform Generator</h1>
        <form method="post" action="">
            <?php wp_nonce_field('brmedia_waveform_generator'); ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audio_files as $file): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_files[]" value="<?php echo esc_attr($file->ID); ?>"></td>
                            <td><?php echo esc_html($file->post_title); ?></td>
                            <td>
                                <?php
                                $status = get_post_meta($file->ID, '_waveform_generated', true) ? 'Processed' : 'Not Processed';
                                echo esc_html($status);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <input type="submit" name="process_selected" class="button button-primary" value="Process Selected">
                <input type="submit" name="process_unprocessed" class="button" value="Process Unprocessed">
                <input type="submit" name="process_all" class="button" value="Process All">
            </p>
        </form>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#select-all').on('change', function() {
                $('input[name="selected_files[]"]').prop('checked', this.checked);
            });
        });
    </script>
    <?php
}