<?php
/**
 * BRMedia Shortcode Manager
 * Handles shortcode generation, listing, and management
 */

class BRMedia_Shortcode_Manager {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_brmedia_generate_shortcode', array($this, 'ajax_generate_shortcode'));
        add_action('wp_ajax_brmedia_copy_shortcode', array($this, 'ajax_copy_shortcode'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function add_admin_menu() {
        add_submenu_page(
            'brmedia-dashboard',
            __('Shortcode Manager', 'brmedia'),
            __('Shortcode Manager', 'brmedia'),
            'manage_options',
            'brmedia-shortcodes',
            array($this, 'render_admin_page')
        );
    }

    public function register_settings() {
        register_setting('brmedia_shortcodes', 'brmedia_shortcode_settings');
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'brmedia_page_brmedia-shortcodes') {
            return;
        }

        wp_enqueue_style(
            'brmedia-shortcode-manager',
            BRMEDIA_URL . 'assets/css/shortcode-manager.css',
            array(),
            BRMEDIA_VERSION
        );

        wp_enqueue_script(
            'brmedia-shortcode-manager',
            BRMEDIA_URL . 'assets/js/shortcode-manager.js',
            array('jquery', 'clipboard'),
            BRMEDIA_VERSION,
            true
        );

        wp_localize_script('brmedia-shortcode-manager', 'BRMediaShortcode', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brmedia_shortcode_nonce'),
            'copied_text' => __('Copied!', 'brmedia'),
            'copy_text' => __('Copy', 'brmedia'),
            'error_text' => __('Error generating shortcode', 'brmedia')
        ));
    }

