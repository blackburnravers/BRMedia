
<?php
/**
 * BRMedia Shortcodes Page
 * Displays all available shortcode IDs with descriptions and copy features
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Shortcodes_Page {

    public function __construct() {
        add_action( 'brmedia_admin_shortcodes', array( $this, 'render_page' ) );
    }

    public function render_page() {
        ?>
        <div class="wrap brmedia-shortcodes">
            <h1><i class="fas fa-code"></i> <?php _e( 'Shortcodes', 'brmedia' ); ?></h1>
            <p><?php _e( 'Use the shortcodes below to embed media players on your site.', 'brmedia' ); ?></p>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php _e( 'Media Type', 'brmedia' ); ?></th>
                        <th><?php _e( 'Shortcode', 'brmedia' ); ?></th>
                        <th><?php _e( 'Example', 'brmedia' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php _e( 'Single Music Track', 'brmedia' ); ?></td>
                        <td><code>[brmedia_player id="123"]</code></td>
                        <td><button class="button copy-shortcode" data-code='[brmedia_player id="123"]'>Copy</button></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Single Video', 'brmedia' ); ?></td>
                        <td><code>[brmedia_video id="456"]</code></td>
                        <td><button class="button copy-shortcode" data-code='[brmedia_video id="456"]'>Copy</button></td>
                    </tr>
                    <tr>
                        <td><?php _e( 'Playlist by Genre', 'brmedia' ); ?></td>
                        <td><code>[brmedia_playlist genre="hardcore"]</code></td>
                        <td><button class="button copy-shortcode" data-code='[brmedia_playlist genre="hardcore"]'>Copy</button></td>
                    </tr>
                </tbody>
            </table>

            <script>
                document.querySelectorAll('.copy-shortcode').forEach(btn => {
                    btn.addEventListener('click', function() {
                        navigator.clipboard.writeText(this.dataset.code);
                        this.innerText = 'Copied!';
                        setTimeout(() => this.innerText = 'Copy', 1500);
                    });
                });
            </script>
        </div>
        <?php
    }
}

new BRMedia_Shortcodes_Page();
