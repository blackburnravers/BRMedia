<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Metaboxes {

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_metadata']);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'brmedia_track_info',
            __('Track Metadata', 'brmedia'),
            [$this, 'render_metabox'],
            'brmedia_track',
            'normal',
            'high'
        );
    }

    public function render_metabox($post) {
        wp_nonce_field('brmedia_save_metadata', 'brmedia_metadata_nonce');

        $fields = [
            'bpm' => [
                'label' => __('BPM', 'brmedia'),
                'type' => 'number',
                'step' => 0.1
            ],
            'key' => [
                'label' => __('Musical Key', 'brmedia'),
                'type' => 'select',
                'options' => $this->get_key_options()
            ],
            'artist' => [
                'label' => __('Artist', 'brmedia'),
                'type' => 'text'
            ]
        ];

        echo '<div class="brmedia-metabox-grid">';
        foreach ($fields as $key => $config) {
            $value = get_post_meta($post->ID, "_brmedia_{$key}", true);
            echo $this->render_field($key, $config, $value);
        }
        echo '</div>';
    }

    private function get_key_options() {
        return [
            'C' => 'C Major',
            'Cm' => 'C Minor',
            'C#' => 'C# Major',
            // ... full key list
        ];
    }

    private function render_field($name, $config, $value) {
        ob_start(); ?>
        <div class="brmedia-field">
            <label for="brmedia_<?= esc_attr($name) ?>">
                <?= esc_html($config['label']) ?>
            </label>
            
            <?php if ($config['type'] === 'select') : ?>
                <select name="brmedia_<?= esc_attr($name) ?>" 
                        id="brmedia_<?= esc_attr($name) ?>">
                    <?php foreach ($config['options'] as $val => $label) : ?>
                        <option value="<?= esc_attr($val) ?>" 
                            <?= selected($value, $val, false) ?>>
                            <?= esc_html($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="<?= esc_attr($config['type']) ?>" 
                       id="brmedia_<?= esc_attr($name) ?>"
                       name="brmedia_<?= esc_attr($name) ?>"
                       value="<?= esc_attr($value) ?>"
                       <?= isset($config['step']) ? "step='{$config['step']}'" : '' ?>
                       class="widefat">
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function save_metadata($post_id) {
        if (!isset($_POST['brmedia_metadata_nonce']) || 
            !wp_verify_nonce($_POST['brmedia_metadata_nonce'], 'brmedia_save_metadata')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $fields = ['bpm', 'key', 'artist', 'album'];
        foreach ($fields as $field) {
            if (isset($_POST["brmedia_{$field}"])) {
                update_post_meta(
                    $post_id,
                    "_brmedia_{$field}",
                    sanitize_text_field($_POST["brmedia_{$field}"])
                );
            }
        }
    }
}

new BRMedia_Metaboxes();