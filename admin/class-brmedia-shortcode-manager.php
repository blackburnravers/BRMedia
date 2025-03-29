<?php
/**
 * BRMedia Shortcode Manager
 * Provides a UI in admin to view, copy, and manage shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Shortcode_Manager {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_shortcode_page' ) );
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
        $music = get_posts( array(
            'post_type' => 'brmedia_music',
            'numberposts' => -1,
            'post_status' => 'publish'
        ) );

        $videos = get_posts( array(
            'post_type' => 'brmedia_video',
            'numberposts' => -1,
            'post_status' => 'publish'
        ) );
        ?>
        <div class="wrap brmedia-shortcodes">
            <h1><i class="fas fa-code"></i> <?php _e( 'BRMedia Shortcodes', 'brmedia' ); ?></h1>

            <div class="brmedia-shortcode-section">
                <h2><?php _e( 'Music Tracks', 'brmedia' ); ?></h2>
                <ul>
                    <?php foreach ( $music as $post ) : ?>
                        <li>
                            <strong><?php echo esc_html( $post->post_title ); ?></strong>
                            <code>[brmedia id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="brmedia-shortcode-section">
                <h2><?php _e( 'Videos', 'brmedia' ); ?></h2>
                <ul>
                    <?php foreach ( $videos as $post ) : ?>
                        <li>
                            <strong><?php echo esc_html( $post->post_title ); ?></strong>
                            <code>[brmedia id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }
}

new BRMedia_Shortcode_Manager();