<?php
/**
 * BRMedia Gaming Addon
 * Integration point for gaming-related features (Twitch, YouTube, etc.)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Gaming_Addon {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Shortcode to embed a stream
        add_shortcode( 'brmedia_gaming_stream', array( $this, 'render_gaming_stream' ) );

        // Optionally enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'brmedia-gaming-style', BRMEDIA_URL . 'addons/gaming/assets/gaming.css', array(), BRMEDIA_VERSION );
    }

    public function render_gaming_stream( $atts ) {
        $atts = shortcode_atts( array(
            'platform' => 'twitch',
            'channel'  => '',
            'width'    => '100%',
            'height'   => '480px'
        ), $atts, 'brmedia_gaming_stream' );

        if ( empty( $atts['channel'] ) ) {
            return '<div class="brmedia-gaming-error">' . __( 'No channel specified.', 'brmedia' ) . '</div>';
        }

        ob_start();

        echo '<div class="brmedia-gaming-embed">';

        if ( $atts['platform'] === 'twitch' ) {
            echo '<iframe
                    src="https://player.twitch.tv/?channel=' . esc_attr( $atts['channel'] ) . '&parent=' . $_SERVER['HTTP_HOST'] . '"
                    height="' . esc_attr( $atts['height'] ) . '"
                    width="' . esc_attr( $atts['width'] ) . '"
                    allowfullscreen>
                  </iframe>';
        } elseif ( $atts['platform'] === 'youtube' ) {
            echo '<iframe
                    src="https://www.youtube.com/embed/live_stream?channel=' . esc_attr( $atts['channel'] ) . '"
                    height="' . esc_attr( $atts['height'] ) . '"
                    width="' . esc_attr( $atts['width'] ) . '"
                    allowfullscreen>
                  </iframe>';
        } else {
            echo '<div class="brmedia-gaming-error">' . __( 'Unsupported platform.', 'brmedia' ) . '</div>';
        }

        echo '</div>';

        return ob_get_clean();
    }
}

BRMedia_Gaming_Addon::instance();