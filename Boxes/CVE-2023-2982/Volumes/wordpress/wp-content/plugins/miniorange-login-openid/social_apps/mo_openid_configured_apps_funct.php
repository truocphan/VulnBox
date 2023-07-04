<?php
function mo_openid_validate_code() {     if (isset($_REQUEST['code'])) {     // phpcs:ignore
        $code = sanitize_text_field($_REQUEST['code']);     // phpcs:ignore
		return $code;
} // this handles incorrect scope(only for linkedin).
    else if (array_key_exists('error', $_REQUEST)) {    //phpcs:ignore
	if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
		update_option( 'mo_openid_test_configuration', 0 );
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
						<div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Please enter correct scope value and try again. <br/>';
		print_r(esc_attr($_REQUEST));   //phpcs:ignore
		echo '</div>
						<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '/includes/images/wrong.png"></div>';
		exit;
	} else {
		echo esc_attr(sanitize_text_field($_REQUEST['error_description'])) . "<br>";        // phpcs:ignore
		wp_die( 'Allow access to your profile to get logged in. Click <a href=' . esc_url( get_site_url() ) . '>here</a> to go back to the website.' );
		exit;
	}
}
}

function mo_get_permalink( $appname ) {
	$value      = get_option( 'siteurl' );
	$apps2_list = array( 'line', 'dribbble', 'discord', 'stack', 'flickr', 'apple', 'tumblr', 'spotify', 'meetup' );
	$appsConfig = 0;
	foreach ( $apps2_list as $key ) {
		if ( $appname == $key ) {
			$appsConfig = 1;
			break;
		}
	}
	if ( get_option( 'mo_openid_malform_error' ) || $appsConfig == '1' ) {
		if ( get_option( 'permalink_structure' ) ) {
			return $value . '/openidcallback/' . $appname;
		} elseif ( $appname == 'linkedin' ) {
			return $value . '/?openidcallback/' . $appname;
		}
		if ( $appname == 'vkontakte' ) {
			return $value;
		} else {
			return $value . '/?openidcallback=' . $appname;
		}
	} else {
		if ( get_option( 'permalink_structure' ) ) {
			return $value . '/openidcallback';
		} else {
			return $value . '/?openidcallback';
		}
	}
}

