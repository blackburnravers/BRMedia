<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$settings = get_option('brmedia_general_options', []);
?>

<div class="container-fluid mt-4">
    <h1 class="mb-4">BRMedia Dashboard</h1>
    <p>Welcome to the BRMedia plugin dashboard. Use the cards below to navigate to different sections of the plugin.</p>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-gears fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">General Settings</h5>
                    <p class="card-text">Manage all your main settings from in here.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-general-settings'); ?>" class="btn btn-danger">Go to Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-cog fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Template Settings</h5>
                    <p class="card-text">Configure general settings and customize player templates.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-template-settings'); ?>" class="btn btn-primary">Go to Template Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-music fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Music Tracks</h5>
                    <p class="card-text">Manage your music tracks, including adding, editing, and deleting tracks.</p>
                    <a href="<?php echo admin_url('edit.php?post_type=brmedia_track'); ?>" class="btn btn-success">Manage Tracks</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x mb-3 text-info"></i>
                    <h5 class="card-title">Stats</h5>
                    <p class="card-text">View playback statistics and charts.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-stats'); ?>" class="btn btn-info">View Stats</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-code fa-3x mb-3 text-secondary"></i>
                    <h5 class="card-title">Shortcodes</h5>
                    <p class="card-text">Learn how to use the BRMedia shortcodes to embed players.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-shortcodes'); ?>" class="btn btn-secondary">View Shortcodes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-file-import fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Import Settings</h5>
                    <p class="card-text">Configure media import services and API credentials.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-import-settings'); ?>" class="btn btn-primary">Go to Import Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-upload fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Import Media</h5>
                    <p class="card-text">Import tracks from your favorite music services.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-import'); ?>" class="btn btn-success">Go to Import</a>
                </div>
            </div>
        </div>
        <?php if (!empty($settings['addon_video'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-video fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">BRMedia Video</h5>
                    <p class="card-text">Add video support to your media player.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-videos'); ?>" class="btn btn-warning">Manage Videos</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_podcast'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-microphone fa-3x mb-3 text-danger"></i>
                    <h5 class="card-title">BRMedia Podcast</h5>
                    <p class="card-text">Manage and play podcasts with BRMedia.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-podcasts'); ?>" class="btn btn-danger">Manage Podcasts</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_radio'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-broadcast-tower fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">BRMedia Radio</h5>
                    <p class="card-text">Stream radio stations with BRMedia.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-radio'); ?>" class="btn btn-primary">Manage Radio</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_gaming'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-gamepad fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">BRMedia Gaming</h5>
                    <p class="card-text">Integrate gaming features with BRMedia.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-gaming'); ?>" class="btn btn-success">Manage Gaming</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_chat'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-comments fa-3x mb-3 text-info"></i>
                    <h5 class="card-title">BRMedia Chat</h5>
                    <p class="card-text">Add chat functionality to BRMedia.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-chat'); ?>" class="btn btn-info">Manage Chat</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_downloads'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-download fa-3x mb-3 text-secondary"></i>
                    <h5 class="card-title">BRMedia Downloads</h5>
                    <p class="card-text">Manage downloads with BRMedia.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-downloads'); ?>" class="btn btn-secondary">Manage Downloads</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($settings['addon_footbar'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-bars-progress fa-3x mb-3 text-warning"></i>
                    <h5 class="card-title">BRMedia Footbar</h5>
                    <p class="card-text">Sticky Footbar player, syncs with BRMedia across the site for continuous playback.</p>
                    <a href="<?php echo admin_url('admin.php?page=brmedia-footbar'); ?>" class="btn btn-warning">Manage Footbar</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>