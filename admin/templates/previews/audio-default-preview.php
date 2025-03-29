<?php
/**
 * BRMedia Audio Default Template Preview
 * Preview for the standard audio player style
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="brmedia-template-preview audio-default-preview">
    <div class="brmedia-cover">
        <img src="<?php echo BRMEDIA_URL; ?>assets/img/preview-cover.jpg" alt="Cover Art">
    </div>
    <div class="brmedia-player">
        <div class="brmedia-track-title">Sample Track Title</div>
        <audio controls>
            <source src="<?php echo BRMEDIA_URL; ?>assets/sample/sample.mp3" type="audio/mpeg">
            <?php _e( 'Your browser does not support the audio element.', 'brmedia' ); ?>
        </audio>
        <div class="brmedia-controls">
            <button><i class="fas fa-backward"></i></button>
            <button><i class="fas fa-play"></i></button>
            <button><i class="fas fa-forward"></i></button>
        </div>
    </div>
</div>