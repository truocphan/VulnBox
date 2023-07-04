<?php
require 'social_apps/mo_openid_configured_apps_funct.php';

function mo_openid_start_session() {
	if ( ! session_id() ) {
		session_start();
	}
}

function mo_openid_end_session() {
	session_start();
	session_unset(); // unsets all session variables
}

function mo_openid_initialize_social_login() {
	$client_name = 'wordpress';
	$appname     = sanitize_text_field( $_REQUEST['app_name'] ); // phpcs:ignore 
	if ( $appname == 'yaahoo' ) {
		$appname = 'yahoo';
	}
	$timestamp       = round( microtime( true ) * 1000 );
	$api_key         = get_option( 'mo_openid_admin_api_key' );
	$token           = $client_name . ':' . number_format( $timestamp, 0, '', '' ) . ':' . $api_key;
	$customer_token  = get_option( 'mo_openid_customer_token' );
	$encrypted_token = encrypt_data( $token, $customer_token );
	$encoded_token   = urlencode( $encrypted_token );
	$userdata        = get_option( 'moopenid_user_attributes' ) ? 'true' : 'false';
	$http            = isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
	$parts           = parse_url( $http . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field($_SERVER['REQUEST_URI']) );
	parse_str( $parts['query'], $query );
	$post            = isset( $query['p'] ) ? '?p=' . $query['p'] : '';
	$base_return_url = $http . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . strtok( sanitize_text_field($_SERVER['REQUEST_URI']), '?' ) . $post;
	$return_url      = strpos( $base_return_url, '?' ) !== false ? urlencode( $base_return_url . '&option=moopenid' ) : urlencode( $base_return_url . '?option=moopenid' );
	$url             = 'https://login.xecurify.com/moas/openid-connect/client-app/authenticate?token=' . $encoded_token . '&userdata=' . $userdata . '&id=' . get_option( 'mo_openid_admin_customer_key' ) . '&encrypted=true&app=' . $appname . '_oauth_xecurify&returnurl=' . $return_url . '&encrypt_response=true';
	wp_redirect( $url ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect --Redirect to the miniorange link to intiate the pre-configured application.
	exit;
}

function mo_openid_custom_app_oauth_redirect( $appname ) {
	if ( isset( $_REQUEST['test'] ) ) { 	// phpcs:ignore
		setcookie( 'mo_oauth_test', true );
	} else {
		setcookie( 'mo_oauth_test', false );
	}
	if ( $appname == 'yaahoo' ) {
		$appname = 'yahoo';
	}
	require 'social_apps/' . $appname . '.php';
	$mo_appname = 'mo_' . $appname;
	$social_app = new $mo_appname();
	$social_app->mo_openid_get_app_code();
}

function mo_openid_process_custom_app_callback() {
	$appname = '';

	if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) != 1 ) {
		return;
	}
	$code               = $profile_url = $client_id = $current_url = $client_secret = $access_token_uri = $postData = $oauth_token = $user_url = $user_name = $email = '';
	$oauth_access_token = $redirect_url = $option = $oauth_token_secret = $screen_name = $profile_json_output = $oauth_verifier = $twitter_oauth_token = $access_token_json_output = array();
	mo_openid_start_session();
	if ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'oauth_verifier' ) !== false ) {
		$_SESSION['appname'] = 'twitter';
	}

	if ( isset( $_SESSION['appname'] ) ) {
		$appname = sanitize_text_field($_SESSION['appname']);
	} elseif ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback' ) !== false ) {
		if ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/google' ) !== false ) || ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback=google' ) !== false ) ) {
			$appname = 'google';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/facebook' ) !== false ) || ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback=facebook' ) !== false ) ) {
			$appname = 'facebook';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/vkontakte' ) !== false ) ) {
			$appname = 'vkontakte';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/linkedin' ) !== false ) ) {
			$appname = 'linkedin';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/amazon' ) !== false ) || ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback=amazon' ) !== false ) ) {
			$appname = 'amazon';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/yahoo' ) !== false ) || ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback=yaahoo' ) !== false ) ) {
			$appname = 'yahoo';
		} elseif ( ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback/wordpress' ) !== false ) || ( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), 'openidcallback=wordpress' ) !== false ) ) {
			$appname = 'wordpress';
		}
	} else {
		return;
	}
	if ( $appname == 'yaahoo' ) {
		$appname = 'yahoo';
	}
	require 'social_apps/' . $appname . '.php';
	$mo_appname     = 'mo_' . $appname;
	$social_app     = new $mo_appname();
	$appuserdetails = $social_app->mo_openid_get_access_token();
	mo_openid_process_user_details( $appuserdetails, $appname );
}

function mo_openid_process_social_login() {

	if ( is_user_logged_in() ) {
		return;
	}
	// Decrypt all entries
	$decrypted_user_name = isset( $_POST['username'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['username'] ) ) : ''; 		// phpcs:ignore
	$decrypted_user_name = str_replace( ' ', '-', $decrypted_user_name );
	$decrypted_user_name = sanitize_text_field( $decrypted_user_name, true );
	$decrypted_email     = isset( $_POST['email'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['email'] ) ) : ''; 	// phpcs:ignore
	if ( $decrypted_user_name == null ) {
		$name_em             = explode( '@', $decrypted_email );
		$decrypted_user_name = isset( $name_em[0] ) ? $name_em[0] : '';
	}
	 $decrypted_first_name = isset( $_POST['firstName'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['firstName'] ) ) : ''; 	//phpcs:ignore
	if ( $decrypted_first_name == null ) {
		$name_em              = explode( '@', $decrypted_email );
		$decrypted_first_name = isset( $name_em[0] ) ? $name_em[0] : '';
	}
	$decrypted_app_name = isset( $_POST['appName'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['appName'] ) ) : ''; 	//phpcs:ignore
	$decrypted_app_name = strtolower( $decrypted_app_name );
	$split_app_name     = explode( '_', $decrypted_app_name );
	// check to ensure login starts at the click of social login button
	if ( empty( $split_app_name[0] ) ) {
		wp_die( esc_attr( get_option( 'mo_manual_login_error_message' ) ) );
	} else {
		$decrypted_app_name = strtolower( $split_app_name[0] );
	}

	$appuserdetails = array(
		'first_name'     => $decrypted_first_name,
		'last_name'      => isset( $_POST['lastName'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['lastName'] ) ) : '',		//phpcs:ignore
		'email'          => $decrypted_email,
		'user_name'      => $decrypted_user_name,
		'user_url'       => isset( $_POST['profileUrl'] ) ?  sanitize_text_field( mo_openid_decrypt_sanitize( $_POST['profileUrl'] ) ) : '',		//phpcs:ignore
		'user_picture'   => isset( $_POST['profilePic'] ) ?  sanitize_text_field( mo_openid_decrypt_sanitize($_POST['profilePic'] ) ) : '',	//phpcs:ignore
		'social_user_id' => isset( $_POST['userid'] ) ? sanitize_text_field(  mo_openid_decrypt_sanitize( $_POST['userid'] ) ) : '', 	//phpcs:ignore
	);

	mo_openid_process_user_details( $appuserdetails, $decrypted_app_name );
}

