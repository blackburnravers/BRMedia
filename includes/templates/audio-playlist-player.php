<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
$settings = get_option('brmedia_audio_templates_settings');

$cover_image = get_the_post_thumbnail_url($post_id, 'full');
$playlist    = get_post_meta($post_id, '_brmedia_playlist_tracks', true); // expects an array of ['title' => '', 'url' => '']
$title       = get_the_title($post_id);

// Settings
$control_color = $settings['audio_playlist_control_color'] ?? '#ff4081';
$play_icon     = $settings['audio_playlist_play_icon'] ?? 'fas fa-play';
$pause_icon    = $settings['audio_playlist_pause_icon'] ?? 'fas fa-pause';
?>

<div class="brmedia-player-container brmedia-playlist-player" data-post-id="<?php echo esc_attr($post_id); ?>">

    <?php if ($cover_image): ?>
        <div class="brmedia-cover">
            <img src="<?php echo esc_url($cover_image); ?>" alt="Cover Image">
        </div>
    <?php endif; ?>

    <h3><?php echo esc_html($title); ?></h3>

    <audio id="brmedia-playlist-audio-<?php echo esc_attr($post_id); ?>" class="plyr" controls preload="none">
        <source src="" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <?php if (!empty($playlist) && is_array($playlist)): ?>
        <ul class="brmedia-playlist">
            <?php foreach ($playlist as $index => $track): ?>
                <li data-src="<?php echo esc_url($track['url']); ?>">
                    <button class="playlist-track-play" style="color: <?php echo esc_attr($control_color); ?>;">
                        <i class="<?php echo esc_attr($play_icon); ?>"></i>
                    </button>
                    <span><?php echo esc_html($track['title']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No playlist tracks found.</p>
    <?php endif; ?>
</div>

<style>
.brmedia-playlist-player {
    background: #fff;
    padding: 25px;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    font-family: Arial, sans-serif;
}

.brmedia-playlist-player h3 {
    margin-bottom: 15px;
}

.brmedia-playlist {
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
    max-height: 200px;
    overflow-y: auto;
    border-top: 1px solid #eee;
}

.brmedia-playlist li {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.playlist-track-play {
    margin-right: 10px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.playlist-track-play:hover {
    opacity: 0.8;
}

.brmedia-cover img {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 15px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.brmedia-playlist-player[data-post-id="<?php echo esc_js($post_id); ?>"]');
    const audio = container.querySelector('audio');
    const playButtons = container.querySelectorAll('.playlist-track-play');
    const playIcon = '<?php echo esc_js($play_icon); ?>';
    const pauseIcon = '<?php echo esc_js($pause_icon); ?>';

    playButtons.forEach(button => {
        button.addEventListener('click', function () {
            const li = this.closest('li');
            const src = li.getAttribute('data-src');

            if (audio.src !== src) {
                audio.src = src;
                audio.play();
                updateIcons(this);
            } else {
                if (audio.paused) {
                    audio.play();
                    updateIcons(this);
                } else {
                    audio.pause();
                    resetIcons();
                }
            }
        });
    });

    function updateIcons(activeBtn) {
        playButtons.forEach(btn => {
            btn.innerHTML = `<i class="${playIcon}"></i>`;
        });
        activeBtn.innerHTML = `<i class="${pauseIcon}"></i>`;
    }

    function resetIcons() {
        playButtons.forEach(btn => {
            btn.innerHTML = `<i class="${playIcon}"></i>`;
        });
    }

    audio.addEventListener('ended', resetIcons);
});
</script>