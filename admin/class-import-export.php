<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Import_Export {

    public function __construct() {
        add_action('admin_post_brmedia_export_settings', [$this, 'export_settings']);
        add_action('admin_post_brmedia_import_settings', [$this, 'import_settings']);
    }

    public function export_settings() {
        check_admin_referer('brmedia_export_nonce');

        $settings = [
            'general' => get_option('brmedia_general'),
            'templates' => get_option('brmedia_templates'),
            'controls' => get_option('brmedia_controls')
        ];

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="brmedia-settings-' . date('Y-m-d') . '.json"');
        echo json_encode($settings);
        exit;
    }

    public function import_settings() {
        check_admin_referer('brmedia_import_nonce');

        if (empty($_FILES['import_file']['tmp_name'])) {
            wp_die('No file uploaded');
        }

        $json = file_get_contents($_FILES['import_file']['tmp_name']);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_die('Invalid JSON file');
        }

        foreach (['general', 'templates', 'controls'] as $section) {
            if (isset($data[$section])) {
                update_option("brmedia_{$section}", $data[$section]);
            }
        }

        wp_redirect(admin_url('admin.php?page=brmedia-settings&tab=general&imported=1'));
        exit;
    }

    public function render_tools_page() {
        ?>
        <div class="brmedia-tools">
            <div class="card">
                <h2>Export Settings</h2>
                <form method="post" action="<?= admin_url('admin-post.php') ?>">
                    <input type="hidden" name="action" value="brmedia_export_settings">
                    <?php wp_nonce_field('brmedia_export_nonce'); ?>
                    <p>Export all BRMedia settings as a JSON file</p>
                    <button type="submit" class="button button-primary">Export</button>
                </form>
            </div>

            <div class="card">
                <h2>Import Settings</h2>
                <form method="post" enctype="multipart/form-data" 
                      action="<?= admin_url('admin-post.php') ?>">
                    <input type="hidden" name="action" value="brmedia_import_settings">
                    <?php wp_nonce_field('brmedia_import_nonce'); ?>
                    <p>
                        <input type="file" name="import_file" accept=".json">
                    </p>
                    <button type="submit" class="button button-primary">Import</button>
                </form>
            </div>
        </div>
        <?php
    }
}

new BRMedia_Import_Export();