function mo_openid_process_user_details( $appuserdetails, $appname ) {
	$first_name    = $last_name = $email = $user_name = $user_url = $user_picture = $social_user_id = $full_name = '';
	$location_city = $location_country = $about_me = $company_name = $age = $gender = $birth_date = $friend_nos = $website = $field_of_study = $university_name = $places_lived = $position = $relationship = $contact_no = $industry = $head_line = $NA = '';

	$first_name       = isset( $appuserdetails['first_name'] ) ? $appuserdetails['first_name'] : '';
	$last_name        = isset( $appuserdetails['last_name'] ) ? $appuserdetails['last_name'] : '';
	$email            = isset( $appuserdetails['email'] ) ? $appuserdetails['email'] : '';
	$user_name        = isset( $appuserdetails['user_name'] ) ? $appuserdetails['user_name'] : '';
	$user_url         = isset( $appuserdetails['user_url'] ) ? $appuserdetails['user_url'] : '';
	$user_picture     = isset( $appuserdetails['user_picture'] ) ? $appuserdetails['user_picture'] : '';
	$social_user_id   = isset( $appuserdetails['social_user_id'] ) ? $appuserdetails['social_user_id'] : '';
	$full_name        = isset( $appuserdetails['full_name'] ) ? $appuserdetails['full_name'] : '';
	$location_city    = isset( $appuserdetails['location_city'] ) ? $appuserdetails['location_city'] : '';
	$location_country = isset( $appuserdetails['location_country'] ) ? $appuserdetails['location_country'] : '';
	$about_me         = isset( $appuserdetails['about_me'] ) ? $appuserdetails['about_me'] : '';
	$company_name     = isset( $appuserdetails['company_name'] ) ? $appuserdetails['company_name'] : '';
	$age              = isset( $appuserdetails['age'] ) ? $appuserdetails['age'] : '';
	$gender           = isset( $appuserdetails['gender'] ) ? $appuserdetails['gender'] : '';
	$birth_date       = isset( $appuserdetails['birth_date'] ) ? $appuserdetails['birth_date'] : '';
	$friend_nos       = isset( $appuserdetails['friend_nos'] ) ? $appuserdetails['friend_nos'] : '';
	$website          = isset( $appuserdetails['website'] ) ? $appuserdetails['website'] : '';
	$field_of_study   = isset( $appuserdetails['field_of_study'] ) ? $appuserdetails['field_of_study'] : '';
	$university_name  = isset( $appuserdetails['university_name'] ) ? $appuserdetails['university_name'] : '';
	$places_lived     = isset( $appuserdetails['places_lived'] ) ? $appuserdetails['places_lived'] : '';
	$position         = isset( $appuserdetails['position'] ) ? $appuserdetails['position'] : '';
	$relationship     = isset( $appuserdetails['relationship'] ) ? $appuserdetails['relationship'] : '';
	$contact_no       = isset( $appuserdetails['contact_no'] ) ? $appuserdetails['contact_no'] : '';
	$industry         = isset( $appuserdetails['industry'] ) ? $appuserdetails['industry'] : '';
	$head_line        = isset( $appuserdetails['head_line'] ) ? $appuserdetails['head_line'] : '';

	$user_name = str_replace( ' ', '-', $user_name );
	$user_name = sanitize_user( $user_name, true );

	if ( $user_name == '-' || $user_name == '' ) {
		$splitemail = explode( '@', $email );
		$user_name  = $splitemail[0];
	}

	// Set User Full Name
	if ( isset( $first_name ) && isset( $last_name ) ) {
		if ( strcmp( $first_name, $last_name ) != 0 ) {
			$user_full_name = $first_name . ' ' . $last_name;
		} else {
			$user_full_name = $first_name;
		}
	} elseif ( isset( $first_name ) ) {
		$user_full_name = $first_name;
	} else {
		$user_full_name = $user_name;
		$first_name     = '';
		$last_name      = '';
	}

	// check_existing_user
	global $wpdb;
	$linked_email_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'mo_openid_linked_user where linked_social_app = %s AND identifier = %s', array( $appname, $social_user_id ) ) );
	$user_email      = sanitize_email( $email );

	$email_user_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'mo_openid_linked_user where linked_email = %s', $user_email ) );

	if ( empty( $user_email ) ) {
		$existing_email_user_id = null;
	} else {
		$existing_email_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_email = %s", $user_email ) );
	}

	$session_values = array(
		'username'        => $user_name,
		'user_email'      => $user_email,
		'user_full_name'  => $user_full_name,
		'first_name'      => $first_name,
		'last_name'       => $last_name,
		'user_url'        => $user_url,
		'user_picture'    => $user_picture,
		'social_app_name' => $appname,
		'social_user_id'  => $social_user_id,
	);
	mo_openid_start_session_login( $session_values );
	// user is a member
	if ( ( isset( $linked_email_id ) ) || ( isset( $email_user_id ) ) || ( isset( $existing_email_user_id ) ) ) {
		if ( ( ! isset( $linked_email_id ) ) && ( isset( $email_user_id ) ) ) {
			$linked_email_id = $email_user_id;
			mo_openid_insert_query( $appname, $user_email, $linked_email_id, $social_user_id );
		}
		if ( isset( $linked_email_id ) ) {
			$user    = get_user_by( 'id', $linked_email_id );
			$user_id = $user->ID;
		} elseif ( isset( $email_user_id ) ) {
			$user    = get_user_by( 'id', $email_user_id );
			$user_id = $user->ID;
		} else {
			$user    = get_user_by( 'id', $existing_email_user_id );
			$user_id = $user->ID;
		}

		// $linked_email_id,$user_id,$user,$user_picture
		mo_openid_login_user( $linked_email_id, $user_id, $user, $user_picture, 1 );
	}

	// new user creation
	if ( get_option( 'mo_openid_auto_register_enable' ) ) {
		$allowed_html = array('style'=> array(), 'head'=> array(), 'meta'=> array('name'=>array(), 'content'=>array(), ), 'link'=>array('rel'=>array(), 'href'=> array(), 'type'=> array(), 'media' => array()), 'body'=> array('class'=> array()), 'div'=> array('style'=> array(), 'class'=>array(), 'id'=> array()), 'form'=> array('name'=> array(), 'method'=> array(), 'action'=> array()), 'span'=> array('style'=> array(), 'class'=> array(), 'align'=> array()), 'p'=> array(), 'br' => array(), 'label' => array('for' => array()), 'input' => array('type'=> array(), 'class'=>array(), 'name'=> array(),'size'=> array(), 'value'=> array()) );
		if ( get_option( 'mo_openid_enable_profile_completion' ) && ( $session_values['user_email'] == '' || $session_values['username'] == '' ) ) { // if newa user and profile completion is enabled
			echo wp_kses( mo_openid_profile_completion_form( $session_values ), $allowed_html );
			exit;
		} else {

			mo_create_new_user( $session_values );
		}
	} else {
		mo_openid_disabled_register_message();
	}
}

