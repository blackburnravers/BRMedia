<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$settings = get_option('brmedia_audio_templates_settings');

$audio_url     = get_post_meta($post_id, '_brmedia_audio_file', true);
$cover_image   = get_the_post_thumbnail_url($post_id, 'full');
$title         = get_the_title($post_id);
$tracklist_raw = get_post_meta($post_id, '_brmedia_tracklist', true);

// Settings
$play_icon     = $settings['audio_modern_play_icon'] ?? 'fas fa-play';
$pause_icon    = $settings['audio_modern_pause_icon'] ?? 'fas fa-pause';
$control_color = $settings['audio_modern_control_color'] ?? '#1e87f0';
$progress_color = $settings['audio_modern_progress_color'] ?? '#ff4081';
?>
<div class="brmedia-modern-player" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-modern-inner">
        <?php if ($cover_image): ?>
            <div class="brmedia-modern-cover">
                <img src="<?php echo esc_url($cover_image); ?>" alt="Cover image">
            </div>
        <?php endif; ?>

        <div class="brmedia-modern-details">
            <div class="brmedia-modern-title"><?php echo esc_html($title); ?></div>

            <div class="brmedia-modern-controls">
                <button class="brmedia-modern-play-toggle" style="color: <?php echo esc_attr($control_color); ?>;">
                    <i class="<?php echo esc_attr($play_icon); ?>"></i>
                </button>
                <audio id="brmedia-audio-<?php echo esc_attr($post_id); ?>" class="plyr" controls>
                    <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>

            <?php if (!empty($tracklist_raw)): ?>
                <div class="brmedia-modern-tracklist">
                    <pre><?php echo esc_html($tracklist_raw); ?></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.brmedia-modern-player {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 30px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.brmedia-modern-inner {
    display: flex;
    flex-wrap: wrap;
}

.brmedia-modern-cover img {
    width: 250px;
    height: 250px;
    object-fit: cover;
}

.brmedia-modern-details {
    flex: 1;
    padding: 20px;
    box-sizing: border-box;
    min-width: 260px;
}

.brmedia-modern-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #222;
}

.brmedia-modern-controls {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.brmedia-modern-play-toggle {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.brmedia-modern-play-toggle:hover {
    transform: scale(1.2);
}

.brmedia-modern-tracklist {
    background: #f9f9f9;
    padding: 12px;
    border-radius: 6px;
    max-height: 200px;
    overflow-y: auto;
    font-size: 14px;
    color: #333;
}

/* Plyr customization */
.plyr--audio .plyr__progress input[type=range] {
    color: <?php echo esc_attr($progress_color); ?>;
}

@media (max-width: 768px) {
    .brmedia-modern-inner {
        flex-direction: column;
    }

    .brmedia-modern-cover img {
        width: 100%;
        height: auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.brmedia-modern-player[data-post-id="<?php echo esc_js($post_id); ?>"]');
    const audio = container.querySelector('audio');
    const toggle = container.querySelector('.brmedia-modern-play-toggle');
    const playIcon = '<?php echo esc_js($play_icon); ?>';
    const pauseIcon = '<?php echo esc_js($pause_icon); ?>';

    toggle.addEventListener('click', function () {
        if (audio.paused) {
            audio.play();
            toggle.innerHTML = `<i class="${pauseIcon}"></i>`;
        } else {
            audio.pause();
            toggle.innerHTML = `<i class="${playIcon}"></i>`;
        }
    });

    audio.addEventListener('ended', function () {
        toggle.innerHTML = `<i class="${playIcon}"></i>`;
    });
});
</script>