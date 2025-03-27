<?php
/**
 * Handles deactivation tasks for BRMedia plugin
 */

class BRMedia_Deactivator {
    public static function deactivate() {
        // Clear scheduled hooks
        self::clear_scheduled_events();
        
        // Remove capabilities (optional - commented out by default)
        // self::remove_capabilities();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Optionally clean up options (commented out by default)
        // self::cleanup_options();
    }

    private static function clear_scheduled_events() {
        $crons = array(
            'brmedia_daily_stats_cleanup',
            'brmedia_hourly_usage_tracking'
        );
        
        foreach ($crons as $cron) {
            wp_clear_scheduled_hook($cron);
        }
    }

    private static function remove_capabilities() {
        $roles = array('administrator', 'editor');
        
        foreach ($roles as $role_name) {
            $role = get_role($role_name);
            
            if ($role) {
                // Music capabilities
                $role->remove_cap('manage_music');
                $role->remove_cap('edit_music');
                $role->remove_cap('edit_music_tracks');
                $role->remove_cap('edit_others_music_tracks');
                $role->remove_cap('publish_music_tracks');
                $role->remove_cap('read_private_music_tracks');
                $role->remove_cap('delete_music_tracks');
                
                // Video capabilities
                $role->remove_cap('manage_videos');
                $role->remove_cap('edit_videos');
                $role->remove_cap('edit_video_items');
                $role->remove_cap('edit_others_video_items');
                $role->remove_cap('publish_video_items');
                $role->remove_cap('read_private_video_items');
                $role->remove_cap('delete_video_items');
                
                // BRMedia specific
                $role->remove_cap('manage_brmedia_settings');
            }
        }
    }

    private static function cleanup_options() {
        $options = array(
            // Core options
            'brmedia_version',
            'brmedia_activated_time',
            
            // Player settings
            'brmedia_player_skin',
            'brmedia_default_volume',
            'brmedia_enable_sharing',
            'brmedia_social_platforms',
            'brmedia_color_primary',
            'brmedia_color_secondary',
            'brmedia_waveform_color',
            'brmedia_enable_airplay',
            'brmedia_enable_cast',
            'brmedia_marquee_speed',
            'brmedia_marquee_direction',
            
            // Template settings
            'brmedia_template_settings',
            
            // Stats settings
            'brmedia_stats_last_cleaned'
        );
        
        foreach ($options as $option) {
            delete_option($option);
        }
        
        // Remove transients
        $transients = array(
            'brmedia_usage_stats',
            'brmedia_popular_tracks'
        );
        
        foreach ($transients as $transient) {
            delete_transient($transient);
        }
    }

    public static function uninstall() {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            exit;
        }
        
        // Only run uninstall routines if configured to do so
        if (get_option('brmedia_cleanup_on_uninstall') === 'yes') {
            self::complete_cleanup();
        }
    }

    private static function complete_cleanup() {
        global $wpdb;
        
        // Remove all plugin options
        self::cleanup_options();
        
        // Drop custom tables
        $tables = array(
            $wpdb->prefix . 'brmedia_play_stats',
            $wpdb->prefix . 'brmedia_downloads'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
        
        // Remove custom post type content
        $post_types = array('brmedia_music', 'brmedia_video');
        
        foreach ($post_types as $post_type) {
            $items = get_posts(array(
                'post_type' => $post_type,
                'post_status' => 'any',
                'numberposts' => -1,
                'fields' => 'ids'
            ));
            
            foreach ($items as $item) {
                wp_delete_post($item, true);
            }
        }
        
        // Remove taxonomies
        $taxonomies = array('brmedia_genre', 'brmedia_video_category');
        
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
                'fields' => 'ids'
            ));
            
            foreach ($terms as $term) {
                wp_delete_term($term, $taxonomy);
            }
        }
    }
}

// Register uninstall hook
register_uninstall_hook(BRMEDIA_BASENAME, array('BRMedia_Deactivator', 'uninstall'));