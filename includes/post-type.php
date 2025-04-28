<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register custom post type
function brmedia_register_post_type() {
    $labels = array(
        'name'               => 'Music Tracks',
        'singular_name'      => 'Track',
        'menu_name'          => 'Music Tracks',
        'name_admin_bar'     => 'Track',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Track',
        'new_item'           => 'New Track',
        'edit_item'          => 'Edit Track',
        'view_item'          => 'View Track',
        'all_items'          => 'All Tracks',
        'search_items'       => 'Search Tracks',
        'not_found'          => 'No tracks found.',
        'not_found_in_trash' => 'No tracks found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_menu'       => false, 
        'rewrite'            => array('slug' => 'tracks'),
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-format-audio',
    );

    register_post_type('brmedia_track', $args);
}
add_action('init', 'brmedia_register_post_type');