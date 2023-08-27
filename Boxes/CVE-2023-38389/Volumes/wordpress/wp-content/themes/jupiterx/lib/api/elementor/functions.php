<?php
/**
 * The Jupiter Elementor component contains a set of functions for Elementor plugin.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since   1.0.0
 */

add_action( 'elementor/widgets/register', 'jupiterx_elementor_register_widgets' );
/**
 * Register widgets to Elementor.
 *
 * @since 1.0.0
 * @access public
 *
 * @param object $widgets_manager The widgets manager.
 */
function jupiterx_elementor_register_widgets( $widgets_manager ) {
	require_once JUPITERX_API_PATH . 'elementor/widgets/sidebar.php';
	require_once JUPITERX_API_PATH . 'elementor/widgets/post-navigation.php';

	// Unregister native sidebar.
	$widgets_manager->unregister( 'sidebar' );

	// Register custom sidebar.
	$widgets_manager->register( new JupiterX_Elementor_Widget_Sidebar() );
}

add_action( 'wp_enqueue_scripts', 'jupiterx_elementor_modify_template_enqueue', 500 );
/**
 * Fix flash of unstyled components by enqueueing styles in head.
 *
 * @since 1.2.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_elementor_modify_template_enqueue() {

	if ( class_exists( '\Elementor\Plugin' ) ) {
		$elementor = \Elementor\Plugin::instance();
		$elementor->frontend->enqueue_styles();
	}

	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		$elementor = \ElementorPro\Plugin::instance();
		$elementor->enqueue_styles();
	}

	if ( ! class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
		return;
	}

	$header_location_exists = false;
	$footer_location_exists = false;

	if ( jupiterx_is_elementor_pro() && function_exists( 'elementor_location_exits' ) ) {
		$header_location_exists = elementor_location_exits( 'header', true );
		$footer_location_exists = elementor_location_exits( 'footer', true );
	}

	$header_type = jupiterx_get_field_mod( 'jupiterx_header_type', 'global' );
	$footer_type = jupiterx_get_field_mod( 'jupiterx_footer_type', 'global' );
	$templates   = [];

	if ( '_custom' === $header_type && ! $header_location_exists ) {
		$templates[] = jupiterx_get_field_mod( 'jupiterx_header_template', 'global', '' );
		$templates[] = jupiterx_get_field_mod( 'jupiterx_header_sticky_template', 'global', '' );
	}

	if ( '_custom' === $footer_type && ! $footer_location_exists ) {
		$templates[] = jupiterx_get_field_mod( 'jupiterx_footer_template', 'global', '' );
	}

	foreach ( $templates as $template ) {
		$css_file = new Elementor\Core\Files\CSS\Post( $template );
		$css_file->enqueue();
	}
}

if ( jupiterx_is_elementor() ) {
	add_action( 'template_include', 'jupiterx_override_archive_template', 999 );
	add_action( 'template_include', 'jupiterx_override_single_template', 999 );
}

/**
 * Override Archive Template by Elementor template.
 *
 * @param string $template Template path.
 *
 * @since 1.10.0
 *
 * @return string
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_override_single_template( $template ) {
	if ( ! is_singular() ) {
		return $template;
	}

	$post_type = get_post_type();

	if ( empty( $post_type ) ) {
		return $template;
	}

	$allowed_post_types = array_merge( [ 'post', 'portfolio', 'page' ], jupiterx_get_custom_post_types() );

	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return $template;
	}

	$elementor = \Elementor\Plugin::$instance;

	if ( $elementor->preview->is_preview_mode() ) {
		return $template;
	}

	if ( jupiterx_is_elementor_pro() ) {
		$conditions = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'conditions' );

		if ( ! empty( $conditions->get_location_templates( 'single' ) ) ) {
			return $template;
		}
	}

	if ( '_custom' !== get_theme_mod( "jupiterx_{$post_type}_single_template_type", '' ) ) {
		return $template;
	}

	$template_id = get_theme_mod( "jupiterx_{$post_type}_single_template", '' );

	if ( 'post' === $post_type ) { // jupiterx_post_single_template jas reserved for another option.
		$template_id = get_theme_mod( 'jupiterx_post_single_template_custom', '' );
	}

	if ( empty( $template_id ) ) {
		return $template;
	}

	jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
	jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

	jupiterx_add_filter( 'jupiterx_layout', 'c' );

	jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $template_id ) {
		jupiterx_output_e( 'jupiterx_custom_single_template', jupiterx_get_custom_template( $template_id ) );
	} );

	return JUPITERX_THEME_PATH . '/full-width.php';
}

/** * @SuppressWarnings(PHPMD.NPathComplexity) */
if ( ! function_exists( 'jupiterx_override_archive_template' ) ) {
	/**
	 * Override Archive Template by Elementor template.
	 *
	 * @param string $template Template path.
	 *
	 * @since 1.10.0
	 *
	 * @return string
	 */
	function jupiterx_override_archive_template( $template ) {
		if ( ! is_archive() ) {
			return $template;
		}

		$post_type = jupiterx_get_archive_post_type();

		if ( empty( $post_type ) ) {
			return $template;
		}

		$allowed_post_types = array_merge( [ 'post', 'portfolio' ], jupiterx_get_custom_post_types() );

		if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
			return $template;
		}

		$elementor = \Elementor\Plugin::$instance;

		if ( $elementor->preview->is_preview_mode() ) {
			return $template;
		}

		if ( jupiterx_is_elementor_pro() ) {
			$conditions = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'conditions' );

			if ( ! empty( $conditions->get_location_templates( 'archive' ) ) ) {
				return $template;
			}
		}

		if (
			'post' === $post_type &&
			'_custom' !== get_theme_mod( "jupiterx_{$post_type}_archive_template_type", '' )
		) {
			return $template;
		}

		$archive_post_id = get_theme_mod( "jupiterx_{$post_type}_archive_template", false );

		if ( empty( $archive_post_id ) ) {
			return $template;
		}

		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

		jupiterx_add_filter( 'jupiterx_layout', 'c' );

		jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $elementor, $archive_post_id ) {
			if ( ! $archive_post_id ) {
				return;
			}

			$document = $elementor->documents->get( $archive_post_id );

			$document->print_content();
		} );

		return JUPITERX_THEME_PATH . '/full-width.php';
	}
}

