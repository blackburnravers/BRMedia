<?php
/**
 * BRMedia Asset Loader
 * Manages all plugin CSS/JS assets
 */

class BRMedia_Asset_Loader {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Frontend assets
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
        
        // Admin assets
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
        
        // Media uploader enhancements
        add_action('admin_enqueue_scripts', array($this, 'enhance_media_uploader'));
        
        // Gutenberg block assets
        add_action('enqueue_block_editor_assets', array($this, 'load_block_assets'));
    }

    /**
     * Load frontend assets
     */
    public function load_frontend_assets() {
        // Main player CSS
        wp_enqueue_style(
            'brmedia-player',
            BRMEDIA_URL . 'assets/css/player.css',
            array(),
            BRMEDIA_VERSION
        );

        // Font Awesome (using Kit for better performance)
        wp_enqueue_style(
            'brmedia-fontawesome',
            'https://kit.fontawesome.com/' . apply_filters('brmedia_fa_kit_id', 'YOUR_KIT_ID') . '.css',
            array(),
            null
        );

        // Waveform.js (only loaded when needed)
        if (apply_filters('brmedia_load_waveform_js', true)) {
            wp_enqueue_script(
                'brmedia-waveform',
                BRMEDIA_URL . 'assets/js/waveform.min.js',
                array(),
                BRMEDIA_VERSION,
                true
            );
        }

        // Main player JS
        wp_enqueue_script(
            'brmedia-player',
            BRMEDIA_URL . 'assets/js/player.js',
            array('jquery', 'wp-util'),
            BRMEDIA_VERSION,
            true
        );

        // Localize script data
        wp_localize_script('brmedia-player', 'BRMediaConfig', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brmedia_ajax'),
            'assets' => BRMEDIA_URL . 'assets/',
            'strings' => array(
                'play' => __('Play', 'brmedia'),
                'pause' => __('Pause', 'brmedia')
            )
        ));
    }

    /**
     * Load admin assets
     */
    public function load_admin_assets($hook) {
        // Only load on BRMedia pages
        if (strpos($hook, 'brmedia') === false) {
            return;
        }

        // Admin CSS
        wp_enqueue_style(
            'brmedia-admin',
            BRMEDIA_URL . 'assets/css/admin.css',
            array('wp-color-picker'),
            BRMEDIA_VERSION
        );

        // Font Awesome for admin
        wp_enqueue_style(
            'brmedia-fontawesome-admin',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
            array(),
            '6.4.0'
        );

        // Admin JS
        wp_enqueue_script(
            'brmedia-admin',
            BRMEDIA_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker', 'jquery-ui-sortable'),
            BRMEDIA_VERSION,
            true
        );

        // Localize admin script
        wp_localize_script('brmedia-admin', 'BRMediaAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brmedia_admin_nonce'),
            'media_frame_title' => __('Select Cover Art', 'brmedia'),
            'media_frame_button' => __('Use as Cover', 'brmedia'),
            'i18n' => array(
                'confirm_reset' => __('Are you sure you want to reset these settings?', 'brmedia'),
                'timestamp_format_error' => __('Please use 00:00:00 format', 'brmedia')
            )
        ));

        // Shortcode manager specific assets
        if (strpos($hook, 'brmedia-shortcodes') !== false) {
            wp_enqueue_script(
                'brmedia-shortcode-manager',
                BRMEDIA_URL . 'assets/js/shortcode-manager.js',
                array('brmedia-admin'),
                BRMEDIA_VERSION,
                true
            );
        }

        // Template customizer specific assets
        if (strpos($hook, 'brmedia-template') !== false) {
            wp_enqueue_script(
                'brmedia-template-customizer',
                BRMEDIA_URL . 'assets/js/template-customizer.js',
                array('brmedia-admin', 'wp-util'),
                BRMEDIA_VERSION,
                true
            );
        }
    }

    /**
     * Enhance media uploader for cover art
     */
    public function enhace_media_uploader() {
        if (get_current_screen()->id === 'brmedia_music') {
            wp_enqueue_media();
            
            wp_enqueue_script(
                'brmedia-media-uploader',
                BRMEDIA_URL . 'assets/js/media-uploader.js',
                array('jquery', 'media-upload'),
                BRMEDIA_VERSION,
                true
            );
        }
    }

    /**
     * Load Gutenberg block assets
     */
    public function load_block_assets() {
        wp_enqueue_style(
            'brmedia-block-editor',
            BRMEDIA_URL . 'assets/css/block-editor.css',
            array('wp-edit-blocks'),
            BRMEDIA_VERSION
        );

        wp_enqueue_script(
            'brmedia-blocks',
            BRMEDIA_URL . 'assets/js/blocks.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor'),
            BRMEDIA_VERSION,
            true
        );

        wp_localize_script('brmedia-blocks', 'BRMediaBlocks', array(
            'music_tracks' => $this->get_music_tracks_for_blocks(),
            'videos' => $this->get_videos_for_blocks(),
            'placeholder' => BRMEDIA_URL . 'assets/images/block-preview.jpg'
        ));

        wp_set_script_translations('brmedia-blocks', 'brmedia');
    }

    /**
     * Get music tracks for block selector
     */
    private function get_music_tracks_for_blocks() {
        $tracks = get_posts(array(
            'post_type' => 'brmedia_music',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        return array_map(function($track) {
            return array(
                'value' => $track->ID,
                'label' => $track->post_title,
                'artwork' => get_the_post_thumbnail_url($track->ID, 'thumbnail')
            );
        }, $tracks);
    }

    /**
     * Get videos for block selector
     */
    private function get_videos_for_blocks() {
        $videos = get_posts(array(
            'post_type' => 'brmedia_video',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        return array_map(function($video) {
            return array(
                'value' => $video->ID,
                'label' => $video->post_title,
                'thumbnail' => get_the_post_thumbnail_url($video->ID, 'thumbnail')
            );
        }, $videos);
    }

    /**
     * Conditionally load waveform visualizer
     */
    public static function load_waveform_assets() {
        wp_enqueue_script('brmedia-waveform');
        wp_enqueue_script(
            'brmedia-waveform-init',
            BRMEDIA_URL . 'assets/js/waveform-init.js',
            array('brmedia-waveform'),
            BRMEDIA_VERSION,
            true
        );
    }

    /**
     * Conditionally load visualization assets
     */
    public static function load_visualization_assets() {
        wp_enqueue_script(
            'brmedia-visualizer',
            BRMEDIA_URL . 'assets/js/visualizer.js',
            array('jquery'),
            BRMEDIA_VERSION,
            true
        );
    }
}