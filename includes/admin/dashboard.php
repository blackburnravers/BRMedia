<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

function brmedia_dashboard_page() {
    ?>
    <div class="wrap brmedia-admin-container">
        <h1 class="brmedia-dashboard-title">BRMedia Dashboard</h1>

        <div class="dashboard-widgets">
            <!-- Quick Stats Widget -->
            <div class="widget">
                <h2>Quick Stats</h2>
                <ul>
                    <li><strong>Music Entries:</strong> <?php echo wp_count_posts('brmusic')->publish; ?></li>
                    <li><strong>Video Entries:</strong> <?php echo wp_count_posts('brvideo')->publish; ?></li>
                    <li><strong>Total Downloads:</strong> <span id="brmedia-total-downloads">Loading...</span></li>
                    <li><strong>Total Plays:</strong> <span id="brmedia-total-plays">Loading...</span></li>
                </ul>
            </div>

            <!-- Quick Actions Widget -->
            <div class="widget">
                <h2>Quick Actions</h2>
                <p><a href="post-new.php?post_type=brmusic" class="button button-primary">Add New Music</a></p>
                <p><a href="post-new.php?post_type=brvideo" class="button button-primary">Add New Video</a></p>
                <p><a href="admin.php?page=brmedia-settings" class="button">Settings</a></p>
                <p><a href="admin.php?page=brmedia-templates" class="button">Templates</a></p>
            </div>
        </div>

        <hr>

        <div class="dashboard-widgets">
            <!-- Coming Soon Modules -->
            <div class="widget">
                <h2>Coming Soon: BRMedia Radio</h2>
                <p>Stream radio live with customizable stations and visuals.</p>
            </div>

            <div class="widget">
                <h2>Coming Soon: BRMedia Gaming</h2>
                <p>Integrate mini-games and interactive media-based fun.</p>
            </div>

            <div class="widget">
                <h2>Coming Soon: BRMedia Chat</h2>
                <p>Let users chat live while music or video plays—real-time sync coming soon.</p>
            </div>

            <div class="widget">
                <h2>Coming Soon: BRMedia Podcasts</h2>
                <p>Host, publish, and track advanced podcast analytics.</p>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function ($) {
        // Simulated AJAX load of stats (replace with real AJAX if needed)
        $('#brmedia-total-downloads').text('1,234');
        $('#brmedia-total-plays').text('5,678');
    });
    </script>
    <?php
}
