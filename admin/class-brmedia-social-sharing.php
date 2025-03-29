<?php
/**
 * BRMedia Social Sharing
 * Adds frontend sharing buttons to media content
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Social_Sharing {

    public function __construct() {
        add_action( 'brmedia_after_player_controls', array( $this, 'render_share_buttons' ), 20, 2 );
    }

    public function render_share_buttons( $post_id, $media_url ) {
        $options = get_option( 'brmedia_social' );
        if ( empty( $options['enable_sharing'] ) ) return;

        $networks = $options['share_networks'] ?? array();
        if ( empty( $networks ) ) return;

        $title = get_the_title( $post_id );
        $url   = get_permalink( $post_id );

        echo '<div class="brmedia-social-share">';
        echo '<span class="share-label">' . __( 'Share:', 'brmedia' ) . '</span>';

        foreach ( $networks as $network ) {
            $link = '';
            switch ( $network ) {
                case 'facebook':
                    $link = 'https://facebook.com/sharer/sharer.php?u=' . urlencode( $url );
                    break;
                case 'x':
                    $link = 'https://x.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title );
                    break;
                case 'whatsapp':
                    $link = 'https://wa.me/?text=' . urlencode( $title . ' ' . $url );
                    break;
                case 'email':
                    $link = 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url );
                    break;
                case 'telegram':
                    $link = 'https://t.me/share/url?url=' . urlencode( $url ) . '&text=' . urlencode( $title );
                    break;
            }

            if ( $link ) {
                echo '<a href="' . esc_url( $link ) . '" target="_blank" class="share-btn share-' . esc_attr( $network ) . '">';
                echo '<i class="fab fa-' . esc_attr( $network ) . '"></i>';
                echo '</a>';
            }
        }

        echo '</div>';
    }
}

new BRMedia_Social_Sharing();