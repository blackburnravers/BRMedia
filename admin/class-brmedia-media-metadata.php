<?php
/**
 * BRMedia Media Metadata
 * Extracts and stores media metadata from uploaded files
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Media_Metadata {

    public function __construct() {
        add_action( 'add_attachment', array( $this, 'extract_metadata' ) );
        add_action( 'brmedia_refresh_metadata', array( $this, 'extract_metadata' ) ); // Manual trigger
    }

    /**
     * Extract metadata and save to attachment postmeta
     */
    public function extract_metadata( $attachment_id ) {
        $mime = get_post_mime_type( $attachment_id );

        if ( ! in_array( $mime, array( 'audio/mpeg', 'audio/mp3', 'audio/wav', 'video/mp4', 'video/webm' ) ) ) {
            return;
        }

        $file = get_attached_file( $attachment_id );

        if ( ! file_exists( $file ) ) {
            return;
        }

        $meta = array();

        // Basic WordPress metadata (duration)
        if ( 0 === strpos( $mime, 'audio' ) ) {
            $audio_meta = wp_read_audio_metadata( $file );

            if ( ! empty( $audio_meta ) ) {
                $meta['duration'] = $audio_meta['length_formatted'] ?? '';
                $meta['bitrate']  = $audio_meta['bitrate'] ?? '';
                $meta['artist']   = $audio_meta['artist'] ?? '';
                $meta['album']    = $audio_meta['album'] ?? '';
                $meta['title']    = $audio_meta['title'] ?? '';
                $meta['track']    = $audio_meta['track_number'] ?? '';
            }
        }

        // Placeholder for advanced metadata (to expand)
        $meta['bpm'] = '';
        $meta['key'] = '';
        $meta['tags'] = array();
        $meta['mood'] = '';
        $meta['genre'] = '';

        foreach ( $meta as $key => $value ) {
            update_post_meta( $attachment_id, '_brmedia_' . $key, $value );
        }
    }

    /**
     * Retrieve a single metadata field
     */
    public static function get_meta( $attachment_id, $field, $default = '' ) {
        return get_post_meta( $attachment_id, '_brmedia_' . $field, true ) ?: $default;
    }

    /**
     * Get all BRMedia-specific metadata
     */
    public static function get_all_meta( $attachment_id ) {
        $fields = array( 'duration', 'bitrate', 'artist', 'album', 'title', 'track', 'bpm', 'key', 'tags', 'mood', 'genre' );
        $meta = array();

        foreach ( $fields as $field ) {
            $meta[ $field ] = self::get_meta( $attachment_id, $field );
        }

        return $meta;
    }
}

new BRMedia_Media_Metadata();