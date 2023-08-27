<?php
/**
 * The file class that handles templates.
 *
 * @package JupiterX_Core\Control_Panel_2\Templates
 *
 * @since 1.18.0
 */

/**
 * Templates manager class.
 *
 * @todo Class duplicate of `includes/templates/class.php`.
 *
 * @since 1.18.0
 */
class JupiterX_Core_Control_Panel_Templates {

	const API_URL           = 'https://themes.artbees.net/wp-json/templates/v1';
	const FILTERS_TRANSIENT = 'jupiterx_templates_filters';
	const MAX_ALIVE         = 24 * HOUR_IN_SECONDS;

	/**
	 * Instance of install template manager class.
	 *
	 * @var JupiterX_Core_Control_Panel_Install_Template Class instance.
	 */
	private $install_manager = null;

	/**
	 * Class instance.
	 *
	 * @since 1.18.0
	 *
	 * @var JupiterX_Core_Control_Panel_Templates Class instance.
	 */
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 1.18.0
	 *
	 * @return JupiterX_Core_Control_Panel_Templates Class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		$this->install_manager = new JupiterX_Core_Control_Panel_Install_Template();

		add_action( 'before_rocket_clean_domain', [ $this, 'clear_transients' ] );
		add_action( 'wp_ajax_jupiterx_core_cp_import_template', [ $this, 'import_template' ] );
		add_action( 'wp_ajax_jupiterx_core_cp_import_template_content', [ $this, 'import_template_content' ] );
		add_action( 'wp_ajax_jupiterx_core_cp_get_template_psd', [ $this, 'get_template_psd' ] );
		add_action( 'wp_ajax_jupiterx_core_cp_get_template_sketch', [ $this, 'get_template_sketch' ] );
		add_action( 'wp_ajax_jupiterx_core_cp_get_templates', [ $this, 'get_templates' ] );
	}

	/**
	 * Clear transients.
	 *
	 * @since 1.18.0
	 */
	public function clear_transients() {
		delete_transient( 'jupiterx_templates_filters' );
	}

	/**
	 * Import template.
	 *
	 * @since 1.18.0
	 */
	public function import_template() {
		$this->install_manager->install_template_procedure();
	}

	/**
	 * Import template content.
	 *
	 * @since 1.18.0
	 */
	public function import_template_content() {
		$this->install_manager->import_theme_content_sse();
	}

	/**
	 * Get template PSD link.
	 *
	 * @since 1.18.0
	 */
	public function get_template_psd() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx' ),
				'status'  => true,
			] );
		}

		$this->install_manager->get_template_psd_link();
	}

	/**
	 * Get template SKETCH link.
	 *
	 * @since 1.18.0
	 */
	public function get_template_sketch() {
		$api_key = jupiterx_get_option( 'api_key' );

		if ( empty( $api_key ) ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx-core' ),
				'status'  => true,
			] );
		}

		$this->install_manager->get_template_sketch_link();
	}

	/**
	 * Get templates.
	 *
	 * @since 1.18.0
	 */
	public function get_templates() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		$filters = filter_input( INPUT_POST, 'filters', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( ! $filters ) {
			$filters = [];
		}

		$args = array_merge( [ 'source' => 'control_panel' ], $filters );

		if ( ! jupiterx_is_premium() ) {
			$args['sort_by'] = '_free_template';
		}

		$url = add_query_arg( $args, 'https://themes.artbees.net/wp-json/templates/v1/list' );

		$headers = [
			'timeout'     => 120,
			'httpversion' => '1.1',
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $headers ) ) );

		jupiterx_log(
			"[Control Panel > Templates] To list templates, the following data is the received response from '{$url}' API",
			$response
		);

		$additional = [
			'is_pro' => jupiterx_is_pro(),
		];

		wp_send_json( array_merge( $additional, (array) $response ) );
	}

	/**
	 * Get installed template.
	 *
	 * @since 1.18.0
	 *
	 * @return array Template data.
	 */
	public function get_installed() {
		$title = jupiterx_get_option( 'template_installed' );
		if ( $title ) {
			$title = ucwords( $title );
		}

		return [
			'id'    => jupiterx_get_option( 'template_installed_id' ),
			'title' => $title,
		];
	}

	/**
	 * Get API available filters.
	 *
	 * @since 1.18.0
	 *
	 * @return array Filters data.
	 */
	public function get_filters() {
		$filters = get_transient( self::FILTERS_TRANSIENT );
		if ( ! empty( $filters ) ) {
			return $filters;
		}

		// Set initial filters data.
		$filters = [
			'category'     => [],
			'style'        => [],
			'content_type' => [],
			'components'   => [],
			'menu_type'    => [],
			'header_type'  => [],
		];

		$request = wp_remote_get( self::API_URL . '/filter_criterias' );
		if ( is_wp_error( $request ) ) {
			return $filters;
		}

		$result = json_decode( wp_remote_retrieve_body( $request ), true );

		// Save categories.
		if ( ! empty( $result['categories'] ) ) {
			foreach ( $result['categories'] as $category ) {
				$filters['category'][ $category['slug'] ] = $category['name'];
			}

			asort( $filters['category'] );
		}

		$meta_fields = [
			'style'        => '_template_style',
			'content_type' => '_content_type',
			'components'   => '_components',
			'menu_type'    => '_menu_type',
			'header_type'  => '_header_type',
		];
		// Save meta fields.
		foreach ( $meta_fields as $key => $meta_name ) {
			$filters[ $key ] = $this->get_meta_options( $meta_name, $result );
			asort( $filters[ $key ] );
		}

		// Only save transient when category was successfully fetched.
		if ( ! empty( $filters['category'] ) ) {
			set_transient( self::FILTERS_TRANSIENT, $filters, self::MAX_ALIVE );
		}

		return $filters;
	}

	/**
	 * Get options by meta name.
	 *
	 * @since 1.18.0
	 *
	 * @param string $meta_name Meta name.
	 * @param array  $result    Filters result from API.
	 */
	private function get_meta_options( $meta_name, $result ) {
		$options = [];
		if ( ! isset( $result['meta_fields'] ) ) {
			return $options;
		}

		foreach ( $result['meta_fields'] as $meta ) {
			if ( isset( $meta['name'] ) && isset( $meta['options'] ) && $meta_name === $meta['name'] ) {
				return wp_list_pluck( $meta['options'], 'value', 'key' );
			}
		}

		return $options;
	}
}

JupiterX_Core_Control_Panel_Templates::get_instance();
