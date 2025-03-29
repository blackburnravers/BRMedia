<?php
/**
 * BRMedia Podcast Addon
 * Adds support for managing and displaying podcast episodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Podcast_Addon {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Register podcast shortcode
        add_shortcode( 'brmedia_podcast', array( $this, 'render_podcast_player' ) );

        // Optional: Enqueue podcast styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'brmedia-podcast-style', BRMEDIA_URL . 'addons/podcast/assets/podcast.css', array(), BRMEDIA_VERSION );
    }

    public function render_podcast_player( $atts ) {
        $atts = shortcode_atts( array(
            'title'     => '',
            'audio_url' => '',
            'cover'     => '',
            'show_title' => 'true'
        ), $atts, 'brmedia_podcast' );

        if ( empty( $atts['audio_url'] ) ) {
            return '<div class="brmedia-podcast-error">' . __( 'No audio URL provided.', 'brmedia' ) . '</div>';
        }

        ob_start();
        ?>
        <div class="brmedia-podcast-player">
            <?php if ( $atts['cover'] ) : ?>
                <img class="brmedia-podcast-cover" src="<?php echo esc_url( $atts['cover'] ); ?>" alt="Podcast Cover">
            <?php endif; ?>
            
            <audio controls preload="none">
                <source src="<?php echo esc_url( $atts['audio_url'] ); ?>" type="audio/mpeg">
                <?php _e( 'Your browser does not support the audio element.', 'brmedia' ); ?>
            </audio>

            <?php if ( $atts['show_title'] === 'true' && ! empty( $atts['title'] ) ) : ?>
                <div class="brmedia-podcast-title"><?php echo esc_html( $atts['title'] ); ?></div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

BRMedia_Podcast_Addon::instance();