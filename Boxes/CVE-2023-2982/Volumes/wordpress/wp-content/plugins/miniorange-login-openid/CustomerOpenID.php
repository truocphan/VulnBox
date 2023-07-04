<?php
/** miniOrange enables user to log in through OpenID to apps such as Google, Salesforce etc.
Copyright (C) 2015  miniOrange

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * @package         miniOrange OAuth
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
/**
This library is miniOrange Authentication Service.
Contains Request Calls to Customer service.
 **/
class CustomerOpenID {

	public $email;
	public $phone;

	private $defaultCustomerKey = '16555';
	private $defaultApiKey      = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	function create_customer( $password ) {
		$url          = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/add';
		$current_user = wp_get_current_user();
		$this->email  = get_option( 'mo_openid_admin_email' );

		$fields       = array(
			'areaOfInterest' => 'WP OpenID Connect Login Plugin',
			'email'          => $this->email,
			'password'       => $password,
		);
		$field_string = json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response     = self::mo_openid_wp_remote_post( $url, $args );
		if ( $response['body'] == null ) {
            if(isset($_POST['action'])?$_POST['action']=='mo_register_new_user':0)  // phpcs:ignore
				wp_send_json( array( 'error' => 'There was an error creating an account for you. Please try again.' ) );
			else {
				update_option( 'mo_openid_message', 'There was an error creating an account for you. Please try again.' );
				update_option( 'mo_openid_registration_status', 'error creating an account' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
			}
		} else {
			return $response['body'];
		}
	}

	function get_customer_key( $password ) {
		$url   = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/key';
		$email = get_option( 'mo_openid_admin_email' );

		$fields       = array(
			'email'    => $email,
			'password' => $password,
		);
		$field_string = json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response     = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function check_customer() {
		$url   = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/check-if-exists';
		$email = get_option( 'mo_openid_admin_email' );

		$fields       = array(
			'email' => $email,
		);
		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function check_customer_valid() {
		$url         = get_option( 'mo_openid_host_name' ) . '/moas/api/customer/license';
		$customerKey = get_option( 'mo_openid_admin_customer_key' );
		$apiKey      = get_option( 'mo_openid_admin_api_key' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = self::get_timestamp();
		$stringToHash        = $customerKey . $currentTimeInMillis . $apiKey;
		/* Creating the Hash using SHA-512 algorithm */
		$hashValue    = hash( 'sha512', $stringToHash );
		$fields       = array(
			'customerId'      => $customerKey,
			'applicationName' => 'wp_social_login_standard_plan',
		);
		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function get_timestamp() {
		$url      = get_option( 'mo_openid_host_name' ) . '/moas/rest/mobile/get-timestamp';
		$response = self::mo_openid_wp_remote_post( $url );
		return $response['body'];
	}

	function mo_openid_send_email_alert( $email, $phone, $message, $skip_followup ) {
		$hostname = get_site_option( 'mo_openid_host_name' );
		$url      = $hostname . '/moas/api/notify/send';
		// $customer_details = Utilities::getCustomerDetails();
		$customerKey = $this->defaultCustomerKey;
		$apiKey      = $this->defaultApiKey;

		$phone_number          = get_option( 'mo_openid_admin_phone' );
		$currentTimeInMillis   = self::get_timestamp();
		$stringToHash          = $customerKey . $currentTimeInMillis . $apiKey;
		$hashValue             = hash( 'sha512', $stringToHash );
		$fromEmail             = $email;
		$subject               = 'MiniOrange Social Login Plugin Feedback : ' . $email;
		$site_url              = site_url();
		$activation_date       = get_option( 'mo_openid_user_activation_date1' );
		$deactivationdate      = date( 'Y-m-d' );
		$version               = get_option( 'mo_openid_social_login_version' );
		$store_activation      = strtotime( $activation_date );
		$store_deactivation    = strtotime( $deactivationdate );
		$diff                  = $store_deactivation - $store_activation;
		$total_activation_days = abs( round( $diff / 86400 ) );
		global $user;
		$user = wp_get_current_user();

		$query   = ' MiniOrange Social Login [Free] ';
		$content = '<div >Hello, <br><br>First Name :<br><br>Last  Name :
								<br><br>Company : ' . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . '
								<br><br>Phone Number : ' . $phone_number . '
								<br><br>Email : <a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a>
								<br><br>Activation time : ' . $activation_date . ' - ' . $deactivationdate . '  [' . $total_activation_days . ']
                                <br><br>Follow-Up: ' . $skip_followup . '
								<br><br>Plugin Deactivated: ' . $query . '' . $version . '
								<br><br>Reason: <b>' . $message . '</b></div>';

		$fields       = array(
			'customerKey' => $customerKey,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customerKey,
				'fromEmail'   => $fromEmail,
				'bccEmail'    => 'socialloginsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'socialloginsupport@xecurify.com',
				'toName'      => 'socialloginsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];

	}
	function submit_rate_us( $email, $query ) {

		$query = 'Star Rating:' . get_option( 'mo_openid_rating_given' ) . ' ' . $query;
		// $hostname = Utilities::getHostname();
		$hostname = get_site_option( 'mo_openid_host_name' );
		$url      = $hostname . '/moas/api/notify/send';

		// $customer_details = Utilities::getCustomerDetails();
		$customerKey = $this->defaultCustomerKey;
		$apiKey      = $this->defaultApiKey;

		$currentTimeInMillis = self::get_timestamp();
		$stringToHash        = $customerKey . $currentTimeInMillis . $apiKey;
		$hashValue           = hash( 'sha512', $stringToHash );
		$fromEmail           = $email;
		$subject             = 'MiniOrange Social Login Plugin Rate us Feedback: ' . $email;
		$site_url            = site_url();

		global $user;
		$user = wp_get_current_user();

		$content = '<div >Hello,  <br><br>First Name :
								<br><br>Last  Name :
								<br><br>Company :
								<br><br><b>Email :<a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a></b>
								<br><br><b>Reason: ' . $query . '</b></div>';

		$fields       = array(
			'customerKey' => $customerKey,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customerKey,
				'fromEmail'   => $fromEmail,
				'bccEmail'    => 'socialloginsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'socialloginsupport@xecurify.com',
				'toName'      => 'socialloginsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}
	function submit_contact_us( $email, $phone, $query, $feature_plan, $enable_setup_call, $timezone, $date, $time ) {
		$url          = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/contact-us';
		$current_user = wp_get_current_user();
		$company      = get_option( 'mo_openid_admin_company_name' ) ? get_option( 'mo_openid_admin_company_name' ) : sanitize_text_field( $_SERVER ['SERVER_NAME'] );
		$first_name   = get_option( 'mo_openid_admin_first_name' ) ? get_option( 'mo_openid_admin_first_name' ) : $current_user->user_firstname;
		$last_name    = get_option( 'mo_openid_admin_last_name' ) ? get_option( 'mo_openid_admin_last_name' ) : $current_user->user_lastname;
		$query        = '[WP OpenID Connect Login Free Plugin Version: ' . get_option( 'mo_openid_social_login_version' ) . '] ' . $feature_plan . ':' . $query;
		$meeting      = '';
		if ( $enable_setup_call ) {
			$meeting = ' I Need a meeting at ' . $time . ' ' . $date . ' timezone:' . $timezone;
		}

		$fields       = array(
			'firstName' => $first_name,
			'lastName'  => $last_name,
			'company'   => $company,
			'email'     => $email,
			'ccEmail'   => 'socialloginsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $meeting . ' ' . $query,
		);
		$field_string = json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response     = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function forgot_password( $email ) {

		$url = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/password-reset';
		/* The customer Key provided to you */
		$customerKey = get_option( 'mo_openid_admin_customer_key' );

		/* The customer API Key provided to you */
		$apiKey = get_option( 'mo_openid_admin_api_key' );

		/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
		$currentTimeInMillis = round( microtime( true ) * 1000 );
		$stringToHash        = $customerKey . number_format( $currentTimeInMillis, 0, '', '' ) . $apiKey;
		$hashValue           = hash( 'sha512', $stringToHash );

		$fields = '';

		// *check for otp over sms/email
		$fields = array(
			'email' => $email,
		);

		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function check_customer_ln( $licience_type ) {
		$url                 = get_option( 'mo_openid_host_name' ) . '/moas/rest/customer/license';
		$customerKey         = get_option( 'mo_openid_admin_customer_key' );
		$apiKey              = get_option( 'mo_openid_admin_api_key' );
		$currentTimeInMillis = self::get_timestamp();
		$stringToHash        = $customerKey . $currentTimeInMillis . $apiKey;
		$hashValue           = hash( 'sha512', $stringToHash );
		if ( $licience_type == 'extra_attributes_addon' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'wp_social_login_extra_attributes_addon',
			);
		} elseif ( $licience_type == 'WP_SOCIAL_LOGIN_WOOCOMMERCE_ADDON' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'WP_SOCIAL_LOGIN_WOOCOMMERCE_ADDON',
			);
		} elseif ( $licience_type == 'WP_SOCIAL_LOGIN_MAILCHIMP_ADDON' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'WP_SOCIAL_LOGIN_MAILCHIMP_ADDON',
			);
		} elseif ( $licience_type == 'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON',
			);
		} elseif ( $licience_type == 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON',
			);
		} elseif ( $licience_type == 'WP_SOCIAL_LOGIN_DISCORD_ADDON' ) {
			$fields = array(
				'customerId'      => $customerKey,
				'applicationName' => 'WP_SOCIAL_LOGIN_DISCORD_ADDON',
			);
		}
		$field_string = json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response     = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function mo_openid_vl( $code, $active ) {
		if ( $active ) {
			$url = get_option( 'mo_openid_host_name' ) . '/moas/api/backupcode/check';
		} else {
			$url = get_option( 'mo_openid_host_name' ) . '/moas/api/backupcode/verify';
		}

		/* The customer Key provided to you */
		$customerKey = get_option( 'mo_openid_admin_customer_key' );

		/* The customer API Key provided to you */
		$apiKey = get_option( 'mo_openid_admin_api_key' );

		$currentTimeInMillis = self::get_timestamp();
		$stringToHash        = $customerKey . $currentTimeInMillis . $apiKey;
		$hashValue           = hash( 'sha512', $stringToHash );

		// *check for otp over sms/email

		$fields = array(
			'code'             => $code,
			'customerKey'      => $customerKey,
			'additionalFields' => array(
				'field1' => home_url(),
			),

		);

		$field_string = json_encode( $fields );

		$headers  = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];

	}

	function mo_openid_update_status() {

		$url         = get_option( 'mo_openid_host_name' ) . '/moas/api/backupcode/updatestatus';
		$customerKey = get_option( 'mo_openid_admin_customer_key' );
		$apiKey      = get_option( 'mo_openid_admin_api_key' );

		$currentTimeInMillis = self::get_timestamp();
		$stringToHash        = $customerKey . $currentTimeInMillis . $apiKey;
		$hashValue           = hash( 'sha512', $stringToHash );

		$customerKeyHeader   = 'Customer-Key: ' . $customerKey;
		$timestampHeader     = 'Timestamp: ' . number_format( $currentTimeInMillis, 0, '', '' );
		$authorizationHeader = 'Authorization: ' . $hashValue;
		$key                 = get_option( 'mo_openid_customer_token' );
		$code                = MOAESEncryption::decrypt_data( get_option( 'mo_openid_opn_lk' ), $key );
		$fields              = array(
			'code'             => $code,
			'customerKey'      => $customerKey,
			'additionalFields' => array( 'field1' => home_url() ),
		);
		$field_string        = json_encode( $fields );
		$headers             = array(
			'Content-Type'  => 'application/json',
			'Customer-Key'  => $customerKey,
			'Timestamp'     => $currentTimeInMillis,
			'Authorization' => $hashValue,
		);
		$args                = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);
		$response            = self::mo_openid_wp_remote_post( $url, $args );
		return $response['body'];
	}

	function mo_openid_wp_remote_post( $url, $args = array() ) {
		$response = wp_remote_post( $url, $args );
		if ( ! is_wp_error( $response ) ) {
			return $response;
		} else {
            if (isset($_POST['action'])? $_POST['action']== 'mo_register_new_user':0) // phpcs:ignore
				wp_send_json( array( 'error' => 'Unable to connect to the Internet. Please try again.' ) );
			else {
				update_option( 'mo_openid_message', 'Unable to connect to the Internet. Please try again.' );
				mo_openid_success_message();
			}
		}
	}

}
