<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Shortcode {

    private $tag = 'brmedia';

    public function __construct() {
        add_shortcode($this->tag, [$this, 'render_shortcode']);
        add_action('brmedia_before_player', [$this, 'enqueue_dependencies']);
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'track_id' => 0,
            'template' => get_option('brmedia_default_template'),
            'controls' => 'default',
            'autoplay' => 'no'
        ], $atts, $this->tag);

        $track_id = $this->validate_track_id($atts['track_id']);
        $player = BRMedia_Core::instance()->player;

        ob_start();
        do_action('brmedia_before_player', $track_id);
        echo $player->init_player($track_id, [
            'template' => sanitize_key($atts['template']),
            'autoplay' => $atts['autoplay'] === 'yes'
        ]);
        do_action('brmedia_after_player', $track_id);
        
        return ob_get_clean();
    }

    private function validate_track_id($id) {
        if (!$id) {
            $tracks = get_posts([
                'post_type' => 'brmedia_track',
                'posts_per_page' => 1,
                'fields' => 'ids'
            ]);
            return $tracks[0] ?? 0;
        }
        return absint($id);
    }

    public function enqueue_dependencies() {
        wp_enqueue_style('plyr');
        wp_enqueue_script('plyr');
        BRMedia_Core::instance()->enqueue_assets();
    }
}