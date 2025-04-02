<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id      = get_the_ID();
$settings     = get_option('brmedia_video_templates_settings');
$video_url    = get_post_meta($post_id, '_brmedia_video_file', true);
$cover_image  = get_the_post_thumbnail_url($post_id, 'full');
$title        = get_the_title($post_id);

// Control icon settings
$show_play    = !empty($settings['video_default_show_play']);
$play_icon    = $settings['video_default_play_icon'] ?? 'fas fa-play';
$fullscreen_icon = $settings['video_default_fullscreen_icon'] ?? 'fas fa-expand';
$popup_icon   = $settings['video_default_popup_icon'] ?? 'fas fa-window-restore';

$control_color = $settings['video_default_control_color'] ?? '#0073aa';
?>

<div class="brmedia-video-player brmedia-template-default" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($cover_image): ?>
        <div class="brmedia-video-thumbnail">
            <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($title); ?>" class="brmedia-cover-img">
        </div>
    <?php endif; ?>

    <div class="brmedia-video-wrapper">
        <video class="plyr" controls playsinline>
            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="brmedia-controls" style="color: <?php echo esc_attr($control_color); ?>;">
            <?php if ($show_play): ?>
                <button class="brmedia-play-btn"><i class="<?php echo esc_attr($play_icon); ?>"></i></button>
            <?php endif; ?>
            <button class="brmedia-popup-open-btn"><i class="<?php echo esc_attr($popup_icon); ?>"></i></button>
            <button class="brmedia-fullscreen-btn"><i class="<?php echo esc_attr($fullscreen_icon); ?>"></i></button>
        </div>
    </div>
</div>

<style>
.brmedia-video-player {
    font-family: Arial, sans-serif;
    margin-bottom: 30px;
    position: relative;
    background: #f8f8f8;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

.brmedia-video-thumbnail img {
    width: 100%;
    height: auto;
    display: block;
    border-bottom: 1px solid #ddd;
}

.brmedia-video-wrapper {
    position: relative;
    padding: 15px;
    background: #000;
}

.brmedia-video-wrapper video {
    width: 100%;
    border-radius: 4px;
}

.brmedia-controls {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.brmedia-controls button {
    background: none;
    border: none;
    font-size: 24px;
    color: inherit;
    cursor: pointer;
    transition: color 0.3s ease;
}

.brmedia-controls button:hover {
    color: #1e87f0;
}
</style>