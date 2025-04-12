<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Track_CPT {

    private $post_type = 'brmedia_track';

    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('init', [$this, 'register_taxonomies']);
        add_filter('manage_'.$this->post_type.'_posts_columns', [$this, 'custom_columns']);
        add_action('manage_'.$this->post_type.'_posts_custom_column', [$this, 'column_data'], 10, 2);
    }

    public function register_cpt() {
        $labels = [
            'name' => __('Tracks', 'brmedia'),
            'singular_name' => __('Track', 'brmedia'),
            'menu_name' => __('Music Library', 'brmedia'),
            'all_items' => __('All Tracks', 'brmedia')
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'thumbnail', 'comments'],
            'rewrite' => ['slug' => 'track'],
            'menu_icon' => 'dashicons-album',
            'show_in_rest' => true,
            'taxonomies' => ['genre', 'mood']
        ];

        register_post_type($this->post_type, $args);
    }

    public function register_taxonomies() {
        register_taxonomy('genre', $this->post_type, [
            'label' => __('Genres', 'brmedia'),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'genre']
        ]);

        register_taxonomy('mood', $this->post_type, [
            'label' => __('Moods', 'brmedia'),
            'hierarchical' => false,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'mood']
        ]);
    }

    public function custom_columns($columns) {
        return array_merge($columns, [
            'bpm' => __('BPM', 'brmedia'),
            'key' => __('Key', 'brmedia'),
            'duration' => __('Duration', 'brmedia')
        ]);
    }

    public function column_data($column, $post_id) {
        switch ($column) {
            case 'bpm':
                echo get_post_meta($post_id, '_brmedia_bpm', true);
                break;
            case 'key':
                echo get_post_meta($post_id, '_brmedia_key', true);
                break;
            case 'duration':
                $duration = get_post_meta($post_id, '_brmedia_duration', true);
                echo $duration ? gmdate("i:s", $duration) : '--:--';
                break;
        }
    }
}