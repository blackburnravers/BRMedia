<?php
/**
 * BRMedia Email Share Template
 * Used when sharing a media item via email
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Expected: $post_id, $media_url, $title, $cover_art (optional)

$title     = get_the_title( $post_id );
$permalink = get_permalink( $post_id );
$excerpt   = get_the_excerpt( $post_id );
$cover_art = get_post_meta( $post_id, '_brmedia_cover_image', true );

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $title ); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .email-box {
            background: #fff;
            border-radius: 6px;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 12px rgba(0,0,0,0.08);
        }
        .email-box img {
            max-width: 100%;
            border-radius: 4px;
        }
        .email-box h2 {
            margin-top: 0;
            font-size: 22px;
            color: #111;
        }
        .email-box p {
            font-size: 16px;
            line-height: 1.6;
        }
        .brmedia-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #3a7bd5;
            color: #fff !important;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-box">
        <?php if ( $cover_art ) : ?>
            <img src="<?php echo esc_url( $cover_art ); ?>" alt="<?php echo esc_attr( $title ); ?>">
        <?php endif; ?>

        <h2><?php echo esc_html( $title ); ?></h2>

        <?php if ( $excerpt ) : ?>
            <p><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>

        <a class="brmedia-link" href="<?php echo esc_url( $permalink ); ?>" target="_blank">
            <?php _e( 'Listen / Watch Now', 'brmedia' ); ?>
        </a>
    </div>
</body>
</html>