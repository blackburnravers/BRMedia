<?php
/**
 * Vertical Player Template
 */
$cover_url = get_the_post_thumbnail_url($track_id, 'medium');
?>
<div class="brmedia-player template-vertical">
    <div class="cover-art" style="background-image: url('<?= esc_url($cover_url) ?>')"></div>
    
    <div class="track-info">
        <div class="main-controls">
            <button class="btn-prev"><i class="fas fa-step-backward"></i></button>
            <button class="btn-play"><i class="fas fa-play"></i></button>
            <button class="btn-next"><i class="fas fa-step-forward"></i></button>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <label><?php esc_html_e('BPM', 'brmedia') ?></label>
                <div class="value"><?= esc_html($meta['bpm']) ?></div>
            </div>
            <div class="meta-item">
                <label><?php esc_html_e('Key', 'brmedia') ?></label>
                <div class="value"><?= esc_html($meta['key']) ?></div>
            </div>
        </div>

        <?php brmedia_progress_bar(); ?>
    </div>

    <div class="social-bar">
        <button class="btn-like"><i class="fas fa-heart"></i></button>
        <button class="btn-share"><i class="fas fa-share"></i></button>
        <button class="btn-playlist"><i class="fas fa-list-ul"></i></button>
    </div>
</div>