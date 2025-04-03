<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$settings = get_option('brmedia_audio_templates_settings');

// Fetch custom settings or use defaults
$audio_url     = get_post_meta($post_id, '_brmedia_audio_file', true);
$tracklist     = get_post_meta($post_id, '_brmedia_tracklist', true);
$cover_image   = get_the_post_thumbnail_url($post_id, 'full');
$show_download = !empty(get_post_meta($post_id, '_brmedia_download_enabled', true));

$show_play     = !empty($settings['audio_default_show_play']);
$play_icon     = $settings['audio_default_play_icon'] ?? 'fas fa-play';
$pause_icon    = $settings['audio_default_pause_icon'] ?? 'fas fa-pause';
$control_color = $settings['audio_default_control_color'] ?? '#0073aa';
$progress_color= $settings['audio_default_progress_color'] ?? '#1e87f0';
$tracklist_bg  = $settings['audio_default_tracklist_bg'] ?? '#f7f7f7';
$download_icon = $settings['audio_default_download_icon'] ?? 'fas fa-download';
$download_label= $settings['audio_default_download_label'] ?? 'Download';
?>

<div class="brmedia-player-container brmedia-default-player" data-post-id="<?php echo esc_attr($post_id); ?>">
    
    <?php if ($cover_image): ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url($cover_image); ?>" alt="Cover Image">
        </div>
    <?php endif; ?>

    <div class="brmedia-controls">
        <button class="brmedia-play-toggle" style="color: <?php echo esc_attr($control_color); ?>;">
            <i class="<?php echo esc_attr($play_icon); ?>"></i>
        </button>

        <audio id="audio-<?php echo esc_attr($post_id); ?>" class="plyr" controls>
            <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <?php if ($show_download): ?>
            <a class="brmedia-download-btn" href="<?php echo esc_url($audio_url); ?>" download>
                <i class="<?php echo esc_attr($download_icon); ?>"></i> <?php echo esc_html($download_label); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($tracklist)): ?>
        <div class="brmedia-tracklist" style="background: <?php echo esc_attr($tracklist_bg); ?>;">
            <?php
            $lines = explode("\n", $tracklist);
            foreach ($lines as $line) {
                // If the line has a timestamp (e.g., 01:30 Intro)
                if (preg_match('/^(\d{1,2}:\d{2})(.*)$/', trim($line), $matches)) {
                    $time = trim($matches[1]);
                    $desc = trim($matches[2]);
                    echo '<div class="brmedia-trackline"><a href="#" class="brmedia-timestamp" data-time="' . esc_attr($time) . '">' . esc_html($time) . '</a> ' . esc_html($desc) . '</div>';
                } else {
                    echo '<div class="brmedia-trackline">' . esc_html($line) . '</div>';
                }
            }
            ?>
        </div>
    <?php endif; ?>
</div>

<style>
.brmedia-default-player {
    margin-bottom: 40px;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.brmedia-cover img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 15px;
}

.brmedia-controls {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
}

.brmedia-play-toggle {
    background: none;
    border: none;
    font-size: 26px;
    cursor: pointer;
    padding: 5px;
    transition: color 0.3s ease;
}

.brmedia-download-btn {
    text-decoration: none;
    padding: 8px 14px;
    background: #0073aa;
    color: #fff;
    border-radius: 4px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
}

.brmedia-download-btn i {
    margin-right: 6px;
}

.brmedia-tracklist {
    margin-top: 15px;
    padding: 15px;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.6;
}

.brmedia-timestamp {
    font-weight: bold;
    color: #0073aa;
    cursor: pointer;
    margin-right: 6px;
    text-decoration: underline;
}

.brmedia-trackline {
    margin-bottom: 6px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('audio-<?php echo esc_js($post_id); ?>');

    document.querySelectorAll('.brmedia-timestamp').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const parts = this.dataset.time.split(':');
            const seconds = parseInt(parts[0]) * 60 + parseInt(parts[1]);
            if (!isNaN(seconds)) {
                audio.currentTime = seconds;
                audio.play();
            }
        });
    });

    const toggle = document.querySelector('.brmedia-play-toggle');
    if (toggle && audio) {
        toggle.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                toggle.innerHTML = '<i class="<?php echo esc_js($pause_icon); ?>"></i>';
            } else {
                audio.pause();
                toggle.innerHTML = '<i class="<?php echo esc_js($play_icon); ?>"></i>';
            }
        });
    }
});
</script>