<?php
/**
 * BRMedia Metaboxes
 * Custom metaboxes for music and video post types
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Metaboxes {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );
        add_action( 'save_post', array( $this, 'save_metabox_data' ) );
    }

    public function register_metaboxes() {
        add_meta_box(
            'brmedia_meta_main',
            __( 'Media Details', 'brmedia' ),
            array( $this, 'render_main_metabox' ),
            array( 'brmedia_music', 'brmedia_video' ),
            'normal',
            'high'
        );
    }

    public function render_main_metabox( $post ) {
        wp_nonce_field( basename( __FILE__ ), 'brmedia_meta_nonce' );

        $media_url   = get_post_meta( $post->ID, '_brmedia_media_url', true );
        $cover_image = get_post_meta( $post->ID, '_brmedia_cover_image', true );
        $enable_dl   = get_post_meta( $post->ID, '_brmedia_enable_download', true );
        $tracklist   = get_post_meta( $post->ID, '_brmedia_tracklist', true );
        ?>
        <p>
            <label for="brmedia_media_url"><strong><?php _e( 'Media URL:', 'brmedia' ); ?></strong></label><br>
            <input type="text" name="brmedia_media_url" id="brmedia_media_url" class="widefat" value="<?php echo esc_url( $media_url ); ?>" />
            <button type="button" class="button brmedia-select-media" data-target="brmedia_media_url"><?php _e( 'Select File', 'brmedia' ); ?></button>
            <?php if ( $media_url ) : ?>
                <button type="button" class="button brmedia-audio-preview" data-url="<?php echo esc_url( $media_url ); ?>"><?php _e( 'Preview', 'brmedia' ); ?></button>
            <?php endif; ?>
        </p>

        <p>
            <label for="brmedia_cover_image"><strong><?php _e( 'Cover Image URL:', 'brmedia' ); ?></strong></label><br>
            <input type="text" name="brmedia_cover_image" id="brmedia_cover_image" class="widefat" value="<?php echo esc_url( $cover_image ); ?>" />
            <button type="button" class="button brmedia-upload-cover" data-target="brmedia_cover_image" data-preview="brmedia_cover_preview"><?php _e( 'Select Image', 'brmedia' ); ?></button>
            <br>
            <img id="brmedia_cover_preview" src="<?php echo esc_url( $cover_image ); ?>" style="margin-top:10px; max-width:100px; <?php echo empty( $cover_image ) ? 'display:none;' : ''; ?>" />
        </p>

        <p>
            <label for="brmedia_enable_download">
                <input type="checkbox" name="brmedia_enable_download" id="brmedia_enable_download" value="1" <?php checked( $enable_dl, '1' ); ?> />
                <?php _e( 'Enable Download?', 'brmedia' ); ?>
            </label>
        </p>

        <p>
            <label for="brmedia_tracklist"><strong><?php _e( 'Tracklist / Timestamps:', 'brmedia' ); ?></strong></label><br>
            <textarea name="brmedia_tracklist" id="brmedia_tracklist" class="widefat" rows="5"><?php echo esc_textarea( $tracklist ); ?></textarea>
            <small><?php _e( 'Format: 00:00:00 Track Title – one per line.', 'brmedia' ); ?></small>
        </p>

        <p>
            <button type="button" class="button brmedia-upload-tracklist" data-target="brmedia_tracklist"><?php _e( 'Upload .txt File', 'brmedia' ); ?></button>
        </p>
        <?php
    }

    public function save_metabox_data( $post_id ) {
        if ( ! isset( $_POST['brmedia_meta_nonce'] ) || ! wp_verify_nonce( $_POST['brmedia_meta_nonce'], basename( __FILE__ ) ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;

        $fields = array(
            'brmedia_media_url'     => '_brmedia_media_url',
            'brmedia_cover_image'   => '_brmedia_cover_image',
            'brmedia_tracklist'     => '_brmedia_tracklist',
        );

        foreach ( $fields as $form_field => $meta_key ) {
            $value = isset($_POST[ $form_field ]) ? sanitize_textarea_field( $_POST[ $form_field ] ) : '';
            update_post_meta( $post_id, $meta_key, $value );
        }

        $enable_dl = isset( $_POST['brmedia_enable_download'] ) ? '1' : '';
        update_post_meta( $post_id, '_brmedia_enable_download', $enable_dl );
    }
}

new BRMedia_Metaboxes();