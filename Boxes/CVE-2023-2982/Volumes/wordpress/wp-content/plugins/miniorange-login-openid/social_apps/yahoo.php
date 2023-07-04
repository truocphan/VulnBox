<?php


class mo_yahoo {

	public $color     = '#400092';
	public $scope     = 'read';
	public $video_url = 'https://www.youtube.com/embed/LVPqnIicAZQ';
	public $instructions;
	public function __construct() {
		if ( get_option( 'permalink_structure' ) !== '' ) {
			$this->site_url     = get_option( 'siteurl' );
			$this->instructions = "Go to <a href=\"https://developer.yahoo.com\" target=\"_blank\">https://developer.yahoo.com</a> Sign in with your yahoo account and select Apps from the menubar.##On the page, Click on the <strong>Create an App</strong> button.##Enter <strong>Application Name</strong> and select <strong>Application Type</strong> as <strong>Web Application</strong>.##Enter <b><code id='13'>" . get_option( 'siteurl' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#13', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> in the <strong>Home Page URL</strong>.##Enter <b><code id='14'>" . mo_get_permalink( 'yahoo' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#14', '#shortcode_url1_copy')\"><span id=\"shortcode_url1_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b></b> in <b>Callback Domain</b>##Select all <b>API Permissions</b>.##Click on <strong>Create App</strong>.##Copy the <b>Client ID</b> and <b>Client Secret</b> from this page and Paste them into the fields above.##Keep <b>Scope</b> blank. ##Click on the <b>Save settings</b> button.</li><li>Go to Social Login tab to configure the display as well as other login settings";
		} else {
			$this->instructions = "<strong style='color: red;font-weight: bold'><br>You have selected plain permalink and Yahoo does not support it.</strong><br><br> Please change the permalink to continue further.Follow the steps given below:<br>1. Go to settings from the left panel and select the permalinks option.<br>2. Plain permalink is selected ,so please select any other permalink and click on save button.<br> <strong class='mo_openid_note_style' style='color: red;font-weight: bold'> When you will change the permalink ,then you have to re-configure all the custom apps because that will change the redirect URL.</strong>";
		}
	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'yahoo' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'yahoo';
		$client_id           = $appslist['yahoo']['clientid'];
		$login_dialog_url    = 'https://api.login.yahoo.com/oauth2/request_auth?client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&response_type=code&language=en-us';
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                     = mo_openid_validate_code();
		$social_app_redirect_uri  = get_social_app_redirect_uri( 'yahoo' );
		$appslist                 = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id                = $appslist['yahoo']['clientid'];
		$client_secret            = $appslist['yahoo']['clientsecret'];
		$access_token_uri         = 'https://api.login.yahoo.com/oauth2/get_token';
		$postData                 = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=' . $social_app_redirect_uri . '&code=' . $code . '&grant_type=authorization_code';
		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'yahoo' );
		$access_token             = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();
		$profile_url         = 'https://api.login.yahoo.com/openid/v1/userinfo';
		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'yahoo' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		if ( isset( $profile_json_output['name'] ) ) {
			$user_name    = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
			$first_name   = isset( $profile_json_output['given_name'] ) ? $profile_json_output['given_name'] : '';
			$last_name    = isset( $profile_json_output['family_name'] ) ? $profile_json_output['family_name'] : '';
			$email        = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
			$user_picture = isset( $profile_json_output['picture'] ) ? $profile_json_output['picture'] : '';
			$gender       = isset( $profile_json_output['gender'] ) ? $profile_json_output['gender'] : '';
		}
		$email          = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
		$social_user_id = isset( $profile_json_output['sub'] ) ? $profile_json_output['sub'] : '';

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