function get_social_app_redirect_uri( $appname ) {
	$apps2_list = array( 'line', 'dribbble', 'discord', 'stack', 'flickr', 'apple', 'tumblr', 'spotify', 'meetup' );
	$appsConfig = 0;
	foreach ( $apps2_list as $key ) {
		if ( $appname == $key ) {
			$appsConfig = 1;
			break;
		}
	}
	if ( get_option( 'mo_openid_malform_error' ) || $appsConfig == '1' ) {
		if ( get_option( 'permalink_structure' ) ) {
			$social_app_redirect_uri = site_url() . '/openidcallback/' . $appname;
		} else {
			if ( $appname == 'vkontakte' ) {
				$social_app_redirect_uri = site_url();
			} else {
				$social_app_redirect_uri = site_url() . '/?openidcallback=' . $appname;
			}
			if ( $appname == 'linkedin' ) {
				$social_app_redirect_uri = site_url() . '/?openidcallback/' . $appname;
			}
		}
	} else {
		if ( get_option( 'permalink_structure' ) ) {
			$social_app_redirect_uri = site_url() . '/openidcallback';
		} else {
			if ( $appname == 'vkontakte' ) {
				$social_app_redirect_uri = site_url();
			} else {
				$social_app_redirect_uri = site_url() . '/?openidcallback';
			}
		}
	}
	return $social_app_redirect_uri;
}
function mo_openid_get_access_token( $postData, $access_token_uri, $appname ) {
	$headers = '';
	if ( $appname == 'google' || $appname == 'spotify' || $appname == 'apple' ) {
		$headers = array( 'Content-Type' => 'application/x-www-form-urlencoded' );
	}
	$args   = array(
		'method'      => 'POST',
		'body'        => $postData,
		'timeout'     => '5',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$result = wp_remote_post( $access_token_uri, $args );
	if ( is_wp_error( $result ) ) {
		update_option( 'mo_openid_test_configuration', 0 );
		echo esc_attr( $result['body'] );
		exit();
	}
	$access_token_json_output = json_decode( $result['body'], true );
	// this handles incorrect client secret for all apps.
	if ( ( array_key_exists( 'error', $access_token_json_output ) ) || array_key_exists( 'error_message', $access_token_json_output ) ) {
		if ( is_user_logged_in() && get_option( 'mo_openid_test_configuration' ) == 1 ) {
			update_option( 'mo_openid_test_configuration', 0 );
			// Test configuration failed window.

			echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Client secret is incorrect for this app. Please check the client secret and try again.<br/>';
			print_r( $access_token_json_output );
			echo '</div>
                    <div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="' . esc_url( plugin_dir_url( __FILE__ ) . '/includes/images/wrong.png' ) . '"></div>';
			exit;
		}
	}
	return $access_token_json_output;
}

function mo_openid_get_social_app_data( $access_token, $profile_url, $app_name ) {
	$headers = '';
	if ( $app_name != 'spotify' && $app_name != 'meetup' ) {
		$headers = ( 'Authorization:Bearer ' . $access_token );
	}
	$args = array(
		'timeout'     => 120,
		'redirection' => 5,
		'httpversion' => '1.1',
		'headers'     => $headers,
	);

	$result = wp_remote_get( $profile_url, $args );
	// echo $result['body'];
	if ( is_wp_error( $result ) ) {
		update_option( 'mo_openid_test_configuration', 0 );
		echo esc_attr( $result['body'] );
		exit();
	}
	$profile_json_output = json_decode( $result['body'], true );
	return $profile_json_output;
}

function mo_openid_app_test_config( $profile_json_output ) {
	update_option( 'mo_openid_test_configuration', 0 );
	$print        = '<div style="color: #3c763d;
        background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
        <div style="display:block;text-align:center;margin-bottom:1%;"><img style="width:15%;" src="' . esc_url( plugin_dir_url( dirname( __FILE__ ) ) . '/includes/images/green_check.png' ) . '"></div>';
	$print       .= mo_openid_json_to_htmltable( $profile_json_output );
	$allowed_html = array(
		'div'    => array( 'style' => array() ),
		'img'    => array(
			'style' => array(),
			'src'   => array(),
		),
		'table'  => array( 'border' => array() ),
		'tbody'  => array(),
		'tr'     => array(),
		'td'     => array(),
		'strong' => array(),
	);
	echo wp_kses( $print, $allowed_html );
	exit;
}

function mo_openid_json_to_htmltable( $arr ) {
	$str = "<table border='1'><tbody>";
	foreach ( $arr as $key => $val ) {
		$str .= '<tr>';
		$str .= "<td>$key</td>";
		$str .= '<td>';
		if ( is_array( $val ) ) {
			if ( ! empty( $val ) ) {
				$str .= mo_openid_json_to_htmltable( $val );
			}
		} else {
			$str .= "<strong>$val</strong>";
		}
		$str .= '</td></tr>';
	}
	$str .= '</tbody></table>';

	return $str;
}

function mo_openid_get_wp_style() {
	$path  = site_url();
	$path .= '/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,buttons,forms,l10n,login&amp;ver=4.8.1';
	return $path;
}

function mo_openid_insert_query( $social_app_name, $user_email, $userid, $social_app_identifier ) {
	// check if none of the column values are empty
	if ( ! empty( $social_app_name ) && ! empty( $user_email ) && ! empty( $userid ) && ! empty( $social_app_identifier ) ) {
		date_default_timezone_set( 'Asia/Kolkata' );
		$date = date( 'Y-m-d H:i:s' );

		global $wpdb;
		$db_prefix  = $wpdb->prefix;
		$table_name = $db_prefix . 'mo_openid_linked_user';

		$result = $wpdb->insert(
			$table_name,
			array(
				'linked_social_app' => $social_app_name,
				'linked_email'      => $user_email,
				'user_id'           => $userid,
				'identifier'        => $social_app_identifier,
				'timestamp'         => $date,
			),
			array(
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
			)
		);
		if ( $result === false ) {
			/*
							$wpdb->show_errors();
							$wpdb->print_error();
							exit;*/
			wp_die( 'Error in insert query' );
		}
	}
}

function mo_openid_start_session_login( $session_values ) {
	mo_openid_start_session();
	$_SESSION['mo_login']        = true;
	$_SESSION['registered_user'] = '1';
	$_SESSION['username']        = isset( $session_values['username'] ) ? $session_values['username'] : '';
	$_SESSION['user_email']      = isset( $session_values['user_email'] ) ? $session_values['user_email'] : '';
	$_SESSION['user_full_name']  = isset( $session_values['user_full_name'] ) ? $session_values['user_full_name'] : '';
	$_SESSION['first_name']      = isset( $session_values['first_name'] ) ? $session_values['first_name'] : '';
	$_SESSION['last_name']       = isset( $session_values['last_name'] ) ? $session_values['last_name'] : '';
	$_SESSION['user_url']        = isset( $session_values['user_url'] ) ? $session_values['user_url'] : '';
	$_SESSION['user_picture']    = isset( $session_values['user_picture'] ) ? $session_values['user_picture'] : '';
	$_SESSION['social_app_name'] = isset( $session_values['social_app_name'] ) ? $session_values['social_app_name'] : '';
	$_SESSION['social_user_id']  = isset( $session_values['social_user_id'] ) ? $session_values['social_user_id'] : '';
}
function mo_openid_login_user( $linked_email_id, $user_id, $user, $user_picture, $user_mod_msg ) {
	if ( get_option( 'moopenid_social_login_avatar' ) && isset( $user_picture ) ) {
		update_user_meta( $user_id, 'moopenid_user_avatar', $user_picture );
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( 'miniorange-login-openid-discord-addon/mo_openid_discord_integration.php' ) ) {
		mo_discord_login( $user_id );
		$discord_status = get_user_meta( $user_id, 'discord_status' );
		if ( $discord_status[0] == 'false' ) {
			?>
			<p class="mo_openid_note_style">You do not have access to this page kindly connect with discord server.</p>
			<?php
			exit;
		}
	}
	// comment by shresth as no add_action is found against this hook
	// do_action('miniorange_collect_attributes_for_authenticated_user', $user, mo_openid_get_redirect_url());
	do_action( 'wp_login', $user->user_login, $user );
	exit;
}

// delete rows from account linking table that correspond to deleted user
function mo_openid_delete_account_linking_rows( $user_id ) {
	global $wpdb;
	$result = $wpdb->get_var( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'mo_openid_linked_user where user_id = %s ', $user_id ) );
	if ( $result === false ) {
		/*
		$wpdb->show_errors();
		$wpdb->print_error();
		exit;*/
		wp_die( esc_attr( get_option( 'mo_delete_user_error_message' ) ) );
	}
}

