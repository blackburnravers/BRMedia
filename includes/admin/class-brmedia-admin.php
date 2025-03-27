<?php
/**
 * BRMedia Admin Class
 * Handles all admin functionality
 */

class BRMedia_Admin {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Setup admin menu
        add_action('admin_menu', array($this, 'setup_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Load admin assets
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Add plugin action links
        add_filter('plugin_action_links_' . BRMEDIA_BASENAME, array($this, 'add_plugin_links'));
        
        // Initialize admin components
        $this->init_components();
    }

    public function setup_admin_menu() {
        // Main BRMedia Menu
        add_menu_page(
            __('BRMedia Dashboard', 'brmedia'),
            __('BRMedia', 'brmedia'),
            'manage_options',
            'brmedia-dashboard',
            array($this, 'render_dashboard_page'),
            'dashicons-admin-media',
            6
        );

        // Submenu Items
        $submenu_pages = array(
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Music', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'edit.php?post_type=brmedia_music',
                'function' => ''
            ),
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Add New Music', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'post-new.php?post_type=brmedia_music',
                'function' => ''
            ),
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Videos', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'edit.php?post_type=brmedia_video',
                'function' => ''
            ),
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Add New Video', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'post-new.php?post_type=brmedia_video',
                'function' => ''
            ),
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Shortcode Manager', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'brmedia-shortcodes',
                'function' => array($this, 'render_shortcodes_page')
            ),
            array(
                'parent' => 'brmedia-dashboard',
                'title'  => __('Settings', 'brmedia'),
                'cap'    => 'manage_options',
                'slug'   => 'brmedia-settings',
                'function' => array($this, 'render_settings_page')
            )
        );

        foreach ($submenu_pages as $page) {
            add_submenu_page(
                $page['parent'],
                $page['title'],
                $page['title'],
                $page['cap'],
                $page['slug'],
                $page['function']
            );
        }
    }

    public function register_settings() {
        // General Settings
        register_setting('brmedia_settings_general', 'brmedia_default_volume');
        register_setting('brmedia_settings_general', 'brmedia_enable_airplay');
        register_setting('brmedia_settings_general', 'brmedia_enable_cast');
        
        // Player Settings
        register_setting('brmedia_settings_player', 'brmedia_color_primary');
        register_setting('brmedia_settings_player', 'brmedia_color_secondary');
        register_setting('brmedia_settings_player', 'brmedia_player_skin');
        
        // Social Settings
        register_setting('brmedia_settings_social', 'brmedia_enable_sharing');
        register_setting('brmedia_settings_social', 'brmedia_social_platforms');
        
        // Advanced Settings
        register_setting('brmedia_settings_advanced', 'brmedia_cleanup_on_uninstall');
        register_setting('brmedia_settings_advanced', 'brmedia_debug_mode');
        
        // Template Settings
        register_setting('brmedia_settings_templates', 'brmedia_template_settings');
    }

    public function enqueue_admin_assets($hook) {
        // Only load on BRMedia pages
        if (strpos($hook, 'brmedia') !== false) {
            // CSS
            wp_enqueue_style(
                'brmedia-admin',
                BRMEDIA_URL . 'assets/css/admin.css',
                array(),
                BRMEDIA_VERSION
            );
            
            // Font Awesome
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                array(),
                '6.4.0'
            );
            
            // JS
            wp_enqueue_script(
                'brmedia-admin',
                BRMEDIA_URL . 'assets/js/admin.js',
                array('jquery', 'wp-color-picker'),
                BRMEDIA_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('brmedia-admin', 'BRMediaAdmin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('brmedia_admin_nonce'),
                'copied_text' => __('Copied!', 'brmedia'),
                'copy_text' => __('Copy', 'brmedia')
            ));
            
            // Color picker
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        }
    }

    public function add_plugin_links($links) {
        $action_links = array(
            'dashboard' => sprintf(
                '<a href="%s" style="font-weight:700;">%s</a>',
                admin_url('admin.php?page=brmedia-dashboard'),
                __('Dashboard', 'brmedia')
            ),
            'settings' => sprintf(
                '<a href="%s">%s</a>',
                admin_url('admin.php?page=brmedia-settings'),
                __('Settings', 'brmedia')
            )
        );
        
        return array_merge($action_links, $links);
    }

    private function init_components() {
        // Load admin components
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-dashboard.php';
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-metaboxes.php';
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-media-upload.php';
        
        // Initialize components
        BRMedia_Dashboard::instance();
        BRMedia_Metaboxes::instance();
        BRMedia_Media_Upload::instance();
    }

    public function render_dashboard_page() {
        include BRMEDIA_PATH . 'includes/admin/views/dashboard.php';
    }

    public function render_shortcodes_page() {
        include BRMEDIA_PATH . 'includes/admin/views/shortcode-manager.php';
    }

    public function render_settings_page() {
        include BRMEDIA_PATH . 'includes/admin/views/settings.php';
    }

    /**
     * Add custom admin notices
     */
    public function admin_notices() {
        if (isset($_GET['brmedia_notice'])) {
            $notice = sanitize_text_field($_GET['brmedia_notice']);
            switch ($notice) {
                case 'settings_updated':
                    echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'brmedia') . '</p></div>';
                    break;
                case 'shortcode_copied':
                    echo '<div class="notice notice-success is-dismissible"><p>' . __('Shortcode copied to clipboard!', 'brmedia') . '</p></div>';
                    break;
            }
        }
    }
}