function mo_create_new_user( $user_val ) {
	
	$user_name      = $user_val['username'];
	$email          = $user_val['user_email'];
	$user_full_name = $user_val['user_full_name'];
	$first_name     = $user_val['first_name'];
	$last_name      = $user_val['last_name'];
	$user_url       = $user_val['user_url'];
	$user_picture   = $user_val['user_picture'];
	$appname        = $user_val['social_app_name'];
	$social_user_id = $user_val['social_user_id'];
	global $wpdb;
	$linked_email_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'mo_openid_linked_user where linked_social_app = %s AND identifier = %s', array( $appname, $social_user_id ) ) );
	$user_email      = sanitize_email( $email );
	if ( empty( $user_name ) && ! empty( $email ) ) {
		$split_email = explode( '@', $email );
		$user_name   = $split_email[0];
		$user_email  = $email;
	} elseif ( empty( $email ) && ! empty( $user_name ) ) {
		$split_app_name = explode( '_', $appname );
		$user_email     = $user_name . '@' . $split_app_name[0] . '.com';
	} elseif ( empty( $email ) && empty( $user_name ) ) {
		wp_die( 'No profile data is returned from application. Please contact your administrator.' );
	}

	$user_email = str_replace( ' ', '', $user_email );

	$random_password  = wp_generate_password( 10, false );
	$user_profile_url = $user_url;

	if ( isset( $appname ) && ! empty( $appname ) && $appname == 'facebook' ) {
		$user_url = '';
	}

	// Checking if username already exist
	$user_name_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_login = %s", $user_name ) );

	if ( isset( $user_name_user_id ) ) {
		$email_array       = explode( '@', $user_email );
		$user_name         = $email_array[0];
		$user_name_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_login = %s", $user_name ) );
		$i                 = 1;
		while ( ! empty( $user_name_user_id ) ) {
			$uname             = $user_name . '_' . $i;
			$user_name_user_id = $wpdb->get_var( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->prefix . 'users where user_login = %s', $uname ) );
			$i++;
			if ( empty( $user_name_user_id ) ) {
				$user_name = $uname;
			}
		}

		if ( isset( $user_name_user_id ) ) {
			echo '<br/>' . 'Error Code Existing Username: ' . esc_attr( get_option( 'mo_existing_username_error_message' ) );
			exit();
		}
	}

	// to check for customisation fields
	if ( get_option( 'mo_openid_customised_field_enable' ) == 1 ) {
		sso_field_mapping_add_on( $user_val );
	}

	$userdata = array(
		'user_login'   => $user_name,
		'user_email'   => $user_email,
		'user_pass'    => $random_password,
		'display_name' => $user_full_name,
		'first_name'   => $first_name,
		'last_name'    => $last_name,
		'user_url'     => $user_url,
	);
	$user_id  = wp_insert_user( $userdata );

	if ( is_wp_error( $user_id ) ) {
		print_r( $user_id );
		wp_die( 'Error Code 5: ' . esc_attr( get_option( 'mo_registration_error_message' ) ) );
	}

	update_option( 'mo_openid_user_count', get_option( 'mo_openid_user_count' ) + 1 );

	$session_values = array(
		'username'        => $user_name,
		'user_email'      => $user_email,
		'user_full_name'  => $user_full_name,
		'first_name'      => $first_name,
		'last_name'       => $last_name,
		'user_url'        => $user_url,
		'user_picture'    => $user_picture,
		'social_app_name' => $appname,
		'social_user_id'  => $social_user_id,
	);

	mo_openid_start_session_login( $session_values );
	$user = get_user_by( 'id', $user_id );
	// registration hook
	do_action( 'mo_user_register', $user_id, $user_profile_url );
	mo_openid_link_account( $user->user_login, $user );
	$linked_email_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'mo_openid_linked_user where linked_social_app = %s AND identifier = %s', array( $appname, $social_user_id ) ) );
	mo_openid_login_user( $linked_email_id, $user_id, $user, $user_picture, 0 );
}

function mo_openid_customize_logo() {
	$logo = " <div style='float:left;' class='mo_image_id'>
                <a target='_blank' href='https://www.miniorange.com/'>
                <img alt='logo' src='" . plugins_url( '/includes/images/miniOrange.png', __FILE__ ) . "' class='mo_openid_image'>
                </a>
                </div>
                <br/>";
	return $logo;
}


function sso_field_mapping_add_on( $user_val ) {
	$user_name        = $user_val['username'];
	$email            = $user_val['user_email'];
	$user_full_name   = $user_val['user_full_name'];
	$first_name       = $user_val['first_name'];
	$last_name        = $user_val['last_name'];
	$user_url         = $user_val['user_url'];
	$user_picture     = $user_val['user_picture'];
	$appname          = $user_val['social_app_name'];
	$social_user_id   = $user_val['social_user_id'];
	$set_cust_field   = get_option( 'mo_openid_custom_field_mapping' );
	$random_password  = wp_generate_password( 10, false );
	$user_profile_url = $user_url;
	if ( $set_cust_field ) {
		foreach ( $set_cust_field as $x ) {
			foreach ( $x as $xx => $x_value ) {
				if ( isset( $xx ) ) {
					?>
					<form id="myForm" action="<?php echo esc_attr( get_option( 'profile_completion_page' ) ); ?>" method="post">
						<?php
						echo '<input type="hidden" name="last_name" value="' . esc_attr( $last_name ) . '">';
						echo '<input type="hidden" name="first_name" value="' . esc_attr( $first_name ) . '">';
						echo '<input type="hidden" name="user_full_name" value="' . esc_attr( $user_full_name ) . '">';
						echo '<input type="hidden" name="user_url" value="' . esc_url( $user_url ) . '">';
						echo '<input type="hidden" name="user_profile_url" value="' . esc_url( $user_profile_url ) . '">';
						echo '<input type="hidden" name="call" value="5">';
						echo '<input type="hidden" name="user_picture" value="' . esc_attr( $user_picture ) . '">';
						echo '<input type="hidden" name="username" value="' . esc_attr( $user_name ) . '">';
						echo '<input type="hidden" name="user_email" value="' . esc_attr( $email ) . '">';
						echo '<input type="hidden" name="random_password" value="' . esc_attr( $random_password ) . '">';
						echo '<input type="hidden" name="social_app_name" value="' . esc_attr( $appname ) . '">';
						echo '<input type="hidden" name="social_user_id" value="' . esc_attr( $social_user_id ) . '">';
						echo '<input type="hidden" name="decrypted_app_name" value="">';
						echo '<input type="hidden" name="decrypted_user_id" value="">';
						?>
					</form>
					<script type="text/javascript">
						document.getElementById('myForm').submit();
					</script>
					<?php
					exit;
				}
			}
		}
	}
}

