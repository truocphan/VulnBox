<?php


class mo_twitter {

	public $color     = '#00ACED';
	public $scope     = '';
	public $video_url = 'https://www.youtube.com/embed/qJmjBQyUBKU';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to <a href=\"https://developer.twitter.com/en/apps\" target=\"_blank\">https://developer.twitter.com/en/apps</a> and sign in with your twitter account.##Twitter might ask you to add your phone number to your profile while creating the app.##Click on <b>Create New App</b>. Enter Name.##Click on <b>Skip to dashboard</b> and <b>settings icon</b> under App.Click on <b>Set up</b> button in <b>User authentication settings</b>##<b>Callback URL</b> <br><u>Example of public website, </u><br>if your website URL is =><code id='7'>" . get_option( 'siteurl' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"mofa mofa-fw mofa-lg mofa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#7', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i><br> then your callback URL should be => <code id='8'>" . get_option( 'siteurl' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"mofa mofa-fw mofa-lg mofa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#8', '#shortcode_url1_copy')\"><span id=\"shortcode_url1_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i><br><u>Example of localhost,</u><br> Twitter may not accept local IPs so try using 127.0.0.1 instead of localhost.<br> Also make sure your Callback URL is prefixed with the website.  <br> Go to Settings-> General and replace \"localhost\" with \"127.0.0.1\" in <b>WordPress Address (URL)</b> and <b>Site Address (URL)</b> <br>if your website URL is => http://127.0.0.1/wordpress <br> then your callback URL should be => http://127.0.0.1/wordpress/openidcallback ##Check the developer agreement checkbox and click on <b>Create your Twitter Application</b>. Under <b>Keys and Access Token</b> Tab, you will find your <b>API Key/Secret</b>. Paste them into the fields above.##Leave the scope field blank.##<u>Instructions to request email address of a user</u>: The “Request email addresses from users” checkbox is available under the app permissions on apps.twitter.com. Privacy Policy URL and Terms of Service URL fields must be completed in the app settings in order for email address access to function. If enabled, users will be informed via the oauth/authorize dialog that your app can access their email address.If the user does not have an email address on their account, or if the email address is not verified, email will not be returned.##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings.";
	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'twitter' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'twitter';
		$client_id           = $appslist['twitter']['clientid'];
		$scope               = $appslist['twitter']['scope'];
		$login_dialog_url    = 'https://twitter.com/i/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&scope=' . $scope . '&state=state&code_challenge=challenge&code_challenge_method=plain';
		header( 'Location:' . $login_dialog_url );
		exit;

	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'twitter' );
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id               = $appslist['twitter']['clientid'];
		$client_secret           = $appslist['twitter']['clientsecret'];
		$access_token_uri        = 'https://api.twitter.com/2/oauth2/token';
		$postData                = 'grant_type=authorization_code&client_id=' . $client_id . '&code=' . $code . '&redirect_uri=' . $social_app_redirect_uri . '&code_verifier=challenge&code_challenge_method=plain';

		$token = base64_encode( $client_id . ':' . $client_secret );
		$ch    = curl_init();

		$postRequest = array(
			'client_id'             => $client_id,
			'client_secret'         => $client_secret,
			'grant_type'            => 'authorization_code',
			'redirect_uri'          => $social_app_redirect_uri,
			'code'                  => $code,
			'code_verifier'         => 'challenge',
			'code_challenge_method' => 'plain',
		);

		curl_setopt( $ch, CURLOPT_URL, 'https://api.twitter.com/2/oauth2/token' );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $postRequest ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );

		$headers   = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$headers[] = 'Authorization: Basic ' . $token;
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$access_token_json_output = json_decode( curl_exec( $ch ), true );
		if ( curl_errno( $ch ) ) {
			echo 'Error:' . esc_attr( curl_error( $ch ) );
		}
		curl_close( $ch );

		$access_token = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';

		mo_openid_start_session();

		$profile_url = 'https://api.twitter.com/2/users/me';

		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'twitter' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		if ( isset( $profile_json_output['data']['name'] ) ) {
			$user_name  = isset( $profile_json_output['data']['name'] ) ? $profile_json_output['data']['name'] : '';
			$full_name  = explode( ' ', $user_name );
			$first_name = isset( $full_name[0] ) ? $full_name[0] : '';
			$last_name  = isset( $full_name[1] ) ? $full_name[1] : '';
		}
		$email          = isset( $profile_json_output['data']['email'] ) ? $profile_json_output['data']['email'] : '';
		$social_user_id = isset( $profile_json_output['data']['id'] ) ? $profile_json_output['data']['id'] : '';

		$appuserdetails = array(
			'first_name'       => $first_name,
			'last_name'        => $last_name,
			'email'            => $email,
			'user_name'        => $user_name,
			'user_url'         => $user_url,
			'user_picture'     => $user_picture,
			'social_user_id'   => $social_user_id,
			'location_city'    => $location_city,
			'location_country' => $location_country,
			'about_me'         => $about_me,
			'company_name'     => $company_name,
			'friend_nos'       => $friend_nos,
			'gender'           => $gender,
			'age'              => $age,
		);

		return $appuserdetails;
	}
}
