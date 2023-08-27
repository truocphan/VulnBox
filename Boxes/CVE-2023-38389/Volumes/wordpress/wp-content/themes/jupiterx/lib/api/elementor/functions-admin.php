<?php
/**
 * This class handles extending Elementor plugin.
 *
 * @package JupiterX\Framework\API\Elementor
 *
 * @since 1.1.0
 */

add_action( 'customize_controls_enqueue_scripts', 'jupiterx_elementor_register_scripts' );
add_action( 'acf/admin_enqueue_scripts', 'jupiterx_elementor_register_scripts' );
/**
 * Register admin scripts.
 *
 * @since 1.2.0
 */
function jupiterx_elementor_register_scripts() {
	wp_enqueue_script( 'jupiterx-featherlight', JUPITERX_API_URL . 'elementor/assets/lib/featherlight/featherlight' . JUPITERX_MIN_JS . '.js', [], '1.7.13', true );
	wp_enqueue_script( 'jupiterx-elementor', JUPITERX_ASSETS_URL . 'dist/js/elementor' . JUPITERX_MIN_JS . '.js', [ 'jupiterx-featherlight' ], JUPITERX_VERSION, true );
	wp_enqueue_style( 'jupiterx-featherlight', JUPITERX_API_URL . 'elementor/assets/lib/featherlight/featherlight' . JUPITERX_MIN_CSS . '.css', [], '1.7.13' );
	wp_enqueue_style( 'jupiterx-elementor', JUPITERX_ASSETS_URL . 'dist/css/elementor' . JUPITERX_MIN_CSS . '.css', [ 'jupiterx-featherlight' ], JUPITERX_VERSION );
	wp_localize_script( 'jupiterx-elementor', 'jupiterxElementorUtils', [
		'editUrl' => jupiterx_elementor_get_edit_template_url(),
		'newUrl'  => jupiterx_elementor_get_new_template_url(),
	] );
}

add_action( 'wp_ajax_jupiterx_get_elementor_templates', 'jupiterx_elementor_get_templates' );
/**
 * Get templates AJAX.
 *
 * @since 1.1.0
 */
function jupiterx_elementor_get_templates() {
	if ( ! current_user_can( 'edit_others_posts' ) || ! current_user_can( 'edit_others_pages' ) ) {
		wp_send_json_error( 'You do not have access to this section', 'jupiterx' );
	}

	$template = [];

	// phpcs:disable
	if ( isset( $_POST['type'] ) && ! empty( $_POST['type'] ) ) {
		$templates = JupiterX_Customizer_Utils::get_templates( $_POST['type'] );
	}
	// phpcs:enable

	wp_send_json_success( $templates );
}

add_action( 'admin_action_elementor_new_post', 'jupiterx_elementor_new_post', 5 );
/**
 * Hook on new Elementor post.
 *
 * @since 1.1.0
 */
function jupiterx_elementor_new_post() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $_GET['context'] ) || 'jupiterx-customizer' !== $_GET['context'] ) {
		return;
	}

	/**
	 * Add `context` query to determine that the page is coming from WP customizer.
	 *
	 * @since 1.1.0
	 *
	 * @return string Modified URL.
	 */
	add_filter( 'elementor/document/urls/edit', function ( $url ) {
		return add_query_arg( [
			'context' => 'jupiterx-customizer',
		], $url );
	} );

	/**
	 * Remove URL hash added by Elementor document types.
	 *
	 * @since 1.1.0
	 *
	 * @return string Removed hash.
	 */
	add_filter( 'wp_redirect', function ( $location ) {
		$url = strstr( $location, '#', true );
		return $url ? $url : $location;
	} );
}

add_action( 'admin_action_elementor', 'jupiterx_elementor_edit_template' );
/**
 * Template add and edit error handling.
 *
 * @since 1.1.0
 */
function jupiterx_elementor_edit_template() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $_GET['post'] ) || ! isset( $_GET['context'] ) || 'jupiterx-customizer' !== $_GET['context'] ) {
		return;
	}

	// / phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$document = Elementor\Plugin::instance()->documents->get( absint( $_GET['post'] ) );

	if ( ! $document || ! $document->is_editable_by_current_user() ) {
		wp_die( esc_html__( 'Raven plugin is disabled. Please enable Raven plugin to be able to edit this template.', 'jupiterx' ) );
	}
}

add_action( 'elementor/editor/after_enqueue_scripts', 'jupiterx_elementor_editor_enqueue_scripts' );
/**
 * Enqueue editor scripts.
 *
 * @since 1.2.0
 */
