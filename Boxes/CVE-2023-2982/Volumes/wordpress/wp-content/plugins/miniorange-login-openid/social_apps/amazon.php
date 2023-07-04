<?php


class mo_amazon {

	public $color     = '#343331';
	public $scope     = 'profile';
	public $video_url = 'https://www.youtube.com/embed/yMjufls41dg';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to <a href=\"http://login.amazon.com\" target=\"_blank\">http://login.amazon.com</a> and sign in with your amazon developer account.##On the developer homepage, in the upper right corner, click <strong>Developer Console</strong>.##On the Developer Console homepage, click <strong>Login with Amazon</strong>, Click on the <strong>Create a New Security Profile</strong> button and enter a <strong>Name, Description, and Privacy Notice URL</strong> for your app. Click on Save.##click the configuration icon in the <strong>Manage</strong> column and then click <strong>Web Settings</strong>.##Enter <b><code id='12'>" . mo_get_permalink( 'amazon' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#12', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> in the <strong>Allowed Return URLs</strong>##  Copy the Client Id and Client Secret from the Web Settings and Paste them into the fields above. ##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings";
	}


	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'amazon' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'amazon';
		$client_id           = $appslist['amazon']['clientid'];
		$scope               = $appslist['amazon']['scope'];
		$login_dialog_url    = 'https://www.amazon.com/ap/oa?client_id=' . $client_id . '&scope=' . $scope . '&redirect_uri=' . $social_app_redirect_uri . '&response_type=code';
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                     = mo_openid_validate_code();
		$social_app_redirect_uri  = get_social_app_redirect_uri( 'amazon' );
		$appslist                 = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id                = $appslist['amazon']['clientid'];
		$client_secret            = $appslist['amazon']['clientsecret'];
		$access_token_uri         = 'https://api.amazon.com/auth/o2/token';
		$postData                 = 'grant_type=authorization_code&client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $code . '&redirect_uri=' . $social_app_redirect_uri;
		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'amazon' );
		$access_token             = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();
		$profile_url         = 'https://api.amazon.com/user/profile?access_token=' . $access_token;
		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'amazon' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		if ( isset( $profile_json_output['name'] ) ) {
			$user_name  = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
			$full_name  = explode( ' ', $user_name );
			$first_name = isset( $full_name[0] ) ? $full_name[0] : '';
			$last_name  = isset( $full_name[1] ) ? $full_name[1] : '';
		}
		$email          = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
		$social_user_id = isset( $profile_json_output['user_id'] ) ? $profile_json_output['user_id'] : '';

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
