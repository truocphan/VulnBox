<?php


class mo_discord {

	public $color     = '#7289DA';
	public $scope     = 'identify+email';
	public $video_url = 'https://www.youtube.com/embed/zryQ0xE5sKA';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to <a href=\"https://discordapp.com/developers\" target=\"_blank\">https://discordapp.com/developers/applications</a> and sign in with your discordapp developer account.##On the page, Click on the <strong>New Application</strong> button and enter a <strong>Name</strong> for your app. Click on Save.##Click on <strong>OAuth2</strong> form left section.</li><li>Click on <b>Add redirect</b> and Enter <b><code id='4'>" . mo_get_permalink( 'discord' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#4', '#shortcode_url4_copy')\"><span id=\"shortcode_url4_copy\" class=\"mo_copytooltiptext mo_copy\">Copy to Clipboard</span></i></b> in that ##Copy the Client Id and Client Secret from the <b>General information</b> and Paste them into the fields above.##Enter <b>identify</b> as Scope.##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings.";
	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'discord' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'discord';
		$client_id           = $appslist['discord']['clientid'];
		$scope               = $appslist['discord']['scope'];
		$login_dialog_url    = 'https://discordapp.com/api/oauth2/authorize?response_type=code&client_id=' . $client_id . '&scope=' . $scope . '&redirect_uri=' . $social_app_redirect_uri;
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {

		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'discord' );

		$appslist         = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id        = $appslist['discord']['clientid'];
		$client_secret    = $appslist['discord']['clientsecret'];
		$access_token_uri = 'https://discordapp.com/api/oauth2/token';
		$postData         = 'client_id=' . $client_id . '&grant_type=authorization_code&code=' . $code . '&redirect_uri=' . $social_app_redirect_uri . '&scope=identify&client_secret=' . $client_secret;

		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'discord' );

		$access_token = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();

		$profile_url = 'https://discordapp.com/api/v6/users/@me?access_token=' . $access_token;

		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'discord' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		if ( isset( $profile_json_output['username'] ) ) {
			$user_name  = isset( $profile_json_output['username'] ) ? $profile_json_output['username'] : '';
			$full_name  = explode( ' ', $user_name );
			$first_name = isset( $full_name[0] ) ? $full_name[0] : '';
			$last_name  = isset( $full_name[1] ) ? $full_name[1] : '';
		}
		if ( isset( $profile_json_output['email'] ) ) {
			$email = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
		}
		if ( isset( $profile_json_output['name'] ) ) {
			$user_name = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
		}
		if ( isset( $profile_json_output['url'] ) ) {
			$user_url = isset( $profile_json_output['url'] ) ? $profile_json_output['url'] : '';
		}
		if ( isset( $profile_json_output['avatar'] ) ) {
			$user_picture = isset( $profile_json_output['avatar'] ) ? 'https://cdn.discordapp.com/avatars/' . $profile_json_output['id'] . '/' . $profile_json_output['avatar'] : '';
		}
		if ( isset( $profile_json_output['id'] ) ) {
			$social_user_id = isset( $profile_json_output['id'] ) ? $profile_json_output['id'] : '';
		}

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
