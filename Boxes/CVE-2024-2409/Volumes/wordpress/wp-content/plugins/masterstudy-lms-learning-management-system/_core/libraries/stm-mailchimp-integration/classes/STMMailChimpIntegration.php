<?php
require_once STM_ADMIN_MAILCHIMP_INTEGRATION_PATH . '/classes/STMMailchimpRequest.php';

class STMMailChimpIntegration {

	private static $apiUrl  = 'microservices.stylemixthemes.com/mailchimp';
	private static $handler = 'member';

	private static function getApiUrl() {
		return 'https://' . self::$apiUrl . '?' . http_build_query( [ 'handler' => self::$handler ], '', '&' );
	}

	public static function addMember( $memberData ) {
		$url     = self::getApiUrl();
		$headers = [ 'Content-Type: multipart/form-data' ];

		/**  Call API to create member */
		$response = STMMailchimpRequest::get( $url, $memberData, $headers, 'multipart' );

		if ( $response['status'] === 200 && property_exists( $response['response'], 'member_id' ) ) {
			return $response['response']->member_id;
		}

		return false;
	}

	public static function deleteMember( $memberData ) {
		$url     = self::getApiUrl();
		$headers = [ 'Content-Type: multipart/form-data' ];

		/**  Call API to create member */
		$response = STMMailchimpRequest::get( $url, $memberData, $headers, 'multipart' );

		if ( $response['status'] === 200 ) {
			return true;
		}

		return false;
	}
}