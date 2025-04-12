<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Template_Manager {

    private $active_template;
    private $custom_styles = [];

    public function __construct() {
        $this->active_template = get_option('brmedia_default_template', 'template-1');
        add_filter('brmedia_player_classes', [$this, 'add_template_class']);
    }

    public function get_active_template() {
        return $this->active_template;
    }

    public function render_template($template, $track_id) {
        $template_file = BRMEDIA_PATH . "templates/{$template}.php";
        
        if (!file_exists($template_file)) {
            $template_file = BRMEDIA_PATH . 'templates/template-1.php';
        }

        $this->load_styles($template);
        extract($this->get_template_vars($track_id));
        
        ob_start();
        include $template_file;
        return ob_get_clean();
    }

    private function load_styles($template) {
        $css_file = BRMEDIA_PATH . "assets/css/templates/{$template}.css";
        if (file_exists($css_file)) {
            wp_enqueue_style(
                "brmedia-{$template}",
                BRMEDIA_ASSETS_URL . "css/templates/{$template}.css",
                [],
                filemtime($css_file)
            );
        }
    }

    private function get_template_vars($track_id) {
        return [
            'track' => get_post($track_id),
            'meta' => $this->get_track_meta($track_id),
            'styles' => $this->get_custom_styles()
        ];
    }

    private function get_track_meta($track_id) {
        return [
            'bpm' => get_post_meta($track_id, '_brmedia_bpm', true),
            'key' => get_post_meta($track_id, '_brmedia_key', true),
            'artist' => get_post_meta($track_id, '_brmedia_artist', true)
        ];
    }

    public function add_template_class($classes) {
        $classes[] = 'template-' . str_replace('template-', '', $this->active_template);
        return $classes;
    }

    public function get_custom_styles() {
        return apply_filters('brmedia_template_styles', [
            'player_bg' => get_option('brmedia_player_bg', '#2c3e50'),
            'text_color' => get_option('brmedia_text_color', '#ffffff')
        ]);
    }
}