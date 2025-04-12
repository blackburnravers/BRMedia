<?php
if (!defined('ABSPATH')) exit;

class BRMedia_Audio_Batch_Processor {

    private $batch_size = 10;

    public function __construct() {
        add_action('admin_post_brmedia_process_batch', [$this, 'handle_batch']);
        add_action('wp_ajax_brmedia_get_batch_status', [$this, 'get_batch_status']);
    }

    public function render_batch_page() {
        $total_tracks = wp_count_posts('brmedia_track')->publish;
        $processed = get_option('brmedia_batch_processed', 0);
        ?>
        <div class="brmedia-batch-processor">
            <div class="progress-container">
                <h3>Audio Analysis Progress</h3>
                <div class="progress-bar">
                    <div class="progress" style="width: <?= esc_attr(($processed / $total_tracks) * 100) ?>%"></div>
                </div>
                <p>
                    Processed <?= (int)$processed ?> of <?= (int)$total_tracks ?> tracks
                    (<span class="percentage"><?= round(($processed / $total_tracks) * 100) ?>%</span>)
                </p>
                <button id="brmediaStartBatch" class="button button-primary">
                    Start Processing
                </button>
            </div>
        </div>
        <?php
    }

    public function handle_batch() {
        check_admin_referer('brmedia_batch_nonce');

        $track_ids = get_posts([
            'post_type' => 'brmedia_track',
            'posts_per_page' => $this->batch_size,
            'offset' => get_option('brmedia_batch_processed', 0),
            'fields' => 'ids'
        ]);

        $analyzer = new BRMedia_Audio_Analyzer();

        foreach ($track_ids as $track_id) {
            $audio_file = get_post_meta($track_id, '_brmedia_audio_file', true);
            if ($audio_file) {
                $analysis = $analyzer->analyze($audio_file);
                update_post_meta($track_id, '_brmedia_bpm', $analysis['bpm']);
                update_post_meta($track_id, '_brmedia_key', $analysis['key']);
            }
            update_option('brmedia_batch_processed', get_option('brmedia_batch_processed', 0) + 1);
        }

        wp_send_json_success([
            'processed' => get_option('brmedia_batch_processed', 0),
            'total' => wp_count_posts('brmedia_track')->publish
        ]);
    }

    public function get_batch_status() {
        wp_send_json_success([
            'processed' => get_option('brmedia_batch_processed', 0),
            'total' => wp_count_posts('brmedia_track')->publish
        ]);
    }
}

new BRMedia_Audio_Batch_Processor();