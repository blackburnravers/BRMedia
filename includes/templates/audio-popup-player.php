<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$settings = get_option('brmedia_audio_templates_settings');

$audio_url   = get_post_meta($post_id, '_brmedia_audio_file', true);
$cover_image = get_the_post_thumbnail_url($post_id, 'full');
$title       = get_the_title($post_id);
$tracklist   = get_post_meta($post_id, '_brmedia_tracklist', true);

// Settings
$play_icon   = $settings['audio_popup_play_icon'] ?? 'fas fa-play';
$pause_icon  = $settings['audio_popup_pause_icon'] ?? 'fas fa-pause';
$control_color = $settings['audio_popup_control_color'] ?? '#ff4081';
?>

<!-- Trigger Button -->
<button class="brmedia-popup-open-btn" data-target="#brmedia-popup-player-<?php echo esc_attr($post_id); ?>">
    <i class="<?php echo esc_attr($play_icon); ?>"></i> Open Popup Player
</button>

<!-- Popup Modal -->
<div id="brmedia-popup-player-<?php echo esc_attr($post_id); ?>" class="brmedia-popup-overlay" style="display: none;" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-popup-content">
        <button class="popup-close-btn" data-target="#brmedia-popup-player-<?php echo esc_attr($post_id); ?>">&times;</button>

        <?php if ($cover_image): ?>
            <div class="popup-cover">
                <img src="<?php echo esc_url($cover_image); ?>" alt="Cover">
            </div>
        <?php endif; ?>

        <h2><?php echo esc_html($title); ?></h2>

        <div class="popup-controls">
            <button class="popup-play-toggle" style="color: <?php echo esc_attr($control_color); ?>;">
                <i class="<?php echo esc_attr($play_icon); ?>"></i>
            </button>
            <audio class="plyr" id="popup-audio-<?php echo esc_attr($post_id); ?>" controls>
                <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <?php if (!empty($tracklist)): ?>
            <div class="popup-tracklist">
                <pre><?php echo esc_html($tracklist); ?></pre>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.brmedia-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(20, 20, 20, 0.95);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    box-sizing: border-box;
}

.brmedia-popup-content {
    background: #fff;
    border-radius: 8px;
    padding: 25px;
    max-width: 700px;
    width: 100%;
    text-align: center;
    position: relative;
    color: #333;
    box-shadow: 0 5px 25px rgba(0,0,0,0.3);
}

.popup-cover img {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 15px;
}

.popup-close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    background: transparent;
    font-size: 28px;
    color: #333;
    border: none;
    cursor: pointer;
}

.popup-close-btn:hover {
    color: #ff5252;
}

.popup-play-toggle {
    background: none;
    border: none;
    font-size: 24px;
    margin-bottom: 10px;
    cursor: pointer;
}

.popup-tracklist {
    background: #f1f1f1;
    border-radius: 6px;
    padding: 15px;
    margin-top: 20px;
    font-size: 14px;
    text-align: left;
    max-height: 150px;
    overflow-y: auto;
}

.brmedia-popup-open-btn {
    padding: 10px 20px;
    background-color: #0073aa;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    font-weight: bold;
    margin: 10px 0;
}
.brmedia-popup-open-btn:hover {
    background-color: #005177;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const id = '<?php echo esc_js($post_id); ?>';
    const popup = document.getElementById('brmedia-popup-player-' + id);
    const audio = popup.querySelector('audio');
    const playBtn = popup.querySelector('.popup-play-toggle');
    const openBtn = document.querySelector('[data-target="#brmedia-popup-player-<?php echo esc_js($post_id); ?>"]');
    const closeBtn = popup.querySelector('.popup-close-btn');

    const playIcon = '<?php echo esc_js($play_icon); ?>';
    const pauseIcon = '<?php echo esc_js($pause_icon); ?>';

    openBtn.addEventListener('click', function () {
        popup.style.display = 'flex';
    });

    closeBtn.addEventListener('click', function () {
        popup.style.display = 'none';
        audio.pause();
        playBtn.innerHTML = `<i class="${playIcon}"></i>`;
    });

    playBtn.addEventListener('click', function () {
        if (audio.paused) {
            audio.play();
            playBtn.innerHTML = `<i class="${pauseIcon}"></i>`;
        } else {
            audio.pause();
            playBtn.innerHTML = `<i class="${playIcon}"></i>`;
        }
    });

    audio.addEventListener('ended', function () {
        playBtn.innerHTML = `<i class="${playIcon}"></i>`;
    });
});
</script>