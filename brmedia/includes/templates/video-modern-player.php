<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id       = get_the_ID();
$settings      = get_option('brmedia_video_templates_settings');
$video_url     = get_post_meta($post_id, '_brmedia_video_file', true);
$tracklist     = get_post_meta($post_id, '_brmedia_tracklist', true);
$cover_image   = get_the_post_thumbnail_url($post_id, 'large');
$title         = get_the_title($post_id);

$play_icon     = $settings['video_modern_play_icon'] ?? 'fas fa-play-circle';
$control_color = $settings['video_modern_control_color'] ?? '#1e87f0';
?>

<div class="brmedia-video-modern-wrapper" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-video-modern-content">
        <?php if ($cover_image): ?>
            <div class="brmedia-modern-thumbnail">
                <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($title); ?>">
                <div class="brmedia-modern-overlay">
                    <i class="<?php echo esc_attr($play_icon); ?>" style="color: <?php echo esc_attr($control_color); ?>;"></i>
                </div>
            </div>
        <?php endif; ?>

        <div class="brmedia-modern-video">
            <video class="plyr" controls>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <?php if (!empty($tracklist)): ?>
            <div class="brmedia-modern-tracklist">
                <h4>Tracklist</h4>
                <pre><?php echo esc_html($tracklist); ?></pre>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.brmedia-video-modern-wrapper {
    font-family: 'Arial', sans-serif;
    margin-bottom: 40px;
}

.brmedia-modern-thumbnail {
    position: relative;
    display: block;
    overflow: hidden;
    border-radius: 8px;
    max-height: 300px;
}

.brmedia-modern-thumbnail img {
    width: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.brmedia-modern-thumbnail:hover img {
    transform: scale(1.03);
}

.brmedia-modern-overlay {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-size: 64px;
    pointer-events: none;
}

.brmedia-modern-video {
    margin-top: 20px;
}

.brmedia-modern-video video {
    width: 100%;
    max-height: 480px;
    border-radius: 6px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.brmedia-modern-tracklist {
    margin-top: 20px;
    background: #f7f7f7;
    padding: 15px;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.5;
    white-space: pre-wrap;
    max-height: 250px;
    overflow-y: auto;
}
</style>