function mo_openid_plugin_update() {
	global $wpdb;
	$table_name      = $wpdb->prefix . 'mo_openid_linked_user';
	$charset_collate = $wpdb->get_charset_collate();

	$time = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE
                                     TABLE_SCHEMA= %s
                                     AND COLUMN_NAME = 'timestamp'",
			$wpdb->dbname
		)
	);

	$data_type = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT DATA_TYPE 
    FROM information_schema.COLUMNS
    WHERE
    TABLE_SCHEMA= %s
    AND TABLE_NAME = %s
    AND COLUMN_NAME = 'user_id'",
			array( $wpdb->dbname, $table_name )
		)
	);

	if ( $data_type == 'mediumint' ) {
		$wpdb->get_var( $wpdb->prepare( 'ALTER TABLE %s CHANGE `user_id` `user_id` BIGINT(20) NOT NULL', $table_name ) );

	}

	// if table mo_openid_linked_user doesn't exist or the 'timestamp' column doesn't exist
	if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', $table_name ) ) != $table_name || empty( $time ) ) {
		$sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    linked_social_app varchar(55) NOT NULL,
                    linked_email varchar(55) NOT NULL,
                    user_id BIGINT(20) NOT NULL,
                    identifier VARCHAR(100) NOT NULL,
                    timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY  (id)
                ) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		$identifier = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COLUMN_NAME 
                                    FROM information_schema.COLUMNS 
                                    WHERE 
                                    TABLE_NAME = %s 
                                    AND TABLE_SCHEMA= %s
                                    AND COLUMN_NAME = 'identifier'",
				array( $wpdb->users, $wpdb->dbname )
			)
		);

		if ( strcasecmp( $identifier, 'identifier' ) == 0 ) {

			$count  = $wpdb->get_var( "SELECT count(ID) FROM $wpdb->users WHERE identifier not LIKE ''" );
			$result = $wpdb->get_results( "SELECT * FROM $wpdb->users WHERE identifier not LIKE ''" );

			for ( $icnt = 0; $icnt < $count; $icnt = $icnt + 1 ) {

				$provider       = $result[ $icnt ]->provider;
				$split_app_name = explode( '_', $provider );
				$provider       = strtolower( $split_app_name[0] );
				$user_email     = $result[ $icnt ]->user_email;
				$ID             = $result[ $icnt ]->ID;
				$identifier     = $result[ $icnt ]->identifier;

				$output = $wpdb->insert(
					$table_name,
					array(
						'linked_social_app' => $provider,
						'linked_email'      => $user_email,
						'user_id'           => $ID,
						'identifier'        => $identifier,
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
					)
				);
				if ( $output === false ) {
					$wpdb->show_errors();
					$wpdb->print_error();
					wp_die( 'Error in insert Query' );
					exit;

				}
			}
			$wpdb->get_var( "ALTER TABLE $wpdb->users DROP COLUMN provider" );
			$wpdb->get_var( "ALTER TABLE $wpdb->users DROP COLUMN identifier" );
		}
	}

	$current_version = get_option( 'mo_openid_social_login_version' );

	if ( ! $current_version && version_compare( MO_OPENID_SOCIAL_LOGIN_VERSION, '200.1.1', '>=' ) ) {
		// delete entries from mo_openid_linked_user table which have empty column values
		$result = $wpdb->query(
			$wpdb->prepare(
				'
                        DELETE FROM %s
                        WHERE linked_social_app = %s
                        OR linked_email = %s
                        OR user_id = %d
                        OR identifier = %s
                        ',
				$table_name,
				'',
				'',
				0,
				''
			)
		);
		if ( $result === false ) {
			/*
			$wpdb->show_errors();
			$wpdb->print_error();
			exit;*/
			wp_die( 'Error in deletion query' );
		}
	}
	update_option( 'mo_openid_social_login_version', MO_OPENID_SOCIAL_LOGIN_VERSION );
}

function mo_openid_delete_social_profile( $id ) {
	// delete first name, last name, user_url and profile_url from usermeta
	global $wpdb;
	$metakey1 = 'first_name';
	$metakey2 = 'last_name';
	$metakey3 = 'moopenid_user_avatar';
	$metakey4 = 'moopenid_user_profile_url';
	$wpdb->query( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'usermeta where user_id = %d and (meta_key = %s or meta_key = %s  or meta_key = %s  or meta_key = %s)', $id, $metakey1, $metakey2, $metakey3, $metakey4 ) );
	update_user_meta( $id, 'mo_openid_data_deleted', '1' );
	exit;
}

function checkTimeStamp( $sentTime, $validatedTime ) {
	$diff = round( abs( $validatedTime - $sentTime ) / 60, 2 );
	if ( $diff > 5 ) {
		return false;
	} else {
		return true;
	}
}

function checkTransactionId( $customerKey, $otpToken, $transactionId, $pass ) {
	if ( ! $pass ) {
		return false;
	}
	$stringToHash = $customerKey . $otpToken;
	$txtID        = hash( 'sha512', $stringToHash );
	if ( $txtID == $transactionId ) {
		return true;
	}
}


function mo_openid_is_customer_addon_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_extra_attr_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_is_wca_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_wca_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_is_bpp_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_bpp_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_is_dis_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_dis_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_is_hub_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_hub_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_is_mailc_license_key_verified() {
	$licenseKey  = get_option( 'mo_openid_opn_lk_mailc_addon' );
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( ! $licenseKey || ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}
function mo_openid_show_addon_message_page( $add ) {
	?>
	<div class="mo_openid_table_layout">
		<?php
		echo "<div style='text-align: center'><p>It seems <b>" . esc_attr( $add ) . '</b> is not installed or activated. Please download or activate the addon.</p></div>';
		?>
		<h2 align="center">How do I download or activate the Social Integration Registration Form Addon ?</h2>
		<p><b>Download:</b> Download addon and license key from xecurify Console
		<ol>
			<li>Login to <a target=”_blank” href="https://login.xecurify.com">xecurify Console</a>.</li>
			<li>Click on <b>License</b> on the left menu.</li>
			<li>You will see the payment made by you. Against the payment, you will see a <b>Download Plugin</b> option. Click on the link to download the plugin.</li>
			<li>A plugin of .zip extension will get downloaded.</li>
			<li>Click on <b>View License Key</b>.</li>
			<li>Copy the license key given for use during installation.</li>
		</ol>
		</p>
		<p><b>Installation:</b>
		<ol>
			<li>Go to <b>Plugins > Add New</b> and click on <b>Upload addon</b>.</li>
			<li>Upload the downloaded zip and Activate.</li>
		</ol>
		</p>
	</div>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('Extra Attributes Add On');
	</script>
	<?php
}

function mo_openid_show_verify_addon_license_page() {
	$nonce = sanitize_text_field( $_POST['mo_openid_verify_addon_license_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-verify-addon-license-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if(current_user_can('administrator')){
		if ( sanitize_text_field( $_POST['plan_name'] ) == 'extra_attributes_addon' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'extra_attributes_addon' ) ) );
		} elseif ( sanitize_text_field( $_POST['plan_name'] ) == 'WP_SOCIAL_LOGIN_WOOCOMMERCE_ADDON' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'WP_SOCIAL_LOGIN_WOOCOMMERCE_ADDON' ) ) );
		} elseif ( sanitize_text_field( $_POST['plan_name'] ) == 'WP_SOCIAL_LOGIN_MAILCHIMP_ADDON' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'WP_SOCIAL_LOGIN_MAILCHIMP_ADDON' ) ) );
		} elseif ( sanitize_text_field( $_POST['plan_name'] ) == 'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON' ) ) );
		} elseif ( sanitize_text_field( $_POST['plan_name'] ) == 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON' ) ) );
		} elseif ( sanitize_text_field( $_POST['plan_name'] ) == 'WP_SOCIAL_LOGIN_DISCORD_ADDON' ) {
			wp_send_json( array( 'html' => mo_openid_show_verify_license_page( 'WP_SOCIAL_LOGIN_DISCORD_ADDON' ) ) );
		}
	}
	}
}

