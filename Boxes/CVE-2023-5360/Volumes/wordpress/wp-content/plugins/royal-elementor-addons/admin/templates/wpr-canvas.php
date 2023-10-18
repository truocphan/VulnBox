<?php

use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-canvas' );

$is_preview_mode = \Elementor\Plugin::$instance->preview->is_preview_mode();
// $woocommerce_class = $is_preview_mode && class_exists( 'WooCommerce' ) ? 'woocommerce woocommerce-page woocommerce-shop canvas-test' : '';
$woocommerce_class =  $is_preview_mode && class_exists( 'WooCommerce' ) ? 'woocommerce woocommerce-page' : '';

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
	<?php

	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	Utils::print_unescaped_internal_string( Utils::get_meta_viewport( 'canvas' ) );
	?>
</head>

<body <?php body_class($woocommerce_class); ?>>
	<?php
	Elementor\Modules\PageTemplates\Module::body_open();
	/**
	 * Before canvas page template content.
	 *
	 * Fires before the content of Elementor canvas page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'elementor/page_templates/canvas/before_content' );

	// Elementor Editor
	if (( \Elementor\Plugin::$instance->preview->is_preview_mode() && Utilities::is_theme_builder_template()) || is_singular('wpr_mega_menu') ) {
	     \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

	// Frontend
	} else {
		// Display Custom Elementor Templates
		do_action( 'elementor/page_templates/canvas/wpr_print_content' );
	}

	/**
	 * After canvas page template content.
	 *
	 * Fires after the content of Elementor canvas page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'elementor/page_templates/canvas/after_content' );

	wp_footer();
	?>
	</body>
</html>
