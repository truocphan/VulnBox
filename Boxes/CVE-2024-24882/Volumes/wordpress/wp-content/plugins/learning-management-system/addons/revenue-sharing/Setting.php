<?php
/**
 * Revenue sharing setting.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing
 */

namespace Masteriyo\Addons\RevenueSharing;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Database\Model;

/**
 * Masteriyo revenue sharing setting class.
 *
 * @since 1.6.14
 */
class Setting {
	/**
	 * Setting option name.
	 *
	 * @var string
	 */
	private $name = 'masteriyo_revenue_sharing_settings';

	/**
	 * Setting data.
	 *
	 * @since 1.6.14
	 *
	 * @var array
	 */
	private $data = array(
		'enable'          => false,
		'deductible_fee'  => array(
			'enable' => false,
			'type'   => 'percentage',
			'amount' => 0,
			'name'   => '',
		),
		'admin_rate'      => 30,
		'instructor_rate' => 70,
		'withdraw'        => array(
			'min_amount'       => 80,
			'methods'          => array(),
			'bank_instruction' => '',
			'maturity_period'  => 7,
		),
	);

	/**
	 * Init.
	 *
	 * @since 1.6.14
	 */
	public function init() {
		add_filter( 'masteriyo_rest_pre_insert_setting_object', array( $this, 'save' ), 10, 3 );
		add_filter( 'masteriyo_rest_prepare_setting_object', array( $this, 'append_setting_in_response' ), 10, 3 );
	}

	/**
	 * Append revenue sharing setting setting to the global settings.
	 *
	 * @since 1.6.14
	 *
	 * @param \WP_REST_Response $response The response object.
	 * @param Model $object Object data.
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function append_setting_in_response( $response, $object, $request ) {
		$data                                = $response->get_data();
		$data['payments']['revenue_sharing'] = $this->get();

		$response->set_data( $data );

		return $response;
	}

	/**
	 * Store revenue sharing setting settings.
	 *
	 * @since 1.6.14
	 *
	 * @param Model $setting Object object.
	 * @param \WP_REST_Request $request Request object.
	 * @param bool $creating If is creating a new object.
	 *
	 * @return
	 */
	public function save( $setting, $request, $creating ) {
		$setting_in_db = get_option( $this->name, $this->data );
		$post_data     = masteriyo_array_get( $request, 'payments.revenue_sharing', $this->data );
		$setting_arr   = wp_parse_args( $post_data, $setting_in_db );

		update_option( $this->name, $setting_arr );

		return $setting;
	}

	/**
	 * Return setting value.
	 *
	 * @since 1.6.14
	 * @param string $key Setting key.
	 * @param mixed $default_value Setting default.
	 * @return mixed
	 */
	public function get( $key = null, $default_value = null ) {
		$setting = get_option( $this->name, array() );
		$setting = masteriyo_parse_args( $setting, $this->data );

		$value = $key ? masteriyo_array_get( $setting, $key, $default_value ) : $setting;

		return $value;
	}

	/**
	 * Save setting value.
	 *
	 * @since 1.6.14
	 *
	 * @param string $key Setting key.
	 * @param mixed $default Setting default.
	 */
	public function set( $key, $value ) {
		masteriyo_array_set( $this->data, $key, $value );
		update_option( $this->name, $this->data );
	}
}
