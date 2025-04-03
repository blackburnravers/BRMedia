<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$settings = get_option('brmedia_audio_templates_settings');

$audio_url     = get_post_meta($post_id, '_brmedia_audio_file', true);
$cover_image   = get_the_post_thumbnail_url($post_id, 'thumbnail');
$title         = get_the_title($post_id);
$show_download = !empty(get_post_meta($post_id, '_brmedia_download_enabled', true));

// Settings
$play_icon     = $settings['audio_minibar_play_icon'] ?? 'fas fa-play';
$pause_icon    = $settings['audio_minibar_pause_icon'] ?? 'fas fa-pause';
$download_icon = $settings['audio_minibar_download_icon'] ?? 'fas fa-download';
$control_color = $settings['audio_minibar_control_color'] ?? '#ffffff';
$background    = $settings['audio_minibar_bg_color'] ?? '#222';
$text_color    = $settings['audio_minibar_text_color'] ?? '#fff';
?>
<div class="brmedia-mini-bar-player" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-mini-inner" style="background: <?php echo esc_attr($background); ?>; color: <?php echo esc_attr($text_color); ?>;">
        <?php if ($cover_image): ?>
            <div class="brmedia-mini-thumb">
                <img src="<?php echo esc_url($cover_image); ?>" alt="Cover">
            </div>
        <?php endif; ?>

        <div class="brmedia-mini-info">
            <strong class="brmedia-mini-title"><?php echo esc_html($title); ?></strong>
            <audio id="minibar-audio-<?php echo esc_attr($post_id); ?>" class="plyr" controls>
                <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <div class="brmedia-mini-actions">
            <button class="brmedia-mini-toggle" style="color: <?php echo esc_attr($control_color); ?>;">
                <i class="<?php echo esc_attr($play_icon); ?>"></i>
            </button>

            <?php if ($show_download): ?>
                <a class="brmedia-mini-download" href="<?php echo esc_url($audio_url); ?>" download title="Download">
                    <i class="<?php echo esc_attr($download_icon); ?>"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.brmedia-mini-bar-player {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.2);
}

.brmedia-mini-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    box-sizing: border-box;
    gap: 12px;
    font-size: 14px;
}

.brmedia-mini-thumb img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 4px;
}

.brmedia-mini-info {
    flex: 1;
    overflow: hidden;
}

.brmedia-mini-title {
    display: block;
    margin-bottom: 4px;
    font-size: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brmedia-mini-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.brmedia-mini-toggle,
.brmedia-mini-download {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: inherit;
}

.brmedia-mini-toggle:hover,
.brmedia-mini-download:hover {
    opacity: 0.75;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('minibar-audio-<?php echo esc_js($post_id); ?>');
    const toggle = document.querySelector('.brmedia-mini-toggle');

    if (audio && toggle) {
        toggle.addEventListener('click', function () {
            if (audio.paused) {
                audio.play();
                this.innerHTML = '<i class="<?php echo esc_js($pause_icon); ?>"></i>';
            } else {
                audio.pause();
                this.innerHTML = '<i class="<?php echo esc_js($play_icon); ?>"></i>';
            }
        });

        audio.addEventListener('ended', function () {
            toggle.innerHTML = '<i class="<?php echo esc_js($play_icon); ?>"></i>';
        });
    }
});
</script>