<?php
/**
 * Card Style Player Template
 */
$album = get_post_meta($track_id, '_brmedia_album', true);
?>
<div class="brmedia-player template-card">
    <div class="card-inner">
        <div class="card-front">
            <div class="cover-container">
                <?= get_the_post_thumbnail($track_id, 'large', [
                    'class' => 'cover-image',
                    'loading' => 'lazy'
                ]) ?>
                <button class="btn-play-overlay"><i class="fas fa-play"></i></button>
            </div>
            
            <div class="card-details">
                <h4 class="album-title"><?= esc_html($album) ?></h4>
                <h3 class="track-title"><?= esc_html($track->post_title) ?></h3>
                <div class="track-artist"><?= esc_html($meta['artist']) ?></div>
            </div>
        </div>

        <div class="card-back">
            <div class="back-content">
                <?php brmedia_control_buttons(); ?>
                <?php brmedia_progress_bar(); ?>
                <?php brmedia_metadata_display($track_id); ?>
            </div>
        </div>
    </div>

    <style>
    .template-card .card-inner {
        perspective: 1000px;
        transform-style: preserve-3d;
        transition: transform 0.6s;
    }
    .template-card:hover .card-inner {
        transform: rotateY(180deg);
    }
    </style>
</div>