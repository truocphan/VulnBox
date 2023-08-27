<?php
/**
 * Active Campaign Handler.
 *
 * @package JupiterX_Core\Raven
 * @since 1.20.0
 */
namespace JupiterX_Core\Raven\Modules\Forms\Classes;

defined( 'ABSPATH' ) || die();

/**
 * Active Campaign Handler.
 *
 * @since 1.20.0
 */
class Active_Campaign_Handler {
	const API_OUTPUT = 'json';
	private $api_url;
	private $api_key;
	private $header;
	private $last_error;

	/**
	 * Initializing the active campaign handler.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function __construct( $api_key, $api_url ) {
		$this->api_url = trailingslashit( $api_url );
		$this->api_key = $api_key;
		$this->header  = [
			'Api-Token'  => $api_key,
		];
	}

	/**
	 * Get all the lists.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_lists() {
		$response = $this->get_request( 'list_list', [
			'api_key'    => $this->api_key,
			'ids'        => 'all',
			'api_output' => self::API_OUTPUT,
		] );

		if ( empty( $response ) ) {
			return false;
		}

		$lists = [];

		foreach ( $response as $list ) {
			if ( empty( $list->id ) ) {
				continue;
			}

			$lists[ $list->id ] = $list;
		}

		return [
			'lists'  => $lists,
			'fields' => $this->get_fields(),
		];
	}

	/**
	 * Get all the fields.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_fields() {
		$fields   = $this->active_campaign_default_fields();
		$response = $this->get_request( 'list_field_view', [
			'ids'        => 'all',
		] );

		if ( empty( $response ) ) {
			return $fields;
		}

		foreach ( $response as $field ) {
			if ( empty( $field->id ) ) {
				continue;
			}

			$fields[] = [
				'remote_label'    => $field->title,
				'remote_type'     => $this->normalize_type( $field->type ),
				'remote_tag'      => 'field[' . $field->id . ',0]',
				'remote_required' => (bool) $field->isrequired,
			];
		}

		return $fields;
	}

	/**
	 * For convert unNormal types to normal types.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @param $type
	 * @return string
	 */
	private function normalize_type( $type ) {
		static $types = [
			'text' => 'text',
			'number' => 'number',
			'address' => 'text',
			'phone' => 'text',
			'date' => 'text',
			'url' => 'url',
			'imageurl' => 'url',
			'radio' => 'radio',
			'dropdown' => 'select',
			'birthday' => 'text',
			'zip' => 'text',
		];

		return $types[ $type ];
	}

	/**
	 * Get last error.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_last_error() {
		return $this->last_error;
	}

	/**
	 * Format the response.
	 *
	 * @since 1.20.0
	 * @access private
	 */
	private function retrieve_response( $response ) {
		$body_response = wp_remote_retrieve_body( $response );
		$result        = json_decode( $body_response );

		if ( ! is_wp_error( $result ) ) {
			return $result;
		}

		$this->last_error = is_wp_error( $body_response );

		return false;
	}

	/**
	 * Send a request.
	 *
	 * @since 1.20.0
	 * @access private
	 */
	private function get_request( $endpoint, $args ) {
		$args['api_key']    = $this->api_key;
		$args['api_output'] = self::API_OUTPUT;
		$url                = add_query_arg( $args, $this->api_url . 'admin/api.php?api_action=' . $endpoint );

		$response = wp_remote_get( $url, [
			'timeout'     => 60,
		] );

		return $this->retrieve_response( $response );
	}

	/**
	 * Send a request in post format.
	 *
	 * @since 1.20.0
	 * @access private
	 */
	private function post_request( $endpoint, $args ) {
		$args['api_key']    = $this->api_key;
		$args['api_output'] = self::API_OUTPUT;
		$url                = add_query_arg( $args, $this->api_url . 'admin/api.php?api_action=' . $endpoint );

		$result = wp_remote_post( $url, [
			'timeout'     => 60,
			'method'      => 'POST',
			'body'        => $args,
		] );

		if ( ! empty( $result['response']['code'] ) && 200 === $result['response']['code'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Default fields.
	 *
	 * @since 1.20.0
	 * @access private
	 */
	private function active_campaign_default_fields() {
		return [
			[
				'remote_label'    => __( 'Email', 'jupiterx-core' ),
				'remote_type'     => 'email',
				'remote_tag'      => 'email',
				'remote_required' => true,
			],
			[
				'remote_label'    => __( 'First Name', 'jupiterx-core' ),
				'remote_type'     => 'text',
				'remote_tag'      => 'first_name',
				'remote_required' => false,
			],
			[
				'remote_label'    => __( 'Last Name', 'jupiterx-core' ),
				'remote_type'     => 'text',
				'remote_tag'      => 'last_name',
				'remote_required' => false,
			],
			[
				'remote_label'    => __( 'Phone', 'jupiterx-core' ),
				'remote_type'     => 'text',
				'remote_tag'      => 'phone',
				'remote_required' => false,
			],
			[
				'remote_label'    => __( 'Organization name', 'jupiterx-core' ),
				'remote_type'     => 'text',
				'remote_tag'      => 'orgname',
				'remote_required' => false,
			],
		];
	}

	/**
	 * Create contact at Activecampaign via api
	 *
	 * @param array $subscriber_data
	 *
	 * @return array|boolean
	 */
	public function create_subscriber( $subscriber_data = [] ) {
		return $this->post_request( 'contact_sync', $subscriber_data );
	}
}
