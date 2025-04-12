<?php
if (!defined('ABSPATH')) exit;

final class BRMedia_Core {

    private static $instance;
    private $components = [];

    private function __construct() {
        $this->define_constants();
        $this->load_dependencies();
        $this->init_hooks();
    }

    public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function define_constants() {
        define('BRMEDIA_VERSION', '1.0.0');
        define('BRMEDIA_PATH', plugin_dir_path(__FILE__));
        define('BRMEDIA_URL', plugin_dir_url(__FILE__));
        define('BRMEDIA_ASSETS_URL', BRMEDIA_URL . 'assets/');
    }

    private function load_dependencies() {
        require_once BRMEDIA_PATH . 'includes/class-track-cpt.php';
        require_once BRMEDIA_PATH . 'includes/class-audio-analyzer.php';
        require_once BRMEDIA_PATH . 'includes/class-player.php';
        require_once BRMEDIA_PATH . 'includes/class-shortcode.php';
        require_once BRMEDIA_PATH . 'includes/class-stats.php';
        require_once BRMEDIA_PATH . 'includes/class-template-manager.php';
        require_once BRMEDIA_PATH . 'includes/template-functions.php';
    }

    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('plugins_loaded', [$this, 'init_components']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function init_components() {
        $this->components = [
            'track_cpt' => new BRMedia_Track_CPT(),
            'player' => new BRMedia_Player(),
            'stats' => new BRMedia_Stats(),
            'templates' => new BRMedia_Template_Manager(),
            'shortcode' => new BRMedia_Shortcode('[brmedia]')
        ];

        if (is_admin()) {
            require_once BRMEDIA_PATH . 'admin/class-admin-settings.php';
            $this->components['admin'] = new BRMedia_Admin_Settings();
        }
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'brmedia-player',
            BRMEDIA_ASSETS_URL . 'css/player.css',
            [],
            filemtime(BRMEDIA_PATH . 'assets/css/player.css')
        );

        wp_enqueue_script(
            'brmedia-player',
            BRMEDIA_ASSETS_URL . 'js/player.js',
            ['plyr', 'wp-api'],
            filemtime(BRMEDIA_PATH . 'assets/js/player.js'),
            true
        );

        wp_localize_script('brmedia-player', 'brmediaConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brmedia_player_nonce'),
            'defaultVolume' => get_option('brmedia_default_volume', 75)
        ]);
    }

    public function activate() {
        BRMedia_Stats_DB::create_tables();
        flush_rewrite_rules();
    }

    public function deactivate() {
        wp_clear_scheduled_hook('brmedia_daily_stats');
        flush_rewrite_rules();
    }

    public function __get($component) {
        if (isset($this->components[$component])) {
            return $this->components[$component];
        }
        return null;
    }
}

BRMedia_Core::instance();