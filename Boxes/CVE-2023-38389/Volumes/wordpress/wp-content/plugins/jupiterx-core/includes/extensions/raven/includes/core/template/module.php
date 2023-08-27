<?php
/**
 * Add Template Library Module.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Core\Template;

use Elementor\TemplateLibrary;
use Elementor\Api;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Raven template library module.
 *
 * Raven template library module handler class is responsible for registering and fetching
 * Raven templates.
 *
 * @since 1.0.0
 */
class Module {

	/**
	 * Templates API URL.
	 *
	 * Holds the URL of the Templates API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API URL.
	 */
	private static $templates_url = 'https://jupiterx.artbees.net/library/wp-json/jupiterx/v1/templates/%s';

	/**
	 * Template Categories API URL.
	 *
	 * Holds the URL of the Template Categories API.
	 *
	 * @access private
	 * @static
	 *
	 * @var string API URL.
	 */
	private static $template_categories_url = 'https://jupiterx.artbees.net/library/wp-json/jupiterx/v1/template-categories';

	/**
	 * Template Categories transient key.
	 *
	 * Holds the name of Template Categories transient key.
	 *
	 * @access private
	 * @static
	 *
	 * @var string Transient key.
	 */
	private static $template_categories_transient_key = 'raven_template_categories';

	/**
	 * Fetch templates from server.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Templates.
	 */
	public static function get_templates() {
		$url = sprintf( self::$templates_url, '' );

		$response = wp_remote_get( $url, [
			'timeout' => 40,
		] );

		jupiterx_log(
			"[Jupiter X Elements > Templates] To show templates, the following data is the received response from '{$url}' API.",
			$response
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}

	/**
	 * Fetch template content from server.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $template_id Template ID.
	 *
	 * @return array Template content.
	 */
	public static function get_template_content( $template_id ) {
		$url = sprintf( self::$templates_url, $template_id );

		$response = wp_remote_get( $url, [
			'timeout' => 60,
		] );

		jupiterx_log(
			"[Jupiter X Elements > Templates] To get template content, the following data is the received response from '{$url}' API.",
			$response
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content['content'] ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		$template_content['content'] = json_decode( $template_content['content'], true );

		return $template_content;
	}

	/**
	 * Initialize Raven template library module.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Add raven categories to the list.
		if ( defined( '\Elementor\Api::LIBRARY_OPTION_KEY' ) ) {
			// Sort before jet elements prepend its categories.
			add_filter( 'option_' . \Elementor\Api::LIBRARY_OPTION_KEY, [ $this, 'add_categories' ], 5 );
		}

		// Register Raven source.
		Elementor::instance()->templates_manager->register_source( __NAMESPACE__ . '\Source_Raven' );

		// Register proper AJAX actions for Raven templates.
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ), 20 );

		add_action( 'set_transient_elementor_remote_info_api_data_' . ELEMENTOR_VERSION, [ $this, 'delete_transients' ] );

		add_filter( 'rest_pre_echo_response', [ $this, 'filter_template_categories' ], 10, 3 );
	}

	/**
	 * Override registered Elementor native actions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ajax AJAX manager.
	 */
	public function register_ajax_actions( $ajax ) {
		// phpcs:disable
		if ( ! isset( $_REQUEST['actions'] ) ) {
			return;
		}

		$actions = json_decode( stripslashes( $_REQUEST['actions'] ), true );

		$data = false;

		foreach ( $actions as $action_data ) {
			if ( ! isset( $action_data['get_template_data'] ) ) {
				$data = $action_data;
			}
		}

		if ( ! $data ) {
			return;
		}

		if ( ! isset( $data['data'] ) ) {
			return;
		}

		$data = $data['data'];

		if ( empty( $data['template_id'] ) ) {
			return;
		}

		if ( false === strpos( $data['template_id'], 'raven_' ) ) {
			return;
		}

		// Once found out that current request is for Raven then replace the native action.
		$ajax->register_ajax_action( 'get_template_data', array( $this, 'get_template_data' ) );
		// phpcs:enable
	}

	/**
	 * Get template data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Request arguments.
	 *
	 * @return array Template data.
	 */
	public static function get_template_data( $args ) {
		$source = Elementor::instance()->templates_manager->get_source( 'raven' );

		$args['template_id'] = intval( str_replace( 'raven_', '', $args['template_id'] ) );

		$data = $source->get_data( $args );

		return $data;
	}

	/**
	 * Add new categories to list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Template library data including categories and templates.
	 *
	 * @return array $data Modified library data.
	 */
	public function add_categories( $data ) {
		$categories = $this->get_categories();

		$data['types_data']['block']['categories'] = array_merge( $categories, $data['types_data']['block']['categories'] );

		sort( $data['types_data']['block']['categories'] );

		return $data;
	}

	/**
	 * Get categories from Artbees Library API.
	 *
	 * @since 1.4.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_categories() {
		$categories = get_transient( self::$template_categories_transient_key );

		if ( ! empty( $categories ) ) {
			return $categories;
		}

		$response = wp_remote_get( self::$template_categories_url, array( 'timeout' => 60 ) );

		jupiterx_log(
			"[Jupiter X Elements > Templates] To show template categories, the following data is the received response from '" . self::$template_categories_url . "' API.",
			$response
		);

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return [];
		}

		$categories = json_decode( wp_remote_retrieve_body( $response ), true );

		set_transient( self::$template_categories_transient_key, $categories );

		return ! empty( $categories ) ? $categories : [];
	}

	/**
	 * Filter categories based on template source.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array           $result  Response data to send to the client.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return array $result Filtered Response data to send to the client.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function filter_template_categories( $result, $server, $request ) {
		if (
			'object' === gettype( $result ) ||
			empty( $result['config']['block']['categories'] )
		) {
			return $result;
		}

		$params = $request->get_query_params();

		if ( empty( $params['source'] ) || 'local' === $params['source'] ) {
			return $result;
		}

		$categories = $result['config']['block']['categories'];

		if ( 'remote' === $params['source'] ) {
			$filtered_categories = array_filter( $categories, function( $category ) {
				return strpos( $category, 'Jupiter X' ) === false;
			});
		}

		if ( 'raven' === $params['source'] ) {
			$filtered_categories = array_filter( $categories, function( $category ) {
				return strpos( $category, 'Jupiter X' ) !== false;
			});

			foreach ( $filtered_categories as $index => $category ) {
				$filtered_categories[ $index ] = str_replace( 'Jupiter X ', '', $category );
			}
		}

		$result['config']['block']['categories'] = array_values( $filtered_categories );

		return $result;
	}

	/**
	 * Delete template transients.
	 *
	 * @since 1.4.0
	 *
	 * @return void
	 */
	public function delete_transients() {
		delete_transient( self::$template_categories_transient_key );
	}
}
