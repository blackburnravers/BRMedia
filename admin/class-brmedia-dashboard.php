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
        </div>
        <?php
    }
}

new BRMedia_Dashboard_Page();