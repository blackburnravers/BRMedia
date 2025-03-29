<?php
/**
 * BRMedia Custom Taxonomies
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'brmedia_register_taxonomies' );

function brmedia_register_taxonomies() {

    // Music Genre
    register_taxonomy( 'brmedia_genre', 'brmedia_music', array(
        'labels' => array(
            'name'              => __( 'Genres', 'brmedia' ),
            'singular_name'     => __( 'Genre', 'brmedia' ),
            'search_items'      => __( 'Search Genres', 'brmedia' ),
            'all_items'         => __( 'All Genres', 'brmedia' ),
            'edit_item'         => __( 'Edit Genre', 'brmedia' ),
            'update_item'       => __( 'Update Genre', 'brmedia' ),
            'add_new_item'      => __( 'Add New Genre', 'brmedia' ),
            'new_item_name'     => __( 'New Genre Name', 'brmedia' ),
            'menu_name'         => __( 'Genres', 'brmedia' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'genre' ),
        'show_in_rest'      => true,
    ) );

    // Mood (shared with music and video)
    register_taxonomy( 'brmedia_mood', array( 'brmedia_music', 'brmedia_video' ), array(
        'labels' => array(
            'name'              => __( 'Moods', 'brmedia' ),
            'singular_name'     => __( 'Mood', 'brmedia' ),
            'search_items'      => __( 'Search Moods', 'brmedia' ),
            'all_items'         => __( 'All Moods', 'brmedia' ),
            'edit_item'         => __( 'Edit Mood', 'brmedia' ),
            'update_item'       => __( 'Update Mood', 'brmedia' ),
            'add_new_item'      => __( 'Add New Mood', 'brmedia' ),
            'new_item_name'     => __( 'New Mood Name', 'brmedia' ),
            'menu_name'         => __( 'Moods', 'brmedia' ),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'mood' ),
        'show_in_rest'      => true,
    ) );

    // Video Category
    register_taxonomy( 'brmedia_video_category', 'brmedia_video', array(
        'labels' => array(
            'name'              => __( 'Video Categories', 'brmedia' ),
            'singular_name'     => __( 'Video Category', 'brmedia' ),
            'search_items'      => __( 'Search Categories', 'brmedia' ),
            'all_items'         => __( 'All Categories', 'brmedia' ),
            'edit_item'         => __( 'Edit Category', 'brmedia' ),
            'update_item'       => __( 'Update Category', 'brmedia' ),
            'add_new_item'      => __( 'Add New Category', 'brmedia' ),
            'new_item_name'     => __( 'New Category Name', 'brmedia' ),
            'menu_name'         => __( 'Categories', 'brmedia' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'video-category' ),
        'show_in_rest'      => true,
    ) );
}