function mo_openid_show_verify_license_page( $licience_type ) {
	?>
	<div class="mo_openid_table_layout" style="padding-bottom:50px;!important">
		<h3>Verify License </h3>
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_openid_verify_license" />
			<input type="hidden" name="mo_openid_verify_license_nonce"
				   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-verify-license-nonce' ) ); ?>"/>

			<p><b><font color="#FF0000">*</font>Enter your license key to activate the plugin:</b>
				<input class="mo_openid_table_textbox" required type="text" style="margin-left:40px;width:300px;"
					   name="openid_licence_key" placeholder="Enter your license key to activate the plugin"/>
			</p>
			<p>
				<label class="mo_openid_checkbox_container">
					<b><font color="#FF0000">*</font>Please check this to confirm that you have read it</b>
					<input style="position: absolute; left:0;top: 88%" required type="checkbox" name="license_conditions"/>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
			</p>

			<ol>
				<li>License key you have entered here is associated with this site instance. In future, if you are re-installing the plugin or your site for any reason. You should deactivate and then delete the plugin from WordPress console and should not manually delete the plugin folder. So that you can resuse the same license key.</li><br>
				<li><b>This is not a developer's license.</b> Making any kind of change to the plugin's code will delete all your configuration and make the plugin unusable.</li>
			</ol>
			<br>
			<input type="hidden" name="licience_type" value="<?php echo esc_attr( $licience_type ); ?>" />
			<input type="submit" name="submit" value="Activate License" class="button button-primary button-large"/>
		</form>
	</div>
	<?php
}

function mo_openid_show_verify_password_page() {
	update_option( 'regi_pop_up', 'no' );
	?>
	<!--Verify password with miniOrange-->
	<form name="f" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_connect_verify_customer" />
		<input type="hidden" name="mo_openid_connect_verify_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-connect-verify-nonce' ) ); ?>"/>
		<div class="mo_openid_table_layout">
			<h3>Login with miniOrange</h3>
			<p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password. <a href="#forgot_password">Click here if you forgot your password?</a></b></p>
			<table class="mo_openid_settings_table">
				<tr>
					<td><b><font color="#FF0000">*</font>Email:</b></td>
					<td><input class="mo_openid_table_textbox" id="email" type="email" name="email"
							   required placeholder="person@example.com"
							   value="<?php echo esc_attr( get_option( 'mo_openid_admin_email' ) ); ?>" /></td>
				</tr>
				<td><b><font color="#FF0000">*</font>Password:</b></td>
				<td><input class="mo_openid_table_textbox" required type="password"
						   name="password" placeholder="Choose your password" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" value="Login"
							   class="button button-primary button-large" />
						<input type="button" value="Registration Page" id="mo_openid_go_back" style="margin-left: 2%"
							   class="button button-primary button-large" />
					</td>
				</tr>
			</table>
		</div>
	</form>
	<form name="f" method="post" action="" id="openidgobackform">
		<input type="hidden" name="option" value="mo_openid_go_back_login"/>
		<input type="hidden" name="mo_openid_go_back_login_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-go-back-login-nonce' ) ); ?>"/>
	</form>
	<form name="forgotpassword" method="post" action="" id="openidforgotpasswordform">
		<input type="hidden" name="option" value="mo_openid_forgot_password"/>
		<input type="hidden" name="mo_openid_forgot_password_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-forgot-password-nonce' ) ); ?>"/>
		<input type="hidden" id="forgot_pass_email" name="email" value=""/>
	</form>
	<script>
		jQuery('#mo_openid_go_back').click(function() {
			jQuery('#openidgobackform').submit();
		});
		jQuery('a[href="#forgot_password"]').click(function(){
			jQuery('#forgot_pass_email').val(jQuery('#email').val());
			jQuery('#openidforgotpasswordform').submit();
		});
	</script>
	<?php
}

function mo_openid_is_customer_registered() {
	$email       = get_option( 'mo_openid_admin_email' );
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( $customerKey == 253560 ) {
		$customerKey = '';
	}
	if ( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function mo_openid_check_empty_or_null( $value ) {
	if ( ! isset( $value ) || empty( $value ) ) {
		return true;
	}
	return false;
}

function mo_openid_decrypt_sanitize( $param ) {
	if ( strcmp( $param, 'null' ) != 0 && strcmp( $param, '' ) != 0 ) {
		$customer_token  = get_option( 'mo_openid_customer_token' );
		$decrypted_token = decrypt_data( $param, $customer_token );
		// removes control characters and some blank characters
		$decrypted_token_sanitise = preg_replace( '/[\x00-\x1F][\x7F][\x81][\x8D][\x8F][\x90][\x9D][\xA0][\xAD]/', '', $decrypted_token );
		// strips space,tab,newline,carriage return,NUL-byte,vertical tab.
		return trim( $decrypted_token_sanitise );
	} else {
		return '';
	}

}

function decrypt_data( $data, $key ) {

	return openssl_decrypt( base64_decode( $data ), 'aes-128-ecb', $key, OPENSSL_RAW_DATA );

}

function mo_openid_show_success_message() {
	remove_action( 'admin_notices', 'mo_openid_success_message' );
	add_action( 'admin_notices', 'mo_openid_error_message' );
}

function mo_openid_show_error_message() {
	remove_action( 'admin_notices', 'mo_openid_error_message' );
	add_action( 'admin_notices', 'mo_openid_success_message' );
}

function mo_openid_success_message() {
	$message = get_option( 'mo_openid_message' );
	?>
	<div id="snackbar"><?php echo esc_attr( ( $message ) ); ?></div>
	<style>
		#snackbar {
			visibility: hidden;
			min-width: 250px;
			margin-left: -125px;
			background-color: #c02f2f;
			color: #fff;
			text-align: center;
			border-radius: 2px;
			padding: 16px;
			position: fixed;
			z-index: 1;
			top: 8%;
			right: 30px;
			font-size: 17px;
		}

		#snackbar.show {
			visibility: visible;
			-webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
			animation: fadein 0.5s, fadeout 0.5s 3.5s;
		}

		@-webkit-keyframes fadein {
			from {right: 0; opacity: 0;}
			to {right: 30px; opacity: 1;}
		}

		@keyframes fadein {
			from {right: 0; opacity: 0;}
			to {right: 30px; opacity: 1;}
		}

		@-webkit-keyframes fadeout {
			from {right: 30px; opacity: 1;}
			to {right: 0; opacity: 0;}
		}

		@keyframes fadeout {
			from {right: 30px; opacity: 1;}
			to {right: 0; opacity: 0;}
		}
	</style>
	<script>
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
	</script>
	<?php
}

