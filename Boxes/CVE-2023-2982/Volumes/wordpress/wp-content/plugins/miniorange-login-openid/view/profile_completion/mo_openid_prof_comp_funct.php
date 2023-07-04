<?php

function mo_openid_save_profile_completion_form( $username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id ) {
	$allowed_html = array(
		'style' => array(),
		'head'  => array(),
		'meta'  => array(
			'name'    => array(),
			'content' => array(),
		),
		'link'  => array(
			'rel'   => array(),
			'href'  => array(),
			'type'  => array(),
			'media' => array(),
		),
		'body'  => array( 'class' => array() ),
		'div'   => array(
			'style' => array(),
			'class' => array(),
			'id'    => array(),
		),
		'form'  => array(
			'name'   => array(),
			'method' => array(),
			'action' => array(),
		),
		'span'  => array(
			'style' => array(),
			'class' => array(),
			'align' => array(),
		),
		'p'     => array(),
		'br'    => array(),
		'label' => array( 'for' => array() ),
		'input' => array(
			'type'  => array(),
			'class' => array(),
			'name'  => array(),
			'size'  => array(),
			'value' => array(),
		),
	);

    if(!isset($_POST['otp_field'])) {   // phpcs:ignore
		$user_email = sanitize_email( $user_email );
		$username   = preg_replace( '/[\x00-\x1F][\x7F][\x81][\x8D][\x8F][\x90][\x9D][\xA0][\xAD]/', '', $username );
		$username   = strtolower( str_replace( ' ', '', $username ) );
		global $wpdb;
		if ( empty( $user_email ) ) {
			$email_user_id = null;
		} else {
			$email_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_email = %s", $user_email ) );
		}
		$username_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_login = %s", $username ) );

		// if email exists, dont check if username is in db or not, send otp and get it over WordPress
		if ( isset( $email_user_id ) ) {
			$send_content = send_otp_token( $user_email );
			if ( $send_content['status'] == 'FAILURE' ) {
				$message = 'Error Code 1: ' . get_option( 'mo_email_failure_message' );
				wp_die( esc_attr( $message ) );
			}
			$transaction_id = $send_content['tId'];
			echo wp_kses( mo_openid_validate_otp_form( $username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name, $first_name, $decrypted_app_name, $decrypted_user_id ), $allowed_html );
			exit;
		}
		// email doesnt exist, check if username is in db or not, acc show form and proceed further
		else {

			if ( isset( $username_user_id ) ) {
				$user_details = array(
					'username'        => $username,
					'user_email'      => $user_email,
					'user_full_name'  => $user_full_name,
					'first_name'      => $first_name,
					'last_name'       => $last_name,
					'user_url'        => $user_url,
					'user_picture'    => $user_picture,
					'social_app_name' => $decrypted_app_name,
					'social_user_id'  => $decrypted_user_id,
				);
				echo esc_attr( mo_openid_profile_completion_form( $user_details, '0' ) );
				exit;
			} else {

				$send_content = send_otp_token( $user_email );
				if ( $send_content['status'] == 'FAILURE' ) {
					$message = 'Error Code 2: ' . get_option( 'mo_email_failure_message' );
					wp_die( esc_attr( $message ) );
				}
				$transaction_id = $send_content['tId'];
				echo wp_kses( mo_openid_validate_otp_form( $username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name, $first_name, $decrypted_app_name, $decrypted_user_id ), $allowed_html );
				exit;
			}
		}
	}
}

