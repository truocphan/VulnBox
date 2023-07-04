<?php


class mo_snapchat {

	public $color = '#FFFC00';
	public $scope = 'snapchat-marketing-api';
	// public $video_url="https://www.youtube.com/embed/yMjufls41dg";
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to <a href=\"https://business.snapchat.com/\" target=\"_blank\">https://business.snapchat.com/</a> and sign in with your snapchat developer account. ##On the page, Click on the Business Details on left panel. ## below their is option of OAuth Apps. Click on <b>OAuth App</b> button ##On Create OAuth App page. Enter your <b>App Name</b>. ##Enter <b><code id='78'>" . mo_get_permalink( 'snapchat' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#78', '#shortcode_url12_copy')\"><span id=\"shortcode_url12_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> in the <strong>Snap Redirect URI</strong>##  Copy the Snap Client ID as Client Id and Snapchat Client Secret as Client Secret from the Create OAuth App settings and Paste them into the fields above. ##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings";
	}


	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'snapchat' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'snapchat';
		$client_id           = $appslist['snapchat']['clientid'];
		$scope               = $appslist['snapchat']['scope'];
		$login_dialog_url    = 'https://accounts.snapchat.com/login/oauth2/authorize?client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&response_type=code&scope=' . $scope . '&state=kdfjsn';
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'snapchat' );

		$appslist         = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id        = $appslist['snapchat']['clientid'];
		$client_secret    = $appslist['snapchat']['clientsecret'];
		$access_token_uri = 'https://accounts.snapchat.com/login/oauth2/access_token';
		$postData         = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code&redirect_uri=' . $social_app_redirect_uri;

		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'snapchat' );

		$access_token = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();

		$profile_url = 'https://adsapi.snapchat.com/v1/me';

		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'snapchat' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		if ( isset( $profile_json_output['me']['display_name'] ) ) {
			$user_name  = isset( $profile_json_output['me']['display_name'] ) ? $profile_json_output['me']['display_name'] : '';
			$full_name  = explode( ' ', $user_name );
			$first_name = isset( $full_name[0] ) ? $full_name[0] : '';
			$last_name  = isset( $full_name[1] ) ? $full_name[1] : '';
		}
		$email          = isset( $profile_json_output['me']['email'] ) ? $profile_json_output['me']['email'] : '';
		$social_user_id = isset( $profile_json_output['me']['id'] ) ? $profile_json_output['me']['id'] : '';

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
