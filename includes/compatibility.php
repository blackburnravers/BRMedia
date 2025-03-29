<?php
/**
 * BRMedia Compatibility & Integration Loader
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'plugins_loaded', 'brmedia_check_compatibility' );

function brmedia_check_compatibility() {
    // Elementor Compatibility
    if ( defined( 'ELEMENTOR_PATH' ) ) {
        // Optionally register custom BRMedia widget in future
        add_action( 'elementor/widgets/register', 'brmedia_register_elementor_widget' );
    }

    // WPML Compatibility
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        // Register CPTs and taxonomies for translation
        do_action( 'wpml_register_single_string', 'BRMedia', 'Music CPT', 'brmedia_music' );
        do_action( 'wpml_register_single_string', 'BRMedia', 'Video CPT', 'brmedia_video' );
    }

    // Yoast SEO: Hide BRMedia from sitemap (optional)
    add_filter( 'wpseo_sitemap_exclude_post_type', 'brmedia_exclude_from_yoast_sitemap' );
}

function brmedia_register_elementor_widget( $widgets_manager ) {
    // Placeholder for future Elementor widget support
    // $widgets_manager->register( new \BRMedia_Elementor_Widget() );
}

function brmedia_exclude_from_yoast_sitemap( $exclude ) {
    $exclude[] = 'brmedia_music';
    $exclude[] = 'brmedia_video';
    return $exclude;
}