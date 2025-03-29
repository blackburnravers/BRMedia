<?php
/**
 * BRMedia Radio Addon
 * Adds support for live radio streaming
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Radio_Addon {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Register radio stream shortcode
        add_shortcode( 'brmedia_radio', array( $this, 'render_radio_player' ) );

        // Enqueue radio CSS (optional)
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'brmedia-radio-style', BRMEDIA_URL . 'addons/radio/assets/radio.css', array(), BRMEDIA_VERSION );
    }

    public function render_radio_player( $atts ) {
        $atts = shortcode_atts( array(
            'station' => '',
            'cover'   => '',
            'title'   => '',
            'autoplay' => 'false'
        ), $atts, 'brmedia_radio' );

        if ( empty( $atts['station'] ) ) {
            return '<div class="brmedia-radio-error">' . __( 'No station stream URL provided.', 'brmedia' ) . '</div>';
        }

        ob_start();
        ?>
        <div class="brmedia-radio-player">
            <?php if ( $atts['cover'] ) : ?>
                <img class="brmedia-radio-cover" src="<?php echo esc_url( $atts['cover'] ); ?>" alt="Station Cover">
            <?php endif; ?>

            <?php if ( $atts['title'] ) : ?>
                <div class="brmedia-radio-title"><?php echo esc_html( $atts['title'] ); ?></div>
            <?php endif; ?>

            <audio controls <?php echo $atts['autoplay'] === 'true' ? 'autoplay' : ''; ?>>
                <source src="<?php echo esc_url( $atts['station'] ); ?>" type="audio/mpeg">
                <?php _e( 'Your browser does not support the audio element.', 'brmedia' ); ?>
            </audio>
        </div>
        <?php
        return ob_get_clean();
    }
}

BRMedia_Radio_Addon::instance();