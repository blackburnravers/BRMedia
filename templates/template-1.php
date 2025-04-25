<?php
// Get track details
$audio_url = get_post_meta($track->ID, 'audio_file', true);
$artist = get_post_meta($track->ID, 'artist', true);
$album = get_post_meta($track->ID, 'album', true);
$bpm = get_post_meta($track->ID, 'bpm', true);
$key = get_post_meta($track->ID, 'key', true);
$tracklist = get_post_meta($track->ID, 'tracklist', true);
$cover_image = get_the_post_thumbnail_url($track->ID, 'full');

// Convert audio URL to file path and get metadata
$audio_file_path = str_replace(get_site_url(), ABSPATH, $audio_url);
$audio_metadata = brmedia_get_audio_metadata($audio_file_path);

// Default settings
$default_settings = [
    'background_color' => '#fff',
    'text_color' => '#000',
    'padding' => '10px',
    'margin' => '0',
    'wave_color' => 'violet',
    'progress_color' => 'purple',
    'wave_height' => 100,
    'bar_width' => 2,
    'controls' => ['play', 'volume', 'mute', 'speed', 'skip-forward', 'skip-backward', 'share', 'fullscreen', 'cast', 'shuffle'],
    'play_icon' => 'fas fa-play',
    'pause_icon' => 'fas fa-pause',
    'volume_icon' => 'fas fa-volume-up',
    'mute_icon' => 'fas fa-volume-mute',
    'speed_icon' => 'fas fa-tachometer-alt',
    'skip_forward_icon' => 'fas fa-forward',
    'skip_backward_icon' => 'fas fa-backward',
    'share_icon' => 'fas fa-share',
    'fullscreen_icon' => 'fas fa-expand',
    'cast_icon' => 'fas fa-broadcast-tower',
    'shuffle_icon' => 'fas fa-random'
];
$settings = wp_parse_args($settings, $default_settings);
$selected_controls = $settings['controls'];
?>

