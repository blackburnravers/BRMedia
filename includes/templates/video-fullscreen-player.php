<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id      = get_the_ID();
$settings     = get_option('brmedia_video_templates_settings');
$video_url    = get_post_meta($post_id, '_brmedia_video_file', true);
$cover_image  = get_the_post_thumbnail_url($post_id, 'full');
$title        = get_the_title($post_id);

$close_icon   = $settings['video_fullscreen_close_icon'] ?? 'fas fa-times';
$control_color = $settings['video_fullscreen_control_color'] ?? '#ffffff';
?>

<div id="brmedia-fullscreen-player" class="brmedia-fullscreen-overlay" style="display:none;" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-fullscreen-header">
        <button class="fullscreen-close-btn" style="color: <?php echo esc_attr($control_color); ?>;">
            <i class="<?php echo esc_attr($close_icon); ?>"></i>
        </button>
    </div>

    <div class="brmedia-fullscreen-video-container">
        <?php if ($cover_image): ?>
            <div class="brmedia-cover-image">
                <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($title); ?>">
            </div>
        <?php endif; ?>

        <video class="plyr" controls playsinline>
            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<style>
#brmedia-fullscreen-player {
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    background: #000;
    z-index: 9999;
    padding: 30px;
    box-sizing: border-box;
    overflow-y: auto;
    display: none;
    color: #fff;
    font-family: Arial, sans-serif;
}

.brmedia-fullscreen-header {
    position: absolute;
    top: 15px;
    right: 20px;
}

.fullscreen-close-btn {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.fullscreen-close-btn:hover {
    color: #ff5252;
}

.brmedia-fullscreen-video-container {
    max-width: 960px;
    margin: 80px auto 0;
    text-align: center;
}

.brmedia-fullscreen-video-container video {
    width: 100%;
    max-height: 500px;
    border-radius: 6px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.3);
    margin-top: 20px;
}

.brmedia-cover-image img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>