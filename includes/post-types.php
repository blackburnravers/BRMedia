<?php
/**
 * BRMedia Custom Post Types
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'brmedia_register_post_types' );

function brmedia_register_post_types() {

    // Music CPT
    register_post_type( 'brmedia_music', array(
        'labels' => array(
            'name'               => __( 'Music', 'brmedia' ),
            'singular_name'      => __( 'Track', 'brmedia' ),
            'add_new'            => __( 'Add New Track', 'brmedia' ),
            'add_new_item'       => __( 'Add New Music Track', 'brmedia' ),
            'edit_item'          => __( 'Edit Track', 'brmedia' ),
            'new_item'           => __( 'New Track', 'brmedia' ),
            'view_item'          => __( 'View Track', 'brmedia' ),
            'search_items'       => __( 'Search Music', 'brmedia' ),
            'not_found'          => __( 'No tracks found', 'brmedia' ),
            'not_found_in_trash' => __( 'No tracks in trash', 'brmedia' )
        ),
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-format-audio',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => true,
        'rewrite'            => array( 'slug' => 'music' ),
        'menu_position'      => 25
    ) );

    // Video CPT
    register_post_type( 'brmedia_video', array(
        'labels' => array(
            'name'               => __( 'Videos', 'brmedia' ),
            'singular_name'      => __( 'Video', 'brmedia' ),
            'add_new'            => __( 'Add New Video', 'brmedia' ),
            'add_new_item'       => __( 'Add New Video', 'brmedia' ),
            'edit_item'          => __( 'Edit Video', 'brmedia' ),
            'new_item'           => __( 'New Video', 'brmedia' ),
            'view_item'          => __( 'View Video', 'brmedia' ),
            'search_items'       => __( 'Search Videos', 'brmedia' ),
            'not_found'          => __( 'No videos found', 'brmedia' ),
            'not_found_in_trash' => __( 'No videos in trash', 'brmedia' )
        ),
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-format-video',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => true,
        'rewrite'            => array( 'slug' => 'videos' ),
        'menu_position'      => 26
    ) );
}