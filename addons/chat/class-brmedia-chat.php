<?php
/**
 * BRMedia Chat Addon
 * Provides real-time chat functionality for media pages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BRMedia_Chat_Addon {
    
    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Load assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Shortcode for chat box
        add_shortcode( 'brmedia_chat', array( $this, 'render_chat_box' ) );

        // AJAX handlers
        add_action( 'wp_ajax_brmedia_send_chat', array( $this, 'handle_send_chat' ) );
        add_action( 'wp_ajax_nopriv_brmedia_send_chat', array( $this, 'handle_send_chat' ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'brmedia-chat-style', BRMEDIA_URL . 'addons/chat/assets/chat.css', array(), BRMEDIA_VERSION );
        wp_enqueue_script( 'brmedia-chat-script', BRMEDIA_URL . 'addons/chat/assets/chat.js', array('jquery'), BRMEDIA_VERSION, true );

        wp_localize_script( 'brmedia-chat-script', 'BRMediaChat', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'brmedia_chat_nonce' )
        ));
    }

    public function render_chat_box( $atts ) {
        ob_start(); ?>
        <div class="brmedia-chat-box">
            <div id="brmedia-chat-messages" class="brmedia-chat-messages"></div>
            <form id="brmedia-chat-form" class="brmedia-chat-form">
                <input type="text" id="brmedia-chat-message" name="message" placeholder="Type your message..." required />
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
        <?php return ob_get_clean();
    }

    public function handle_send_chat() {
        check_ajax_referer( 'brmedia_chat_nonce', 'nonce' );

        $message = sanitize_text_field( $_POST['message'] ?? '' );
        $user = wp_get_current_user();
        $username = $user->exists() ? $user->display_name : 'Guest';

        if ( empty( $message ) ) {
            wp_send_json_error( 'Message is empty.' );
        }

        $formatted = array(
            'time' => current_time( 'H:i' ),
            'user' => $username,
            'message' => $message
        );

        // For demo purposes we just return the formatted message
        // In production you would store in DB or broadcast via sockets
        wp_send_json_success( $formatted );
    }
}

BRMedia_Chat_Addon::instance();