function mo_openid_validate_otp_form( $username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name, $first_name, $decrypted_app_name, $decrypted_user_id, $message = '' ) {
	$path  = mo_openid_get_wp_style();
	$nonce = wp_create_nonce( 'mo-openid-user-otp-validation-nonce' );
	if ( $message == '' ) {
		$message = get_option( 'mo_email_verify_message' );
	} else {
		$message = get_option( 'mo_email_verify_wrong_otp' );
	}

	$html = '<style>
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
                       
                        <div style="text-align:center"><span style="font-size: 24px;font-family: Arial">' . get_option( 'mo_email_verify_title' ) . '</span></div><br>
                        <div style="padding: 12px;"></div>
                        <div style=" padding: 16px;background-color:rgba(1, 145, 191, 0.117647);color: black;">
                        <span style=" margin-left: 15px;color: black;font-weight: bold;float: right;font-size: 22px;line-height: 20px;cursor: pointer;font-family: Arial;transition: 0.3s"></span>' . $message . '</div>	<br>					
                        <p>
                        <label for="user_login">' . esc_html( get_option( 'mo_email_verify_verification_code_instruction' ) ) . '<br/>
                        <input type="text" pattern="\d{4,5}" class="input" name="otp_field" value=""  size="50" /></label>
                        </p>
                        <input type="hidden" name="username_field" value=' . esc_attr( $username ) . '>
                        <input type="hidden" name="email_field" value=' . esc_attr( $user_email ) . '>						
                        <input type="hidden" name="first_name" value=' . esc_attr( $first_name ) . '>
                        <input type="hidden" name="last_name" value=' . esc_attr( $last_name ) . '>
                        <input type="hidden" name="user_full_name" value=' . esc_attr( $user_full_name ) . '>
                        <input type="hidden" name="user_url" value=' . esc_url( $user_url ) . '>
                        <input type="hidden" name="user_picture" value=' . esc_url( $user_picture ) . '>
                        <input type="hidden" name="transaction_id" value=' . esc_attr( $transaction_id ) . '>
                        <input type="hidden" name="decrypted_app_name" value=' . esc_attr( $decrypted_app_name ) . '>
                        <input type="hidden" name="decrypted_user_id" value=' . esc_attr( $decrypted_user_id ) . '>				
                        <input type="hidden" name="option" value="mo_openid_otp_validation">
                        <input type="hidden" name="mo_openid_user_otp_validation_nonce" value="' . $nonce . '"/>
                        </div>
                        <div style="float: right;margin-right: 11px;">  
                        <input type="submit" style="margin-left:10px"  name="wp-submit" id="wp-submit" class="button button-primary button-large" value="' . get_option( 'mo_profile_complete_submit_button' ) . '"/>

                        </div>
                        <div style="float: right">
                        <input type="button"  style="margin-left:10px" onclick="show_profile_completion_form()" name="otp_back" id="back" class="button button-primary button-large" value="' . get_option( 'mo_email_verify_back_button' ) . '"/>&emsp;
                        <input type="submit" name="resend_otp" id="resend_otp" class="button button-primary button-large" value="' . get_option( 'mo_email_verify_resend_otp_button' ) . '"/>
                        </div>';

	if ( get_option( 'moopenid_logo_check_prof' ) == 1 ) {
		$html .= mo_openid_customize_logo();
	}
	$nonce = wp_create_nonce( 'mo-openid-user-show-profile-form-nonce' );
	$html .= '</form>
                    <form style="display:none;" name="go_back_form" id="go_back_form" method="post">
                    <input hidden name="option" value="mo_openid_show_profile_form"/>
                    <input type="hidden" name="mo_openid_show_profile_form_nonce" value="' . $nonce . '"/>
                    <input type="hidden" name="username_field" value=' . esc_attr( $username ) . '>
                    <input type="hidden" name="email_field" value=' . esc_attr( $user_email ) . '>						
                    <input type="hidden" name="first_name" value=' . esc_attr( $first_name ) . '>
                    <input type="hidden" name="last_name" value=' . esc_attr( $last_name ) . '>
                    <input type="hidden" name="user_full_name" value=' . esc_attr( $user_full_name ) . '>
                    <input type="hidden" name="user_url" value=' . esc_url( $user_url ) . '>
                    <input type="hidden" name="user_picture" value=' . esc_url( $user_picture ) . '>
                    <input type="hidden" name="transaction_id" value=' . esc_attr( $transaction_id ) . '>
                    <input type="hidden" name="decrypted_app_name" value=' . esc_attr( $decrypted_app_name ) . '>
                    <input type="hidden" name="decrypted_user_id" value=' . esc_attr( $decrypted_user_id ) . '>
                    </form>
                    </div>
                    </div>
                    </body>
                    <script>
                    function show_profile_completion_form(){
                        document.getElementById("go_back_form").submit(); 
                    }     
                    </script>';
	return $html;
}

function send_otp_token( $email ) {
	$otp           = wp_rand( 1000, 99999 );
	$customerKey   = get_option( 'mo_openid_admin_customer_key' );
	$stringToHash  = $customerKey . $otp;
	$transactionId = hash( 'sha512', $stringToHash );
	// wp_email function will come here
	$subject = '[' . get_bloginfo( 'name' ) . '] Verify your email';

	$message = str_replace( '##otp##', $otp, get_option( 'custom_otp_msg' ) );

	$response = wp_mail( $email, $subject, $message );

	if ( $response ) {
		mo_openid_start_session();
		$_SESSION['mo_otptoken'] = true;
		$_SESSION['sent_on']     = time();
		$content                 = array(
			'status' => 'SUCCESS',
			'tId'    => $transactionId,
		);
	} else {
		$content = array( 'status' => 'FAILURE' );
	}
	return $content;
}