function jupiterx_elementor_editor_enqueue_scripts() {
	// Embedded editor.
	if ( 'jupiterx-customizer' === jupiterx_get( 'context' ) ) {
		wp_enqueue_style(
			'jupiterx-elementor-editor-embedded',
			JUPITERX_ASSETS_URL . 'dist/css/elementor-editor-embedded' . JUPITERX_MIN_CSS . '.css',
			[],
			JUPITERX_VERSION
		);
	}

	wp_enqueue_style(
		'jupiterx-elementor-editor',
		JUPITERX_ASSETS_URL . 'dist/css/elementor-editor' . JUPITERX_MIN_CSS . '.css',
		[],
		JUPITERX_VERSION
	);

	wp_enqueue_script(
		'jupiterx-elementor-editor',
		JUPITERX_ASSETS_URL . 'dist/js/elementor-editor' . JUPITERX_MIN_JS . '.js',
		[ 'jquery' ],
		JUPITERX_VERSION,
		true
	);

	wp_enqueue_style(
		'jupiterx-admin-icons',
		JUPITERX_ASSETS_URL . 'dist/css/icons-admin.css',
		[],
		JUPITERX_VERSION
	);

	wp_enqueue_script(
		'jupiterx-gsap',
		JUPITERX_ADMIN_URL . 'control-panel/assets/lib/gsap/gsap' . JUPITERX_MIN_JS . '.js',
		[],
		'1.19.1',
		true
	);

	wp_enqueue_style(
		'jupiterx-modal',
		JUPITERX_ASSETS_URL . 'dist/css/jupiterx-modal' . JUPITERX_MIN_CSS . '.css',
		[],
		JUPITERX_VERSION
	);

	wp_enqueue_script(
		'jupiterx-modal',
		JUPITERX_ASSETS_URL . 'dist/js/jupiterx-modal' . JUPITERX_MIN_JS . '.js',
		[],
		JUPITERX_VERSION,
		true
	);

	wp_enqueue_style(
		'jupiterx-common',
		JUPITERX_ASSETS_URL . 'dist/css/common' . JUPITERX_MIN_CSS . '.css',
		[],
		JUPITERX_VERSION
	);

	wp_enqueue_script(
		'jupiterx-common',
		JUPITERX_ASSETS_URL . 'dist/js/common' . JUPITERX_MIN_JS . '.js',
		[ 'wp-util' ],
		JUPITERX_VERSION,
		true
	);

	wp_enqueue_script(
		'wp-color-picker-alpha',
		JUPITERX_ASSETS_URL . 'dist/js/wp-color-picker-alpha' . JUPITERX_MIN_JS . '.js',
		[ 'wp-color-picker' ],
		JUPITERX_VERSION,
		true
	);

	jupiterx_wpcolorpickeralpha_localize();

	if ( jupiterx_is_premium() || function_exists( 'jupiterx_pro' ) ) {
		wp_add_inline_script( 'jupiterx-common', 'var jupiterxPremium = true;', 'before' );
		wp_add_inline_script( 'jupiterx-common', 'var jupiterXControlPanelURL = "' . esc_url( admin_url( 'admin.php?page=jupiterx' ) ) . '";', 'before' );
	}
}

/**
 * Get new template URL.
 *
 * @since 1.2.0
 *
 * @param array $args Extended args.
 *
 * @return array
 */
function jupiterx_elementor_get_new_template_url( $args = [] ) {
	$defaults = [
		'post_type' => 'elementor_library',
		'action'    => 'elementor_new_post',
		'_wpnonce'  => wp_create_nonce( 'elementor_action_new_post' ),
		'context'   => 'jupiterx-customizer',
		'post_data' => [
			'post_status' => 'publish',
		],
	];

	if ( ! empty( $args ) && 'section' === $args['template_type'] ) {
		$defaults = [
			'post_type' => 'elementor_library',
			'action'    => 'elementor_new_post',
			'_wpnonce'  => wp_create_nonce( 'elementor_action_new_post' ),
			'post_data' => [
				'post_status' => 'publish',
			],
		];
	}

	return esc_url( add_query_arg( array_merge( $defaults, $args ), admin_url( 'edit.php' ) ) );
}

/**
 * Get edit template URL.
 *
 * @since 1.2.0
 *
 * @param array $args Extended args.
 *
 * @return array
 */
function jupiterx_elementor_get_edit_template_url( $args = [] ) {
	$defaults = [
		'action'  => 'elementor',
		'context' => 'jupiterx-customizer',
	];

	return esc_url( add_query_arg( array_merge( $defaults, $args ), admin_url( 'post.php' ) ) );
}

/**
 * Flush Elementor assets' cache.
 *
 * @since 1.2.0
 */
function jupiterx_elementor_flush_cache() {
	if ( ! jupiterx_is_callable( '\Elementor\Plugin' ) ) {
		return false;
	}

	try {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	} catch ( \Throwable $th ) {
		return false;
	}

	return true;
}