/**
 * Check if Elementor is active.
 *
 * @since 1.10.0
 */
function jupiterx_is_elementor() {
	if ( ! jupiterx_is_callable( '\Elementor\Plugin' ) ) {
		return false;
	}

	return true;
}

/**
 * Check if Elementor Pro is active.
 *
 * @since 1.2.0
 */
function jupiterx_is_elementor_pro() {
	if ( class_exists( '\ElementorPro\Plugin' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if condition is set for location.
 *
 * @param string $location Name of the main location.
 * @param string $sub_location Name of the sub location.
 *
 * @since 1.7.0
 */
function jupiterx_is_location_conditions_set( $location, $sub_location ) {
	$elementor_conditions = get_option( 'elementor_pro_theme_builder_conditions', [] );

	if ( ! isset( $elementor_conditions[ $location ] ) || ! is_array( $elementor_conditions[ $location ] ) ) {
		return false;
	}

	foreach ( (array) $elementor_conditions[ $location ] as $archive_template ) {
		if ( false !== array_search( "include/{$location}/{$sub_location}", $archive_template, true ) ) {
			return true;
		}
	}

	return false;
}

add_action( 'elementor/theme/register_locations', 'jupiterx_theme_register_elementor_locations' );
/**
 * Register Elementor theme locations.
 *
 * @since 1.13.0
 *
 * @param ThemeBuilder $elementor_theme_manager Theme builder class.
 */
function jupiterx_theme_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );
}



add_action( 'elementor/widgets/register', 'jupiterx_load_wc_elementor_fragments', 1 );
/**
 * Load wc fragments.
 *
 * @since 1.15.0
 *
 * @return void
 */
function jupiterx_load_wc_elementor_fragments() {
	$elementor    = \Elementor\Plugin::instance();
	$is_edit_mode = $elementor->editor->is_edit_mode();

	if ( ! $is_edit_mode ) {
		return;
	}

	if ( ! defined( 'WC_ABSPATH' ) ) {
		return;
	}

	if ( ! file_exists( WC_ABSPATH . 'includes/wc-template-hooks.php' ) ) {
		return;
	}

	add_action( 'woocommerce_init', function() {
		include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
	} );

	jupiterx_load_fragment_file( 'woocommerce' );
	jupiterx_load_fragment_file( 'product-list' );
}

add_action( 'elementor/finder/categories/register', 'jupiterx_elementor_extend_finder' );
/**
 * Extend Elementor finder.
 *
 * @since 1.18.0
 *
 * @param array $categories_manager The category manager.
 */
function jupiterx_elementor_extend_finder( $categories_manager ) {
	// Include the Finder Category class file.
	require_once JUPITERX_API_PATH . 'elementor/class-finder.php';

	// Add the category.
	$categories_manager->add_category( 'jupiterx', new JupiterX_Finder_Category() );
}

if ( jupiterx_is_elementor_pro() ) {
	add_filter( 'template_include', 'jupiterx_elementor_theme_template' );
}

if ( ! function_exists( 'jupiterx_elementor_theme_template' ) ) {
	/**
	 * Add support for Elementor "theme" page template.
	 *
	 * @since 1.25.0
	 *
	 * @param string $template The template.
	 *
	 * @return string
	 */
	function jupiterx_elementor_theme_template( $template ) {
		$post_id = get_queried_object_id();

		// Check if post id exists.
		if ( empty( $post_id ) ) {
			return $template;
		}

		$page_template            = get_page_template_slug( $post_id );
		$elementor_page_templates = \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' );

		// Check if page template is elementor_theme.
		if ( $page_template !== $elementor_page_templates::TEMPLATE_THEME ) {
			return $template;
		}

		$conditions = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'conditions' );

		// Check if single template has any conditions.
		if ( empty( $conditions->get_location_templates( 'single' ) ) ) {
			return $template;
		}

		// Prepare layout.
		jupiterx_add_filter( 'jupiterx_layout', 'c' );
		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

		// Find the single template page id.
		$post_ids = $conditions->get_location_templates( 'single' );
		$post_id  = array_key_first( $post_ids );

		// Replace content.
		jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $post_id ) {
			jupiterx_output_e( 'jupiterx_custom_single_template', jupiterx_get_custom_template( $post_id ) );
		} );

		return JUPITERX_THEME_PATH . '/full-width.php';
	}
}
