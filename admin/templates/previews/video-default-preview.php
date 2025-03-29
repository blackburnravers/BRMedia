<?php
/**
 * BRMedia Video Default Template Preview
 * Displays the standard video player preview
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="brmedia-template-preview video-default-preview">
    <div class="brmedia-player video-style">
        <video width="100%" height="auto" controls poster="<?php echo BRMEDIA_URL; ?>assets/img/preview-video.jpg">
            <source src="<?php echo BRMEDIA_URL; ?>assets/sample/sample-video.mp4" type="video/mp4">
            <?php _e( 'Your browser does not support the video element.', 'brmedia' ); ?>
        </video>
        <div class="brmedia-track-title">Sample Video Title</div>
    </div>
</div>