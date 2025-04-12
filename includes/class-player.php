<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Player {

    private $player_instances = [];
    private $current_track_id = 0;

    public function __construct() {
        add_action('wp_footer', [$this, 'render_global_controls']);
        add_filter('brmedia_player_config', [$this, 'default_config']);
    }

    public function init_player($track_id, $config = []) {
        $this->current_track_id = $track_id;
        $defaults = apply_filters('brmedia_player_config', []);
        $config = wp_parse_args($config, $defaults);

        ob_start();
        $this->render_player_container();
        $html = ob_get_clean();

        $this->player_instances[$track_id] = [
            'config' => $config,
            'html' => $html
        ];

        return $html;
    }

    private function render_player_container() {
        $template = BRMedia_Core::instance()->templates->get_active_template();
        include BRMEDIA_PATH . "templates/{$template}.php";
    }

    public function default_config($config) {
        return wp_parse_args($config, [
            'autoplay' => false,
            'loop' => false,
            'volume' => get_option('brmedia_default_volume', 75) / 100,
            'controls' => $this->get_enabled_controls()
        ]);
    }

    private function get_enabled_controls() {
        $controls = get_option('brmedia_enabled_controls', [
            'play', 'progress', 'current-time', 'mute', 'volume'
        ]);

        return apply_filters('brmedia_active_controls', $controls);
    }

    public function render_global_controls() {
        if (empty($this->player_instances)) return;
        
        echo '<div class="brmedia-global-controls" style="display:none;">';
        echo '<audio id="brmedia-main-audio" crossorigin></audio>';
        echo '</div>';

        $this->enqueue_scripts();
    }

    private function enqueue_scripts() {
        wp_enqueue_script('plyr');
        wp_add_inline_script('brmedia-player', 
            'document.addEventListener("DOMContentLoaded", function() {
                BRMedia.initPlayers(' . json_encode($this->player_instances) . ');
            });'
        );
    }

    public function get_current_track() {
        return $this->current_track_id;
    }
}