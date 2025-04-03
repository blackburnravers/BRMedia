<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id       = get_the_ID();
$settings      = get_option('brmedia_video_templates_settings');
$video_url     = get_post_meta($post_id, '_brmedia_video_file', true);
$tracklist     = get_post_meta($post_id, '_brmedia_tracklist', true);
$cover_image   = get_the_post_thumbnail_url($post_id, 'large');
$title         = get_the_title($post_id);

$play_icon     = $settings['video_popup_play_icon'] ?? 'fas fa-play';
$control_color = $settings['video_popup_control_color'] ?? '#0073aa';
?>

<div class="brmedia-video-popup-wrapper" data-post-id="<?php echo esc_attr($post_id); ?>">
    <button class="brmedia-popup-open-btn" style="color: <?php echo esc_attr($control_color); ?>;">
        <i class="<?php echo esc_attr($play_icon); ?>"></i> Open Video Player
    </button>

    <div id="brmedia-popup-player" class="brmedia-popup-overlay" style="display: none;">
        <div class="brmedia-popup-header">
            <button class="popup-close-btn"><i class="fas fa-times"></i></button>
        </div>

        <div class="brmedia-popup-body">
            <?php if ($cover_image): ?>
                <div class="popup-cover">
                    <img src="<?php echo esc_url($cover_image); ?>" alt="Cover Image">
                </div>
            <?php endif; ?>

            <video class="plyr" controls>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <?php if (!empty($tracklist)): ?>
                <div class="popup-tracklist">
                    <h4>Tracklist</h4>
                    <pre><?php echo esc_html($tracklist); ?></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>