    public function render_admin_page() {
        $music_tracks = $this->get_music_tracks();
        $videos = $this->get_videos();
        ?>
        <div class="wrap brmedia-shortcode-manager">
            <h1>
                <i class="fas fa-code"></i> 
                <?php _e('BRMedia Shortcode Manager', 'brmedia'); ?>
            </h1>

            <div class="brmedia-shortcode-container">
                <div class="brmedia-shortcode-generator">
                    <h2><?php _e('Shortcode Generator', 'brmedia'); ?></h2>
                    
                    <div class="brmedia-generator-form">
                        <div class="brmedia-form-group">
                            <label for="brmedia-sc-type"><?php _e('Media Type', 'brmedia'); ?></label>
                            <select id="brmedia-sc-type" class="brmedia-form-control">
                                <option value="music"><?php _e('Music Track', 'brmedia'); ?></option>
                                <option value="video"><?php _e('Video', 'brmedia'); ?></option>
                            </select>
                        </div>

                        <div class="brmedia-form-group">
                            <label for="brmedia-sc-item"><?php _e('Select Item', 'brmedia'); ?></label>
                            <select id="brmedia-sc-item" class="brmedia-form-control">
                                <?php foreach ($music_tracks as $track) : ?>
                                    <option value="<?php echo $track->ID; ?>" data-type="music">
                                        <?php echo esc_html($track->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php foreach ($videos as $video) : ?>
                                    <option value="<?php echo $video->ID; ?>" data-type="video">
                                        <?php echo esc_html($video->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="brmedia-form-group" id="brmedia-sc-template-container">
                            <label for="brmedia-sc-template"><?php _e('Player Template', 'brmedia'); ?></label>
                            <select id="brmedia-sc-template" class="brmedia-form-control">
                                <option value="stylish"><?php _e('Stylish', 'brmedia'); ?></option>
                                <option value="waveform"><?php _e('Waveform', 'brmedia'); ?></option>
                                <option value="visualization"><?php _e('Visualization', 'brmedia'); ?></option>
                            </select>
                        </div>

                        <div class="brmedia-form-group brmedia-switch-group">
                            <label><?php _e('Autoplay', 'brmedia'); ?></label>
                            <label class="brmedia-switch">
                                <input type="checkbox" id="brmedia-sc-autoplay">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="brmedia-form-group brmedia-switch-group" id="brmedia-sc-controls-container">
                            <label><?php _e('Show Controls', 'brmedia'); ?></label>
                            <label class="brmedia-switch">
                                <input type="checkbox" id="brmedia-sc-controls" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <button id="brmedia-generate-sc" class="button button-primary">
                            <i class="fas fa-bolt"></i> <?php _e('Generate Shortcode', 'brmedia'); ?>
                        </button>
                    </div>

                    <div class="brmedia-generator-result">
                        <h3><?php _e('Your Shortcode:', 'brmedia'); ?></h3>
                        <div class="brmedia-result-box">
                            <code id="brmedia-generated-sc"></code>
                            <button id="brmedia-copy-sc" class="button">
                                <i class="fas fa-copy"></i> <span><?php _e('Copy', 'brmedia'); ?></span>
                            </button>
                        </div>
                        <p class="description">
                            <?php _e('Paste this shortcode into any page, post, or widget to display your media.', 'brmedia'); ?>
                        </p>
                    </div>
                </div>

                <div class="brmedia-shortcode-list">
                    <h2><?php _e('Available Shortcodes', 'brmedia'); ?></h2>
                    
                    <div class="brmedia-sc-tabs">
                        <button class="brmedia-sc-tab active" data-tab="music"><?php _e('Music', 'brmedia'); ?></button>
                        <button class="brmedia-sc-tab" data-tab="video"><?php _e('Videos', 'brmedia'); ?></button>
                    </div>

                    <div class="brmedia-sc-tab-content active" data-tab-content="music">
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Track', 'brmedia'); ?></th>
                                    <th><?php _e('Shortcode', 'brmedia'); ?></th>
                                    <th><?php _e('Actions', 'brmedia'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($music_tracks as $track) : ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo esc_html($track->post_title); ?></strong>
                                        </td>
                                        <td>
                                            <code class="brmedia-sc-code">[brmedia_music id="<?php echo $track->ID; ?>"]</code>
                                        </td>
                                        <td>
                                            <button class="button brmedia-copy-sc" data-sc='[brmedia_music id="<?php echo $track->ID; ?>"]'>
                                                <i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>
                                            </button>
                                            <a href="<?php echo get_edit_post_link($track->ID); ?>" class="button">
                                                <i class="fas fa-edit"></i> <?php _e('Edit', 'brmedia'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="brmedia-sc-tab-content" data-tab-content="video">
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Video', 'brmedia'); ?></th>
                                    <th><?php _e('Shortcode', 'brmedia'); ?></th>
                                    <th><?php _e('Actions', 'brmedia'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($videos as $video) : ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo esc_html($video->post_title); ?></strong>
                                        </td>
                                        <td>
                                            <code class="brmedia-sc-code">[brmedia_video id="<?php echo $video->ID; ?>"]</code>
                                        </td>
                                        <td>
                                            <button class="button brmedia-copy-sc" data-sc='[brmedia_video id="<?php echo $video->ID; ?>"]'>
                                                <i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>
                                            </button>
                                            <a href="<?php echo get_edit_post_link($video->ID); ?>" class="button">
                                                <i class="fas fa-edit"></i> <?php _e('Edit', 'brmedia'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function ajax_generate_shortcode() {
        check_ajax_referer('brmedia_shortcode_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'music';
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $template = isset($_POST['template']) ? sanitize_text_field($_POST['template']) : 'stylish';
        $autoplay = isset($_POST['autoplay']) ? sanitize_text_field($_POST['autoplay']) : 'false';
        $controls = isset($_POST['controls']) ? sanitize_text_field($_POST['controls']) : 'true';

        if (!$id) {
            wp_send_json_error(__('Invalid media ID', 'brmedia'));
        }

        $shortcode = "[brmedia_{$type} id=\"{$id}\"";

        if ($type === 'music') {
            $shortcode .= " template=\"{$template}\"";
        } else {
            $shortcode .= " controls=\"{$controls}\"";
        }

        if ($autoplay === 'true') {
            $shortcode .= ' autoplay="true"';
        }

        $shortcode .= "]";

        wp_send_json_success(array(
            'shortcode' => $shortcode,
            'preview' => $this->get_shortcode_preview($type, $id)
        ));
    }

    public function ajax_copy_shortcode() {
        check_ajax_referer('brmedia_shortcode_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $shortcode = isset($_POST['shortcode']) ? wp_unslash($_POST['shortcode']) : '';
        
        if (empty($shortcode)) {
            wp_send_json_error(__('Empty shortcode', 'brmedia'));
        }

        wp_send_json_success(array(
            'message' => __('Shortcode copied to clipboard', 'brmedia')
        ));
    }

    private function get_music_tracks() {
        return get_posts(array(
            'post_type' => 'brmedia_music',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        ));
    }

    private function get_videos() {
        return get_posts(array(
            'post_type' => 'brmedia_video',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        ));
    }

    private function get_shortcode_preview($type, $id) {
        ob_start();
        if ($type === 'music') {
            echo do_shortcode("[brmedia_music id=\"{$id}\" preview=\"true\"]");
        } else {
            echo do_shortcode("[brmedia_video id=\"{$id}\" preview=\"true\"]");
        }
        return ob_get_clean();
    }
}