<?php
/**
 * Handles core functionality for BRMedia plugin
 */

class BRMedia_Core {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Register custom post types
        add_action('init', array($this, 'register_custom_post_types'));
        
        // Register taxonomies
        add_action('init', array($this, 'register_taxonomies'));
        
        // Register custom metadata
        add_action('init', array($this, 'register_metadata_fields'));
        
        // Initialize shortcode system
        $this->init_shortcodes();
        
        // Setup template system
        add_filter('template_include', array($this, 'custom_templates'));
    }

    public function register_custom_post_types() {
        // Music Tracks CPT
        register_post_type('brmedia_music',
            array(
                'labels' => array(
                    'name'               => __('Music Tracks', 'brmedia'),
                    'singular_name'      => __('Music Track', 'brmedia'),
                    'menu_name'          => __('Music', 'brmedia'),
                    'add_new'            => __('Add New Track', 'brmedia'),
                    'add_new_item'       => __('Add New Music Track', 'brmedia'),
                    'edit_item'          => __('Edit Track', 'brmedia'),
                    'new_item'           => __('New Track', 'brmedia'),
                    'view_item'          => __('View Track', 'brmedia'),
                    'search_items'       => __('Search Tracks', 'brmedia'),
                    'not_found'          => __('No tracks found', 'brmedia'),
                    'not_found_in_trash' => __('No tracks found in Trash', 'brmedia')
                ),
                'public'              => true,
                'has_archive'         => true,
                'rewrite'             => array('slug' => 'music-tracks'),
                'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields', 'comments'),
                'menu_icon'          => 'dashicons-format-audio',
                'show_in_rest'        => true,
                'capability_type'     => 'post',
                'show_in_menu'        => false // Will be shown in our custom admin menu
            )
        );

        // Videos CPT
        register_post_type('brmedia_video',
            array(
                'labels' => array(
                    'name'               => __('Videos', 'brmedia'),
                    'singular_name'      => __('Video', 'brmedia'),
                    'menu_name'          => __('Videos', 'brmedia'),
                    'add_new'            => __('Add New Video', 'brmedia'),
                    'add_new_item'       => __('Add New Video', 'brmedia'),
                    'edit_item'          => __('Edit Video', 'brmedia'),
                    'new_item'           => __('New Video', 'brmedia'),
                    'view_item'          => __('View Video', 'brmedia'),
                    'search_items'       => __('Search Videos', 'brmedia'),
                    'not_found'          => __('No videos found', 'brmedia'),
                    'not_found_in_trash' => __('No videos found in Trash', 'brmedia')
                ),
                'public'              => true,
                'has_archive'         => true,
                'rewrite'             => array('slug' => 'videos'),
                'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields', 'comments'),
                'menu_icon'          => 'dashicons-video-alt3',
                'show_in_rest'        => true,
                'capability_type'     => 'post',
                'show_in_menu'        => false // Will be shown in our custom admin menu
            )
        );
    }

    public function register_taxonomies() {
        // Music Genres
        register_taxonomy('brmedia_genre', 'brmedia_music',
            array(
                'labels' => array(
                    'name' => __('Genres', 'brmedia'),
                    'singular_name' => __('Genre', 'brmedia')
                ),
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'music-genre')
            )
        );

        // Video Categories
        register_taxonomy('brmedia_video_category', 'brmedia_video',
            array(
                'labels' => array(
                    'name' => __('Video Categories', 'brmedia'),
                    'singular_name' => __('Video Category', 'brmedia')
                ),
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => array('slug' => 'video-category')
            )
        );
    }

    public function register_metadata_fields() {
        // Music track metadata
        register_post_meta('brmedia_music', 'brmedia_track_file', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string'
        ));

        register_post_meta('brmedia_music', 'brmedia_track_duration', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string'
        ));

        register_post_meta('brmedia_music', 'brmedia_tracklist', array(
            'show_in_rest' => array(
                'schema' => array(
                    'type'  => 'array',
                    'items' => array(
                        'type' => 'object',
                        'properties' => array(
                            'timestamp' => array('type' => 'string'),
                            'title' => array('type' => 'string')
                        )
                    )
                )
            ),
            'single' => true,
            'type' => 'array'
        ));

        // Video metadata
        register_post_meta('brmedia_video', 'brmedia_video_file', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string'
        ));

        register_post_meta('brmedia_video', 'brmedia_video_duration', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string'
        ));
    }

    private function init_shortcodes() {
        add_shortcode('brmedia_music', array($this, 'music_player_shortcode'));
        add_shortcode('brmedia_video', array($this, 'video_player_shortcode'));
    }

    public function music_player_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'template' => 'stylish',
            'autoplay' => false
        ), $atts, 'brmedia_music');

        // Will be handled by frontend class
        ob_start();
        do_action('brmedia_render_music_player', $atts);
        return ob_get_clean();
    }

    public function video_player_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'autoplay' => false,
            'controls' => true
        ), $atts, 'brmedia_video');

        // Will be handled by frontend class
        ob_start();
        do_action('brmedia_render_video_player', $atts);
        return ob_get_clean();
    }

    public function custom_templates($template) {
        if (is_singular('brmedia_music')) {
            return BRMEDIA_PATH . 'templates/single-music.php';
        }

        if (is_singular('brmedia_video')) {
            return BRMEDIA_PATH . 'templates/single-video.php';
        }

        if (is_post_type_archive('brmedia_music')) {
            return BRMEDIA_PATH . 'templates/archive-music.php';
        }

        if (is_post_type_archive('brmedia_video')) {
            return BRMEDIA_PATH . 'templates/archive-video.php';
        }

        return $template;
    }

    public static function get_template($template_name, $data = array()) {
        $template_path = BRMEDIA_PATH . 'templates/' . $template_name . '.php';
        
        if (file_exists($template_path)) {
            extract($data);
            include $template_path;
        } else {
            trigger_error(sprintf(__('Template %s does not exist.', 'brmedia'), $template_path));
        }
    }
}