function mo_openid_profile_completion_form( $user_val, $existing_uname = '1' ) {

	$decrypted_user_name = $user_val['username'];
	$decrypted_email     = $user_val['user_email'];
	$user_full_name      = $user_val['user_full_name'];
	$first_name          = $user_val['first_name'];
	$last_name           = $user_val['last_name'];
	$user_url            = $user_val['user_url'];
	$user_picture        = $user_val['user_picture'];
	$decrypted_app_name  = $user_val['social_app_name'];
	$decrypted_user_id   = $user_val['social_user_id'];

	$path = mo_openid_get_wp_style();
	if ( $existing_uname == '1' ) {
		$instruction_msg   = esc_html( get_option( 'mo_profile_complete_instruction' ) );
		$extra_instruction = esc_html( get_option( 'mo_profile_complete_extra_instruction' ) );
	} else {
		$instruction_msg   = esc_html( get_option( 'mo_profile_complete_uname_exist' ) );
		$extra_instruction = '';
	}
	$nonce = wp_create_nonce( 'mo-openid-profile-form-submitted-nonce' );
	$html  = '<style>.form-input-validation.note {color: #d94f4f;}</style>
	    		<style>
                        .mocomp {
                                     margin: auto !important;
                                 }
                        @media only screen and (max-width: 600px) {
                          .mocomp {width: 90%;}
                        }
                        @media only screen and (min-width: 600px) {
                          .mocomp {width: 500px;}
                        }
                </style>
                <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
					<link rel="stylesheet" href=' . $path . ' type="text/css" media="all" /></head>     
                    <body class="login login-action-login wp-core-ui  locale-en-us">
                    <div style="position:fixed;background:#f1f1f1;"></div>
                    <div id="add_field" style="position:fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1;padding-top:2%;">
                    <div class="mocomp">   
                    <form name="f" method="post" action="">
                    <div style="background: white;margin-top:-15px;padding: 15px;">
                   
                   <div style="text-align:center"><span style="font-size: 24px;font-family: Arial">' . esc_html( get_option( 'mo_profile_complete_title' ) ) . '</span></div><br>
                    <div style="padding: 12px;"></div>
                    <div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
                    <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>' . $instruction_msg . '</div><br>					
                    <p>
                    <label for="user_login">' . esc_html( get_option( 'mo_profile_complete_username_label' ) ) . '<br/>
                    <input type="text" class="input" name="username_field"  size="50" required value=' . esc_attr( $decrypted_user_name ) . '></label>
                    </p>
                    <p>
                    <label for="user_pass">' . esc_html( get_option( 'mo_profile_complete_email_label' ) ) . '<br/>
                    <input type="email"  class="input" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]+$" name="email_field"  size="50"  required value=' . esc_attr( $decrypted_email ) . '></label>
                    <span align="center" class="form-input-validation note">' . $extra_instruction . '</span>
                    </p>
                    <input type="hidden" name="first_name" value=' . esc_attr( $first_name ) . '>
                    <input type="hidden" name="last_name" value=' . esc_attr( $last_name ) . '>
                    <input type="hidden" name="user_full_name" value=' . esc_attr( $user_full_name ) . '>
                    <input type="hidden" name="user_url" value=' . esc_url( $user_url ) . '>
                    <input type="hidden" name="user_picture" value=' . esc_url( $user_picture ) . '>
                    <input type="hidden" name="decrypted_app_name" value=' . esc_attr( $decrypted_app_name ) . '>
                    <input type="hidden" name="decrypted_user_id" value=' . esc_attr( $decrypted_user_id ) . '>
                    <input type="hidden" name="option" value="mo_openid_profile_form_submitted">
                    <input type="hidden" name="mo_openid_profile_form_submitted_nonce" value="' . $nonce . '"/>
                    </div>
                    <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="' . get_option( 'mo_profile_complete_submit_button' ) . '"/>
                    </p> ';

	if ( get_option( 'moopenid_logo_check_prof' ) == 1 ) {
		$html .= mo_openid_customize_logo();
	}

	$html .= '</form>
                    </div>
                    </div>
                    </body>';
	return $html;
}
