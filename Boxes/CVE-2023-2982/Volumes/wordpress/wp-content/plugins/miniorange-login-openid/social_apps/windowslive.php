<?php


class mo_windowslive {

	public $color     = '#00A1F1';
	public $scope     = 'wl.emails,wl.basic,wl.photos,wl.contacts_photos';
	public $video_url = 'https://www.youtube.com/embed/hEzghbFJzm8';
	public $instructions;

	public function __construct() {
		if ( get_option( 'permalink_structure' ) !== '' ) {
			$this->site_url     = get_option( 'siteurl' );
			$this->instructions = "Go to <a href=\"https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps/ApplicationsListBlade/\" target=\"_blank\">https://portal.azure.com/#blade/Microsoft_AAD_RegisteredApps/ApplicationsListBlade</a> and sign in with your Microsoft azure account.##Click on <b>New Registration tab</b>.##Name your new app and select <b>Accounts in any organizational directory (Any Azure AD directory - Multitenant) and personal Microsoft accounts (e.g. Skype, Xbox)
</b> as supported accounts types . ##Enter <b><code id='12'>" . mo_get_permalink( 'windowslive' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#12', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> as Redirect URL.<strong> Click on <b>Register</b>.##Copy the Client ID from the Overview tab.</strong>. This is your <b>Client ID </b>.##Go to Certificates and Security tab.##Click on <b>New Client Sceret</b>. Copy your password. This is your <b>Client Secret</b>.##Click on the Save button.##Copy the client ID and client secret to your clipboard, as you will need them to configure above. ##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings.";
		} else {
			$this->instructions = "<strong style='color: red;font-weight: bold'><br>You have selected plain permalink and Windows Live doesnot support it.</strong><br><br> Please change the permalink to continue further.Follow the steps given below:<br>1. Go to settings from the left panel and select the permalinks option.<br>2. Plain permalink is selected ,so please select any other permalink and click on save button.<br> <strong class='mo_openid_note_style' style='color: red;font-weight: bold'> When you will change the permalink ,then you have to re-configure the already set up custom apps because that will change the redirect URL.</strong>";
		}

	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'windowslive' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'windowslive';
		$client_id           = $appslist['windowslive']['clientid'];
		$scope               = $appslist['windowslive']['scope'];
		$login_dialog_url    = 'https://login.live.com/oauth20_authorize.srf?client_id=' . $client_id . '&scope=' . $scope . '&response_type=code&redirect_uri=' . $social_app_redirect_uri;

		header( 'Location:' . $login_dialog_url );

		exit;
	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'windowslive' );

		$appslist                 = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id                = $appslist['windowslive']['clientid'];
		$client_secret            = $appslist['windowslive']['clientsecret'];
		$access_token_uri         = 'https://login.live.com/oauth20_token.srf';
		$postData                 = 'grant_type=authorization_code&client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&code=' . $code . '&client_secret=' . $client_secret;
		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'windowslive' );
		$access_token             = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();
		$profile_url         = 'https://apis.live.net/v5.0/me?access_token=' . $access_token;
		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'windowslive' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		$first_name = isset( $profile_json_output['first_name'] ) ? $profile_json_output['first_name'] : '';
		$last_name  = isset( $profile_json_output['last_name'] ) ? $profile_json_output['last_name'] : '';
		$user_name  = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
		if ( isset( $profile_json_output['emails']['preferred'] ) ) {
			$email = isset( $profile_json_output['emails']['preferred'] ) ? $profile_json_output['emails']['preferred'] : '';
		} elseif ( isset( $profile_json_output['emails']['account'] ) ) {
			$email = isset( $profile_json_output['emails']['account'] ) ? $profile_json_output['emails']['account'] : '';
		} elseif ( isset( $profile_json_output['emails']['personal'] ) ) {
			$email = isset( $profile_json_output['emails']['personal'] ) ? $profile_json_output['emails']['personal'] : '';
		} elseif ( isset( $profile_json_output['emails']['business'] ) ) {
			$email = isset( $profile_json_output['emails']['business'] ) ? $profile_json_output['emails']['business'] : '';
		}
		$user_url         = isset( $profile_json_output['url'] ) ? $profile_json_output['url'] : '';
		$user_picture     = isset( $profile_json_output['profile_image_url'] ) ? $profile_json_output['profile_image_url'] : '';
		$social_user_id   = isset( $profile_json_output['id'] ) ? $profile_json_output['id'] : '';
		$gender           = isset( $profile_json_output['gender'] ) ? $profile_json_output['gender'] : '';
		$location_country = isset( $profile_json_output['locale'] ) ? $profile_json_output['locale'] : '';

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