function mo_openid_error_message() {
	$message = get_option( 'mo_openid_message' );
	?>
	<div id="snackbar"><?php echo esc_attr( ( $message ) ); ?></div>
	<style>
		#snackbar {
			visibility: hidden;
			min-width: 250px;
			margin-left: -125px;
			background-color: #4CAF50;
			color: #fff;
			text-align: center;
			border-radius: 2px;
			padding: 16px;
			position: fixed;
			z-index: 1;
			top: 8%;
			right: 30px;
			font-size: 17px;
		}

		#snackbar.show {
			visibility: visible;
			-webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
			animation: fadein 0.5s, fadeout 0.5s 3.5s;
		}

		@-webkit-keyframes fadein {
			from {right: 0; opacity: 0;}
			to {right: 30px; opacity: 1;}
		}

		@keyframes fadein {
			from {right: 0; opacity: 0;}
			to {right: 30px; opacity: 1;}
		}

		@-webkit-keyframes fadeout {
			from {right: 30px; opacity: 1;}
			to {right: 0; opacity: 0;}
		}

		@keyframes fadeout {
			from {right: 30px; opacity: 1;}
			to {right: 0; opacity: 0;}
		}
	</style>
	<script>

		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);

	</script>
	<?php
}

function mo_openid_registeration_modal() {
	update_option( 'regi_pop_up', 'yes' );
	update_option( 'mo_openid_new_registration', 'true' );

	$current_user = wp_get_current_user();
	?>
	<div id="request_registeration" class="mo_openid_modal" style="height:100%">
	<!-- Modal content -->
	<div class="mo_openid_modal-content" style="text-align:center;margin-left: 32%;margin-top: 4%;width: 37%;">
		<div class="modal-head">
			<!--                <span class="mo_close">&times;</span>-->
			<a href="" class="mo_close" id="mo_close" style="text-decoration:none;margin-top: 3%;">&times;</a>
			<h1>Save As</h1>
		</div>
		<br>
		<span id="msg1" style="text-align: center;color: #56b11e">
		<?php
		echo esc_attr( ( get_option( 'pop_regi_msg' ) ) );
		update_option( 'pop_regi_msg', 'Your settings are saved successfully. Please enter your valid email address and enter a password to get touch with our support team.' );
		?>
		</span>
		<br>
		<br>
		<br>
		<div id="mo_saml_show_registeration_modal" >

			<!--Register with miniOrange-->

			<form name="f" method="post" action="" id="pop-register-form">
				<input name="option" value="mo_openid_connect_register_customer" type="hidden"/>
				<input type="hidden" name="mo_openid_connect_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-connect-register-nonce' ) ); ?>"/>
				<div>
					<table style="text-align: left;width: 100%">
						<tbody><tr>
							<td><b><font color="#FF0000">*</font>Email:</b></td>
							<td><input class="mo_openid_table_textbox" name="email" style="width: 100%"
									   required placeholder="person@example.com"
									   value="<?php echo esc_attr( $current_user->user_email ); ?>" type="email" />
							</td>
						</tr>

						<tr>
							<td><b><font color="#FF0000">*</font>Password:</b></td>
							<td><input class="mo_openid_table_textbox" required name="password"
									   style="width: 100%" placeholder="Choose your password (Min. length 6)"
									   type="password" /></td>
						</tr>
						<tr id="pop_register" >
							<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
							<td><input class="mo_openid_table_textbox" required name="confirmPassword"
									   style="width: 100%" placeholder="Confirm your password"
									   type="password" /></td>
						</tr>

						</tbody></table>
					<br><br>
					<input value="Submit" id="register_submit" style="margin-right: 28%;" class="button button-primary button-large" type="submit">

				</div>
			</form>
			<form method="post">
				<input id="pop_next" name="show_login" value="Existing Account" class="button button-primary button-large" style="margin-left: 35%;margin-top: -7.7%;" type="submit">
			</form>
		</div>
	</div>
	</div>
	<?php
}

function create_customer($password) {
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-connect-register-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		$customer    = new CustomerOpenID();
		$customerKey = json_decode( $customer->create_customer($password), true );
		if ( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS' ) == 0 ) {
			get_current_customer($password);
		} elseif ( ( strcasecmp( $customerKey['status'], 'INVALID_EMAIL_QUICK_EMAIL' ) == 0 ) && ( strcasecmp( $customerKey['message'], 'This is not a valid email. please enter a valid email.' ) == 0 ) ) {
			if ( isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) == 'mo_register_new_user' : 0 ) {
				wp_send_json( array( 'error' => 'There was an error creating an account for you. You may have entered an invalid Email-Id. (We discourage the use of disposable emails) Please try again with a valid email.' ) );
			} else {
				update_option( 'mo_openid_message', 'There was an error creating an account for you. You may have entered an invalid Email-Id. <b> (We discourage the use of disposable emails) </b> Please try again with a valid email.' );
				update_option( 'mo_openid_registration_status', 'EMAIL_IS_NOT_VALID' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
			}
		} elseif ( ( strcasecmp( $customerKey['status'], 'FAILED' ) == 0 ) && ( strcasecmp( $customerKey['message'], 'Email is not enterprise email.' ) == 0 ) ) {
			if ( isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) == 'mo_register_new_user' : 0 ) {
				wp_send_json( array( 'error' => 'There was an error creating an account for you. You may have entered an invalid Email-Id. (We discourage the use of disposable emails) Please try again with a valid email.' ) );
			} else {
				update_option( 'mo_openid_message', 'There was an error creating an account for you. You may have entered an invalid Email-Id. <b> (We discourage the use of disposable emails) </b> Please try again with a valid email.' );
				update_option( 'mo_openid_registration_status', 'EMAIL_IS_NOT_ENTERPRISE' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
			}
		} elseif ( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option( 'mo_openid_cust', '0' );
			update_option( 'mo_openid_message', 'Registration complete!' );
			update_option( 'mo_openid_registration_status', 'MO_OPENID_REGISTRATION_COMPLETE' );
			delete_option( 'mo_openid_verify_customer' );
			delete_option( 'mo_openid_new_registration' );
			if ( isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) == 'mo_register_new_user' : 0 ) {
				wp_send_json( array( 'success' => 'Registration complete!' ) );
			} else {
				mo_openid_show_success_message();
				header( 'Location: admin.php?page=mo_openid_general_settings&tab=licensing_plans' );
			}
		}
	}
}

