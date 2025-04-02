<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Retrieve necessary post meta data
$audio_file = get_post_meta(get_the_ID(), '_brmedia_audio_file', true);
$cover_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
$tracklist = get_post_meta(get_the_ID(), '_brmedia_tracklist', true);
$download_enabled = get_post_meta(get_the_ID(), '_brmedia_download_enabled', true);
$download_label = get_post_meta(get_the_ID(), '_brmedia_download_label', true) ?: 'Download';
$download_icon = get_post_meta(get_the_ID(), '_brmedia_download_icon', true) ?: 'fas fa-download';

// Ensure the audio file exists
if (!$audio_file) {
    echo '<div class="brmedia-error">Audio file not found.</div>';
    return;
}
?>

<div class="brmedia-audio-compact-player">
    <?php if ($cover_image): ?>
        <div class="brmedia-audio-cover">
            <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        </div>
    <?php endif; ?>

    <div class="brmedia-audio-controls">
        <audio id="brmedia-audio-<?php echo esc_attr(get_the_ID()); ?>" class="brmedia-audio-element" controls>
            <source src="<?php echo esc_url($audio_file); ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <?php if ($download_enabled): ?>
            <a class="brmedia-download-btn" href="<?php echo esc_url($audio_file); ?>" download>
                <i class="<?php echo esc_attr($download_icon); ?>"></i> <?php echo esc_html($download_label); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ($tracklist): ?>
        <div class="brmedia-audio-tracklist">
            <h4>Tracklist</h4>
            <pre><?php echo esc_html($tracklist); ?></pre>
        </div>
    <?php endif; ?>
</div>

<style>
.brmedia-audio-compact-player {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    padding: 10px;
    background-color: #f9f9f9;
}

.brmedia-audio-cover img {
    max-width: 100px;
    margin-right: 15px;
}

.brmedia-audio-controls {
    flex-grow: 1;
}

.brmedia-download-btn {
    display: inline-flex;
    align-items: center;
    margin-top: 5px;
    text-decoration: none;
    background-color: #0073aa;
    color: #fff;
    padding: 5px 10px;
    border-radius: 3px;
}

.brmedia-download-btn i {
    margin-right: 5px;
}

.brmedia-audio-tracklist {
    margin-top: 10px;
}

.brmedia-audio-tracklist h4 {
    margin-bottom: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const audioElement = document.getElementById('brmedia-audio-<?php echo esc_js(get_the_ID()); ?>');

    audioElement.addEventListener('play', function () {
        // Example: Send AJAX request to update play count
        // jQuery.post(ajaxurl, { action: 'brmedia_update_play_count', post_id: <?php echo esc_js(get_the_ID()); ?> });
    });

    // Additional event listeners can be added here
});
</script>