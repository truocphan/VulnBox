<?php


class mo_google {

	public $color     = '#db4437';
	public $scope     = 'email+profile';
	public $video_url = 'https://www.youtube.com/embed/q7nK1lp7yqc';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Visit the Google website for developers <a href=\"https://console.developers.google.com/project/\" target=\"_blank\">console.developers.google.com</a>.
                                ##After sign-in, click on <b>Create project</b>, enter a name for the project, and optionally, edit the provided Project ID. Click <b>Create</b>.
                                ##Click on Left side Menu and go to <b>APIs & Services</b> -> <b>OAuth consent screen</b>.
                                ##Click on <b>Select a Project</b> and select the newly created Project by clicking on the project name that you entered in the previous step. 
                                ##Select <b>External</b> as user type and click on <b>Create</b>.
                                ##You are prompted to Edit App Registration, Enter all the Required details (AppName & User support Email) Add Authorized domain as <b><code id='11'>" . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#11', '#shortcode_url_copy1')\"><span id=\"shortcode_url_copy1\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b>. 
                                ##On the Scopes screen click on <b>Add or Remove Scopes</b>. Check  and <b>.../auth/userinfo.email</b> and <b>.../auth/userinfo.profile</b>. Click on <b>Update</b>. Scroll down, Click on <b>Save and Continue</b>.
                                ##From the Left side Menu, Click on <b>Credentials</b> then click on <b>Create Credential</b> from dropdown select <b>Oauth client ID</b>. 
                                ##From <b>Application Type</b> drop down, Select <b>Web Application</b>. 
                                ##Enter <b>client name</b> and Add <b><code id='12'>" . mo_get_permalink( 'google' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#12', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> in <b>Authorized Redirect URL<b/>, Click on <b>Create</b>. 
                                ##Copy <b>Client ID and Client Secret</b> and paste it on the above field <b>App ID and App Secret</b>, Click on <b>Save & Test Configuration</b>. 
                                ##Click on Left side Menu and go to <b>APIs & Services</b> -> <b>OAuth consent screen</b>. Under Publishing Status Click on <b>Publish App</b> and <b>Confirm</b>. 
                                ##Go to Customise Social Login Icons tab and configure the icons.";

	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'google' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'google';
		$client_id           = $appslist['google']['clientid'];
		$scope               = $appslist['google']['scope'];
		$login_dialog_url    = 'https://accounts.google.com/o/oauth2/auth?redirect_uri=' . $social_app_redirect_uri . '&response_type=code&client_id=' . $client_id . '&scope=' . $scope . '&access_type=offline';
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'google' );

		$appslist                 = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id                = $appslist['google']['clientid'];
		$client_secret            = $appslist['google']['clientsecret'];
		$access_token_uri         = 'https://accounts.google.com/o/oauth2/token';
		$postData                 = 'code=' . $code . '&client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=' . $social_app_redirect_uri . '&grant_type=authorization_code';
		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'google' );
		$access_token             = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();
		$profile_url         = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token;
		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'google' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		$first_name     = isset( $profile_json_output['given_name'] ) ? $profile_json_output['given_name'] : '';
		$user_name      = isset( $profile_json_output['name'] ) ? $profile_json_output['name'] : '';
		$last_name      = isset( $profile_json_output['family_name'] ) ? $profile_json_output['family_name'] : '';
		$email          = isset( $profile_json_output['email'] ) ? $profile_json_output['email'] : '';
		$user_url       = isset( $profile_json_output['link'] ) ? $profile_json_output['link'] : '';
		$user_picture   = isset( $profile_json_output['picture'] ) ? $profile_json_output['picture'] : '';
		$social_user_id = isset( $profile_json_output['id'] ) ? $profile_json_output['id'] : '';

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