function mo_openid_register_user() {
	// validation and sanitization
	$email           = '';
	$password        = '';
	$confirmPassword = '';
	$illegal         = "#$%^*()+=[]';,/{}|:<>?~";
	$illegal         = $illegal . '"';
	$nonce           = sanitize_text_field( $_POST['mo_openid_connect_register_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-connect-register-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {

			if ( mo_openid_check_empty_or_null( sanitize_email( $_POST['email'] ) ) || mo_openid_check_empty_or_null( $_POST['password'] ) || mo_openid_check_empty_or_null( $_POST['confirmPassword'] ) )  // phpcs:ignore
			{
				update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
				return;
			} elseif ( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6 )  // phpcs:ignore
			{    // check password is of minimum length 6
				update_option( 'mo_openid_message', 'Choose a password with minimum length 6.' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
				return;
			} elseif ( strpbrk( sanitize_email( $_POST['email'] ), $illegal ) ) {
				update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.' );
				mo_openid_show_error_message();
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
				return;
			} elseif ( strcmp( stripslashes( $_POST['password'] ), stripslashes( $_POST['confirmPassword'] ) ) != 0 ) // phpcs:ignore
			{
				update_option( 'mo_openid_message', 'Passwords do not match.' );
				if ( get_option( 'regi_pop_up' ) == 'yes' ) {
					update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
					mo_openid_registeration_modal();
				}
				delete_option( 'mo_openid_verify_customer' );
				mo_openid_show_error_message();
			} else {
				$email    = sanitize_email( $_POST['email'] );
				$password = stripslashes( $_POST['password'] ); //phpcs:ignore
				update_option( 'mo_openid_admin_email', $email );
				$customer = new CustomerOpenID();
				$content  = json_decode( $customer->check_customer(), true );
				if ( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) == 0 ) {
					if ( $content['status'] == 'ERROR' ) {
						if ( sanitize_text_field( $_POST['action'] ) == 'mo_register_new_user' ) {
							wp_send_json( array( 'error' => $content['message'] ) );
						} else {
							update_option( 'mo_openid_message', $content['message'] );
							mo_openid_show_error_message();
						}
					} else {
						create_customer($password);
						update_option( 'mo_openid_oauth', '1' );
						update_option( 'mo_openid_new_user', '1' );
						update_option( 'mo_openid_malform_error', '1' );
					}
				} elseif ( $content == null ) {
					if ( sanitize_text_field( $_POST['action'] ) == 'mo_register_new_user' ) {
						wp_send_json( array( 'error' => 'Please check your internet connetion and try again.' ) );
					} else {
						update_option( 'mo_openid_message', 'Please check your internet connetion and try again.' );
						mo_openid_show_error_message();
						if ( get_option( 'regi_pop_up' ) == 'yes' ) {
							update_option( 'pop_regi_msg', get_option( 'mo_openid_message' ) );
							mo_openid_registeration_modal();
						}
					}
				} else {
					get_current_customer($password);
				}

			}
		}
	}
}

function mo_register_old_user() {
	// validation and sanitization
	$email    = '';
	$password = '';
	$illegal  = "#$%^*()+=[]';,/{}|:<>?~";
	$illegal  = $illegal . '"';
	$message  = new miniorange_openid_sso_settings();
	$nonce    = sanitize_text_field( $_POST['mo_openid_connect_verify_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-connect-verify-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if(current_user_can('administrator')){

		if ( mo_openid_check_empty_or_null( sanitize_email( $_POST['email'] ) ) || mo_openid_check_empty_or_null( $_POST['password'] ) ) {  // phpcs:ignore
			update_option( 'mo_openid_message', 'All the fields are required. Please enter valid entries.' );
			$message->mo_openid_show_error_message();
			return;
		} elseif ( strpbrk( sanitize_email( $_POST['email'] ), $illegal ) ) {
			update_option( 'mo_openid_message', 'Please match the format of Email. No special characters are allowed.' );
			$message->mo_openid_show_error_message();
			return;
		} else {
			$email    = sanitize_email( $_POST['email'] );
			$password = stripslashes( $_POST['password'] );  // phpcs:ignore
		}
		update_option( 'mo_openid_admin_email', $email );
		$customer    = new CustomerOpenID();
		$content     = $customer->get_customer_key($password);
		$customerKey = json_decode( $content, true );
		if ( isset( $customerKey ) ) {
			update_option( 'mo_openid_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_openid_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_openid_customer_token', $customerKey['token'] );
			update_option( 'mo_openid_admin_phone', isset( $customerKey['phone'] ) ? $customerKey['phone'] : '' );
			update_option( 'mo_openid_message', 'Your account has been retrieved successfully.' );
			delete_option( 'mo_openid_verify_customer' );
			if ( isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) == 'mo_register_old_user' : false ) {
				wp_send_json( array( 'success' => 'Your account has been retrieved successfully.' ) );
			} else {
				mo_openid_show_success_message();
			}
		} else {
			if ( isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) == 'mo_register_old_user' : false ) {
				wp_send_json( array( 'error' => 'Invalid username or password. Please try again.' ) );
			} else {
				update_option( 'mo_openid_message', 'Invalid username or password. Please try again.' );
				mo_openid_show_error_message();
			}
		}
	}
	}
}

