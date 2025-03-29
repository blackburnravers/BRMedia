<?php
/**
 * BRMedia Dashboard Page
 * Displays the main plugin dashboard in wp-admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Dashboard_Page {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_page' ) );
    }

    public function add_dashboard_page() {
        add_menu_page(
            __( 'BRMedia', 'brmedia' ),
            'BRMedia',
            'manage_options',
            'brmedia-dashboard',
            array( $this, 'render_dashboard' ),
            'dashicons-format-audio',
            3
        );
    }

    public function render_dashboard() {
        ?>
        <div class="wrap brmedia-dashboard">
            <h1><i class="fas fa-headphones-alt"></i> <?php _e( 'BRMedia Dashboard', 'brmedia' ); ?></h1>

            <div class="brmedia-dashboard-grid">
                <?php do_action( 'brmedia_admin_dashboard' ); ?>
            </div>

            <div class="brmedia-dashboard-sections">
                <div class="brmedia-box-grid">
                    <div class="brmedia-box">
                        <h3><i class="fas fa-music"></i> <?php _e( 'Add New Track', 'brmedia' ); ?></h3>
                        <p><a href="<?php echo admin_url('post-new.php?post_type=brmedia_music'); ?>" class="button button-primary"><?php _e( 'Add Music', 'brmedia' ); ?></a></p>
                    </div>

                    <div class="brmedia-box">
                        <h3><i class="fas fa-video"></i> <?php _e( 'Add New Video', 'brmedia' ); ?></h3>
                        <p><a href="<?php echo admin_url('post-new.php?post_type=brmedia_video'); ?>" class="button button-primary"><?php _e( 'Add Video', 'brmedia' ); ?></a></p>
                    </div>

                    <div class="brmedia-box">
                        <h3><i class="fas fa-chart-pie"></i> <?php _e( 'View Stats', 'brmedia' ); ?></h3>
                        <p><a href="<?php echo admin_url('admin.php?page=brmedia-stats'); ?>" class="button"><?php _e( 'View Stats', 'brmedia' ); ?></a></p>
                    </div>

                    <div class="brmedia-box">
                        <h3><i class="fas fa-code"></i> <?php _e( 'Shortcodes', 'brmedia' ); ?></h3>
                        <p><a href="<?php echo admin_url('admin.php?page=brmedia-shortcodes'); ?>" class="button"><?php _e( 'Shortcode List', 'brmedia' ); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

new BRMedia_Dashboard_Page();