function validate_otp_token( $transactionId, $otpToken ) {
	mo_openid_start_session();
	$customerKey = get_option( 'mo_openid_admin_customer_key' );
	if ( $_SESSION['mo_otptoken'] ) {
		$pass = checkTimeStamp( ( $_SESSION['sent_on'] ), time() );
		$pass = checkTransactionId( $customerKey, $otpToken, $transactionId, $pass );
		if ( $pass ) {
			$content = array( 'status' => 'SUCCESS' );
		} else {
			$content = array( 'status' => 'FAILURE' );
		}
		unset( $_SESSION['$mo_otptoken'] );
	} else {
		$content = array( 'status' => 'FAILURE' );
	}

	return $content;
}

function mo_openid_social_login_validate_otp( $username, $user_email, $first_name, $last_name, $user_full_name, $user_url, $user_picture, $decrypted_app_name, $decrypted_user_id, $otp_token, $transaction_id ) {

	$validate_content = validate_otp_token( $transaction_id, $otp_token );
	$status           = $validate_content['status'];
	// if invalid OTP
	if ( $status == 'FAILURE' ) {
		$message = 'You have entered an invalid verification code. Enter a valid code.';

		$allowed_html = array(
			'style' => array(),
			'head'  => array(),
			'meta'  => array(
				'name'    => array(),
				'content' => array(),
			),
			'link'  => array(
				'rel'   => array(),
				'href'  => array(),
				'type'  => array(),
				'media' => array(),
			),
			'body'  => array( 'class' => array() ),
			'div'   => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'form'  => array(
				'name'   => array(),
				'method' => array(),
				'action' => array(),
			),
			'span'  => array(
				'style' => array(),
				'class' => array(),
				'align' => array(),
			),
			'p'     => array(),
			'br'    => array(),
			'label' => array( 'for' => array() ),
			'input' => array(
				'type'  => array(),
				'class' => array(),
				'name'  => array(),
				'size'  => array(),
				'value' => array(),
			),
		);

		echo wp_kses( mo_openid_validate_otp_form( $username, $user_email, $transaction_id, $user_picture, $user_url, $last_name, $user_full_name, $first_name, $decrypted_app_name, $decrypted_user_id, $message ), $allowed_html );
		exit;

	}
	// if OTP is Valid
	else {
		$user_details = array(
			'username'        => $username,
			'user_email'      => $user_email,
			'user_full_name'  => $user_full_name,
			'first_name'      => $first_name,
			'last_name'       => $last_name,
			'user_url'        => $user_url,
			'user_picture'    => $user_picture,
			'social_app_name' => $decrypted_app_name,
			'social_user_id'  => $decrypted_user_id,
		);
		// check_existing_user
		global $wpdb;
		$email_user_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'mo_openid_linked_user where linked_email = %s', $user_email ) );
		if ( empty( $user_email ) ) {
			$existing_email_user_id = null;
		} else {
			$existing_email_user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users where user_email = %s", $user_email ) );
		}
		if ( ( isset( $email_user_id ) ) || ( isset( $existing_email_user_id ) ) ) {

			if ( isset( $email_user_id ) ) {
				$user    = get_user_by( 'id', $email_user_id );
				$user_id = $user->ID;
			} else {
				$user    = get_user_by( 'id', $existing_email_user_id );
				$user_id = $user->ID;
			}
			mo_openid_start_session_login( $user_details );
			mo_openid_login_user( $user_email, $user_id, $user, $user_picture, 1 );
			exit;
		} else {
			mo_create_new_user( $user_details );
		}
	}
}

function mo_openid_profile_comp_action() {

	$nonce = sanitize_text_field( $_POST['mo_openid_profile_comp_nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mo-openid-profile-comp' ) ) {
		wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
	} else {
		if ( current_user_can( 'administrator' ) ) {
			if ( sanitize_text_field( $_POST['enabled'] ) == 'true' ) {
				update_option( 'mo_openid_enable_profile_completion', 1 );

			} else {
				update_option( 'mo_openid_enable_profile_completion', 0 );
			}
		}
	}
}
