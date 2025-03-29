<?php
/**
 * BRMedia Settings API
 * A modular tabbed settings API for admin settings and template configuration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRMedia_Settings_API {

    private $settings_sections = array();
    private $settings_fields = array();

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'brmedia' ) === false ) return;

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_media();

        wp_enqueue_style( 'brmedia-admin-settings', BRMEDIA_URL . 'assets/css/settings.css', array(), BRMEDIA_VERSION );
        wp_enqueue_script( 'brmedia-admin-settings', BRMEDIA_URL . 'assets/js/settings.js', array( 'jquery' ), BRMEDIA_VERSION, true );
    }

    public function set_sections( $sections ) {
        $this->settings_sections = $sections;
        return $this;
    }

    public function add_section( $section ) {
        $this->settings_sections[] = $section;
        return $this;
    }

    public function set_fields( $fields ) {
        $this->settings_fields = $fields;
        return $this;
    }

    public function add_field( $section, $field ) {
        $defaults = array(
            'name'    => '',
            'label'   => '',
            'desc'    => '',
            'type'    => 'text',
            'default' => ''
        );

        $this->settings_fields[ $section ][] = wp_parse_args( $field, $defaults );
        return $this;
    }

    public function admin_init() {
        foreach ( $this->settings_sections as $section ) {
            if ( ! get_option( $section['id'] ) ) {
                add_option( $section['id'] );
            }

            add_settings_section(
                $section['id'],
                $section['title'],
                array( $this, 'section_callback' ),
                $section['id']
            );
        }

        foreach ( $this->settings_fields as $section => $fields ) {
            foreach ( $fields as $field ) {
                $field_args = array_merge(
                    array(
                        'id'                => $field['name'],
                        'label_for'         => $field['name'],
                        'desc'              => isset( $field['desc'] ) ? $field['desc'] : '',
                        'label'             => isset( $field['label'] ) ? $field['label'] : '',
                        'type'              => $field['type'],
                        'section'           => $section,
                        'default'           => isset( $field['default'] ) ? $field['default'] : '',
                        'options'           => isset( $field['options'] ) ? $field['options'] : array(),
                        'placeholder'       => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
                        'size'              => isset( $field['size'] ) ? $field['size'] : 'regular',
                        'sanitize_callback' => isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '',
                        'min'               => isset( $field['min'] ) ? $field['min'] : '',
                        'max'               => isset( $field['max'] ) ? $field['max'] : '',
                        'step'              => isset( $field['step'] ) ? $field['step'] : ''
                    ),
                    $field
                );

                add_settings_field(
                    $field_args['id'],
                    $field_args['label'],
                    array( $this, 'render_field' ),
                    $section,
                    $section,
                    $field_args
                );
            }

            register_setting( $section, $section, array( $this, 'sanitize_options' ) );
        }
    }

    public function section_callback( $args ) {
        $section = $args['id'];
        if ( isset( $this->settings_sections ) ) {
            foreach ( $this->settings_sections as $sec ) {
                if ( $sec['id'] === $section && isset( $sec['desc'] ) ) {
                    echo '<div class="brmedia-section-desc">' . esc_html( $sec['desc'] ) . '</div>';
                    break;
                }
            }
        }
    }

    public function render_field( $args ) {
        $value = $this->get_option( $args['id'], $args['section'], $args['default'] );
        $html = '';
        $id = $args['section'] . '[' . $args['id'] . ']';

        switch ( $args['type'] ) {
            case 'text':
                $html = "<input type='text' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' class='{$args['size']}-text' placeholder='{$args['placeholder']}' />";
                break;

            case 'number':
                $html = "<input type='number' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' min='{$args['min']}' max='{$args['max']}' step='{$args['step']}' />";
                break;

            case 'textarea':
                $html = "<textarea id='{$id}' name='{$id}' rows='5' cols='60' placeholder='{$args['placeholder']}'>" . esc_textarea( $value ) . "</textarea>";
                break;

            case 'checkbox':
                $checked = checked( $value, 'on', false );
                $html = "<label><input type='checkbox' id='{$id}' name='{$id}' value='on' {$checked}/> {$args['desc']}</label>";
                break;

            case 'select':
                $html = "<select id='{$id}' name='{$id}'>";
                foreach ( $args['options'] as $key => $label ) {
                    $selected = selected( $value, $key, false );
                    $html .= "<option value='{$key}' {$selected}>{$label}</option>";
                }
                $html .= "</select>";
                break;

            case 'radio':
                foreach ( $args['options'] as $key => $label ) {
                    $checked = checked( $value, $key, false );
                    $html .= "<label><input type='radio' id='{$id}-{$key}' name='{$id}' value='{$key}' {$checked}/> {$label}</label><br>";
                }
                break;

            case 'color':
                $html = "<input type='text' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' class='wp-color-picker-field' data-default-color='{$args['default']}' />";
                break;

            case 'range':
                $html = "<input type='range' id='{$id}' name='{$id}' value='" . esc_attr( $value ) . "' min='{$args['min']}' max='{$args['max']}' step='{$args['step']}' />";
                $html .= "<span class='brmedia-range-display'>" . esc_attr( $value ) . "</span>";
                break;

            default:
                $html = apply_filters( 'brmedia_custom_field_' . $args['type'], $args, $value );
                break;
        }

        if ( $args['desc'] && $args['type'] !== 'checkbox' ) {
            $html .= "<p class='description'>{$args['desc']}</p>";
        }

        echo "<div class='brmedia-field brmedia-{$args['type']}-field'>{$html}</div>";
    }

    public function sanitize_options( $options ) {
        if ( ! $options ) return $options;

        foreach ( $options as $slug => $value ) {
            $callback = $this->get_sanitize_callback( $slug );
            if ( $callback ) {
                $options[ $slug ] = call_user_func( $callback, $value );
            } elseif ( ! is_array( $value ) ) {
                $options[ $slug ] = sanitize_text_field( $value );
            }
        }

        return $options;
    }

    public function get_sanitize_callback( $slug = '' ) {
        if ( empty( $slug ) || ! isset( $this->settings_fields ) ) return false;

        foreach ( $this->settings_fields as $section => $fields ) {
            foreach ( $fields as $field ) {
                if ( $field['name'] === $slug && isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
                    return $field['sanitize_callback'];
                }
            }
        }

        return false;
    }

    public function get_option( $option, $section, $default = '' ) {
        $options = get_option( $section );
        return $options[ $option ] ?? $default;
    }

    public function render_tabs() {
        echo '<h2 class="nav-tab-wrapper brmedia-tab-wrapper">';
        foreach ( $this->settings_sections as $tab ) {
            printf( '<a href="#%1$s" class="nav-tab">%2$s</a>', esc_attr( $tab['id'] ), esc_html( $tab['title'] ) );
        }
        echo '</h2>';
    }

    public function render_forms() {
        echo '<div class="brmedia-settings-forms">';
        foreach ( $this->settings_sections as $section ) {
            echo '<div id="' . esc_attr( $section['id'] ) . '" class="brmedia-settings-section">';
            echo '<form method="post" action="options.php">';
            settings_fields( $section['id'] );
            do_settings_sections( $section['id'] );
            submit_button();
            echo '</form></div>';
        }
        echo '</div>';

        $this->inline_script();
    }

    private function inline_script() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.brmedia-tab-wrapper a').on('click', function(e) {
                e.preventDefault();
                $('.brmedia-tab-wrapper a').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');

                $('.brmedia-settings-section').hide();
                $($(this).attr('href')).fadeIn();
            });

            $('.brmedia-tab-wrapper a:first').click();

            $('.wp-color-picker-field').wpColorPicker();

            $('input[type="range"]').on('input', function() {
                $(this).next('.brmedia-range-display').text($(this).val());
            });
        });
        </script>
        <?php
    }
}