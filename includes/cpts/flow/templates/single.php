<?php
/**
 * Template for single Review Collector CPT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only show review box
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, sans-serif;
            background: #f9f9f9;
        }
    </style>
</head>
<body <?php body_class(); ?>>

<div id="review-box">
	<?php
	echo do_blocks('<!-- wp:review-bird/flow {"flow_id":"' . get_the_ID() . '"} /-->');
	// TODO call do_blocks() or do_shortcode() by passing the_ID
	?>
</div>

<?php wp_footer(); ?>
</body>
</html>
