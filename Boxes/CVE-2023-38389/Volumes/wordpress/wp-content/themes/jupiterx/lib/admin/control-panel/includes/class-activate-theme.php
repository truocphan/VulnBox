<?php
/**
 * This class provides the methods to Store and retrieve Image sizes from database.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since   1.0.0
 */

/**
 * Store and retrieve Image sizes.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 */
class JupiterX_Control_Panel_Activate_Theme {


	/**
	 * JupiterX_Control_Panel_Activate_Theme constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->api_url       = 'https://artbees.net/api/v1/';
		add_action( 'wp_ajax_jupiterx_cp_register_revoke_api_action', array( &$this, 'activate_api_key' ) );
	}

	/**
	 *
	 *
	 *
	 * @param string $apikey Api key.
	 * @since 1.0
	 * @return array.
	 */
	public function remote_validate_artbees_apikey( $apikey ) {
		$data   = array(
			'timeout'     => 10,
			'httpversion' => '1.1',
			'body'        => array(
				'apikey' => $apikey,
				'domain' => $_SERVER['SERVER_NAME'],
			),
		);

		$query = wp_remote_post( $this->api_url . 'verify', $data );
		if ( ! is_wp_error( $query ) ) {
			$result = json_decode( $query['body'], true );
			return $result;
		}

		return array(
			'message' => $query->get_error_message() . ' Please contact Artbees Support',
		);
	}

	/**
	 *
	 * Activate & deactivate API keu
	 *
	 * @param string $apikey Api key.
	 * @since 1.0
	 * @return array.
	 */
	public function activate_api_key() {

		check_ajax_referer( 'jupiterx-cp-ajax-register-api', 'security' );
		$method = $_POST['method'];

		if ( 'register' == $method ) {
			$api_key = $_POST['api_key'];
			$result  = $this->remote_validate_artbees_apikey( $api_key );

			if ( $result['is_verified'] ) {
				jupiterx_update_option( 'api_key', $api_key, 'yes' );
				$message = __( 'Your product registration was successful.', 'jupiterx' );
				$status  = true;
			} else {
				jupiterx_delete_option( 'api_key' );
				$message = __( 'Your API key could not be verified. ', 'jupiterx' ) . (isset( $result['message'] ) ? $result['message'] : __( 'An error occured', 'jupiterx' )) . '.';
				$status  = false;
			}
		} elseif ( 'revoke' == $method ) {
			jupiterx_delete_option( 'api_key' );
			$message = __( 'Your API key has been successfully revoked.', 'jupiterx' );
			$status  = true;
		}

		echo json_encode(
			array(
				'message' => $message,
				'status'  => $status,
				'data'    => [ 'status' => $status ],
			)
		);

		wp_die();

	}


}
new JupiterX_Control_Panel_Activate_Theme();
