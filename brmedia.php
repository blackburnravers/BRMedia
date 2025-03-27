<?php
/**
 * Plugin Name: BRMedia
 * Plugin URI: https://www.blackburnravers.co.uk
 * Description: Advanced media management with customizable players, visualizations, and video handling.
 * Version: 1.0.0
 * Author: Rhys Cole
 * Author URI: https://www.blackburnravers.co.uk
 * Text Domain: brmedia
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

/**
 * Define plugin constants
 */
define('BRMEDIA_VERSION', '1.0.0');
define('BRMEDIA_PATH', plugin_dir_path(__FILE__));
define('BRMEDIA_URL', plugin_dir_url(__FILE__));
define('BRMEDIA_BASENAME', plugin_basename(__FILE__));

/**
 * Autoloader for plugin classes
 */
spl_autoload_register(function ($class) {
    $prefix = 'BRMedia_';
    $base_dir = BRMEDIA_PATH . 'includes/';
    
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    
    $relative_class = substr($class, strlen($prefix));
    $file = $base_dir . str_replace('_', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Main plugin class
 */
class BRMedia_Plugin {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Load core functionality
        $this->load_core();
        
        // Load admin functionality
        if (is_admin()) {
            $this->load_admin();
        }
        
        // Load frontend functionality
        $this->load_frontend();
        
        // Load text domain
        load_plugin_textdomain('brmedia', false, dirname(BRMEDIA_BASENAME) . '/languages');
    }

    private function load_core() {
        require_once BRMEDIA_PATH . 'includes/class-brmedia-core.php';
        BRMedia_Core::instance();
    }

    private function load_admin() {
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-admin.php';
        BRMedia_Admin::instance();
        
        // Load dashboard components
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-dashboard.php';
        BRMedia_Dashboard::instance();
    }

    private function load_frontend() {
        require_once BRMEDIA_PATH . 'includes/frontend/class-brmedia-shortcodes.php';
        require_once BRMEDIA_PATH . 'includes/frontend/class-brmedia-template-loader.php';
    }

    public static function activate() {
        require_once BRMEDIA_PATH . 'includes/class-brmedia-activator.php';
        BRMedia_Activator::activate();
    }

    public static function deactivate() {
        require_once BRMEDIA_PATH . 'includes/class-brmedia-deactivator.php';
        BRMedia_Deactivator::deactivate();
    }
}

// Register activation/deactivation hooks
register_activation_hook(__FILE__, array('BRMedia_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('BRMedia_Plugin', 'deactivate'));

// Initialize plugin
add_action('plugins_loaded', array('BRMedia_Plugin', 'instance'));

/**
 * Helper function for easy access to plugin instance
 */
function BRMedia() {
    return BRMedia_Plugin::instance();
}