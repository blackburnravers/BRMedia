<?php
/**
 * BRMedia Widgets
 * Custom media player widgets for sidebars and builders
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'widgets_init', 'brmedia_register_widgets' );

function brmedia_register_widgets() {
    register_widget( 'BRMedia_Music_Widget' );
    register_widget( 'BRMedia_Video_Widget' );
}

/**
 * Music Widget
 */
class BRMedia_Music_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'brmedia_music_widget',
            __( 'BRMedia Music Player', 'brmedia' ),
            array( 'description' => __( 'Display a music track in widget area.', 'brmedia' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if ( ! empty( $instance['track_id'] ) && function_exists( 'brmedia_the_player' ) ) {
            echo brmedia_get_player( $instance['track_id'] );
        }

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title    = isset( $instance['title'] ) ? $instance['title'] : '';
        $track_id = isset( $instance['track_id'] ) ? $instance['track_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'brmedia' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'track_id' ); ?>"><?php _e( 'Track ID:', 'brmedia' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'track_id' ); ?>"
                   name="<?php echo $this->get_field_name( 'track_id' ); ?>" type="number"
                   value="<?php echo esc_attr( $track_id ); ?>"/>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance              = array();
        $instance['title']     = sanitize_text_field( $new_instance['title'] );
        $instance['track_id']  = absint( $new_instance['track_id'] );
        return $instance;
    }
}

/**
 * Video Widget
 */
class BRMedia_Video_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'brmedia_video_widget',
            __( 'BRMedia Video Player', 'brmedia' ),
            array( 'description' => __( 'Display a video in widget area.', 'brmedia' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if ( ! empty( $instance['video_id'] ) && function_exists( 'brmedia_the_player' ) ) {
            echo brmedia_get_player( $instance['video_id'] );
        }

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title    = isset( $instance['title'] ) ? $instance['title'] : '';
        $video_id = isset( $instance['video_id'] ) ? $instance['video_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'brmedia' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'video_id' ); ?>"><?php _e( 'Video ID:', 'brmedia' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'video_id' ); ?>"
                   name="<?php echo $this->get_field_name( 'video_id' ); ?>" type="number"
                   value="<?php echo esc_attr( $video_id ); ?>"/>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance               = array();
        $instance['title']      = sanitize_text_field( $new_instance['title'] );
        $instance['video_id']   = absint( $new_instance['video_id'] );
        return $instance;
    }
}