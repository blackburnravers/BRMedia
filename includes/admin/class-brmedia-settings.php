<?php
/**
 * BRMedia Settings Class
 * Handles all plugin settings and options
 */

class BRMedia_Settings {
    private static $instance = null;
    private $settings_api;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        require_once BRMEDIA_PATH . 'includes/admin/class-brmedia-settings-api.php';
        $this->settings_api = new BRMedia_Settings_API();

        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_init() {
        // Set the settings
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());

        // Initialize settings
        $this->settings_api->admin_init();
    }

    public function admin_menu() {
        add_submenu_page(
            'brmedia-dashboard',
            __('BRMedia Settings', 'brmedia'),
            __('Settings', 'brmedia'),
            'manage_options',
            'brmedia-settings',
            array($this, 'plugin_page')
        );
    }

    public function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'brmedia_general',
                'title' => __('General Settings', 'brmedia')
            ),
            array(
                'id'    => 'brmedia_player',
                'title' => __('Player Settings', 'brmedia')
            ),
            array(
                'id'    => 'brmedia_templates',
                'title' => __('Templates', 'brmedia')
            ),
            array(
                'id'    => 'brmedia_social',
                'title' => __('Social Sharing', 'brmedia')
            ),
            array(
                'id'    => 'brmedia_advanced',
                'title' => __('Advanced', 'brmedia')
            )
        );

        return apply_filters('brmedia_settings_sections', $sections);
    }

    /**
     * Returns all the settings fields
     */
    public function get_settings_fields() {
        $settings_fields = array(
            'brmedia_general' => array(
                array(
                    'name'    => 'default_volume',
                    'label'   => __('Default Volume', 'brmedia'),
                    'desc'    => __('Initial volume level (0-100)', 'brmedia'),
                    'type'    => 'range',
                    'min'     => '0',
                    'max'     => '100',
                    'step'    => '1',
                    'default' => '80'
                ),
                array(
                    'name'    => 'enable_airplay',
                    'label'   => __('Enable AirPlay', 'brmedia'),
                    'desc'    => __('Show AirPlay button on supported devices', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'enable_cast',
                    'label'   => __('Enable Cast', 'brmedia'),
                    'desc'    => __('Show Cast button for Chromecast devices', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'track_plays',
                    'label'   => __('Track Plays', 'brmedia'),
                    'desc'    => __('Record statistics about media plays', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                )
            ),

            'brmedia_player' => array(
                array(
                    'name'    => 'default_skin',
                    'label'   => __('Default Player Skin', 'brmedia'),
                    'desc'    => __('Default appearance for media players', 'brmedia'),
                    'type'    => 'select',
                    'options' => array(
                        'stylish'      => __('Stylish', 'brmedia'),
                        'waveform'     => __('Waveform', 'brmedia'),
                        'visualization' => __('Visualization', 'brmedia')
                    ),
                    'default' => 'stylish'
                ),
                array(
                    'name'    => 'primary_color',
                    'label'   => __('Primary Color', 'brmedia'),
                    'desc'    => __('Main color for player controls', 'brmedia'),
                    'type'    => 'color',
                    'default' => '#3a7bd5'
                ),
                array(
                    'name'    => 'secondary_color',
                    'label'   => __('Secondary Color', 'brmedia'),
                    'desc'    => __('Accent color for player elements', 'brmedia'),
                    'type'    => 'color',
                    'default' => '#00d2ff'
                ),
                array(
                    'name'    => 'waveform_color',
                    'label'   => __('Waveform Color', 'brmedia'),
                    'desc'    => __('Color for waveform display', 'brmedia'),
                    'type'    => 'color',
                    'default' => '#ffffff'
                )
            ),

            'brmedia_templates' => array(
                array(
                    'name'    => 'stylish_show_artwork',
                    'label'   => __('Stylish - Show Artwork', 'brmedia'),
                    'desc'    => __('Display cover art in stylish player', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'stylish_show_tracklist',
                    'label'   => __('Stylish - Show Tracklist', 'brmedia'),
                    'desc'    => __('Display timestamp tracklist', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'waveform_height',
                    'label'   => __('Waveform - Height', 'brmedia'),
                    'desc'    => __('Height of waveform display (px)', 'brmedia'),
                    'type'    => 'number',
                    'default' => '100',
                    'min'     => '50',
                    'max'     => '300'
                ),
                array(
                    'name'    => 'visualization_type',
                    'label'   => __('Visualization - Type', 'brmedia'),
                    'desc'    => __('Visualization style for audio', 'brmedia'),
                    'type'    => 'select',
                    'options' => array(
                        'bars'    => __('Bars', 'brmedia'),
                        'wave'    => __('Wave', 'brmedia'),
                        'particles' => __('Particles', 'brmedia')
                    ),
                    'default' => 'bars'
                )
            ),

            'brmedia_social' => array(
                array(
                    'name'    => 'enable_sharing',
                    'label'   => __('Enable Sharing', 'brmedia'),
                    'desc'    => __('Show social sharing buttons', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'on'
                ),
                array(
                    'name'    => 'share_platforms',
                    'label'   => __('Share Platforms', 'brmedia'),
                    'desc'    => __('Select which platforms to show', 'brmedia'),
                    'type'    => 'multicheck',
                    'options' => array(
                        'facebook'  => 'Facebook',
                        'x'         => 'X (Twitter)',
                        'whatsapp'  => 'WhatsApp',
                        'telegram'  => 'Telegram',
                        'reddit'    => 'Reddit',
                        'linkedin'  => 'LinkedIn',
                        'tumblr'    => 'Tumblr',
                        'pinterest' => 'Pinterest'
                    ),
                    'default' => array('facebook' => 'facebook', 'x' => 'x', 'whatsapp' => 'whatsapp')
                ),
                array(
                    'name'    => 'share_text',
                    'label'   => __('Share Text', 'brmedia'),
                    'desc'    => __('Default text for shared posts', 'brmedia'),
                    'type'    => 'text',
                    'default' => __('Check out this awesome track!', 'brmedia')
                )
            ),

            'brmedia_advanced' => array(
                array(
                    'name'    => 'cleanup_on_uninstall',
                    'label'   => __('Cleanup on Uninstall', 'brmedia'),
                    'desc'    => __('Remove all plugin data when uninstalling', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'off'
                ),
                array(
                    'name'    => 'debug_mode',
                    'label'   => __('Debug Mode', 'brmedia'),
                    'desc'    => __('Enable debug logging for troubleshooting', 'brmedia'),
                    'type'    => 'checkbox',
                    'default' => 'off'
                ),
                array(
                    'name'    => 'custom_css',
                    'label'   => __('Custom CSS', 'brmedia'),
                    'desc'    => __('Add custom CSS for advanced styling', 'brmedia'),
                    'type'    => 'textarea',
                    'default' => ''
                )
            )
        );

        return apply_filters('brmedia_settings_fields', $settings_fields);
    }

    public function plugin_page() {
        echo '<div class="wrap brmedia-settings-wrap">';
        
        echo '<h1><i class="fas fa-cog"></i> ' . __('BRMedia Settings', 'brmedia') . '</h1>';
        
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        
        echo '</div>';
    }

    /**
     * Get all the pages
     */
    public function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ($pages) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }

    /**
     * Get the value of a settings field
     */
    public static function get_option($option, $section, $default = '') {
        $options = get_option($section);

        if (isset($options[$option])) {
            return $options[$option];
        }

        return $default;
    }
}