function mo_pop_show_verify_password_page() {
	update_option( 'regi_pop_up', 'yes' );
	?>
	<div id="request_registeration" class="mo_openid_modal" style="height:100%">

		<!-- Modal content -->
		<div class="mo_openid_modal-content" style="text-align:center;margin-left: 32%;margin-top: 2%;width: 37%;">
			<div class="modal-head">
				<a href="" class="mo_close" id="mo_close" style="text-decoration:none;margin-top: 3%;">&times;</a>
				<h1>Login</h1>
			</div>
			<br>
			<span style="text-align: center;color: #56b11e">
			<?php
			echo esc_attr( ( get_option( 'pop_login_msg' ) ) );
			update_option( 'pop_login_msg', 'Enter Your Login Credentials.' );
			?>
			</span>
			<br><br>
			<div id="mo_saml_show_registeration_modal" >

				<!--Register with miniOrange-->
				<form name="f" method="post" action="" id="pop-register-form">
					<input type="hidden" name="option" value="mo_openid_connect_verify_customer" />
					<input type="hidden" name="mo_openid_connect_verify_nonce"
						   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-connect-verify-nonce' ) ); ?>"/>
					<div>
						<table style="text-align: left;width: 100%">

							<tr>
								<td><b><font color="#FF0000">*</font>Email:</b></td>
								<td><input class="mo_openid_table_textbox" id="email" type="email" name="email"
										   required placeholder="person@example.com"
										   value="<?php echo esc_attr( get_option( 'mo_openid_admin_email' ) ); ?>" />
								</td>
							</tr>

							<tr>
								<td><b><font color="#FF0000">*</font>Password:</b></td>
								<td><input class="mo_openid_table_textbox" required type="password"
										   name="password" placeholder="Choose your password" /></td>
							</tr>
							<tr style="text-align: center">
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>

							</tr>
						</table>
						<input type="submit"  value="Login" id="login_submit" style="margin-left: -21%;margin-top: -1%;" class="button button-primary button-large" "/>
					</div>
				</form>
				&nbsp &nbsp &nbsp <br><form method="post" style="margin-top: -15.1%;margin-left: 46%;">
					<input type="submit" id="go_to_register" name="go_to_register" value="Register" class="button button-primary button-large" style="margin-left: -67%;margin-top: 7.8%;">
				</form>
				<p><b><a href="#forgot_password">Click here if you forgot your password?</a></b></p>
				<form name="forgotpassword" method="post" action="" id="openidforgotpasswordform">
					<input type="hidden" name="option" value="mo_openid_forgot_password"/>
					<input type="hidden" name="mo_openid_forgot_password_nonce"
						   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-forgot-password-nonce' ) ); ?>"/>
					<input type="hidden" id="forgot_pass_email" name="email" value=""/>
				</form>
			</div>

		</div>

	</div>

	<script>
		jQuery('a[href="#forgot_password"]').click(function(){
			jQuery('#forgot_pass_email').val(jQuery('#email').val());
			jQuery('#openidforgotpasswordform').submit();
		});
	</script>
	<?php
}

function mo_openid_share_action() {
	$nonce = sanitize_text_field( $_POST['mo_openid_share_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-share' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			$enable_id = sanitize_text_field( $_POST['enabled'] );

			if ( $enable_id == 'true' ) {

				update_option( sanitize_text_field( $_POST['id_name'] ), 1 );
			} elseif ( $enable_id == 'false' ) {

				update_option( sanitize_text_field( $_POST['id_name'] ), 0 );
			}
		}
	}
}

function mo_openid_restrict_user() {
	if ( ( get_option( 'mo_openid_admin_customer_key' ) > 151617 ) || ( get_option( 'mo_openid_new_user' ) == 1 ) || ! mo_openid_is_customer_registered() ) {

			return true;
	} else {
			return false;
	}
}

function mo_sharing_app_value() {
	$nonce = sanitize_text_field( $_POST['mo_openid_sharing_app_value_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-sharing-app-value-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if(current_user_can('administrator')){
		$return_apps = '';
		$count       = 1;
		$appname_arr = isset( $_POST['app_name'] ) ? array_map( 'sanitize_text_field', $_POST['app_name'] ) : '';
		foreach ( $appname_arr as $app ) {
			$app_value = get_option( 'mo_openid_' . $app . '_share_enable' );
			if ( $app_value ) {
				if ( $count == 1 ) {
					$return_apps = $app;
					$count++;
				} else {
					$return_apps .= '#' . $app;
				}
			}
		}
		wp_send_json( $return_apps );
	}
}
}

function update_custom_data( $user_id ) {  // phpcs:ignore 
	$set_cust_field = get_option( 'mo_openid_custom_field_mapping' );
	$cust_reg_val   = array();
	foreach ( $set_cust_field as $x ) {
		$count = 0;
		$a     = 1;
		$res   = '';
		foreach ( $x as $xx => $x_value ) {
			if ( $count == 0 ) {
				$predefine = $x_value;
			} elseif ( $count == 1 ) {
				$opt_val = $x_value;
			} elseif ( $count == 2 ) {
				$field = $x_value;
			} elseif ( $count == 3 ) {
				$type = $x_value;
			} elseif ( $count == 4 ) {
				$add_field = $x_value;
			}
			$count++;
		}

		if ( $opt_val === 'other' ) {
			if ( $predefine != '' ) {
				$field_update = $predefine;
			} else {
				$field_update = $field;
			}
		} else {
			if ( $predefine != '' ) {
				$field_update = $predefine;
				$field        = $predefine;
			} else {
				$field_update = $opt_val;
				$field        = $opt_val . '_update';
			}
		}
		if ( $type != 'checkbox' ) {
			// if(get_option('mo_customer_validation_mo_social_login_enable')=="1" && get_option('mo_openid_customised_field_enable') == "1" )
			// array_push($cust_reg_val,[$field_update=>$_POST[$field]]);
			// else
			if ( $type == 'dropdown' && sanitize_text_field( $_POST['user_role'] ) ) { // phpcs:ignore 
				$user = get_user_by( 'ID', $user_id );
				$user->set_role( strtolower( sanitize_text_field( $_POST['user_role'] ) ) );  // phpcs:ignore 
				update_user_meta( $user_id, $field_update, sanitize_text_field( $_POST['user_role'] ) ); 	// phpcs:ignore 
			} else {
				update_user_meta( $user_id, $field_update, sanitize_text_field( $_POST[ $field ] ) );	// phpcs:ignore 
			}
		} else {
			$flag    = 0;
			$str_res = explode( ';', $add_field );
			foreach ( $str_res as $value_genetared ) {
				if ( isset( $_POST[ $field . $a ] ) ) {   	// phpcs:ignore 
					if ( $flag != 0 ) {
						$res = $res . ';';
					}
					$res = $res . sanitize_text_field( $_POST[ $field . $a ] );		// phpcs:ignore 
					$flag++;
				}
				$a++;
			}
			// if(get_option('mo_customer_validation_mo_social_login_enable')=="1" && get_option('mo_openid_customised_field_enable') == "1" )
			// array_push($cust_reg_val,[$field_update=>$res]);
			// else
				update_user_meta( $user_id, $field_update, $res );
		}
	}
	return $cust_reg_val;
}

function mo_openid_activation_message() {
	$class   = 'updated';
	$message = get_option( 'mo_openid_message' );
	echo "<div class='" . esc_attr( $class ) . "'> <p>" . esc_attr( $message ) . '</p></div>';
}

function mo_openid_rating_given() {
	$nonce = sanitize_text_field( $_POST['mo_openid_rating_given_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-rating-given-nonce' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if(current_user_can('administrator')){
		update_option( 'mo_openid_rating_given', sanitize_text_field( $_POST['rating'] ) );
		}
	}
}


