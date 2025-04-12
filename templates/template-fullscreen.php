<?php
/**
 * Fullscreen Player Template
 */
$cover_url = get_the_post_thumbnail_url($track_id, 'full');
?>
<div class="brmedia-player template-fullscreen">
    <div class="fullscreen-background" style="background-image: url('<?= esc_url($cover_url) ?>')">
        <div class="background-overlay"></div>
    </div>

    <div class="fullscreen-content">
        <div class="now-playing">
            <div class="cover-art"></div>
            <div class="track-details">
                <h1 class="track-title"><?= esc_html($track->post_title) ?></h1>
                <h2 class="track-artist"><?= esc_html($meta['artist']) ?></h2>
                <h3 class="track-album"><?= esc_html($meta['album']) ?></h3>
            </div>
        </div>

        <div class="fullscreen-controls">
            <div class="left-controls">
                <button class="btn-like"><i class="fas fa-heart"></i></button>
                <button class="btn-share"><i class="fas fa-share"></i></button>
            </div>

            <div class="center-controls">
                <button class="btn-skip-prev"><i class="fas fa-step-backward"></i></button>
                <button class="btn-play"><i class="fas fa-play"></i></button>
                <button class="btn-skip-next"><i class="fas fa-step-forward"></i></button>
            </div>

            <div class="right-controls">
                <button class="btn-queue"><i class="fas fa-list-ol"></i></button>
                <button class="btn-devices"><i class="fas fa-broadcast-tower"></i></button>
            </div>
        </div>

        <div class="fullscreen-meta">
            <?php brmedia_metadata_display($track_id); ?>
            <div class="bpm-display">
                <div class="bpm-value"><?= esc_html($meta['bpm']) ?></div>
                <div class="bpm-label">BPM</div>
            </div>
        </div>
    </div>

    <button class="btn-exit-fullscreen">
        <i class="fas fa-compress"></i>
    </button>

    <script>
    document.querySelector('.btn-exit-fullscreen').addEventListener('click', () => {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    });
    </script>
</div>