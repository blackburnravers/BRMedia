<?php
/**
 * BRMedia Metabox Handler
 * Manages custom metaboxes for music tracks and videos
 */

class BRMedia_Metaboxes {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Music track metaboxes
        add_action('add_meta_boxes_brmedia_music', array($this, 'add_music_metaboxes'));
        add_action('save_post_brmedia_music', array($this, 'save_music_metadata'), 10, 2);
        
        // Video metaboxes
        add_action('add_meta_boxes_brmedia_video', array($this, 'add_video_metaboxes'));
        add_action('save_post_brmedia_video', array($this, 'save_video_metadata'), 10, 2);
        
        // Media upload hooks
        add_action('admin_enqueue_scripts', array($this, 'enqueue_metabox_assets'));
    }

    /**
     * Add metaboxes for music tracks
     */
    public function add_music_metaboxes($post) {
        add_meta_box(
            'brmedia_track_details',
            __('Track Details', 'brmedia'),
            array($this, 'render_track_details_metabox'),
            'brmedia_music',
            'normal',
            'high'
        );

        add_meta_box(
            'brmedia_track_file',
            __('Audio File', 'brmedia'),
            array($this, 'render_track_file_metabox'),
            'brmedia_music',
            'normal',
            'high'
        );

        add_meta_box(
            'brmedia_track_timestamps',
            __('Track Timestamps', 'brmedia'),
            array($this, 'render_timestamps_metabox'),
            'brmedia_music',
            'normal',
            'default'
        );
    }

    /**
     * Render track details metabox
     */
    public function render_track_details_metabox($post) {
        wp_nonce_field('brmedia_save_track_data', 'brmedia_track_nonce');

        $duration = get_post_meta($post->ID, 'brmedia_track_duration', true);
        $bpm = get_post_meta($post->ID, 'brmedia_track_bpm', true);
        $key = get_post_meta($post->ID, 'brmedia_track_key', true);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field">
                <label for="brmedia_track_duration"><?php _e('Duration (HH:MM:SS)', 'brmedia'); ?></label>
                <input type="text" id="brmedia_track_duration" name="brmedia_track_duration" 
                       value="<?php echo esc_attr($duration); ?>" class="brmedia-time-input">
            </div>

            <div class="brmedia-field">
                <label for="brmedia_track_bpm"><?php _e('BPM', 'brmedia'); ?></label>
                <input type="number" id="brmedia_track_bpm" name="brmedia_track_bpm" 
                       value="<?php echo esc_attr($bpm); ?>" min="0" step="1">
            </div>

            <div class="brmedia-field">
                <label for="brmedia_track_key"><?php _e('Musical Key', 'brmedia'); ?></label>
                <select id="brmedia_track_key" name="brmedia_track_key">
                    <option value=""><?php _e('-- Select Key --', 'brmedia'); ?></option>
                    <?php foreach ($this->get_musical_keys() as $group => $keys) : ?>
                        <optgroup label="<?php echo esc_attr($group); ?>">
                            <?php foreach ($keys as $key_value => $key_label) : ?>
                                <option value="<?php echo esc_attr($key_value); ?>" <?php selected($key, $key_value); ?>>
                                    <?php echo esc_html($key_label); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * Render track file metabox
     */
    public function render_track_file_metabox($post) {
        $audio_file = get_post_meta($post->ID, 'brmedia_track_file', true);
        $cover_id = get_post_thumbnail_id($post->ID);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field brmedia-audio-upload">
                <label for="brmedia_track_file"><?php _e('Audio File', 'brmedia'); ?></label>
                <div class="brmedia-upload-container">
                    <input type="text" id="brmedia_track_file" name="brmedia_track_file" 
                           value="<?php echo esc_attr($audio_file); ?>" class="regular-text brmedia-file-url">
                    <button type="button" class="button brmedia-upload-button" data-uploader-title="<?php esc_attr_e('Select Audio File', 'brmedia'); ?>" data-uploader-button-text="<?php esc_attr_e('Use as Track', 'brmedia'); ?>" data-mime-type="audio">
                        <?php _e('Upload', 'brmedia'); ?>
                    </button>
                </div>
                <?php if ($audio_file) : ?>
                    <div class="brmedia-audio-preview">
                        <audio controls src="<?php echo esc_url($audio_file); ?>"></audio>
                        <button type="button" class="button-link brmedia-remove-audio"><?php _e('Remove', 'brmedia'); ?></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="brmedia-field brmedia-cover-art">
                <label><?php _e('Cover Art', 'brmedia'); ?></label>
                <div class="brmedia-cover-art-container" data-frame-title="<?php esc_attr_e('Select Cover Art', 'brmedia'); ?>" data-frame-button="<?php esc_attr_e('Use as Cover', 'brmedia'); ?>">
                    <?php if ($cover_id) : ?>
                        <?php echo wp_get_attachment_image($cover_id, 'medium'); ?>
                        <input type="hidden" name="brmedia_cover_id" value="<?php echo esc_attr($cover_id); ?>">
                    <?php else : ?>
                        <div class="brmedia-placeholder">
                            <i class="fas fa-compact-disc"></i>
                        </div>
                        <input type="hidden" name="brmedia_cover_id" value="">
                    <?php endif; ?>
                </div>
                <div class="brmedia-cover-art-actions">
                    <button type="button" class="button brmedia-upload-cover"><?php _e('Select Image', 'brmedia'); ?></button>
                    <?php if ($cover_id) : ?>
                        <button type="button" class="button-link brmedia-remove-cover"><?php _e('Remove', 'brmedia'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render timestamps metabox
     */
    public function render_timestamps_metabox($post) {
        $timestamps = get_post_meta($post->ID, 'brmedia_track_timestamps', true);
        $timestamps = is_array($timestamps) ? $timestamps : array();
        ?>
        <div class="brmedia-timestamps">
            <div class="brmedia-timestamps-list">
                <?php foreach ($timestamps as $i => $timestamp) : ?>
                    <div class="brmedia-timestamp" data-index="<?php echo esc_attr($i); ?>">
                        <div class="brmedia-timestamp-field">
                            <label><?php _e('Time', 'brmedia'); ?></label>
                            <input type="text" name="brmedia_timestamps[<?php echo $i; ?>][time]" 
                                   value="<?php echo esc_attr($timestamp['time']); ?>" 
                                   class="brmedia-time-input" placeholder="00:00:00">
                        </div>
                        <div class="brmedia-timestamp-field">
                            <label><?php _e('Label', 'brmedia'); ?></label>
                            <input type="text" name="brmedia_timestamps[<?php echo $i; ?>][label]" 
                                   value="<?php echo esc_attr($timestamp['label']); ?>" 
                                   placeholder="<?php esc_attr_e('Section name', 'brmedia'); ?>">
                        </div>
                        <button type="button" class="button-link brmedia-remove-timestamp">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <template id="brmedia-timestamp-template">
                <div class="brmedia-timestamp" data-index="{{index}}">
                    <div class="brmedia-timestamp-field">
                        <label><?php _e('Time', 'brmedia'); ?></label>
                        <input type="text" name="brmedia_timestamps[{{index}}][time]" 
                               class="brmedia-time-input" placeholder="00:00:00">
                    </div>
                    <div class="brmedia-timestamp-field">
                        <label><?php _e('Label', 'brmedia'); ?></label>
                        <input type="text" name="brmedia_timestamps[{{index}}][label]" 
                               placeholder="<?php esc_attr_e('Section name', 'brmedia'); ?>">
                    </div>
                    <button type="button" class="button-link brmedia-remove-timestamp">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </template>

            <div class="brmedia-timestamps-actions">
                <button type="button" id="brmedia-add-timestamp" class="button">
                    <i class="fas fa-plus"></i> <?php _e('Add Timestamp', 'brmedia'); ?>
                </button>
                <button type="button" id="brmedia-detect-silence" class="button">
                    <i class="fas fa-waveform-lines"></i> <?php _e('Auto-Detect Sections', 'brmedia'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Save music track metadata
     */
    public function save_music_metadata($post_id, $post) {
        if (!isset($_POST['brmedia_track_nonce']) || 
            !wp_verify_nonce($_POST['brmedia_track_nonce'], 'brmedia_save_track_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save basic track info
        $fields = array(
            'brmedia_track_duration' => 'sanitize_text_field',
            'brmedia_track_bpm' => 'intval',
            'brmedia_track_key' => 'sanitize_text_field',
            'brmedia_track_file' => 'esc_url_raw'
        );

        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
            }
        }

        // Save cover art
        if (isset($_POST['brmedia_cover_id'])) {
            $cover_id = intval($_POST['brmedia_cover_id']);
            if ($cover_id > 0) {
                set_post_thumbnail($post_id, $cover_id);
            } else {
                delete_post_thumbnail($post_id);
            }
        }

        // Save timestamps
        $timestamps = array();
        if (isset($_POST['brmedia_timestamps']) && is_array($_POST['brmedia_timestamps'])) {
            foreach ($_POST['brmedia_timestamps'] as $timestamp) {
                if (!empty($timestamp['time'])) {
                    $timestamps[] = array(
                        'time' => $this->sanitize_timestamp($timestamp['time']),
                        'label' => sanitize_text_field($timestamp['label'])
                    );
                }
            }
        }
        update_post_meta($post_id, 'brmedia_track_timestamps', $timestamps);
    }

    /**
     * Add metaboxes for videos
     */
    public function add_video_metaboxes($post) {
        add_meta_box(
            'brmedia_video_details',
            __('Video Details', 'brmedia'),
            array($this, 'render_video_details_metabox'),
            'brmedia_video',
            'normal',
            'high'
        );

        add_meta_box(
            'brmedia_video_file',
            __('Video File', 'brmedia'),
            array($this, 'render_video_file_metabox'),
            'brmedia_video',
            'normal',
            'high'
        );
    }

    /**
     * Render video details metabox
     */
    public function render_video_details_metabox($post) {
        wp_nonce_field('brmedia_save_video_data', 'brmedia_video_nonce');

        $duration = get_post_meta($post->ID, 'brmedia_video_duration', true);
        $aspect = get_post_meta($post->ID, 'brmedia_video_aspect', true);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field">
                <label for="brmedia_video_duration"><?php _e('Duration (HH:MM:SS)', 'brmedia'); ?></label>
                <input type="text" id="brmedia_video_duration" name="brmedia_video_duration" 
                       value="<?php echo esc_attr($duration); ?>" class="brmedia-time-input">
            </div>

            <div class="brmedia-field">
                <label for="brmedia_video_aspect"><?php _e('Aspect Ratio', 'brmedia'); ?></label>
                <select id="brmedia_video_aspect" name="brmedia_video_aspect">
                    <option value="16:9" <?php selected($aspect, '16:9'); ?>>16:9 (Widescreen)</option>
                    <option value="4:3" <?php selected($aspect, '4:3'); ?>>4:3 (Standard)</option>
                    <option value="1:1" <?php selected($aspect, '1:1'); ?>>1:1 (Square)</option>
                    <option value="9:16" <?php selected($aspect, '9:16'); ?>>9:16 (Vertical)</option>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * Render video file metabox
     */
    public function render_video_file_metabox($post) {
        $video_file = get_post_meta($post->ID, 'brmedia_video_file', true);
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field brmedia-video-upload">
                <label for="brmedia_video_file"><?php _e('Video File', 'brmedia'); ?></label>
                <div class="brmedia-upload-container">
                    <input type="text" id="brmedia_video_file" name="brmedia_video_file" 
                           value="<?php echo esc_attr($video_file); ?>" class="regular-text brmedia-file-url">
                    <button type="button" class="button brmedia-upload-button" data-uploader-title="<?php esc_attr_e('Select Video File', 'brmedia'); ?>" data-uploader-button-text="<?php esc_attr_e('Use as Video', 'brmedia'); ?>" data-mime-type="video">
                        <?php _e('Upload', 'brmedia'); ?>
                    </button>
                </div>
                <?php if ($video_file) : ?>
                    <div class="brmedia-video-preview">
                        <video controls width="250" src="<?php echo esc_url($video_file); ?>"></video>
                        <button type="button" class="button-link brmedia-remove-video"><?php _e('Remove', 'brmedia'); ?></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="brmedia-field brmedia-video-thumbnail">
                <label><?php _e('Video Thumbnail', 'brmedia'); ?></label>
                <div class="brmedia-thumbnail-container" data-frame-title="<?php esc_attr_e('Select Thumbnail', 'brmedia'); ?>" data-frame-button="<?php esc_attr_e('Use as Thumbnail', 'brmedia'); ?>">
                    <?php if ($thumbnail_id) : ?>
                        <?php echo wp_get_attachment_image($thumbnail_id, 'medium'); ?>
                        <input type="hidden" name="brmedia_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>">
                    <?php else : ?>
                        <div class="brmedia-placeholder">
                            <i class="fas fa-film"></i>
                        </div>
                        <input type="hidden" name="brmedia_thumbnail_id" value="">
                    <?php endif; ?>
                </div>
                <div class="brmedia-thumbnail-actions">
                    <button type="button" class="button brmedia-upload-thumbnail"><?php _e('Select Image', 'brmedia'); ?></button>
                    <?php if ($thumbnail_id) : ?>
                        <button type="button" class="button-link brmedia-remove-thumbnail"><?php _e('Remove', 'brmedia'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save video metadata
     */
    public function save_video_metadata($post_id, $post) {
        if (!isset($_POST['brmedia_video_nonce']) || 
            !wp_verify_nonce($_POST['brmedia_video_nonce'], 'brmedia_save_video_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save basic video info
        $fields = array(
            'brmedia_video_duration' => 'sanitize_text_field',
            'brmedia_video_aspect' => 'sanitize_text_field',
            'brmedia_video_file' => 'esc_url_raw'
        );

        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
            }
        }

        // Save thumbnail
        if (isset($_POST['brmedia_thumbnail_id'])) {
            $thumbnail_id = intval($_POST['brmedia_thumbnail_id']);
            if ($thumbnail_id > 0) {
                set_post_thumbnail($post_id, $thumbnail_id);
            } else {
                delete_post_thumbnail($post_id);
            }
        }
    }

    /**
     * Enqueue metabox assets
     */
    public function enqueue_metabox_assets($hook) {
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }

        $screen = get_current_screen();
        if (!in_array($screen->post_type, array('brmedia_music', 'brmedia_video'))) {
            return;
        }

        // Enqueue WordPress media scripts
        wp_enqueue_media();

        // Enqueue plugin assets
        wp_enqueue_style(
            'brmedia-metabox',
            BRMEDIA_URL . 'assets/css/metabox.css',
            array(),
            BRMEDIA_VERSION
        );

        wp_enqueue_script(
            'brmedia-metabox',
            BRMEDIA_URL . 'assets/js/metabox.js',
            array('jquery', 'wp-util', 'media-upload'),
            BRMEDIA_VERSION,
            true
        );

        // Localize script data
        wp_localize_script('brmedia-metabox', 'BRMediaMetabox', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('brmedia_metabox_nonce'),
            'timeFormatError' => __('Please use HH:MM:SS format', 'brmedia'),
            'audioMimeTypes' => wp_get_mime_types()['audio'],
            'videoMimeTypes' => wp_get_mime_types()['video']
        ));
    }

    /**
     * Helper: Get musical keys
     */
    private function get_musical_keys() {
        return array(
            'Major Keys' => array(
                'C' => 'C Major',
                'G' => 'G Major',
                'D' => 'D Major',
                'A' => 'A Major',
                'E' => 'E Major',
                'B' => 'B Major',
                'F#' => 'F# Major',
                'C#' => 'C# Major',
                'F' => 'F Major',
                'Bb' => 'Bb Major',
                'Eb' => 'Eb Major',
                'Ab' => 'Ab Major',
                'Db' => 'Db Major',
                'Gb' => 'Gb Major',
                'Cb' => 'Cb Major'
            ),
            'Minor Keys' => array(
                'Am' => 'A Minor',
                'Em' => 'E Minor',
                'Bm' => 'B Minor',
                'F#m' => 'F# Minor',
                'C#m' => 'C# Minor',
                'G#m' => 'G# Minor',
                'D#m' => 'D# Minor',
                'A#m' => 'A# Minor',
                'Dm' => 'D Minor',
                'Gm' => 'G Minor',
                'Cm' => 'C Minor',
                'Fm' => 'F Minor',
                'Bbm' => 'Bb Minor',
                'Ebm' => 'Eb Minor',
                'Abm' => 'Ab Minor'
            )
        );
    }

    /**
     * Helper: Sanitize timestamp
     */
    private function sanitize_timestamp($time) {
        $time = preg_replace('/[^0-9:]/', '', $time);
        $parts = explode(':', $time);
        
        // Convert to HH:MM:SS format
        if (count($parts) === 1) {
            $time = '00:00:' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        } elseif (count($parts) === 2) {
            $time = '00:' . str_pad($parts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
        }
        
        return $time;
    }
}