<style>
    .brmedia-container {
        background-color: <?php echo esc_attr($settings['background_color']); ?>;
        color: <?php echo esc_attr($settings['text_color']); ?>;
        padding: <?php echo esc_attr($settings['padding']); ?>;
        margin: <?php echo esc_attr($settings['margin']); ?>;
        font-family: Arial, sans-serif;
    }
    .brmedia-top {
        display: flex;
        align-items: center;
    }
    .brmedia-cover {
        width: 100px;
        height: 100px;
        margin-right: 20px;
    }
    .brmedia-player {
        flex-grow: 1;
    }
    #waveform {
        width: 100%;
    }
    .brmedia-controls {
        margin: 10px 0;
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .brmedia-controls button {
        padding: 10px;
        background: #333;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    .brmedia-controls button:hover {
        background: #555;
    }
    .brmedia-controls input[type="range"], .brmedia-controls select {
        vertical-align: middle;
        width: 100px;
    }
    .brmedia-metadata p {
        margin: 5px 0;
    }
    .brmedia-tracklist {
        margin-top: 10px;
        max-height: 150px;
        overflow-y: auto;
    }
    :fullscreen .brmedia-container {
        background-image: url('<?php echo esc_url($cover_image); ?>');
        background-size: cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    :fullscreen .brmedia-player {
        width: 80%;
    }
    :fullscreen .brmedia-metadata {
        font-size: 1.5em;
    }
    :fullscreen .brmedia-tracklist {
        font-size: 1.2em;
    }
    @media (max-width: 768px) {
        .brmedia-top {
            flex-direction: column;
            align-items: center;
        }
        .brmedia-cover {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
</style>

<div class="brmedia-container">
    <div class="brmedia-top">
        <img src="<?php echo esc_url($cover_image); ?>" class="brmedia-cover" alt="Cover Image">
        <div class="brmedia-player">
            <div id="waveform"></div>
            <audio id="audio" src="<?php echo esc_url($audio_url); ?>"></audio>
        </div>
    </div>
    <div class="brmedia-controls">
        <?php if (in_array('play', $selected_controls)): ?>
            <button id="play-pause-btn" aria-label="Play/Pause"><i class="<?php echo esc_attr($settings['play_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('volume', $selected_controls)): ?>
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="0.5" aria-label="Volume">
        <?php endif; ?>
        <?php if (in_array('mute', $selected_controls)): ?>
            <button id="mute-toggle-btn" aria-label="Mute/Unmute"><i class="<?php echo esc_attr($settings['mute_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('speed', $selected_controls)): ?>
            <button id="speed-control-btn" aria-label="Adjust Speed"><i class="<?php echo esc_attr($settings['speed_icon']); ?>"></i></button>
            <select id="speed-control" style="display: none;" aria-label="Playback Speed">
                <option value="0.5">0.5x</option>
                <option value="1" selected>1x</option>
                <option value="1.5">1.5x</option>
                <option value="2">2x</option>
            </select>
        <?php endif; ?>
        <?php if (in_array('skip-backward', $selected_controls)): ?>
            <button id="skip-backward-btn" aria-label="Skip Backward"><i class="<?php echo esc_attr($settings['skip_backward_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('skip-forward', $selected_controls)): ?>
            <button id="skip-forward-btn" aria-label="Skip Forward"><i class="<?php echo esc_attr($settings['skip_forward_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('share', $selected_controls)): ?>
            <button id="share-btn" aria-label="Share"><i class="<?php echo esc_attr($settings['share_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('fullscreen', $selected_controls)): ?>
            <button id="fullscreen-btn" aria-label="Fullscreen"><i class="<?php echo esc_attr($settings['fullscreen_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('cast', $selected_controls)): ?>
            <button id="cast-btn" aria-label="Cast"><i class="<?php echo esc_attr($settings['cast_icon']); ?>"></i></button>
        <?php endif; ?>
        <?php if (in_array('shuffle', $selected_controls)): ?>
            <button id="shuffle-btn" aria-label="Shuffle"><i class="<?php echo esc_attr($settings['shuffle_icon']); ?>"></i></button>
        <?php endif; ?>
    </div>
    <div class="brmedia-metadata">
        <p><strong>Artist:</strong> <?php echo esc_html($artist); ?></p>
        <p><strong>Album:</strong> <?php echo esc_html($album); ?></p>
        <p><strong>BPM:</strong> <?php echo esc_html($bpm); ?></p>
        <p><strong>Key:</strong> <?php echo esc_html($key); ?></p>
        <?php if ($audio_metadata['duration']): ?>
            <p><strong>Duration:</strong> <?php echo esc_html($audio_metadata['duration']); ?></p>
        <?php endif; ?>
    </div>
    <?php if (!empty($tracklist)): ?>
        <div class="brmedia-tracklist">
            <h3>Tracklist</h3>
            <pre><?php echo esc_html($tracklist); ?></pre>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var wavesurfer = WaveSurfer.create({
        container: '#waveform',
        waveColor: '<?php echo esc_js($settings['wave_color']); ?>',
        progressColor: '<?php echo esc_js($settings['progress_color']); ?>',
        height: <?php echo intval($settings['wave_height']); ?>,
        barWidth: <?php echo intval($settings['bar_width']); ?>,
        responsive: true
    });
    wavesurfer.load('<?php echo esc_url($audio_url); ?>');
    wavesurfer.setVolume(0.5);

    var startTime;
    wavesurfer.on('play', function() {
        startTime = Date.now();
    });

    wavesurfer.on('pause', function() {
        var durationPlayed = Math.floor((Date.now() - startTime) / 1000);
        jQuery.post(ajaxurl, {
            action: 'brmedia_log_event',
            track_id: <?php echo $track->ID; ?>,
            action_type: 'play',
            duration_played: durationPlayed
        });
    });

    var playPauseBtn = document.getElementById('play-pause-btn');
    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', function() {
            wavesurfer.playPause();
            var icon = this.querySelector('i');
            if (wavesurfer.isPlaying()) {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['play_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['pause_icon'])); ?>');
            } else {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['pause_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['play_icon'])); ?>');
            }
        });
    }

    var volumeSlider = document.getElementById('volume-slider');
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            wavesurfer.setVolume(parseFloat(this.value));
        });
    }

    var muteToggleBtn = document.getElementById('mute-toggle-btn');
    if (muteToggleBtn) {
        muteToggleBtn.addEventListener('click', function() {
            var isMuted = wavesurfer.getMute();
            wavesurfer.setMute(!isMuted);
            var icon = this.querySelector('i');
            if (isMuted) {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['mute_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['volume_icon'])); ?>');
            } else {
                icon.classList.remove('<?php echo esc_js(str_replace('fas ', '', $settings['volume_icon'])); ?>');
                icon.classList.add('<?php echo esc_js(str_replace('fas ', '', $settings['mute_icon'])); ?>');
            }
        });
    }

    var speedControlBtn = document.getElementById('speed-control-btn');
    var speedControl = document.getElementById('speed-control');
    if (speedControlBtn && speedControl) {
        speedControlBtn.addEventListener('click', function() {
            speedControl.style.display = speedControl.style.display === 'none' ? 'inline-block' : 'none';
        });
        speedControl.addEventListener('change', function() {
            wavesurfer.setPlaybackRate(parseFloat(this.value));
            speedControl.style.display = 'none';
        });
    }

    var skipBackwardBtn = document.getElementById('skip-backward-btn');
    if (skipBackwardBtn) {
        skipBackwardBtn.addEventListener('click', function() {
            wavesurfer.skipBackward(10);
        });
    }

    var skipForwardBtn = document.getElementById('skip-forward-btn');
    if (skipForwardBtn) {
        skipForwardBtn.addEventListener('click', function() {
            wavesurfer.skipForward(10);
        });
    }

    var shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            jQuery.post(ajaxurl, {
                action: 'brmedia_log_event',
                track_id: <?php echo $track->ID; ?>,
                action_type: 'share'
            });
            navigator.share({
                title: '<?php echo esc_html($track->post_title); ?>',
                url: '<?php echo esc_url(get_permalink($track->ID)); ?>'
            });
        });
    }

    var fullscreenBtn = document.getElementById('fullscreen-btn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            var elem = document.querySelector('.brmedia-container');
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            }
        });
    }

    var castBtn = document.getElementById('cast-btn');
    if (castBtn) {
        castBtn.addEventListener('click', function() {
            alert('Casting functionality not implemented yet.');
        });
    }

    var shuffleBtn = document.getElementById('shuffle-btn');
    if (shuffleBtn) {
        shuffleBtn.addEventListener('click', function() {
            alert('Shuffle functionality not implemented yet.');
        });
    }
});
</script>