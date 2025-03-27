<?php
/**
 * BRMedia Admin Dashboard View
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrap brmedia-dashboard">
    <header class="brmedia-dashboard__header">
        <h1>
            <i class="fas fa-broadcast-tower"></i> 
            <?php _e('BRMedia Dashboard', 'brmedia'); ?>
        </h1>
        <div class="brmedia-version">v<?php echo BRMEDIA_VERSION; ?></div>
    </header>

    <div class="brmedia-dashboard__grid">
        <!-- Main Stats Section -->
        <section class="brmedia-card brmedia-card--stats">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-chart-pie"></i> <?php _e('Media Overview', 'brmedia'); ?></h2>
                <div class="brmedia-timeframe">
                    <select id="brmedia-stats-timeframe">
                        <option value="7days"><?php _e('Last 7 Days', 'brmedia'); ?></option>
                        <option value="30days"><?php _e('Last 30 Days', 'brmedia'); ?></option>
                        <option value="90days"><?php _e('Last 90 Days', 'brmedia'); ?></option>
                        <option value="all"><?php _e('All Time', 'brmedia'); ?></option>
                    </select>
                </div>
            </div>
            <div class="brmedia-stats-grid">
                <div class="brmedia-stat">
                    <div class="brmedia-stat__icon bg-purple">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="brmedia-stat__info">
                        <span class="brmedia-stat__value" id="total-tracks">0</span>
                        <span class="brmedia-stat__label"><?php _e('Music Tracks', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat">
                    <div class="brmedia-stat__icon bg-blue">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="brmedia-stat__info">
                        <span class="brmedia-stat__value" id="total-videos">0</span>
                        <span class="brmedia-stat__label"><?php _e('Videos', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat">
                    <div class="brmedia-stat__icon bg-green">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="brmedia-stat__info">
                        <span class="brmedia-stat__value" id="total-plays">0</span>
                        <span class="brmedia-stat__label"><?php _e('Total Plays', 'brmedia'); ?></span>
                    </div>
                </div>
                
                <div class="brmedia-stat">
                    <div class="brmedia-stat__icon bg-orange">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="brmedia-stat__info">
                        <span class="brmedia-stat__value" id="total-downloads">0</span>
                        <span class="brmedia-stat__label"><?php _e('Downloads', 'brmedia'); ?></span>
                    </div>
                </div>
            </div>
            <div class="brmedia-chart-container">
                <canvas id="brmedia-usage-chart" height="250"></canvas>
            </div>
        </section>

        <!-- Quick Actions Section -->
        <section class="brmedia-card brmedia-card--quick-actions">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-bolt"></i> <?php _e('Quick Actions', 'brmedia'); ?></h2>
            </div>
            <div class="brmedia-actions-grid">
                <a href="<?php echo admin_url('post-new.php?post_type=brmedia_music'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-blue">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <span><?php _e('Add Music Track', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('post-new.php?post_type=brmedia_video'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-red">
                        <i class="fas fa-video"></i>
                    </div>
                    <span><?php _e('Add Video', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=brmedia-shortcodes'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-purple">
                        <i class="fas fa-code"></i>
                    </div>
                    <span><?php _e('Manage Shortcodes', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=brmedia-settings'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-green">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span><?php _e('Plugin Settings', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=brmedia_genre&post_type=brmedia_music'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-orange">
                        <i class="fas fa-tags"></i>
                    </div>
                    <span><?php _e('Manage Genres', 'brmedia'); ?></span>
                </a>
                
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=brmedia_video_category&post_type=brmedia_video'); ?>" class="brmedia-action">
                    <div class="brmedia-action__icon bg-teal">
                        <i class="fas fa-folder"></i>
                    </div>
                    <span><?php _e('Video Categories', 'brmedia'); ?></span>
                </a>
            </div>
        </section>

        <!-- Recent Activity Section -->
        <section class="brmedia-card brmedia-card--activity">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-history"></i> <?php _e('Recent Activity', 'brmedia'); ?></h2>
                <a href="#" class="brmedia-view-all"><?php _e('View All', 'brmedia'); ?></a>
            </div>
            <div class="brmedia-activity-list" id="brmedia-recent-activity">
                <div class="brmedia-activity-item">
                    <div class="brmedia-activity-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="brmedia-activity-content">
                        <p><?php _e('Loading recent activity...', 'brmedia'); ?></p>
                        <small class="brmedia-activity-time"></small>
                    </div>
                </div>
            </div>
        </section>

        <!-- Future Addons Section -->
        <section class="brmedia-card brmedia-card--addons">
            <div class="brmedia-card__header">
                <h2><i class="fas fa-puzzle-piece"></i> <?php _e('Future Addons', 'brmedia'); ?></h2>
            </div>
            <div class="brmedia-addons-grid">
                <div class="brmedia-addon">
                    <div class="brmedia-addon__icon">
                        <i class="fas fa-podcast"></i>
                    </div>
                    <h3><?php _e('Podcast Module', 'brmedia'); ?></h3>
                    <p><?php _e('Full podcast management with RSS feeds and episode management', 'brmedia'); ?></p>
                    <div class="brmedia-addon-badge"><?php _e('Coming Soon', 'brmedia'); ?></div>
                </div>
                
                <div class="brmedia-addon">
                    <div class="brmedia-addon__icon">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <h3><?php _e('Radio Streaming', 'brmedia'); ?></h3>
                    <p><?php _e('Live radio streaming with schedule and DJ management', 'brmedia'); ?></p>
                    <div class="brmedia-addon-badge"><?php _e('Planned', 'brmedia'); ?></div>
                </div>
                
                <div class="brmedia-addon">
                    <div class="brmedia-addon__icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h3><?php _e('Gaming Integration', 'brmedia'); ?></h3>
                    <p><?php _e('Twitch/YouTube gaming streams and tournament management', 'brmedia'); ?></p>
                    <div class="brmedia-addon-badge"><?php _e('Future', 'brmedia'); ?></div>
                </div>
                
                <div class="brmedia-addon">
                    <div class="brmedia-addon__icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3><?php _e('Live Chat', 'brmedia'); ?></h3>
                    <p><?php _e('Real-time chat for your media pages with moderation', 'brmedia'); ?></p>
                    <div class="brmedia-addon-badge"><?php _e('Future', 'brmedia'); ?></div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Load stats via AJAX
    BRMediaDashboard.loadStats();
    
    // Timeframe selector
    $('#brmedia-stats-timeframe').change(function() {
        BRMediaDashboard.loadStats($(this).val());
    });
});
</script>