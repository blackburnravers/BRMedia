<?php
/**
 * Minimalist Player Template
 */
?>
<div class="brmedia-player template-minimal">
    <div class="minimal-controls">
        <button class="btn-play-pause">
            <i class="fas fa-play"></i>
        </button>
        <div class="track-info">
            <div class="title-artist">
                <span class="title"><?= esc_html($track->post_title) ?></span>
                <span class="artist"><?= esc_html($meta['artist']) ?></span>
            </div>
            <?php brmedia_progress_bar(); ?>
        </div>
        <button class="btn-mute">
            <i class="fas fa-volume-up"></i>
        </button>
    </div>

    <script>
    document.querySelector('.template-minimal .btn-play-pause').addEventListener('click', () => {
        BRMedia.togglePlayback(<?= (int)$track_id ?>);
    });
    </script>
</div>