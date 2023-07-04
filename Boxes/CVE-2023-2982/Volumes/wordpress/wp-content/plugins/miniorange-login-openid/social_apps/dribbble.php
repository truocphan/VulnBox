<?php


class mo_dribbble {

	public $color     = '#E84C88';
	public $scope     = 'public';
	public $video_url = 'https://www.youtube.com/embed/9M95pxJ8Emo';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to Dribbble developers dashboard <a href=\"https://dribbble.com/account/applications/\" target=\"_blank\">https://dribbble.com/account/applications/</a>. ##Go to applications, click on register a new application.## Enter <b>Application details. </b> ##Click on Register Application</b>. Enter <b><code id='7'>" . get_option( 'siteurl' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#7', '#shortcode_url7_copy')\"><span id=\"shortcode_url7_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> into <b>Website</b> ##Under Client Redirect URI section, Enter <b><code id='8'>" . mo_get_permalink( 'dribbble' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#8', '#shortcode_url8_copy')\"><span id=\"shortcode_url8_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> and click on <b>Add</b> button. ##Paste your <b>Client ID</b> and <b>CLient Secret</b> provided by dribbble into the fields above.##Click on the <b>Save settings</b> button.##Go to Social Login tab to configure the display as well as other login settings.";
	}
	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'dribbble' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'dribbble';
		$client_id           = $appslist['dribbble']['clientid'];
		$scope               = $appslist['dribbble']['scope'];
		$login_dialog_url    = 'https://dribbble.com/oauth/authorize?client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&scope=' . $scope . '&state=' . $state;
		header( 'Location:' . $login_dialog_url );
		exit;
	}
	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'dribbble' );

		$appslist         = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id        = $appslist['dribbble']['clientid'];
		$client_secret    = $appslist['dribbble']['clientsecret'];
		$access_token_uri = 'https://dribbble.com/oauth/token';
		$postData         = 'client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type="authorization_code" ';

		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'dribbble' );

		$access_token = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();

		$profile_url = 'https://api.dribbble.com/v2/user?access_token=' . $access_token_json_output['access_token'];

		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'dribbble' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name  = $email = $user_name  = $user_url  = $user_picture  = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		$first_name     = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
		$email          = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
		$user_name      = isset( $profile_json_output['login'] ) ? $profile_json_output['login'] : '';
		$user_url       = isset( $profile_json_output['link'] ) ? $profile_json_output['link'] : '';
		$user_picture   = isset( $profile_json_output['avatar_url'] ) ? $profile_json_output['avatar_url'] : '';
		$social_user_id = isset( $profile_json_output['id'] ) ? $profile_json_output['id'] : '';

		$appuserdetails = array(
			'first_name'     => $first_name,
			'email'          => $email,
			'user_name'      => $user_name,
			'user_url'       => $user_url,
			'user_picture'   => $user_picture,
			'social_user_id' => $social_user_id,

		);
		return $appuserdetails;
	}
}
