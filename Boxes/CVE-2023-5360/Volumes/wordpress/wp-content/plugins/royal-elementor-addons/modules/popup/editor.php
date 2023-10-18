<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Elementor Instance
$elementor_plugin = \Elementor\Plugin::$instance;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo esc_html(wp_get_document_title()); ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="wpr-template-popup">
		<div class="wpr-template-popup-inner">

			<!-- Popup Overlay & Close Button -->
			<div class="wpr-popup-overlay"></div>

			<!-- Template Container -->
			<div class="wpr-popup-container">

				<!-- Popup Close Button -->
				<div class="wpr-popup-close-btn"><i class="eicon-close"></i></div>

				<div class="wpr-popup-container-inner">
					<?php $elementor_plugin->modules_manager->get_modules( 'page-templates' )->print_content(); ?>
				</div>

			</div>

		</div>
	</div>

	<?php wp_footer(); ?>

</body>
</html>
