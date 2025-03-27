<?php
/**
 * BRMedia Social Sharing Handler
 * Manages social media sharing for music tracks and videos
 */

class BRMedia_Social_Sharing {
    private static $instance = null;
    private $enabled_platforms = array();

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Get enabled platforms from settings
        $this->enabled_platforms = BRMedia_Settings::get_option('share_platforms', 'brmedia_social', array());
        
        // Frontend hooks
        add_action('brmedia_player_footer', array($this, 'render_sharing_buttons'), 20);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Shortcode
        add_shortcode('brmedia_social_share', array($this, 'social_share_shortcode'));
    }

    public function enqueue_assets() {
        if (apply_filters('brmedia_load_social_assets', true)) {
            // Font Awesome for icons
            wp_enqueue_style(
                'brmedia-fontawesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                array(),
                '6.4.0'
            );
            
            // Social sharing styles
            wp_enqueue_style(
                'brmedia-social-share',
                BRMEDIA_URL . 'assets/css/social-share.css',
                array(),
                BRMEDIA_VERSION
            );
            
            // Social sharing JS
            wp_enqueue_script(
                'brmedia-social-share',
                BRMEDIA_URL . 'assets/js/social-share.js',
                array('jquery'),
                BRMEDIA_VERSION,
                true
            );
        }
    }

    public function render_sharing_buttons($media_id) {
        if (!BRMedia_Settings::get_option('enable_sharing', 'brmedia_social', false)) {
            return;
        }

        $media = get_post($media_id);
        if (!$media) {
            return;
        }

        $type = ($media->post_type === 'brmedia_music') ? 'music' : 'video';
        $title = urlencode(get_the_title($media_id));
        $url = urlencode(get_permalink($media_id));
        $share_text = urlencode(
            BRMedia_Settings::get_option(
                'share_text', 
                'brmedia_social', 
                __('Check out this awesome track!', 'brmedia')
            )
        );
        
        $image = $this->get_media_image($media_id, $type);
        ?>
        <div class="brmedia-social-share">
            <h4 class="brmedia-share-title">
                <i class="fas fa-share-alt"></i> <?php _e('Share', 'brmedia'); ?>
            </h4>
            
            <div class="brmedia-share-buttons">
                <?php foreach ($this->enabled_platforms as $platform => $enabled) : ?>
                    <?php if ($enabled) : ?>
                        <?php echo $this->get_share_button($platform, $title, $url, $share_text, $image); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function social_share_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'type' => 'music' // music or video
        ), $atts);

        ob_start();
        $this->render_sharing_buttons($atts['id']);
        return ob_get_clean();
    }

    private function get_share_button($platform, $title, $url, $text, $image = '') {
        $platforms = $this->get_platform_data();
        
        if (!isset($platforms[$platform])) {
            return '';
        }

        $data = $platforms[$platform];
        $share_url = '';
        
        switch ($platform) {
            case 'facebook':
                $share_url = "https://www.facebook.com/sharer/sharer.php?u={$url}";
                break;
            case 'x':
                $share_url = "https://twitter.com/intent/tweet?text={$text}&url={$url}";
                break;
            case 'whatsapp':
                $share_url = "https://wa.me/?text={$text}%20{$url}";
                break;
            case 'telegram':
                $share_url = "https://t.me/share/url?url={$url}&text={$text}";
                break;
            case 'reddit':
                $share_url = "https://www.reddit.com/submit?url={$url}&title={$title}";
                break;
            case 'linkedin':
                $share_url = "https://www.linkedin.com/shareArticle?mini=true&url={$url}&title={$title}&summary={$text}";
                break;
            case 'tumblr':
                $share_url = "https://www.tumblr.com/share/link?url={$url}&name={$title}&description={$text}";
                break;
            case 'pinterest':
                $share_url = "https://pinterest.com/pin/create/button/?url={$url}&media={$image}&description={$text}";
                break;
            default:
                return '';
        }

        return sprintf(
            '<a href="%s" class="brmedia-share-btn brmedia-share-%s" target="_blank" rel="noopener noreferrer" title="%s">%s%s</a>',
            esc_url($share_url),
            esc_attr($platform),
            esc_attr($data['label']),
            $data['icon'],
            esc_html($data['label'])
        );
    }

    private function get_media_image($media_id, $type) {
        if ($type === 'music') {
            $image = get_the_post_thumbnail_url($media_id, 'large');
        } else {
            $image = get_the_post_thumbnail_url($media_id, 'large');
        }

        return $image ? urlencode($image) : '';
    }

    private function get_platform_data() {
        return array(
            'facebook' => array(
                'label' => 'Facebook',
                'icon' => '<i class="fab fa-facebook-f"></i>',
                'color' => '#3b5998'
            ),
            'x' => array(
                'label' => 'X',
                'icon' => '<i class="fab fa-x-twitter"></i>',
                'color' => '#000000'
            ),
            'whatsapp' => array(
                'label' => 'WhatsApp',
                'icon' => '<i class="fab fa-whatsapp"></i>',
                'color' => '#25D366'
            ),
            'telegram' => array(
                'label' => 'Telegram',
                'icon' => '<i class="fab fa-telegram-plane"></i>',
                'color' => '#0088cc'
            ),
            'reddit' => array(
                'label' => 'Reddit',
                'icon' => '<i class="fab fa-reddit-alien"></i>',
                'color' => '#ff5700'
            ),
            'linkedin' => array(
                'label' => 'LinkedIn',
                'icon' => '<i class="fab fa-linkedin-in"></i>',
                'color' => '#0077b5'
            ),
            'tumblr' => array(
                'label' => 'Tumblr',
                'icon' => '<i class="fab fa-tumblr"></i>',
                'color' => '#35465c'
            ),
            'pinterest' => array(
                'label' => 'Pinterest',
                'icon' => '<i class="fab fa-pinterest-p"></i>',
                'color' => '#bd081c'
            )
        );
    }

    /**
     * Get share counts for a media item
     */
    public function get_share_counts($media_id) {
        $counts = get_post_meta($media_id, 'brmedia_share_counts', true);
        return is_array($counts) ? $counts : array();
    }

    /**
     * Log a share event
     */
    public function log_share($media_id, $platform) {
        $counts = $this->get_share_counts($media_id);
        
        if (!isset($counts[$platform])) {
            $counts[$platform] = 0;
        }
        
        $counts[$platform]++;
        update_post_meta($media_id, 'brmedia_share_counts', $counts);
        
        // Also log in the global stats table
        $this->log_share_in_stats($media_id, $platform);
        
        return $counts[$platform];
    }

    /**
     * Log share in the global stats table
     */
    private function log_share_in_stats($media_id, $platform) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'brmedia_share_stats';
        
        $wpdb->insert(
            $table_name,
            array(
                'media_id' => $media_id,
                'platform' => $platform,
                'share_date' => current_time('mysql'),
                'ip_address' => $this->get_client_ip()
            ),
            array('%d', '%s', '%s', '%s')
        );
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return sanitize_text_field($ip);
    }
}