<?php


class mo_vkontakte {

	public $color     = '#4C75A3';
	public $scope     = 'friends,photos,email';
	public $video_url = 'https://www.youtube.com/embed/DmF-jflxQ9o';
	public $instructions;


	public function __construct() {
		if ( get_option( 'permalink_structure' ) !== '' ) {
			$this->site_url     = get_option( 'siteurl' );
			$this->instructions = "Go to <a href=\"https://new.vk.com/dev\" target=\"_blank\">https://new.vk.com/dev</a> and sign in with your vkontakte account.##Go to <strong>My Apps</strong> and click on <strong>Create an Application</strong>.##Provide a name for your app in Title field..##Select <strong>Website</strong> as the <strong>Category</strong>. Select <b><code id='4'>" . get_option( 'siteurl' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#4', '#shortcode_url_copy')\"><span id=\"shortcode_url_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> as <strong>Webite address</strong>##Enter the <b><code id='5'>" . str_replace( 'https://', '', get_option( 'siteurl' ) ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#5', '#shortcode_url1_copy')\"><span id=\"shortcode_url1_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> as Base domain.##Click on <strong>Connect Webite</strong> to create the app.##You will be required to confirm your request with a code received via Call or SMS.##Once the application is created, select <strong>Settings</strong> in the left nav.##Enter the <b><code id='6'>" . get_social_app_redirect_uri( 'vkontakte' ) . "</code><i style= \"width: 11px;height: 9px;padding-left:2px;padding-top:3px\" class=\"far fa-fw fa-lg fa-copy mo_copy mo_copytooltip\" onclick=\"copyToClipboard(this, '#6', '#shortcode_url2_copy')\"><span id=\"shortcode_url2_copy\" class=\"mo_copytooltiptext\">Copy to Clipboard</span></i></b> as <b>Authorized redirect URI.</b>##Click on Save.##From the top of the same page, copy the <b>Application ID</b> (This is your <b>Client ID </b>) and <b>Secure Key</b> (This is your <b>Client Secret</b>). Paste them into the fields above.##Click on the Save and Test Configuration button.##Go to Social Login tab to configure the display as well as other login settings.";
		} else {
			$this->instructions = "<strong style='color: red;font-weight: bold'><br>You have selected plain permalink and vkontakte does not support it.</strong><br><br> Please change the permalink to continue further.Follow the steps given below:<br>1. Go to settings from the left panel and select the permalinks option.<br>2. Plain permalink is selected ,so please select any other permalink and click on save button.<br> <strong class='mo_openid_note_style' style='color: red;font-weight: bold'> When you will change the permalink ,then you have to re-configure all the custom apps because that will change the redirect URL.</strong>";
		}
	}

	function mo_openid_get_app_code() {
		$appslist                = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$social_app_redirect_uri = get_social_app_redirect_uri( 'vkontakte' );
		mo_openid_start_session();
		$_SESSION['appname'] = 'vkontakte';
		$client_id           = $appslist['vkontakte']['clientid'];
		$scope               = $appslist['vkontakte']['scope'];
		$login_dialog_url    = 'https://oauth.vk.com/authorize?client_id=' . $client_id . '&scope=' . $scope . '&response_type=code&redirect_uri=' . $social_app_redirect_uri . '&v=5.131';
		header( 'Location:' . $login_dialog_url );
		exit;
	}

	function mo_openid_get_access_token() {
		$code                    = mo_openid_validate_code();
		$social_app_redirect_uri = get_social_app_redirect_uri( 'vkontakte' );

		$appslist                 = maybe_unserialize( get_option( 'mo_openid_apps_list' ) );
		$client_id                = $appslist['vkontakte']['clientid'];
		$client_secret            = $appslist['vkontakte']['clientsecret'];
		$access_token_uri         = 'https://oauth.vk.com/access_token';
		$postData                 = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $code . '&redirect_uri=' . $social_app_redirect_uri . '&v=5.131';
		$access_token_json_output = mo_openid_get_access_token( $postData, $access_token_uri, 'vkontakte' );
		$access_token             = isset( $access_token_json_output['access_token'] ) ? $access_token_json_output['access_token'] : '';
		$userid                   = isset( $access_token_json_output['user_id'] ) ? $access_token_json_output['user_id'] : '';
		mo_openid_start_session();
		$profile_url         = 'https://api.vk.com/method/users.get?uids=' . $userid . '&fields=uid,hash,occupation,photos,first_name,last_name,nickname,domain,site,education,relation,timezone,screen_name,sex,bdate,city,country,timezone,photo,lists,contacts,universities,schools,status,about&access_token=' . $access_token . '&v=5.131';
		$profile_json_output = mo_openid_get_social_app_data( $access_token, $profile_url, 'vkontakte' );

		// Test Configuration
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			mo_openid_app_test_config( $profile_json_output );
		}
		// set all profile details
		// Set User current app
		$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = '';
		$location_city = $location_country = $about_me = $company_name = $age = $gender = $friend_nos = '';

		$first_name     = isset( $profile_json_output['response']['0']['first_name'] ) ? $profile_json_output['response']['0']['first_name'] : '';
		$last_name      = isset( $profile_json_output['response']['0']['last_name'] ) ? $profile_json_output['response']['0']['last_name'] : '';
		$user_url       = isset( $profile_json_output['response']['0']['url'] ) ? $profile_json_output['response']['0']['url'] : '';
		$email          = isset( $access_token_json_output['email'] ) ? $access_token_json_output['email'] : '';
		$user_picture   = isset( $profile_json_output['response']['0']['photo'] ) ? $profile_json_output['response']['0']['photo'] : '';
		$social_user_id = isset( $userid ) ? $userid : '';

		if ( isset( $first_name ) && isset( $last_name ) ) {
			if ( strcmp( $first_name, $last_name ) != 0 ) {
				$user_name = $first_name . '_' . $last_name;
			} else {
				$user_name = $first_name;
			}
		} else {
			$user_name = $first_name;
		}
		if ( isset( $profile_json_output['response']['0']['sex'] ) ) {
			if ( $profile_json_output['response']['0']['sex'] == '2' ) {
				$gender = 'male';
			} else {
				$gender = 'female';
			}
		} else {
			$gender = '';
		}
		$birth_date       = isset( $profile_json_output['response']['0']['bdate'] ) ? $profile_json_output['response']['0']['bdate'] : '';
		$location_city    = isset( $profile_json_output['response']['0']['city']['title'] ) ? $profile_json_output['response']['0']['city']['title'] : '';
		$location_country = isset( $profile_json_output['response']['0']['country']['title'] ) ? $profile_json_output['response']['0']['country']['title'] : '';
		$contact_no       = isset( $profile_json_output['response']['0']['home_phone'] ) ? $profile_json_output['response']['0']['home_phone'] : '';
		$website          = isset( $profile_json_output['response']['0']['site'] ) ? $profile_json_output['response']['0']['site'] : '';
		$about_me         = isset( $profile_json_output['response']['0']['about'] ) ? $profile_json_output['response']['0']['about'] : '';
		$company_name     = isset( $profile_json_output['response']['0']['occupation']['name'] ) ? $profile_json_output['response']['0']['occupation']['name'] : '';
		if ( isset( $profile_json_output['response']['0']['relation'] ) ) {
			if ( $profile_json_output['response']['0']['relation'] == '1' ) {
				$relationship = 'single';
			}
			if ( $profile_json_output['response']['0']['relation'] == '2' ) {
				$relationship = 'In relationship';
			}
			if ( $profile_json_output['response']['0']['relation'] == '3' ) {
				$relationship = 'engaged';
			}
			if ( $profile_json_output['response']['0']['relation'] == '4' ) {
				$relationship = 'married';
			}
			if ( $profile_json_output['response']['0']['relation'] == '5' ) {
				$relationship = 'In a civil union';
			}
			if ( $profile_json_output['response']['0']['relation'] == '6' ) {
				$relationship = 'In love';
			}
			if ( $profile_json_output['response']['0']['relation'] == '7' ) {
				$relationship = 'Its complecated';
			}
			if ( $profile_json_output['response']['0']['relation'] == '8' ) {
				$relationship = 'Actively searching';
			}
		} else {
			$relationship = '';
		}
		$university_name = isset( $profile_json_output['response']['0']['universities']['0']['name'] ) ? $profile_json_output['response']['0']['universities']['0']['name'] : '';
		$field_of_study  = isset( $profile_json_output['response']['0']['universities']['0']['chair_name'] ) ? $profile_json_output['response']['0']['universities']['0']['chair_name'] : '';

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
