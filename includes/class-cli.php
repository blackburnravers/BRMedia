<?php
class BRMedia_CLI {

    /**
     * Clear all cached data
     */
    public function clear_cache() {
        global $wpdb;
        
        $count = $wpdb->query(
            "DELETE FROM $wpdb->options 
             WHERE option_name LIKE '_transient_brmedia_%'"
        );
        
        WP_CLI::success("Cleared {$count} cached items");
    }

    /**
     * Reanalyze audio files
     * 
     * @synopsis [--force]
     */
    public function reanalyze($args, $assoc_args) {
        $force = isset($assoc_args['force']);
        $analyzer = new BRMedia_Audio_Analyzer();
        
        $tracks = get_posts([
            'post_type' => 'brmedia_track',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);
        
        $progress = WP_CLI\Utils\make_progress_bar('Analyzing tracks', count($tracks));
        
        foreach ($tracks as $track_id) {
            if ($force || !get_post_meta($track_id, '_brmedia_bpm', true)) {
                $analyzer->process_track($track_id);
            }
            $progress->tick();
        }
        
        $progress->finish();
        WP_CLI::success('Audio analysis complete');
    }
}