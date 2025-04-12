<?php
/**
 * Compact Player Template
 */
$track = get_post($track_id);
$meta = BRMedia_Core::instance()->templates->get_track_meta($track_id);
$styles = BRMedia_Core::instance()->templates->get_custom_styles();
?>
<div class="brmedia-player template-compact" 
     style="--player-bg: <?= esc_attr($styles['player_bg']) ?>;
            --text-color: <?= esc_attr($styles['text_color']) ?>;">
    
    <div class="player-header">
        <h3 class="track-title"><?= esc_html($track->post_title) ?></h3>
        <div class="track-artist"><?= esc_html($meta['artist']) ?></div>
    </div>

    <div class="player-body">
        <?php brmedia_control_buttons(); ?>
        <?php brmedia_progress_bar(); ?>
    </div>

    <div class="player-footer">
        <?php brmedia_metadata_display($track_id); ?>
        <div class="secondary-controls">
            <button class="btn-volume">
                <i class="fas fa-volume-up"></i>
                <input type="range" class="volume-slider" min="0" max="100">
            </button>
            <button class="btn-fullscreen">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        BRMedia.initPlayer({
            trackId: <?= (int)$track_id ?>,
            element: this.closest('.brmedia-player'),
            config: <?= json_encode($config) ?>
        });
    });
    </script>
</div>