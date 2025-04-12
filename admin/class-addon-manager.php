<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Addon_Manager {

    private $registered_addons = [];

    public function __construct() {
        add_action('admin_init', [$this, 'register_core_addons']);
        add_action('wp_ajax_brmedia_toggle_addon', [$this, 'toggle_addon']);
    }

    public function register_core_addons() {
        $this->register_addon([
            'id' => 'video',
            'name' => 'Video Player',
            'description' => '4K video playback with HLS support',
            'icon' => 'fas fa-video',
            'version' => '1.0.0',
            'active' => false
        ]);

        // Additional addons...
    }

    public function register_addon($args) {
        $defaults = [
            'settings_callback' => null,
            'requirements' => []
        ];
        $this->registered_addons[$args['id']] = wp_parse_args($args, $defaults);
    }

    public function toggle_addon() {
        check_ajax_referer('brmedia_addon_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }

        $addon_id = sanitize_key($_POST['addon_id']);
        $status = filter_var($_POST['status'], FILTER_VALIDATE_BOOLEAN);

        if (isset($this->registered_addons[$addon_id])) {
            update_option("brmedia_addon_{$addon_id}_active", $status);
            wp_send_json_success(['new_status' => $status]);
        }

        wp_send_json_error('Addon not found', 404);
    }

    public function render_addons_page() {
        echo '<div class="brmedia-addons-grid">';
        foreach ($this->registered_addons as $id => $addon) {
            $active = get_option("brmedia_addon_{$id}_active", false);
            ?>
            <div class="addon-card <?= $active ? 'active' : '' ?>" data-addon="<?= esc_attr($id) ?>">
                <div class="addon-icon">
                    <i class="<?= esc_attr($addon['icon']) ?>"></i>
                </div>
                <h3><?= esc_html($addon['name']) ?></h3>
                <p><?= esc_html($addon['description']) ?></p>
                <div class="addon-footer">
                    <span class="version">v<?= esc_html($addon['version']) ?></span>
                    <label class="switch">
                        <input type="checkbox" <?= checked($active, true) ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
}

new BRMedia_Addon_Manager();