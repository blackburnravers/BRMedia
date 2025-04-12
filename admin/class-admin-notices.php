<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Admin_Notices {

    private static $notices = [];

    public static function add($message, $type = 'success', $dismissible = true) {
        self::$notices[] = [
            'message' => $message,
            'type' => $type,
            'dismissible' => $dismissible
        ];
        add_action('admin_notices', [__CLASS__, 'display']);
    }

    public static function display() {
        foreach (self::$notices as $notice) {
            $classes = "notice notice-{$notice['type']}";
            if ($notice['dismissible']) $classes .= ' is-dismissible';
            ?>
            <div class="<?= esc_attr($classes) ?>">
                <p><?= wp_kses_post($notice['message']) ?></p>
            </div>
            <?php
        }
    }

    public static function init_hooks() {
        add_action('admin_init', [__CLASS__, 'check_for_notices']);
    }

    public static function check_for_notices() {
        if (!empty($_GET['settings-updated'])) {
            self::add('Settings saved successfully!');
        }
        
        if (get_transient('brmedia_audio_analysis_complete')) {
            self::add('Batch audio analysis completed!', 'success');
            delete_transient('brmedia_audio_analysis_complete');
        }
    }
}

BRMedia_Admin_Notices::init_hooks();