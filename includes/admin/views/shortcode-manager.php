<?php
/**
 * BRMedia Shortcode Manager View
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get all media items
$music_tracks = get_posts(array(
    'post_type' => 'brmedia_music',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
));

$videos = get_posts(array(
    'post_type' => 'brmedia_video',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
));
?>

<div class="wrap brmedia-shortcodes">
    <header class="brmedia-shortcodes__header">
        <h1>
            <i class="fas fa-code"></i> 
            <?php _e('Shortcode Manager', 'brmedia'); ?>
        </h1>
        <div class="brmedia-version">v<?php echo BRMEDIA_VERSION; ?></div>
    </header>

    <div class="brmedia-shortcodes__grid">
        <!-- Music Shortcodes Section -->
        <section class="brmedia-card">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-music"></i> <?php _e('Music Player Shortcodes', 'brmedia'); ?></h2>
                <div class="brmedia-card__actions">
                    <button class="button button-secondary brmedia-copy-all" data-type="music">
                        <i class="fas fa-copy"></i> <?php _e('Copy All', 'brmedia'); ?>
                    </button>
                </div>
            </div>
            
            <div class="brmedia-table-container">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="30%"><?php _e('Track Title', 'brmedia'); ?></th>
                            <th width="20%"><?php _e('Shortcode', 'brmedia'); ?></th>
                            <th width="15%"><?php _e('Template', 'brmedia'); ?></th>
                            <th width="15%"><?php _e('Autoplay', 'brmedia'); ?></th>
                            <th width="20%"><?php _e('Actions', 'brmedia'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($music_tracks)) : ?>
                            <?php foreach ($music_tracks as $track) : 
                                $cover = get_the_post_thumbnail_url($track->ID, 'thumbnail');
                            ?>
                                <tr>
                                    <td>
                                        <div class="brmedia-track-info">
                                            <?php if ($cover) : ?>
                                                <img src="<?php echo esc_url($cover); ?>" class="brmedia-track-cover">
                                            <?php endif; ?>
                                            <strong><?php echo esc_html($track->post_title); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="brmedia-shortcode-code">[brmedia_music id="<?php echo $track->ID; ?>"]</code>
                                    </td>
                                    <td>
                                        <select class="brmedia-template-select" data-id="<?php echo $track->ID; ?>" data-type="music">
                                            <option value="stylish"><?php _e('Stylish', 'brmedia'); ?></option>
                                            <option value="waveform"><?php _e('Waveform', 'brmedia'); ?></option>
                                            <option value="visualization"><?php _e('Visualization', 'brmedia'); ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <label class="brmedia-switch brmedia-small">
                                            <input type="checkbox" class="brmedia-autoplay-toggle" data-id="<?php echo $track->ID; ?>" data-type="music">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button class="button button-primary brmedia-copy-shortcode" 
                                                data-id="<?php echo $track->ID; ?>" 
                                                data-type="music">
                                            <i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>
                                        </button>
                                        <a href="<?php echo get_edit_post_link($track->ID); ?>" 
                                           class="button" title="<?php _e('Edit Track', 'brmedia'); ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="brmedia-no-items">
                                    <i class="fas fa-info-circle"></i>
                                    <?php _e('No music tracks found. Add some tracks to generate shortcodes.', 'brmedia'); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Video Shortcodes Section -->
        <section class="brmedia-card">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-video"></i> <?php _e('Video Player Shortcodes', 'brmedia'); ?></h2>
                <div class="brmedia-card__actions">
                    <button class="button button-secondary brmedia-copy-all" data-type="video">
                        <i class="fas fa-copy"></i> <?php _e('Copy All', 'brmedia'); ?>
                    </button>
                </div>
            </div>
            
            <div class="brmedia-table-container">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="30%"><?php _e('Video Title', 'brmedia'); ?></th>
                            <th width="20%"><?php _e('Shortcode', 'brmedia'); ?></th>
                            <th width="15%"><?php _e('Autoplay', 'brmedia'); ?></th>
                            <th width="15%"><?php _e('Controls', 'brmedia'); ?></th>
                            <th width="20%"><?php _e('Actions', 'brmedia'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($videos)) : ?>
                            <?php foreach ($videos as $video) : 
                                $thumbnail = get_the_post_thumbnail_url($video->ID, 'thumbnail');
                            ?>
                                <tr>
                                    <td>
                                        <div class="brmedia-track-info">
                                            <?php if ($thumbnail) : ?>
                                                <img src="<?php echo esc_url($thumbnail); ?>" class="brmedia-track-cover">
                                            <?php endif; ?>
                                            <strong><?php echo esc_html($video->post_title); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="brmedia-shortcode-code">[brmedia_video id="<?php echo $video->ID; ?>"]</code>
                                    </td>
                                    <td>
                                        <label class="brmedia-switch brmedia-small">
                                            <input type="checkbox" class="brmedia-autoplay-toggle" data-id="<?php echo $video->ID; ?>" data-type="video">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="brmedia-switch brmedia-small">
                                            <input type="checkbox" class="brmedia-controls-toggle" data-id="<?php echo $video->ID; ?>" data-type="video" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button class="button button-primary brmedia-copy-shortcode" 
                                                data-id="<?php echo $video->ID; ?>" 
                                                data-type="video">
                                            <i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>
                                        </button>
                                        <a href="<?php echo get_edit_post_link($video->ID); ?>" 
                                           class="button" title="<?php _e('Edit Video', 'brmedia'); ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="brmedia-no-items">
                                    <i class="fas fa-info-circle"></i>
                                    <?php _e('No videos found. Add some videos to generate shortcodes.', 'brmedia'); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Shortcode Generator Section -->
        <section class="brmedia-card">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-magic"></i> <?php _e('Shortcode Generator', 'brmedia'); ?></h2>
            </div>
            <div class="brmedia-generator">
                <div class="brmedia-generator__form">
                    <div class="brmedia-form-group">
                        <label><?php _e('Media Type', 'brmedia'); ?></label>
                        <select id="brmedia-generator-type" class="brmedia-form-control">
                            <option value="music"><?php _e('Music Track', 'brmedia'); ?></option>
                            <option value="video"><?php _e('Video', 'brmedia'); ?></option>
                        </select>
                    </div>
                    
                    <div class="brmedia-form-group">
                        <label><?php _e('Select Item', 'brmedia'); ?></label>
                        <select id="brmedia-generator-id" class="brmedia-form-control">
                            <?php foreach ($music_tracks as $track) : ?>
                                <option value="<?php echo $track->ID; ?>" data-type="music"><?php echo esc_html($track->post_title); ?></option>
                            <?php endforeach; ?>
                            <?php foreach ($videos as $video) : ?>
                                <option value="<?php echo $video->ID; ?>" data-type="video"><?php echo esc_html($video->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="brmedia-form-group" id="brmedia-template-option">
                        <label><?php _e('Player Template', 'brmedia'); ?></label>
                        <select id="brmedia-generator-template" class="brmedia-form-control">
                            <option value="stylish"><?php _e('Stylish', 'brmedia'); ?></option>
                            <option value="waveform"><?php _e('Waveform', 'brmedia'); ?></option>
                            <option value="visualization"><?php _e('Visualization', 'brmedia'); ?></option>
                        </select>
                    </div>
                    
                    <div class="brmedia-form-group brmedia-switch-group">
                        <label><?php _e('Autoplay', 'brmedia'); ?></label>
                        <label class="brmedia-switch">
                            <input type="checkbox" id="brmedia-generator-autoplay">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <div class="brmedia-form-group brmedia-switch-group" id="brmedia-controls-option">
                        <label><?php _e('Show Controls', 'brmedia'); ?></label>
                        <label class="brmedia-switch">
                            <input type="checkbox" id="brmedia-generator-controls" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <button id="brmedia-generate-shortcode" class="button button-primary">
                        <i class="fas fa-bolt"></i> <?php _e('Generate Shortcode', 'brmedia'); ?>
                    </button>
                </div>
                
                <div class="brmedia-generator__result">
                    <h4><?php _e('Your Shortcode:', 'brmedia'); ?></h4>
                    <div class="brmedia-result-container">
                        <code id="brmedia-generated-code"></code>
                        <button id="brmedia-copy-generated" class="button button-primary">
                            <i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>
                        </button>
                    </div>
                    <div class="brmedia-preview-info">
                        <i class="fas fa-info-circle"></i> 
                        <?php _e('Paste this shortcode into any page/post to display your media.', 'brmedia'); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle template option based on media type
    $('#brmedia-generator-type').change(function() {
        if ($(this).val() === 'music') {
            $('#brmedia-template-option').show();
            $('#brmedia-controls-option').hide();
        } else {
            $('#brmedia-template-option').hide();
            $('#brmedia-controls-option').show();
        }
        
        // Update items dropdown
        $('#brmedia-generator-id option').hide();
        $('#brmedia-generator-id option[data-type="' + $(this).val() + '"]').show();
        $('#brmedia-generator-id').val($('#brmedia-generator-id option[data-type="' + $(this).val() + '"]:first').val());
    });
    
    // Generate shortcode
    $('#brmedia-generate-shortcode').click(function(e) {
        e.preventDefault();
        
        var type = $('#brmedia-generator-type').val();
        var id = $('#brmedia-generator-id').val();
        var shortcode = '[' + (type === 'music' ? 'brmedia_music' : 'brmedia_video') + ' id="' + id + '"';
        
        if (type === 'music') {
            shortcode += ' template="' + $('#brmedia-generator-template').val() + '"';
        } else {
            shortcode += ' controls="' + ($('#brmedia-generator-controls').is(':checked') ? 'true' : 'false') + '"';
        }
        
        if ($('#brmedia-generator-autoplay').is(':checked')) {
            shortcode += ' autoplay="true"';
        }
        
        shortcode += ']';
        
        $('#brmedia-generated-code').text(shortcode);
    });
    
    // Copy generated shortcode
    $('#brmedia-copy-generated').click(function() {
        var code = $('#brmedia-generated-code').text();
        if (code) {
            navigator.clipboard.writeText(code);
            $(this).html('<i class="fas fa-check"></i> <?php _e('Copied!', 'brmedia'); ?>');
            setTimeout(function() {
                $('#brmedia-copy-generated').html('<i class="fas fa-copy"></i> <?php _e('Copy', 'brmedia'); ?>');
            }, 2000);
        }
    });
});
</script>