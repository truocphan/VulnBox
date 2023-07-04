<?php


class mo_linkedin {

	public $color     = '#007AB9';
	public $scope     = 'r_liteprofile r_emailaddress w_member_social';
	public $video_url = 'https://www.youtube.com/embed/Qs-PSyy7KVQ';
	public $instructions;
	public function __construct() {
		$this->site_url     = get_option( 'siteurl' );
		$this->instructions = "Go to <a href=\"http://developer.linkedin.com/\" target=\"_blank\">http://developer.linkedin.com/</a> and click on <strong>Create Apps</strong> and sign in with your linkedin account.##Enter the Application Name, Linkedin page URl or name, Privacy Policy URL, And upload app logo.##If you don't have a linked in page click on <a href=\"https://www.linkedin.com/company/setup/new/\" target=\"_blank\">https://www.linkedin.com/company/setup/new/</a> to create a new page.##Check the <b>API Terms of Use</b> and click on create app.##Click on <b>Auth</b> tab and enter <b><code id='11'>" . mo_get_permalink( 'linkedin' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#11', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> as <strong>Redirect URLs </strong>and click on <strong>Update</strong>##On the same page you will be able to see your <strong>Client ID</strong> and <strong>Client Secret</strong> under the <strong>Application credentials</strong> section. Copy these and Paste them into the fields above. ##Go to the <b>Product tab</b>.##For <b>r_liteprofile</b> and <b>r_emailaddress</b> scope permission, Find <b>Sign In with LinkedIn</b> and click on <b>Select</b>. Check the legal agreement check box and Click on <b>Add Product</b>.## For <b>w_member_social </b> scope permission Find <b>Share on LinkedIn</b> and click on <b>Select</b>.Check the legal agreement check box and Click on <b>Add Product</b>, This permission required for social sharing.##Wait till Linkedin approves your permission. ##Click on the Save settings button.##Go to Social Login tab to configure the display as well as other login settings";
	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'linkedin' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'linkedin';
		$client_id           = $appslist['linkedin']['clientid'];
		$scope               = $appslist['linkedin']['scope'];
		$login_dialog_url    = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $social_app_redirect_uri . '&state=fooobar&scope=' . $scope;
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'linkedin' );

		$appslist         = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id        = $appslist['linkedin']['clientid'];
		$client_secret    = $appslist['linkedin']['clientsecret'];
		$access_token_uri = 'https://www.linkedin.com/oauth/v2/accessToken';
		$postData         = 'grant_type=authorization_code&code=' . $code . '&redirect_uri=' . $social_app_redirect_uri . '&client_id=' . $client_id . '&client_secret=' . $client_secret;

		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'linkedin' );

		$access_token = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		mo_openid_start_session();

		$profile_url_email = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))';
		$profile_url       = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,emailAddress,profilePicture(displayImage~:playableStreams))';

		$headers = ( 'Authorization:Bearer ' . $access_token );
		$args    = array(
			'timeout'     => 120,
			'redirection' => 5,
			'httpversion' => '1.1',
			'headers'     => $headers,
		);

		$result = wp_remote_get( $profile_url_email, $args );
		if ( is_wp_error( $result ) ) {
			update_option( 'mo_openid_test_configuration', 0 );
			echo esc_attr( $result['body'] );
			exit();
		}
		$profile_json_output_email = json_decode( $result['body'], true );

		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'linkedin' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$name          = $first_name = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		$email          = isset( $profile_json_output_email['elements']['0']['handle~']['emailAddress'] ) ? $profile_json_output_email['elements']['0']['handle~']['emailAddress'] : '';
		$first_name     = isset( $profile_json_output['firstName']['localized']['en_US'] ) ? $profile_json_output['firstName']['localized']['en_US'] : '';
		$name           = isset( $profile_json_output['firstName']['localized']['en_US'] ) ? $profile_json_output['firstName']['localized']['en_US'] : '';
		$last_name      = isset( $profile_json_output['lastName']['localized']['en_US'] ) ? $profile_json_output['lastName']['localized']['en_US'] : '';
		$user_picture   = isset( $profile_json_output['profilePicture']['displayImage~']['elements']['3']['identifiers']['0']['identifier'] ) ? $profile_json_output['profilePicture']['displayImage~']['elements']['0']['identifiers']['0']['identifier'] : '';
		$social_user_id = isset( $profile_json_output['id'] ) ? $profile_json_output['id'] : '';

		$appuserdetails = array(
			'first_name'       => $first_name,
			'last_name'        => $last_name,
			'email'            => $email,
			'user_name'        => $name,
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
