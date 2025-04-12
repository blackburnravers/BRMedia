<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Admin_Ajax {

    public function __construct() {
        // Template Preview
        add_action('wp_ajax_brmedia_get_template_preview', [$this, 'get_template_preview']);

        // Stats Data
        add_action('wp_ajax_brmedia_get_stats_data', [$this, 'get_stats_data']);

        // Audio Processing
        add_action('wp_ajax_brmedia_analyze_audio', [$this, 'analyze_audio']);
    }

    public function get_template_preview() {
        check_ajax_referer('brmedia_preview_nonce', 'security');

        $template = sanitize_text_field($_POST['template']);
        ob_start();
        include BRMEDIA_PATH . "templates/{$template}.php";
        $html = ob_get_clean();

        wp_send_json_success([
            'html' => $html,
            'css' => file_get_contents(BRMEDIA_PATH . "assets/css/templates/{$template}.css")
        ]);
    }

    public function get_stats_data() {
        check_ajax_referer('brmedia_stats_nonce', 'security');

        $range = isset($_POST['range']) ? (int)$_POST['range'] : 7;
        $stats = new BRMedia_Stats();

        wp_send_json_success([
            'labels' => $stats->get_date_labels($range),
            'data' => $stats->get_play_counts($range)
        ]);
    }

    public function analyze_audio() {
        check_ajax_referer('brmedia_audio_nonce', 'security');

        $attachment_id = (int)$_POST['attachment_id'];
        $file_path = get_attached_file($attachment_id);

        if (!file_exists($file_path)) {
            wp_send_json_error('File not found');
        }

        $analyzer = new BRMedia_Audio_Analyzer();
        $result = $analyzer->process($file_path);

        wp_send_json_success([
            'bpm' => $result['bpm'],
            'key' => $result['key']
        ]);
    }
}

new BRMedia_Admin_Ajax();