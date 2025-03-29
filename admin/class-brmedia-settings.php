<?php
/**
 * BRMedia Settings Page
 * Loads admin UI using the BRMedia Settings API
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Settings_Page {

    private $settings_api;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'init_settings' ) );
    }

    public function add_settings_page() {
        add_submenu_page(
            'brmedia-dashboard',
            __( 'Settings', 'brmedia' ),
            __( 'Settings', 'brmedia' ),
            'manage_options',
            'brmedia-settings',
            array( $this, 'render_page' )
        );
    }

    public function init_settings() {
        require_once BRMEDIA_PATH . 'admin/class-brmedia-settings-api.php';
        $this->settings_api = new BRMedia_Settings_API();

        $this->settings_api->set_sections( $this->get_sections() );
        $this->settings_api->set_fields( $this->get_fields() );

        $this->settings_api->admin_init();
    }

    public function get_sections() {
        return array(
            array(
                'id'    => 'brmedia_general',
                'title' => __( 'General Settings', 'brmedia' )
            ),
            array(
                'id'    => 'brmedia_audio',
                'title' => __( 'Audio Player', 'brmedia' )
            ),
            array(
                'id'    => 'brmedia_video',
                'title' => __( 'Video Player', 'brmedia' )
            ),
            array(
                'id'    => 'brmedia_templates',
                'title' => __( 'Templates', 'brmedia' )
            ),
            array(
                'id'    => 'brmedia_social',
                'title' => __( 'Sharing & Social', 'brmedia' )
            )
        );
    }

    public function get_fields() {
        return array(
            'brmedia_general' => array(
                array(
                    'name'    => 'branding_name',
                    'label'   => __( 'Player Branding', 'brmedia' ),
                    'desc'    => __( 'Displayed name on player if enabled.', 'brmedia' ),
                    'type'    => 'text',
                    'default' => 'BRMedia'
                ),
                array(
                    'name'    => 'footer_player',
                    'label'   => __( 'Enable Footer Player', 'brmedia' ),
                    'desc'    => __( 'Toggle global footer player.', 'brmedia' ),
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => 'popup_player',
                    'label'   => __( 'Enable Popup Player', 'brmedia' ),
                    'desc'    => __( 'Toggle popup player support.', 'brmedia' ),
                    'type'    => 'checkbox'
                )
            ),

            'brmedia_audio' => array(
                array(
                    'name'    => 'default_volume',
                    'label'   => __( 'Default Volume', 'brmedia' ),
                    'desc'    => __( 'Initial volume (0 to 1).', 'brmedia' ),
                    'type'    => 'range',
                    'default' => 0.8,
                    'min'     => 0,
                    'max'     => 1,
                    'step'    => 0.1
                ),
                array(
                    'name'    => 'show_waveform',
                    'label'   => __( 'Show Waveform', 'brmedia' ),
                    'desc'    => __( 'Enable waveform rendering.', 'brmedia' ),
                    'type'    => 'checkbox'
                )
            ),

            'brmedia_video' => array(
                array(
                    'name'    => 'video_autoplay',
                    'label'   => __( 'Autoplay Videos', 'brmedia' ),
                    'desc'    => __( 'Autoplay videos when loaded.', 'brmedia' ),
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => 'video_poster_fallback',
                    'label'   => __( 'Default Poster Image', 'brmedia' ),
                    'desc'    => __( 'Fallback image when none is set.', 'brmedia' ),
                    'type'    => 'text'
                )
            ),

            'brmedia_templates' => array(
                array(
                    'name'    => 'template_style',
                    'label'   => __( 'Audio Template Style', 'brmedia' ),
                    'desc'    => __( 'Choose which template layout to use.', 'brmedia' ),
                    'type'    => 'select',
                    'options' => array(
                        'default'    => __( 'Default', 'brmedia' ),
                        'waveform'   => __( 'Waveform', 'brmedia' ),
                        'visualizer' => __( 'Visualizer', 'brmedia' )
                    )
                ),
                array(
                    'name'    => 'template_shadow',
                    'label'   => __( 'Player Box Shadow', 'brmedia' ),
                    'desc'    => __( 'CSS box-shadow value.', 'brmedia' ),
                    'type'    => 'text',
                    'default' => '0 4px 12px rgba(0,0,0,0.15)'
                ),
                array(
                    'name'    => 'template_border',
                    'label'   => __( 'Player Border Radius', 'brmedia' ),
                    'desc'    => __( 'Rounded corners (e.g., 6px).', 'brmedia' ),
                    'type'    => 'text',
                    'default' => '6px'
                )
            ),

            'brmedia_social' => array(
                array(
                    'name'    => 'enable_sharing',
                    'label'   => __( 'Enable Sharing Buttons', 'brmedia' ),
                    'desc'    => __( 'Show social share icons.', 'brmedia' ),
                    'type'    => 'checkbox'
                ),
                array(
                    'name'    => 'share_networks',
                    'label'   => __( 'Networks', 'brmedia' ),
                    'desc'    => __( 'Choose networks to show.', 'brmedia' ),
                    'type'    => 'multicheck',
                    'options' => array(
                        'x' => 'X (formerly Twitter)',
                        'facebook' => 'Facebook',
                        'whatsapp' => 'WhatsApp',
                        'email' => 'Email',
                        'telegram' => 'Telegram'
                    ),
                    'default' => array()
                )
            )
        );
    }

    public function render_page() {
        echo '<div class="wrap brmedia-settings">';
        echo '<h1><i class="fas fa-cogs"></i> ' . __( 'BRMedia Settings', 'brmedia' ) . '</h1>';
        $this->settings_api->render_tabs();
        $this->settings_api->render_forms();
        echo '</div>';
    }
}

new BRMedia_Settings_Page();