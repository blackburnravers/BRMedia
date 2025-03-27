<?php
/**
 * BRMedia Media Metadata Handler
 * Manages custom fields for music tracks and videos
 */

class BRMedia_Media_Metadata {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Music track metadata
        add_action('add_meta_boxes_brmedia_music', array($this, 'add_music_meta_boxes'));
        add_action('save_post_brmedia_music', array($this, 'save_music_metadata'));
        
        // Video metadata
        add_action('add_meta_boxes_brmedia_video', array($this, 'add_video_meta_boxes'));
        add_action('save_post_brmedia_video', array($this, 'save_video_metadata'));
        
        // Media column customization
        add_filter('manage_brmedia_music_posts_columns', array($this, 'add_music_columns'));
        add_action('manage_brmedia_music_posts_custom_column', array($this, 'render_music_columns'), 10, 2);
        
        add_filter('manage_brmedia_video_posts_columns', array($this, 'add_video_columns'));
        add_action('manage_brmedia_video_posts_custom_column', array($this, 'render_video_columns'), 10, 2);
    }

    /**
     * Add meta boxes for music tracks
     */
    public function add_music_meta_boxes($post) {
        add_meta_box(
            'brmedia_track_info',
            __('Track Information', 'brmedia'),
            array($this, 'render_track_info_meta_box'),
            'brmedia_music',
            'normal',
            'high'
        );
        
        add_meta_box(
            'brmedia_track_file',
            __('Audio File', 'brmedia'),
            array($this, 'render_track_file_meta_box'),
            'brmedia_music',
            'normal',
            'high'
        );
        
        add_meta_box(
            'brmedia_tracklist',
            __('Track Timestamps', 'brmedia'),
            array($this, 'render_tracklist_meta_box'),
            'brmedia_music',
            'normal',
            'default'
        );
    }

    /**
     * Render track info meta box
     */
    public function render_track_info_meta_box($post) {
        wp_nonce_field('brmedia_save_track_data', 'brmedia_track_nonce');
        
        $duration = get_post_meta($post->ID, 'brmedia_track_duration', true);
        $bpm = get_post_meta($post->ID, 'brmedia_track_bpm', true);
        $key = get_post_meta($post->ID, 'brmedia_track_key', true);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field">
                <label for="brmedia_track_duration"><?php _e('Duration', 'brmedia'); ?></label>
                <input type="text" id="brmedia_track_duration" name="brmedia_track_duration" 
                       value="<?php echo esc_attr($duration); ?>" placeholder="00:00:00">
                <p class="description"><?php _e('Format: HH:MM:SS', 'brmedia'); ?></p>
            </div>
            
            <div class="brmedia-field">
                <label for="brmedia_track_bpm"><?php _e('BPM', 'brmedia'); ?></label>
                <input type="number" id="brmedia_track_bpm" name="brmedia_track_bpm" 
                       value="<?php echo esc_attr($bpm); ?>" min="0" step="1">
            </div>
            
            <div class="brmedia-field">
                <label for="brmedia_track_key"><?php _e('Musical Key', 'brmedia'); ?></label>
                <select id="brmedia_track_key" name="brmedia_track_key">
                    <option value=""><?php _e('None', 'brmedia'); ?></option>
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
     * Render track file meta box
     */
    public function render_track_file_meta_box($post) {
        $audio_file = get_post_meta($post->ID, 'brmedia_track_file', true);
        $cover_art = get_post_thumbnail_id($post->ID);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field brmedia-audio-upload">
                <label for="brmedia_track_file"><?php _e('Audio File', 'brmedia'); ?></label>
                <input type="text" id="brmedia_track_file" name="brmedia_track_file" 
                       value="<?php echo esc_attr($audio_file); ?>" class="regular-text">
                <button type="button" class="button brmedia-media-upload" data-target="#brmedia_track_file" data-mime="audio">
                    <?php _e('Upload', 'brmedia'); ?>
                </button>
                <?php if ($audio_file) : ?>
                    <div class="brmedia-audio-preview">
                        <audio controls src="<?php echo esc_url($audio_file); ?>"></audio>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="brmedia-field brmedia-cover-art">
                <label><?php _e('Cover Art', 'brmedia'); ?></label>
                <div class="brmedia-cover-art-container">
                    <?php if ($cover_art) : ?>
                        <?php echo wp_get_attachment_image($cover_art, 'medium'); ?>
                        <input type="hidden" name="brmedia_cover_art_id" value="<?php echo esc_attr($cover_art); ?>">
                    <?php else : ?>
                        <div class="brmedia-cover-art-placeholder">
                            <i class="fas fa-compact-disc"></i>
                        </div>
                        <input type="hidden" name="brmedia_cover_art_id" value="">
                    <?php endif; ?>
                </div>
                <div class="brmedia-cover-art-actions">
                    <button type="button" class="button brmedia-media-upload" data-target=".brmedia-cover-art-container" data-mime="image">
                        <?php _e('Select Image', 'brmedia'); ?>
                    </button>
                    <?php if ($cover_art) : ?>
                        <button type="button" class="button button-link brmedia-remove-cover-art">
                            <?php _e('Remove', 'brmedia'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render tracklist meta box
     */
    public function render_tracklist_meta_box($post) {
        $timestamps = get_post_meta($post->ID, 'brmedia_tracklist', true);
        $timestamps = is_array($timestamps) ? $timestamps : array();
        ?>
        <div class="brmedia-tracklist-editor">
            <div class="brmedia-tracklist-items">
                <?php if (!empty($timestamps)) : ?>
                    <?php foreach ($timestamps as $index => $timestamp) : ?>
                        <div class="brmedia-tracklist-item" data-index="<?php echo esc_attr($index); ?>">
                            <div class="brmedia-tracklist-time">
                                <input type="text" name="brmedia_tracklist[<?php echo $index; ?>][timestamp]" 
                                       value="<?php echo esc_attr($timestamp['timestamp']); ?>" 
                                       placeholder="00:00:00" class="brmedia-time-input">
                            </div>
                            <div class="brmedia-tracklist-title">
                                <input type="text" name="brmedia_tracklist[<?php echo $index; ?>][title]" 
                                       value="<?php echo esc_attr($timestamp['title']); ?>" 
                                       placeholder="<?php esc_attr_e('Section title', 'brmedia'); ?>">
                            </div>
                            <div class="brmedia-tracklist-actions">
                                <button type="button" class="button-link brmedia-remove-timestamp">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="brmedia-tracklist-empty">
                        <p><?php _e('No timestamps added yet.', 'brmedia'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="brmedia-tracklist-actions">
                <button type="button" id="brmedia-add-timestamp" class="button">
                    <i class="fas fa-plus"></i> <?php _e('Add Timestamp', 'brmedia'); ?>
                </button>
                <button type="button" id="brmedia-detect-silence" class="button">
                    <i class="fas fa-waveform-lines"></i> <?php _e('Auto-Detect Sections', 'brmedia'); ?>
                </button>
            </div>
            
            <template id="brmedia-tracklist-template">
                <div class="brmedia-tracklist-item" data-index="{{index}}">
                    <div class="brmedia-tracklist-time">
                        <input type="text" name="brmedia_tracklist[{{index}}][timestamp]" 
                               placeholder="00:00:00" class="brmedia-time-input">
                    </div>
                    <div class="brmedia-tracklist-title">
                        <input type="text" name="brmedia_tracklist[{{index}}][title]" 
                               placeholder="<?php esc_attr_e('Section title', 'brmedia'); ?>">
                    </div>
                    <div class="brmedia-tracklist-actions">
                        <button type="button" class="button-link brmedia-remove-timestamp">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <?php
    }

    /**
     * Save music track metadata
     */
    public function save_music_metadata($post_id) {
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

        // Save track info
        if (isset($_POST['brmedia_track_duration'])) {
            update_post_meta($post_id, 'brmedia_track_duration', sanitize_text_field($_POST['brmedia_track_duration']));
        }

        if (isset($_POST['brmedia_track_bpm'])) {
            update_post_meta($post_id, 'brmedia_track_bpm', intval($_POST['brmedia_track_bpm']));
        }

        if (isset($_POST['brmedia_track_key'])) {
            update_post_meta($post_id, 'brmedia_track_key', sanitize_text_field($_POST['brmedia_track_key']));
        }

        // Save audio file
        if (isset($_POST['brmedia_track_file'])) {
            update_post_meta($post_id, 'brmedia_track_file', esc_url_raw($_POST['brmedia_track_file']));
        }

        // Save cover art
        if (isset($_POST['brmedia_cover_art_id'])) {
            $cover_art_id = intval($_POST['brmedia_cover_art_id']);
            if ($cover_art_id) {
                set_post_thumbnail($post_id, $cover_art_id);
            } else {
                delete_post_thumbnail($post_id);
            }
        }

        // Save tracklist
        if (isset($_POST['brmedia_tracklist'])) {
            $tracklist = array();
            foreach ($_POST['brmedia_tracklist'] as $item) {
                if (!empty($item['timestamp'])) {
                    $tracklist[] = array(
                        'timestamp' => $this->sanitize_timestamp($item['timestamp']),
                        'title' => sanitize_text_field($item['title'])
                    );
                }
            }
            update_post_meta($post_id, 'brmedia_tracklist', $tracklist);
        }
    }

    /**
     * Add meta boxes for videos
     */
    public function add_video_meta_boxes($post) {
        add_meta_box(
            'brmedia_video_info',
            __('Video Information', 'brmedia'),
            array($this, 'render_video_info_meta_box'),
            'brmedia_video',
            'normal',
            'high'
        );
        
        add_meta_box(
            'brmedia_video_file',
            __('Video File', 'brmedia'),
            array($this, 'render_video_file_meta_box'),
            'brmedia_video',
            'normal',
            'high'
        );
    }

    /**
     * Render video info meta box
     */
    public function render_video_info_meta_box($post) {
        wp_nonce_field('brmedia_save_video_data', 'brmedia_video_nonce');
        
        $duration = get_post_meta($post->ID, 'brmedia_video_duration', true);
        $aspect_ratio = get_post_meta($post->ID, 'brmedia_video_aspect_ratio', true);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field">
                <label for="brmedia_video_duration"><?php _e('Duration', 'brmedia'); ?></label>
                <input type="text" id="brmedia_video_duration" name="brmedia_video_duration" 
                       value="<?php echo esc_attr($duration); ?>" placeholder="00:00:00">
                <p class="description"><?php _e('Format: HH:MM:SS', 'brmedia'); ?></p>
            </div>
            
            <div class="brmedia-field">
                <label for="brmedia_video_aspect_ratio"><?php _e('Aspect Ratio', 'brmedia'); ?></label>
                <select id="brmedia_video_aspect_ratio" name="brmedia_video_aspect_ratio">
                    <option value="16:9" <?php selected($aspect_ratio, '16:9'); ?>>16:9 (Widescreen)</option>
                    <option value="4:3" <?php selected($aspect_ratio, '4:3'); ?>>4:3 (Standard)</option>
                    <option value="1:1" <?php selected($aspect_ratio, '1:1'); ?>>1:1 (Square)</option>
                    <option value="9:16" <?php selected($aspect_ratio, '9:16'); ?>>9:16 (Vertical)</option>
                </select>
            </div>
        </div>
        <?php
    }

    /**
     * Render video file meta box
     */
    public function render_video_file_meta_box($post) {
        $video_file = get_post_meta($post->ID, 'brmedia_video_file', true);
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        ?>
        <div class="brmedia-metabox-grid">
            <div class="brmedia-field brmedia-video-upload">
                <label for="brmedia_video_file"><?php _e('Video File', 'brmedia'); ?></label>
                <input type="text" id="brmedia_video_file" name="brmedia_video_file" 
                       value="<?php echo esc_attr($video_file); ?>" class="regular-text">
                <button type="button" class="button brmedia-media-upload" data-target="#brmedia_video_file" data-mime="video">
                    <?php _e('Upload', 'brmedia'); ?>
                </button>
                <?php if ($video_file) : ?>
                    <div class="brmedia-video-preview">
                        <video controls width="250" src="<?php echo esc_url($video_file); ?>"></video>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="brmedia-field brmedia-video-thumbnail">
                <label><?php _e('Video Thumbnail', 'brmedia'); ?></label>
                <div class="brmedia-thumbnail-container">
                    <?php if ($thumbnail_id) : ?>
                        <?php echo wp_get_attachment_image($thumbnail_id, 'medium'); ?>
                        <input type="hidden" name="brmedia_video_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>">
                    <?php else : ?>
                        <div class="brmedia-thumbnail-placeholder">
                            <i class="fas fa-film"></i>
                        </div>
                        <input type="hidden" name="brmedia_video_thumbnail_id" value="">
                    <?php endif; ?>
                </div>
                <div class="brmedia-thumbnail-actions">
                    <button type="button" class="button brmedia-media-upload" data-target=".brmedia-thumbnail-container" data-mime="image">
                        <?php _e('Select Image', 'brmedia'); ?>
                    </button>
                    <?php if ($thumbnail_id) : ?>
                        <button type="button" class="button button-link brmedia-remove-thumbnail">
                            <?php _e('Remove', 'brmedia'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save video metadata
     */
    public function save_video_metadata($post_id) {
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

        // Save video info
        if (isset($_POST['brmedia_video_duration'])) {
            update_post_meta($post_id, 'brmedia_video_duration', sanitize_text_field($_POST['brmedia_video_duration']));
        }

        if (isset($_POST['brmedia_video_aspect_ratio'])) {
            update_post_meta($post_id, 'brmedia_video_aspect_ratio', sanitize_text_field($_POST['brmedia_video_aspect_ratio']));
        }

        // Save video file
        if (isset($_POST['brmedia_video_file'])) {
            update_post_meta($post_id, 'brmedia_video_file', esc_url_raw($_POST['brmedia_video_file']));
        }

        // Save thumbnail
        if (isset($_POST['brmedia_video_thumbnail_id'])) {
            $thumbnail_id = intval($_POST['brmedia_video_thumbnail_id']);
            if ($thumbnail_id) {
                set_post_thumbnail($post_id, $thumbnail_id);
            } else {
                delete_post_thumbnail($post_id);
            }
        }
    }

    /**
     * Add custom columns for music tracks
     */
    public function add_music_columns($columns) {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'duration' => __('Duration', 'brmedia'),
            'bpm' => __('BPM', 'brmedia'),
            'key' => __('Key', 'brmedia'),
            'genres' => __('Genres', 'brmedia'),
            'date' => $columns['date']
        );
        
        return $new_columns;
    }

    /**
     * Render custom music columns
     */
    public function render_music_columns($column, $post_id) {
        switch ($column) {
            case 'duration':
                echo esc_html(get_post_meta($post_id, 'brmedia_track_duration', true));
                break;
                
            case 'bpm':
                echo esc_html(get_post_meta($post_id, 'brmedia_track_bpm', true));
                break;
                
            case 'key':
                echo esc_html($this->get_key_label(get_post_meta($post_id, 'brmedia_track_key', true)));
                break;
                
            case 'genres':
                $terms = get_the_terms($post_id, 'brmedia_genre');
                if ($terms && !is_wp_error($terms)) {
                    $term_links = array();
                    foreach ($terms as $term) {
                        $term_links[] = sprintf(
                            '<a href="%s">%s</a>',
                            esc_url(add_query_arg('brmedia_genre', $term->slug, admin_url('edit.php?post_type=brmedia_music'))),
                            esc_html($term->name)
                        );
                    }
                    echo implode(', ', $term_links);
                }
                break;
        }
    }

    /**
     * Add custom columns for videos
     */
    public function add_video_columns($columns) {
        $new_columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'duration' => __('Duration', 'brmedia'),
            'aspect_ratio' => __('Aspect Ratio', 'brmedia'),
            'categories' => __('Categories', 'brmedia'),
            'date' => $columns['date']
        );
        
        return $new_columns;
    }

    /**
     * Render custom video columns
     */
    public function render_video_columns($column, $post_id) {
        switch ($column) {
            case 'duration':
                echo esc_html(get_post_meta($post_id, 'brmedia_video_duration', true));
                break;
                
            case 'aspect_ratio':
                echo esc_html(get_post_meta($post_id, 'brmedia_video_aspect_ratio', true));
                break;
                
            case 'categories':
                $terms = get_the_terms($post_id, 'brmedia_video_category');
                if ($terms && !is_wp_error($terms)) {
                    $term_links = array();
                    foreach ($terms as $term) {
                        $term_links[] = sprintf(
                            '<a href="%s">%s</a>',
                            esc_url(add_query_arg('brmedia_video_category', $term->slug, admin_url('edit.php?post_type=brmedia_video'))),
                            esc_html($term->name)
                        );
                    }
                    echo implode(', ', $term_links);
                }
                break;
        }
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
     * Helper: Get key label
     */
    private function get_key_label($key) {
        $keys = $this->get_musical_keys();
        foreach ($keys as $group) {
            if (isset($group[$key])) {
                return $group[$key];
            }
        }
        return '';
    }

    /**
     * Helper: Sanitize timestamp
     */
    private function sanitize_timestamp($timestamp) {
        $timestamp = preg_replace('/[^0-9:]/', '', $timestamp);
        $parts = explode(':', $timestamp);
        
        // Ensure we have HH:MM:SS format
        if (count($parts) === 1) {
            $timestamp = '00:00:' . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        } elseif (count($parts) === 2) {
            $timestamp = '00:' . str_pad($parts[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
        }
        
        return $timestamp;
    }
}