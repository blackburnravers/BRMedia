<?php
/**
 * BRMedia Dashboard Class
 * Handles the admin dashboard functionality
 */

class BRMedia_Dashboard {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function init() {
        // Register dashboard widgets
        add_action('brmedia_admin_dashboard', array($this, 'render_stats_widget'), 10);
        add_action('brmedia_admin_dashboard', array($this, 'render_quick_links'), 20);
        add_action('brmedia_admin_dashboard', array($this, 'render_recent_activity'), 30);
        add_action('brmedia_admin_dashboard', array($this, 'render_addons_grid'), 40);
        
        // Setup AJAX handlers
        add_action('wp_ajax_brmedia_get_dashboard_stats', array($this, 'ajax_get_stats'));
    }

    /**
     * Render the stats widget
     */
    public function render_stats_widget() {
        ?>
        <div class="brmedia-card brmedia-stats-widget">
            <div class="brmedia-card-header">
                <h3><i class="fas fa-chart-pie"></i> <?php _e('Media Statistics', 'brmedia'); ?></h3>
                <div class="brmedia-timeframe-selector">
                    <select id="brmedia-stats-timeframe" class="brmedia-select">
                        <option value="7days"><?php _e('Last 7 Days', 'brmedia'); ?></option>
                        <option value="30days"><?php _e('Last 30 Days', 'brmedia'); ?></option>
                        <option value="90days"><?php _e('Last 90 Days', 'brmedia'); ?></option>
                        <option value="all"><?php _e('All Time', 'brmedia'); ?></option>
                    </select>
                </div>
            </div>
            <div class="brmedia-stats-grid">
                <div class="brmedia-stat-card stat-music">
                    <div class="stat-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="stat-data">
                        <span class="stat-value" id="brmedia-stat-tracks">0</span>
                        <span class="stat-label"><?php _e('Music Tracks', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat-card stat-videos">
                    <div class="stat-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="stat-data">
                        <span class="stat-value" id="brmedia-stat-videos">0</span>
                        <span class="stat-label"><?php _e('Videos', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat-card stat-plays">
                    <div class="stat-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="stat-data">
                        <span class="stat-value" id="brmedia-stat-plays">0</span>
                        <span class="stat-label"><?php _e('Total Plays', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat-card stat-downloads">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-data">
                        <span class="stat-value" id="brmedia-stat-downloads">0</span>
                        <span class="stat-label"><?php _e('Downloads', 'brmedia'); ?></span>
                    </div>
                </div>
            </div>
            <div class="brmedia-chart-container">
                <canvas id="brmedia-stats-chart" height="250"></canvas>
            </div>
        </div>
        <?php
    }

    /**
     * Render quick links widget
     */
    public function render_quick_links() {
        ?>
        <div class="brmedia-card brmedia-quick-links">
            <div class="brmedia-card-header">
                <h3><i class="fas fa-rocket"></i> <?php _e('Quick Actions', 'brmedia'); ?></h3>
            </div>
            <div class="brmedia-links-grid">
                <a href="<?php echo admin_url('post-new.php?post_type=brmedia_music'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-blue">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <span><?php _e('Add Music Track', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('post-new.php?post_type=brmedia_video'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-red">
                        <i class="fas fa-video"></i>
                    </div>
                    <span><?php _e('Add Video', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=brmedia-shortcodes'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-purple">
                        <i class="fas fa-code"></i>
                    </div>
                    <span><?php _e('Manage Shortcodes', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=brmedia-settings'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-green">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span><?php _e('Plugin Settings', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=brmedia_genre&post_type=brmedia_music'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-orange">
                        <i class="fas fa-tags"></i>
                    </div>
                    <span><?php _e('Manage Genres', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=brmedia_video_category&post_type=brmedia_video'); ?>" class="brmedia-quick-link">
                    <div class="link-icon bg-teal">
                        <i class="fas fa-folder"></i>
                    </div>
                    <span><?php _e('Video Categories', 'brmedia'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Render recent activity widget
     */
    public function render_recent_activity() {
        $activities = $this->get_recent_activities(5);
        ?>
        <div class="brmedia-card brmedia-activity">
            <div class="brmedia-card-header">
                <h3><i class="fas fa-history"></i> <?php _e('Recent Activity', 'brmedia'); ?></h3>
                <a href="<?php echo admin_url('admin.php?page=brmedia-statistics'); ?>" class="brmedia-view-all">
                    <?php _e('View All', 'brmedia'); ?>
                </a>
            </div>
            <div class="brmedia-activity-list">
                <?php if (!empty($activities)) : ?>
                    <?php foreach ($activities as $activity) : ?>
                        <div class="brmedia-activity-item">
                            <div class="activity-icon">
                                <i class="<?php echo esc_attr($activity['icon']); ?>"></i>
                            </div>
                            <div class="activity-content">
                                <p><?php echo esc_html($activity['message']); ?></p>
                                <small class="activity-time">
                                    <?php echo esc_html($this->time_ago($activity['time'])); ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="brmedia-activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="activity-content">
                            <p><?php _e('No recent activity yet', 'brmedia'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render future addons widget
     */
    public function render_addons_grid() {
        $addons = array(
            'podcast' => array(
                'icon' => 'fas fa-podcast',
                'title' => __('Podcast Module', 'brmedia'),
                'desc' => __('Full podcast management with RSS feeds', 'brmedia'),
                'status' => 'soon',
                'badge' => __('Coming Soon', 'brmedia')
            ),
            'radio' => array(
                'icon' => 'fas fa-broadcast-tower',
                'title' => __('Radio Streaming', 'brmedia'),
                'desc' => __('Live radio streaming with schedules', 'brmedia'),
                'status' => 'planned',
                'badge' => __('Planned', 'brmedia')
            ),
            'gaming' => array(
                'icon' => 'fas fa-gamepad',
                'title' => __('Gaming Integration', 'brmedia'),
                'desc' => __('Twitch/YouTube gaming streams', 'brmedia'),
                'status' => 'planned',
                'badge' => __('Planned', 'brmedia')
            ),
            'chat' => array(
                'icon' => 'fas fa-comments',
                'title' => __('Live Chat', 'brmedia'),
                'desc' => __('Real-time chat for media pages', 'brmedia'),
                'status' => 'future',
                'badge' => __('Future', 'brmedia')
            )
        );
        ?>
        <div class="brmedia-card brmedia-addons">
            <div class="brmedia-card-header">
                <h3><i class="fas fa-puzzle-piece"></i> <?php _e('Future Addons', 'brmedia'); ?></h3>
            </div>
            <div class="brmedia-addons-grid">
                <?php foreach ($addons as $addon) : ?>
                    <div class="brmedia-addon-card status-<?php echo esc_attr($addon['status']); ?>">
                        <div class="addon-icon">
                            <i class="<?php echo esc_attr($addon['icon']); ?>"></i>
                        </div>
                        <h4><?php echo esc_html($addon['title']); ?></h4>
                        <p><?php echo esc_html($addon['desc']); ?></p>
                        <div class="addon-badge"><?php echo esc_html($addon['badge']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler for dashboard stats
     */
    public function ajax_get_stats() {
        check_ajax_referer('brmedia_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'brmedia'));
        }

        $timeframe = isset($_POST['timeframe']) ? sanitize_text_field($_POST['timeframe']) : '7days';

        wp_send_json_success(array(
            'tracks' => $this->count_music_tracks(),
            'videos' => $this->count_videos(),
            'plays' => $this->count_plays($timeframe),
            'downloads' => $this->count_downloads($timeframe),
            'chart' => $this->get_chart_data($timeframe)
        ));
    }

    /**
     * Helper: Count music tracks
     */
    private function count_music_tracks() {
        $count = wp_count_posts('brmedia_music');
        return $count->publish;
    }

    /**
     * Helper: Count videos
     */
    private function count_videos() {
        $count = wp_count_posts('brmedia_video');
        return $count->publish;
    }

    /**
     * Helper: Count plays within timeframe
     */
    private function count_plays($timeframe) {
        global $wpdb;
        $where = $this->get_timeframe_where($timeframe, 'play_date');
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}brmedia_play_stats WHERE $where");
    }

    /**
     * Helper: Count downloads within timeframe
     */
    private function count_downloads($timeframe) {
        global $wpdb;
        $where = $this->get_timeframe_where($timeframe, 'download_date');
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}brmedia_downloads WHERE $where");
    }

    /**
     * Helper: Get timeframe SQL WHERE clause
     */
    private function get_timeframe_where($timeframe, $date_column = 'date') {
        switch ($timeframe) {
            case '30days':
                return "$date_column >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            case '90days':
                return "$date_column >= DATE_SUB(NOW(), INTERVAL 90 DAY)";
            case 'all':
                return '1=1';
            default: // 7 days
                return "$date_column >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        }
    }

    /**
     * Helper: Get chart data
     */
    private function get_chart_data($timeframe) {
        global $wpdb;
        $where = $this->get_timeframe_where($timeframe, 'play_date');
        
        return $wpdb->get_results("
            SELECT DATE(play_date) as date, COUNT(*) as plays 
            FROM {$wpdb->prefix}brmedia_play_stats 
            WHERE $where
            GROUP BY DATE(play_date)
            ORDER BY date ASC
        ");
    }

    /**
     * Helper: Get recent activities
     */
    private function get_recent_activities($limit = 5) {
        global $wpdb;
        
        $activities = array();
        
        // Get recent plays
        $plays = $wpdb->get_results("
            SELECT p.post_title, ps.play_date 
            FROM {$wpdb->prefix}brmedia_play_stats ps
            LEFT JOIN {$wpdb->posts} p ON ps.media_id = p.ID
            WHERE p.post_type = 'brmedia_music'
            ORDER BY ps.play_date DESC
            LIMIT $limit
        ");
        
        foreach ($plays as $play) {
            $activities[] = array(
                'icon' => 'fas fa-play-circle',
                'message' => sprintf(__('%s was played', 'brmedia'), $play->post_title),
                'time' => $play->play_date
            );
        }
        
        // Get recent uploads
        $uploads = get_posts(array(
            'post_type' => array('brmedia_music', 'brmedia_video'),
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        foreach ($uploads as $upload) {
            $activities[] = array(
                'icon' => $upload->post_type === 'brmedia_music' ? 'fas fa-music' : 'fas fa-video',
                'message' => sprintf(__('New %s added: %s', 'brmedia'), 
                    $upload->post_type === 'brmedia_music' ? __('track', 'brmedia') : __('video', 'brmedia'),
                    $upload->post_title),
                'time' => $upload->post_date
            );
        }
        
        // Sort all activities by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, $limit);
    }

    /**
     * Helper: Time ago format
     */
    private function time_ago($date) {
        $time = strtotime($date);
        $diff = time() - $time;
        
        if ($diff < 60) {
            return __('Just now', 'brmedia');
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return sprintf(_n('%d minute ago', '%d minutes ago', $mins, 'brmedia'), $mins);
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return sprintf(_n('%d hour ago', '%d hours ago', $hours, 'brmedia'), $hours);
        } else {
            $days = floor($diff / 86400);
            return sprintf(_n('%d day ago', '%d days ago', $days, 'brmedia'), $days);
        }
    }
}