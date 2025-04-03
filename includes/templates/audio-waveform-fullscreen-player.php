<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id     = get_the_ID();
$settings    = get_option('brmedia_audio_templates_settings');
$audio_url   = get_post_meta($post_id, '_brmedia_audio_file', true);
$cover_image = get_the_post_thumbnail_url($post_id, 'full');
$title       = get_the_title($post_id);
$tracklist   = get_post_meta($post_id, '_brmedia_tracklist', true);

// Custom settings
$show_play     = !empty($settings['audio_waveform_show_play']);
$play_icon     = $settings['audio_waveform_play_icon'] ?? 'fas fa-play';
$pause_icon    = $settings['audio_waveform_pause_icon'] ?? 'fas fa-pause';
$wave_color    = $settings['audio_waveform_color'] ?? '#1e87f0';
$progress_color = $settings['audio_waveform_progress_color'] ?? '#1565c0';
?>

<div id="brmedia-waveform-fullscreen-player" class="brmedia-waveform-fullscreen" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="brmedia-fullscreen-header">
        <button class="brmedia-fullscreen-close"><i class="fas fa-times"></i></button>
    </div>

    <?php if ($cover_image): ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url($cover_image); ?>" alt="Cover">
        </div>
    <?php endif; ?>

    <h2><?php echo esc_html($title); ?></h2>

    <div id="brmedia-waveform-<?php echo esc_attr($post_id); ?>" class="brmedia-waveform-container"></div>

    <div class="brmedia-controls">
        <?php if ($show_play): ?>
            <button class="brmedia-play-btn" data-playing="false">
                <i class="<?php echo esc_attr($play_icon); ?>"></i>
            </button>
        <?php endif; ?>
    </div>

    <?php if (!empty($tracklist)): ?>
        <div class="brmedia-tracklist">
            <pre><?php echo esc_html($tracklist); ?></pre>
        </div>
    <?php endif; ?>
</div>

<style>
.brmedia-waveform-fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    background: #000;
    color: #fff;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    padding: 30px;
    box-sizing: border-box;
    overflow-y: auto;
    text-align: center;
}

.brmedia-fullscreen-header {
    position: absolute;
    top: 10px;
    right: 15px;
}

.brmedia-fullscreen-close {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 26px;
    cursor: pointer;
}

.brmedia-cover img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 15px;
}

.brmedia-waveform-container {
    margin: 25px auto;
    max-width: 900px;
    height: 128px;
    background: #111;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(255, 255, 255, 0.2);
}

.brmedia-controls {
    margin: 20px 0;
}

.brmedia-play-btn {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 36px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.brmedia-play-btn:hover {
    color: #1e87f0;
}

.brmedia-tracklist {
    margin-top: 25px;
    background: rgba(255,255,255,0.05);
    padding: 15px;
    border-radius: 5px;
    text-align: left;
    max-height: 180px;
    overflow-y: auto;
}
</style>

<script src="https://unpkg.com/wavesurfer.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const player = document.querySelector('#brmedia-waveform-fullscreen-player[data-post-id="<?php echo esc_js($post_id); ?>"]');
    const playBtn = player.querySelector('.brmedia-play-btn i');
    const audioFile = "<?php echo esc_url($audio_url); ?>";
    const playIcon = "<?php echo esc_js($play_icon); ?>";
    const pauseIcon = "<?php echo esc_js($pause_icon); ?>";

    const wavesurfer = WaveSurfer.create({
        container: '#brmedia-waveform-<?php echo esc_js($post_id); ?>',
        waveColor: "<?php echo esc_js($wave_color); ?>",
        progressColor: "<?php echo esc_js($progress_color); ?>",
        height: 128,
        barWidth: 2,
        responsive: true,
        cursorColor: "#fff"
    });

    wavesurfer.load(audioFile);

    if (player.querySelector('.brmedia-play-btn')) {
        player.querySelector('.brmedia-play-btn').addEventListener('click', function () {
            wavesurfer.playPause();
            if (this.getAttribute('data-playing') === 'false') {
                playBtn.className = pauseIcon;
                this.setAttribute('data-playing', 'true');
            } else {
                playBtn.className = playIcon;
                this.setAttribute('data-playing', 'false');
            }
        });

        wavesurfer.on('finish', function () {
            playBtn.className = playIcon;
            player.querySelector('.brmedia-play-btn').setAttribute('data-playing', 'false');
        });
    }

    const closeBtn = player.querySelector('.brmedia-fullscreen-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            player.style.display = 'none';
            wavesurfer.pause();
        });
    }
});
</script>