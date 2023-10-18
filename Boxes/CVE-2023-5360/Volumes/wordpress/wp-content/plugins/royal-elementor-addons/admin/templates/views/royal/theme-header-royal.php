<?php
use WprAddons\Admin\Includes\WPR_Conditions_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$conditions = json_decode( get_option('wpr_header_conditions', '[]'), true );
$template_slug = WPR_Conditions_Manager::header_footer_display_conditions($conditions);

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title>
			<?php echo esc_html(wp_get_document_title()); ?>
		</title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php

do_action( 'wp_body_open' );

// Royal themes compatibility
echo '<div id="page-wrap">';

// Render WPR Header
Utilities::render_elementor_template($template_slug);

// Royal themes compatibility
echo '<div class="page-content">';