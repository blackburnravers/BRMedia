<?php
/**
 * Plugin Name: BRMedia Advanced Media Player
 * Plugin URI: https://www.blackburnravers.co.uk/
 * Description: Professional music player with analytics and template system
 * Version: 1.0.0
 * Author: Rhys Cole
 * Author URI: https://www.blackburnravers.co.uk/
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: brmedia
 * Domain Path: /languages
 */

// Prevent direct access
defined('ABSPATH') || exit;

// Define core constants
define('BRMEDIA_VERSION', '1.0.0');
define('BRMEDIA_FILE', __FILE__);
define('BRMEDIA_PATH', plugin_dir_path(BRMEDIA_FILE));
define('BRMEDIA_URL', plugin_dir_url(BRMEDIA_FILE));
define('BRMEDIA_ASSETS_URL', BRMEDIA_URL . 'assets/');
define('BRMEDIA_MIN_PHP', '7.4');
define('BRMEDIA_MIN_WP', '5.9');

// Debug mode (only enable when needed)
define('BRMEDIA_DEBUG', false);

class BRMedia_Requirements_Check {
    public function __construct() {
        add_action('admin_init', [$this, 'check']);
    }

    public function check() {
        if (!$this->php_version_check() || !$this->wp_version_check()) {
            add_action('admin_notices', [$this, 'show_notice']);
            deactivate_plugins(plugin_basename(BRMEDIA_FILE));
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }

    private function php_version_check() {
        return version_compare(PHP_VERSION, BRMEDIA_MIN_PHP, '>=');
    }

    private function wp_version_check() {
        return version_compare(get_bloginfo('version'), BRMEDIA_MIN_WP, '>=');
    }

    public function show_notice() {
        echo '<div class="error"><p>';
        printf(
            __('BRMedia requires PHP %1$s+ and WordPress %2$s+. Your site uses PHP %3$s and WordPress %4$s.', 'brmedia'),
            BRMEDIA_MIN_PHP,
            BRMEDIA_MIN_WP,
            PHP_VERSION,
            get_bloginfo('version')
        );
        echo '</p></div>';
    }
}

new BRMedia_Requirements_Check();

if (!class_exists('BRMedia_Core')) {

    final class BRMedia_Core {

        private static $instance;
        private $components = [];

        private function __construct() {
            $this->define_hooks();
            if (!$this->load_dependencies()) return;
            $this->init_components();
        }

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function define_hooks() {
            register_activation_hook(BRMEDIA_FILE, [$this, 'activate']);
            register_deactivation_hook(BRMEDIA_FILE, [$this, 'deactivate']);
            
            add_action('plugins_loaded', [$this, 'load_textdomain']);
            add_action('init', [$this, 'init'], 5); // Priority 5 for proper loading
        }

        private function load_dependencies() {
            $required_files = [
                'includes/vendor/class-webdevs-settings-api.php',
                'includes/class-track-cpt.php',
                'includes/class-audio-analyzer.php',
                'includes/class-player.php',
                'includes/class-shortcode.php',
                'includes/class-stats.php',
                'includes/class-template-manager.php',
                'includes/class-rest-api.php' // Now properly checked
            ];

            foreach ($required_files as $file) {
                $path = BRMEDIA_PATH . $file;
                if (!file_exists($path)) {
                    add_action('admin_notices', function() use ($file) {
                        echo '<div class="error"><p>BRMedia missing required file: ' . esc_html($file) . '</p></div>';
                    });
                    return false;
                }
                require_once $path;
            }

            if (is_admin()) {
                require_once BRMEDIA_PATH . 'admin/class-admin-settings.php';
                require_once BRMEDIA_PATH . 'admin/class-admin-stats.php';
                require_once BRMEDIA_PATH . 'admin/class-metaboxes.php';
            }

            return true;
        }

        public function activate() {
            if (!current_user_can('activate_plugins')) return;

            // Initialize database tables
            if (file_exists(BRMEDIA_PATH . 'includes/class-stats-db.php')) {
                require_once BRMEDIA_PATH . 'includes/class-stats-db.php';
                BRMedia_Stats_DB::create_tables();
            }

            if (!wp_next_scheduled('brmedia_daily_cleanup')) {
                wp_schedule_event(time(), 'daily', 'brmedia_daily_cleanup');
            }

            flush_rewrite_rules();
        }

        public function deactivate() {
            if (!current_user_can('activate_plugins')) return;

            wp_clear_scheduled_hook('brmedia_daily_cleanup');
            flush_rewrite_rules();
        }

        public function load_textdomain() {
            load_plugin_textdomain(
                'brmedia',
                false,
                dirname(plugin_basename(BRMEDIA_FILE)) . '/languages/'
            );
        }

        public function init() {
            if (!did_action('plugins_loaded')) {
                wp_die(__('BRMedia initialized too early. Please deactivate and reactivate the plugin.', 'brmedia'));
            }

            $this->components = [
                'track_cpt' => new BRMedia_Track_CPT(),
                'player' => new BRMedia_Player(),
                'stats' => new BRMedia_Stats(),
                'templates' => new BRMedia_Template_Manager(),
                'shortcode' => new BRMedia_Shortcode('[brmedia]'),
                'rest_api' => new BRMedia_REST_API()
            ];

            if (is_admin()) {
                $this->components['admin_settings'] = new BRMedia_Admin_Settings();
                $this->components['admin_stats'] = new BRMedia_Admin_Stats();
                $this->components['metaboxes'] = new BRMedia_Metaboxes();
            }

            $this->enqueue_assets();
            $this->register_cli();
        }

        private function enqueue_assets() {
            // Frontend assets
            add_action('wp_enqueue_scripts', function() {
                wp_enqueue_style(
                    'brmedia-player',
                    BRMEDIA_ASSETS_URL . 'css/player.css',
                    [],
                    filemtime(BRMEDIA_PATH . 'assets/css/player.css')
                );

                wp_enqueue_script(
                    'plyr',
                    BRMEDIA_ASSETS_URL . 'js/vendors/plyr.min.js',
                    [],
                    '3.7.8',
                    true
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
                    'defaultVolume' => get_option('brmedia_default_volume', 75) / 100,
                    'debug' => BRMEDIA_DEBUG
                ]);
            });

            // Admin assets
            add_action('admin_enqueue_scripts', function($hook) {
                if (strpos($hook, 'brmedia') === false) return;

                wp_enqueue_style(
                    'brmedia-admin',
                    BRMEDIA_ASSETS_URL . 'css/admin.css',
                    ['wp-color-picker'],
                    filemtime(BRMEDIA_PATH . 'assets/css/admin.css')
                );

                wp_enqueue_script(
                    'chart-js',
                    BRMEDIA_ASSETS_URL . 'js/vendors/chart.min.js',
                    [],
                    '3.7.1',
                    true
                );

                wp_enqueue_script(
                    'brmedia-admin',
                    BRMEDIA_ASSETS_URL . 'js/admin.js',
                    ['jquery', 'wp-color-picker', 'chart-js'],
                    filemtime(BRMEDIA_PATH . 'assets/js/admin.js'),
                    true
                );
            });
        }

        private function register_cli() {
            if (defined('WP_CLI') && WP_CLI && file_exists(BRMEDIA_PATH . 'includes/class-cli.php')) {
                require_once BRMEDIA_PATH . 'includes/class-cli.php';
                WP_CLI::add_command('brmedia', 'BRMedia_CLI');
            }
        }

        public function __get($component) {
            if (isset($this->components[$component])) {
                return $this->components[$component];
            }
            trigger_error('Undefined component: ' . $component, E_USER_NOTICE);
            return null;
        }

        public function log($message, $data = []) {
            if (BRMEDIA_DEBUG) {
                error_log('[BRMedia] ' . $message);
                if (!empty($data)) {
                    error_log(print_r($data, true));
                }
            }
        }
    }

    // Initialize plugin
    add_action('plugins_loaded', function() {
        BRMedia_Core::instance();
    }, 10);

    // REST API initialization
    add_action('rest_api_init', function() {
        if (class_exists('BRMedia_REST_API')) {
            $rest_api = new BRMedia_REST_API();
            $rest_api->register_routes();
        }
    });

    // Daily cleanup hook
    add_action('brmedia_daily_cleanup', function() {
        global $wpdb;
        
        $retention_days = get_option('brmedia_data_retention', 365);
        $date = date('Y-m-d', strtotime("-{$retention_days} days"));
        
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}brmedia_stats 
             WHERE created_at < %s",
            $date
        ));
    });

    // Global helper function
    if (!function_exists('brmedia_player')) {
        function brmedia_player($track_id = 0, $template = '') {
            $core = BRMedia_Core::instance();
            return isset($core->player) ? $core->player->render($track_id, $template) : '';
        }
    }
}