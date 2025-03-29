<?php
/**
 * BRMedia Shortcode Manager
 * Provides a UI in admin to view, copy, and manage shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BRMedia_Shortcode_Manager {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_shortcode_page' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
    }

    public function add_shortcode_page() {
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Shortcodes', 'brmedia' ),
            __( 'Shortcodes', 'brmedia' ),
            'manage_options',
            'brmedia-shortcodes',
            array( $this, 'render_shortcode_page' )
        );
    }

    public function render_shortcode_page() {
        $music = get_posts(array(
            'post_type' => 'brmedia_music',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));

        $videos = get_posts(array(
            'post_type' => 'brmedia_video',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        ?>
        <div class="wrap brmedia-shortcodes">
            <h1><i class="fas fa-code"></i> <?php _e('BRMedia Shortcodes', 'brmedia'); ?></h1>

            <div class="brmedia-shortcode-section">
                <h2><?php _e('Music Tracks', 'brmedia'); ?></h2>
                <ul>
                    <?php foreach ( $music as $post ) : ?>
                        <li>
                            <strong><?php echo esc_html( $post->post_title ); ?></strong>
                            <code>[brmedia_player id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="brmedia-shortcode-section">
                <h2><?php _e('Videos', 'brmedia'); ?></h2>
                <ul>
                    <?php foreach ( $videos as $post ) : ?>
                        <li>
                            <strong><?php echo esc_html( $post->post_title ); ?></strong>
                            <code>[brmedia_player id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    public function register_shortcodes() {
        add_shortcode('brmedia_player', array( $this, 'render_shortcode_player' ));
    }

    public function render_shortcode_player( $atts ) {
        $atts = shortcode_atts(array(
            'id' => ''
        ), $atts, 'brmedia_player');

        $post_id = absint($atts['id']);
        if ( ! $post_id ) return '';

        $media_url = get_post_meta($post_id, '_brmedia_media_url', true);
        $cover     = get_post_meta($post_id, '_brmedia_cover_image', true);
        $title     = get_the_title($post_id);

        ob_start();
        ?>
        <div class="brmedia-player-wrapper">
            <?php if ( $cover ) : ?>
                <div class="brmedia-player-cover">
                    <img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                </div>
            <?php endif; ?>
            <div class="brmedia-player">
                <audio controls>
                    <source src="<?php echo esc_url( $media_url ); ?>" type="audio/mpeg" />
                    <?php _e('Your browser does not support the audio element.', 'brmedia'); ?>
                </audio>
                <div class="brmedia-title"><?php echo esc_html( $title ); ?></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new BRMedia_Shortcode_Manager();