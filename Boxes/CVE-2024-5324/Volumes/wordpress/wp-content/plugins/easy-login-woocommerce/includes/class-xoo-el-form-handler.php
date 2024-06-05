<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Xoo_El_Form_Handler{

	public static $glSettings;

	public static function init(){
		self::$glSettings = xoo_el_helper()->get_general_option();
		self::hooks();
	}

	public static function hooks(){

		add_action( 'wp_ajax_xoo_el_form_action', array( __CLASS__, 'form_action' ) );
		add_action( 'wp_ajax_nopriv_xoo_el_form_action', array( __CLASS__, 'form_action' ) );

		if( self::$glSettings['m-reset-pw'] === "yes" ){
			add_action( 'template_redirect', array( __CLASS__, 'redirect_reset_password_link' ) );
			add_action( 'template_redirect', array( __CLASS__, 'remove_query_args' ) );
			add_filter( 'wp_new_user_notification_email', array( __CLASS__, 'newuser_notification_email' ), 20, 3 );
			add_filter( 'lostpassword_url', array( __CLASS__, 'lost_password_url' ), 20, 1 );
			add_filter( 'woocommerce_get_endpoint_url', array( __CLASS__, 'edit_wc_lost_password_url' ), 20, 4 );
		}
	}


	//Process form
	public static function form_action(){

		if( !isset( $_POST['_xoo_el_form'] ) ) return;

		$form_action = sanitize_text_field( $_POST['_xoo_el_form'] );

		switch ($form_action) {
			case 'login':
				$action = Xoo_El_Form_Handler::process_login();
				break;

			case 'register':
				$action = Xoo_El_Form_Handler::process_registration();
				break;

			case 'lostPassword':
				$action = Xoo_El_Form_Handler::process_lost_password();
				break;

			case 'resetPassword':
				$action = Xoo_El_Form_Handler::process_reset_password();
				break;

			case 'single':
				$action = Xoo_El_Form_Handler::process_single_form();
				break;

			default:
				$action = array(
					'error' 	=> 0,
					'notice' 	=> xoo_el_add_notice( 'Form type not found', 'error' )
				);
		}

		wp_send_json( $action );
		
		die();

	}


	public static function process_single_form(){

		if ( !isset($_POST['_xoo_el_form']) ||  $_POST['_xoo_el_form'] !== 'single'  ) return;

		try {

			$userInput 				= sanitize_user( trim( $_POST['xoo-el-sing-user'] ) );
			$allowUsernameSearch	= xoo_el()->aff->fields->get_field_data('xoo-el-sing-user')['settings']['xoo_el_username'] === 'yes';

			$validation_error = apply_filters( 'xoo_el_process_single_errors', new WP_Error(), $userInput );

			if ( $validation_error->get_error_code() ) {
				throw new Xoo_Exception( '<strong>' . __( 'Error:', 'easy-login-woocommerce' ) . '</strong> ' . $validation_error->get_error_message() );
			}

			if ( empty( $userInput) ) {
				throw new Xoo_Exception( '<strong>' . __( 'Error:', 'easy-login-woocommerce' ) . '</strong> ' . __( 'Username is required.', 'easy-login-woocommerce' ) );
			}

			if( !$allowUsernameSearch && !is_email( $userInput ) ){
				throw new Xoo_Exception( __( 'Please enter a valid email address.', 'easy-login-woocommerce' ) );
			}

			$user 					= get_user_by( 'email', $userInput );
			$fieldValue 			= '';

			if( !$user && $allowUsernameSearch ){
				$user 				= get_user_by( 'login', $userInput );
				$fieldValue 		= sanitize_user( $userInput ); //
			}

			$user = apply_filters( 'xoo_el_single_user_found', $user, $userInput  );

			$returnData = array();
			$notice 	= '';
			$error 		= 0;

			if( $user ){
				$fieldValue 			= $fieldValue ? $fieldValue : esc_html( $user->user_email );
				$notice 				= xoo_el_add_notice(  'warning', __( 'Please enter your password to login', 'easy-login-woocommerce' ) );
				$field					= 'input[name="xoo-el-username"]';
			}
			else{
				if( !isset( $_POST['_xoo_el_login_has_register'] ) || !is_email( $userInput ) ){
					$notice = xoo_el_add_notice( 'error', __( "We couldn't find your account.", 'easy-login-woocommerce' ) );
					$error 	= 1;
				}
				else{
					$field 					= 'input[name="xoo_el_reg_email"]';
					$notice 				= xoo_el_add_notice(  'warning', isset( $_POST['_xoo_el_referral'] ) && $_POST['_xoo_el_referral'] === 'register' ? __( 'Please enter your details to register.', 'easy-login-woocommerce' ) :  __( "We couldn't find your account. Please register.", 'easy-login-woocommerce' ) );
				}
			}

			if( isset( $field ) ){
				$returnData['field'] = $field;
			}

			$returnData['notice'] 			= $notice;
			$returnData['fieldValue'] 		= $fieldValue;
			$returnData['error'] 			= $error;

			$returnData = apply_filters( 'xoo_el_single_return_data', $returnData, $user, $userInput );

			return $returnData;
			
		} catch ( Xoo_Exception $e ) {


			$error = apply_filters( 'xoo_el_single_errors', $e->getMessage(), $e );

			do_action( 'xo_el_single_failed', $e );

			return array(
				'error'			=> 1,
				'error_code' 	=> $e->getWpErrorCode(),
				'notice' 		=> xoo_el_add_notice('error', $error, $e->getWpErrorCode()),
			);

		}
	}


	//Process Login
	public static function process_login(){

		if ( !isset($_POST['_xoo_el_form']) ||  $_POST['_xoo_el_form'] !== 'login'  ) return;

				
		try {

			$creds = array(
				'user_login'    => sanitize_user( trim( $_POST['xoo-el-username'] ) ),
				'user_password' => $_POST['xoo-el-password'],
				'remember'      => isset( $_POST['xoo-el-rememberme'] ),
			);

			$validation_error = apply_filters( 'xoo_el_process_login_errors', new WP_Error(), $creds );

			if ( $validation_error->get_error_code() ) {
				throw new Xoo_Exception( '<strong>' . __( 'Error:', 'easy-login-woocommerce' ) . '</strong> ' . $validation_error->get_error_message() );
			}

			if ( empty( $creds['user_login'] ) ) {
				throw new Xoo_Exception( '<strong>' . __( 'Error:', 'easy-login-woocommerce' ) . '</strong> ' . __( 'Username is required.', 'easy-login-woocommerce' ) );
			}

			// On multisite, ensure user exists on current site, if not add them before allowing login.
			if ( is_multisite() ) {
				$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

				if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
					add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
				}
			}

			// Perform the login
			$user = wp_signon( apply_filters( 'xoo_el_login_credentials', $creds ), is_ssl() );

			if ( is_wp_error( $user ) ) {

				$message_code = $user->get_error_code();

				$lost_pw_text = ' <a class="xoo-el-lostpw-tgr">' .
					__( 'Lost your password?','easy-login-woocommerce' ).
					'</a>';

				$show_lostpw_on_errors = array(
					'incorrect_password', 'invalid_username', 'invalid_email'
				);

				if( in_array( $message_code, $show_lostpw_on_errors ) ){
					if( isset( $_POST['_xoo_el_login_has_single'] ) && $message_code === 'incorrect_password' ){
						$message = __( 'Wrong password.', 'easy-login-woocommerce' ).$lost_pw_text;
					}
					else{
						$message  = __( 'Incorrect Email or Username.','easy-login-woocommerce' ).$lost_pw_text;
					}
				}
				else{
					$message = $user->get_error_message();
					$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', $message );
				}
				throw new Xoo_Exception( $message );

			} else {

				if ( ! empty( $_POST['xoo_el_redirect'] ) ) {
					$redirect = sanitize_url( $_POST['xoo_el_redirect'] );
				}

				$redirect = apply_filters( 'xoo_el_login_redirect', wp_validate_redirect( $redirect ), $user );

				$success_notice = apply_filters( 'xoo_el_login_success_notice', '<i class="fa fa-check-circle" aria-hidden="true"></i> '.__( 'Login successful', 'easy-login-woocommerce' ), $user );

				do_action( 'xoo_el_login_success', $user );

				return array(
					'error' 	=> 0,
					'notice' 	=> xoo_el_add_notice('success', $success_notice ),
					'redirect' 	=> $redirect
				);

			}
		} catch ( Xoo_Exception $e ) {


			$error = apply_filters( 'xoo_el_login_errors', $e->getMessage(), $e );

			do_action( 'xo_el_login_failed', $e );

			return array(
				'error'			=> 1,
				'error_code' 	=> $e->getWpErrorCode(),
				'notice' 	=> xoo_el_add_notice('error', $error, $e->getWpErrorCode()),
			);

		}

	}


	/**
	 * Process the registration form.
	 */
	public static function process_registration() {

		if ( isset($_POST['_xoo_el_form']) &&  $_POST['_xoo_el_form'] === 'register' ) {
			$username 		= isset( $_POST['xoo_el_reg_username'] ) ? sanitize_user( $_POST['xoo_el_reg_username'] ) : '';
			$password 		= isset( $_POST['xoo_el_reg_pass'] ) ? $_POST['xoo_el_reg_pass'] : '';
			$email    		= isset( $_POST['xoo_el_reg_email'] ) ? sanitize_email( $_POST['xoo_el_reg_email'] ) : '';
			$reg_extra_data = array();

			try {

				$validation_error = apply_filters( 'xoo_el_process_registration_errors', new WP_Error(), $username, $password, $email );

				if ( $validation_error->get_error_code() ) {
					throw new Xoo_Exception( $validation_error );
				}

				$reg_admin_fields = xoo_el()->aff->fields->get_fields_data();

				$doNotValidateOtherFields = array_keys( array_diff_key( $reg_admin_fields, xoo_el_fields()->get_fields('register') ) );

				$fieldValues = xoo_el()->aff->fields->validate_submitted_field_values( $_POST, $doNotValidateOtherFields );

				if( is_wp_error( $fieldValues ) ){
					$message = '';
					if( count( $fieldValues->get_error_messages() ) > 1 ){
						foreach ( $fieldValues->get_error_messages() as $error_message ) {
							$message .= '<p>'.$error_message.'</p>';
						}
					}
					else{
						$message = $fieldValues;
					}

					throw new Xoo_Exception( $message );
				}

				foreach ( $fieldValues as $field_id => $field_value ) {

					//Add extra data for custom fields
					if( in_array( $field_id , xoo_el_fields()->skipFields ) ) continue;

					$reg_extra_data[ $field_id ] = $field_value;
					
				}

				
				// Handle password.
				if ( empty( $password ) && $reg_admin_fields['xoo_el_reg_pass']['settings']['active'] === "yes" ) {
					throw new Xoo_Exception( __( 'Please enter an account password.', 'easy-login-woocommerce' ) );
				}

				if( $reg_admin_fields['xoo_el_reg_pass']['settings']['active'] === "yes" && $reg_admin_fields['xoo_el_reg_pass_again']['settings']['active'] === "yes" && $password !== $_POST['xoo_el_reg_pass_again'] ){
					throw new Xoo_Exception( __("Passwords don't match","easy-login-woocommerce") );
				}

				if( isset( $_POST['xoo_el_reg_fname'] ) ){
					$reg_extra_data['first_name'] 	= sanitize_text_field( $_POST['xoo_el_reg_fname'] );
				}

				if( isset( $_POST['xoo_el_reg_lname'] ) ){
					$reg_extra_data['last_name'] 	= sanitize_text_field( $_POST['xoo_el_reg_lname'] );
				}
				
				if( $reg_admin_fields['xoo_el_reg_username']['settings']['active'] !== "yes" ){
					$username = '';
				}

				$new_customer = self::create_customer( $email, $username, $password, $reg_extra_data );


				if ( is_wp_error( $new_customer ) ) {
					throw new Xoo_Exception( $new_customer );
				}


				if( self::$glSettings['m-auto-login'] === "yes" ){

					if( class_exists( 'woocommerce' ) ){
						wc_set_customer_auth_cookie( $new_customer );
					}
					else{
						wp_set_auth_cookie( $new_customer, true );
					}

					$redirect = true;
					$success_notice = __( 'Registration successful', 'easy-login-woocommerce' );

				}
				else{
					$redirect = false;
					$success_notice = __( 'Registration successful. Please <a class="xoo-el-login-tgr">Login</a>', 'easy-login-woocommerce' );
				}


				if( class_exists( 'woocommerce' ) && empty( $password ) ){
					$success_notice = $email ? __( 'Your account was created successfully and a password has been sent to your email address', 'easy-login-woocommerce' ) : __( 'Your account was created successfully.', 'easy-login-woocommerce' );
					$redirect = false;
				}
			

				$success_notice = '<i class="fa fa-check-circle" aria-hidden="true"></i> '. $success_notice;

				if ( ! empty( $_POST['xoo_el_redirect'] ) && $redirect ) {
					$redirect = wp_sanitize_redirect( $_POST['xoo_el_redirect'] );
				}

				$redirect = apply_filters( 'xoo_el_registration_redirect', wp_validate_redirect( $redirect ), $new_customer );

				$success_notice = apply_filters( 'xoo_el_registration_success_notice', $success_notice, $new_customer );

				do_action( 'xoo_el_registration_success', $new_customer );

				return array(
					'error' => 0,
					'notice' => xoo_el_add_notice('success', $success_notice),
					'redirect' => $redirect
				);

			} catch ( Xoo_Exception $e ) {

				$message = apply_filters( 'xoo_el_registration_errors', $e->getMessage(), $e );

				do_action( 'xoo_el_registration_failed', $e );

				return array(
					'error' 		=> 1,
					'error_code' 	=> $e->getWpErrorCode(),
					'notice' 		=> xoo_el_add_notice('error', $message, $e->getWpErrorCode()),
				);
			}
		}
	}


	//Create new user
	public static function create_customer( $email = '', $username = '', $password = '', $extra_data = array() ){

		// Check the email address.
		if ( !empty( $email ) ) {

			if ( email_exists( $email ) ) {
				return new WP_Error( 'registration-error-email-exists',  __( 'An account is already registered with your email address. Please log in.', 'easy-login-woocommerce' ) );
			}

			if( !is_email( $email ) ){
				return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'easy-login-woocommerce' ) );
			}
			
		}

	

		// Handle username creation.
		if ( !empty( $username ) ) {
			$username = sanitize_user( $username );

			if ( !validate_username( $username ) ) {
				return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'easy-login-woocommerce' ) );
			}

			if ( username_exists( $username ) ) {
				return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'easy-login-woocommerce' ) );
			}
		} else {
			$username = self::create_new_username( $email, $extra_data );
		}

		// Handle password creation.
		$password_generated = false;
		if ( empty( $password ) ) {
			$password           = wp_generate_password();
			$password_generated = true;
		}

		$customer_data = array(
			'user_login' => $username,
			'user_pass'  => $password,
			'user_email' => $email,
			'role'       => esc_attr( self::$glSettings['m-user-role'] ),
		);
		
		$new_customer_data = apply_filters( 'xoo_el_register_new_customer_data', $customer_data );

		$customer_id = wp_insert_user( $new_customer_data );

		if ( is_wp_error( $customer_id ) ) {
			return new WP_Error( 'registration-error', __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'easy-login-woocommerce' ) );
		}

		$extra_data = apply_filters( 'xoo_el_register_customer_extra_data', $extra_data );
		
		foreach ( $extra_data as $meta_id => $meta_value ) {
			update_user_meta( $customer_id, $meta_id, $meta_value );
		}

		do_action( 'xoo_el_created_customer', $customer_id, $new_customer_data);

		if(class_exists('woocommerce')){
			do_action( 'woocommerce_created_customer', $customer_id, $new_customer_data, $password_generated );
		}else{
			do_action( 'register_new_user', $customer_id );
		}

		return $customer_id;
	}



	/**
	 * Create a unique username for a new customer.
	 * @param string $email New customer email address.
	 * @param array  $new_user_args Array of new user args, maybe including first and last names.
	 * @param string $suffix Append string to username to make it unique.
	 * @return string Generated username.
	 */
	public static function create_new_username( $email = '', $new_user_args = array(), $suffix = '' ) {

		$username_parts = array();

		if ( isset( $new_user_args['first_name'] ) ) {
			$username_parts[] = sanitize_user( $new_user_args['first_name'], true );
		}

		if ( isset( $new_user_args['last_name'] ) ) {
			$username_parts[] = sanitize_user( $new_user_args['last_name'], true );
		}

		// Remove empty parts.
		$username_parts = array_filter( $username_parts );

		// If there are no parts, e.g. name had unicode chars, or was not provided, fallback to email.
		if ( empty( $username_parts ) && $email ) {
			$email_parts    = explode( '@', $email );
			$email_username = $email_parts[0];

			// Exclude common prefixes.
			if ( in_array(
				$email_username,
				array(
					'sales',
					'hello',
					'mail',
					'contact',
					'info',
				),
				true
			) ) {
				// Get the domain part.
				$email_username = $email_parts[1];
			}

			$username_parts[] = sanitize_user( $email_username, true );
		}

		if( !empty( $username_parts ) ){
			$username = strtolower( implode( '.', $username_parts ) );
		}
		else{
			$username = wp_rand( 0, 9999999999 ); //random username
		}
		

		if ( $suffix ) {
			$username .= $suffix;
		}

		/**
		 * WordPress 4.4 - filters the list of blacklisted usernames.
		 *
		 * @since 3.7.0
		 * @param array $usernames Array of blacklisted usernames.
		 */
		$illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

		// Stop illegal logins and generate a new random username.
		if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_logins ), true ) ) {
			$new_args = array();

			/**
			 * Filter generated customer username.
			 *
			 * @param string $username      Generated username.
			 * @param string $email         New customer email address.
			 * @param array  $new_user_args Array of new user args, maybe including first and last names.
			 * @param string $suffix        Append string to username to make it unique.
			 */
			$new_args['first_name'] = apply_filters(
				'xoo_el_generated_customer_username',
				'wp_user_' . zeroise( wp_rand( 0, 9999 ), 4 ),
				$email,
				$new_user_args,
				$suffix
			);

			return self::create_new_username( $email, $new_args, $suffix );
		}

		if ( username_exists( $username ) ) {
			// Generate something unique to append to the username in case of a conflict with another user.
			$suffix = '-' . zeroise( wp_rand( 0, 9999 ), 4 );
			return self::create_new_username( $email, $new_user_args, $suffix );
		}

		/**
		 * Filter new customer username.
		 * @param string $username      Customer username.
		 * @param string $email         New customer email address.
		 * @param array  $new_user_args Array of new user args, maybe including first and last names.
		 * @param string $suffix        Append string to username to make it unique.
		 */
		return apply_filters( 'xoo_el_new_customer_username', $username, $email, $new_user_args, $suffix );
	}


	// Process lost password form
	public static function process_lost_password(){

		if( isset($_POST['_xoo_el_form']) &&  $_POST['_xoo_el_form'] === 'lostPassword' ){

			try{

				$validation_error = apply_filters( 'xoo_el_process_lostpw_errors', new WP_Error() );

				if ( $validation_error->get_error_code() ) {
					throw new Xoo_Exception( $validation_error );
				}

				if( class_exists( 'woocommerce' ) ){

					wc_clear_notices(); // clear notices
					$success = WC_Shortcode_My_Account::retrieve_password();

					if($success){
						ob_start();
						wc_get_template( 'myaccount/lost-password-confirmation.php' );
						$lost_password_confirmation = ob_get_clean();
					}
					else{
						$errors = wc_get_notices('error');
						$error = 1;
						throw new Xoo_Exception(  $errors[0]['notice'] );
					}

				}
				else{

					$success = self::retrieve_password();
					
					if( is_wp_error( $success ) ){
						throw new Xoo_Exception( $success );
					}
					else{
						$lost_password_confirmation = __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'easy-login-woocommerce' );
					}
				}

				$notice = '<div class="xoo-el-lostpw-success">'.$lost_password_confirmation.'</div>';

				return array(
					'error' 	=> 0,
					'notice' 	=> xoo_el_add_notice('success', $notice),
					'redirect' 	=> false
				);

			}
			catch( Xoo_Exception $e ){

				$message = apply_filters( 'xoo_el_lost_password_errors', $e->getMessage() );

				do_action( 'xoo_el_lost_password_failed' );

				return array(
					'error' 	=> 1,
					'error_code' 	=> $e->getWpErrorCode(),
					'notice' 	=> xoo_el_add_notice('error', $message, $e->getWpErrorCode()),
				);

			}
		}
	}


	public static function process_reset_password(){

		if( !isset( $_POST['xoo-el-resetpw-nonce-field'] ) || !wp_verify_nonce( $_POST['xoo-el-resetpw-nonce-field'], 'xoo-el-resetpw-nonce' ) ){
			return;
		}

		try {

			$user = check_password_reset_key( $_POST['rp_key'], $_POST['rp_login'] );

			if( is_wp_error( $user ) ){
				throw new Xoo_Exception( __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'easy-login-woocommerce' ) );
			}

			if ( $user instanceof WP_User ) {

				if( !isset( $_POST['xoo-el-rp-pass'] ) || !isset( $_POST['xoo-el-rp-pass-again'] ) || $_POST['xoo-el-rp-pass'] !== $_POST['xoo-el-rp-pass-again'] ){
					throw new Xoo_Exception( __( "Passwords don't match", 'easy-login-woocommerce' ) );
				}

				$new_pass = $_POST['xoo-el-rp-pass'];

				$errors = new WP_Error();

				do_action( 'validate_password_reset', $errors, $user );

				if( $user->has_errors() ){
					throw new Xoo_Exception( $user );
					
				}

				do_action( 'password_reset', $user, $new_pass );

				wp_set_password( $new_pass, $user->ID );

				//reset cookie
				self::set_reset_password_cookie();

				do_action( 'xoo_el_reset_password_success', $user );

				$success_notice = apply_filters( 'xoo_el_reset_password_success_notice', __( 'Your password has been reset successfully! Please <a class="xoo-el-login-tgr">Login</a>', 'easy-login-woocommerce' ), $user );

				return array(
					'error' 	=> 0,
					'notice' 	=> xoo_el_add_notice('success', $success_notice),
					'redirect' 	=> false
				);
			}

			

		} catch ( Xoo_Exception $e) {

			$message = apply_filters( 'xoo_el_reset_password_errors', $e->getMessage(), $e );

			do_action( 'xoo_el_reset_password_failed', $e );

			return array(
				'error' 		=> 1,
				'error_code' 	=> $e->getWpErrorCode(),
				'notice' 		=> xoo_el_add_notice( 'error', $message, $e->getWpErrorCode() ),
			);
		}
		

		
	}


	public static function retrieve_password() {

		$errors    = new WP_Error();

		$user_data = false;

		// Use the passed $user_login if available, otherwise use $_POST['user_login'].
		if ( ! empty( $_POST['user_login'] ) ) {
			$user_login = $_POST['user_login'];
		}

		$user_login = trim( wp_unslash( $user_login ) );

		if ( empty( $user_login ) ) {
			$$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.','easy-login-woocommerce' ));
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', $user_login );

			if ( empty( $user_data ) ) {
				$user_data = get_user_by( 'login', $user_login );
			}

			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', __( 'There is no account with that username or email address.', 'easy-login-woocommerce' ) );
			}
		} else {
			$user_data = get_user_by( 'login', $user_login );
		}

		/**
		* Fires before errors are returned from a password reset request.
		*
		* @since 2.1.0
		* @since 4.4.0 Added the `$errors` parameter.
		* @since 5.4.0 Added the `$user_data` parameter.
		*
		* @param WP_Error      $errors    A WP_Error object containing any errors generated
		*                                 by using invalid credentials.
		* @param WP_User|false $user_data WP_User object if found, false if the user does not exist.
		*/
		do_action( 'lostpassword_post', $errors, $user_data );


		/**
		* Filters the errors encountered on a password reset request.
		*
		* The filtered WP_Error object may, for example, contain errors for an invalid
		* username or email address. A WP_Error object should always be returned,
		* but may or may not contain errors.
		*
		* If any errors are present in $errors, this will abort the password reset request.
		*
		* @since 5.5.0
		*
		* @param WP_Error      $errors    A WP_Error object containing any errors generated
		*                                 by using invalid credentials.
		* @param WP_User|false $user_data WP_User object if found, false if the user does not exist.
		*/
		$errors = apply_filters( 'lostpassword_errors', $errors, $user_data );


		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( !$user_data ) {
			$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.','easy-login-woocommerce' ));
			return $errors;
		}


		/**
		* Filters whether to send the retrieve password email.
		*
		* Return false to disable sending the email.
		*
		* @since 6.0.0
		*
		* @param bool    $send       Whether to send the email.
		* @param string  $user_login The username for the user.
		* @param WP_User $user_data  WP_User object.
		*/
		if ( ! apply_filters( 'send_retrieve_password_email', true, $user_login, $user_data ) ) {
			return true;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			return $key;
		}

		// Localize password reset message content for user.
		$locale = get_user_locale( $user_data );

		$switched_locale = switch_to_user_locale( $user_data->ID );

		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$baseurl 	= self::$glSettings['m-reset-pw'] === "yes" ? '?action=reset_password&' : 'wp-login.php?action=rp&';

		$message = __( 'Someone has requested a password reset for the following account:','easy-login-woocommerce' ) . "\r\n\r\n";
		/* translators: %s: site name */
		$message .= sprintf( __( 'Site Name: %s','easy-login-woocommerce' ), $site_name ) . "\r\n\r\n";
		/* translators: %s: user login */
		$message .= sprintf( __( 'Username: %s','easy-login-woocommerce' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.','easy-login-woocommerce' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:','easy-login-woocommerce' ) . "\r\n\r\n";
		$message .= network_site_url( "{$baseurl}key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale . "\r\n\r\n";

		if ( ! is_user_logged_in() ) {
			$requester_ip = $_SERVER['REMOTE_ADDR'];
			if ( $requester_ip ) {
				$message .= sprintf(
					/* translators: %s: IP address of password reset requester. */
					__( 'This password reset request originated from the IP address %s.' ),
					$requester_ip
				) . "\r\n";
			}
		}

		/* translators: Password reset email subject. %s: Site name */
		$title = sprintf( __( '[%s] Password Reset','easy-login-woocommerce' ), $site_name );

		/**
		 * Filters the subject of the password reset email.
		 *
		 * @since 2.8.0
		 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $title      Default email title.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

		/**
		 * Filters the message body of the password reset mail.
		 *
		 * If the filtered message is empty, the password reset email will not be sent.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $message    Default mail message.
		 * @param string  $key        The activation key.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );



		/*
		* Wrap the single notification email arguments in an array
		* to pass them to the retrieve_password_notification_email filter.
		*/
		$defaults = array(
			'to'      => $user_email,
			'subject' => $title,
			'message' => $message,
			'headers' => '',
		);

		/**
		* Filters the contents of the reset password notification email sent to the user.
		*
		* @since 6.0.0
		*
		* @param array $defaults {
		*     The default notification email arguments. Used to build wp_mail().
		*
		*     @type string $to      The intended recipient - user email address.
		*     @type string $subject The subject of the email.
		*     @type string $message The body of the email.
		*     @type string $headers The headers of the email.
		* }
		* @type string  $key        The activation key.
		* @type string  $user_login The username for the user.
		* @type WP_User $user_data  WP_User object.
		*/
		$notification_email = apply_filters( 'retrieve_password_notification_email', $defaults, $key, $user_login, $user_data );

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		if ( is_array( $notification_email ) ) {
			// Force key order and merge defaults in case any value is missing in the filtered array.
			$notification_email = array_merge( $defaults, $notification_email );
		} else {
			$notification_email = $defaults;
		}

		list( $to, $subject, $message, $headers ) = array_values( $notification_email );

		$subject = wp_specialchars_decode( $subject );


		if ( ! wp_mail( $to, $subject, $message, $headers )  ){
			$errors->add(
			'retrieve_password_email_failure',__('The email could not be sent.','easy-login-woocommerce' ) . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.','easy-login-woocommerce' ) );
			return $errors;
		}

		return true;
	}




	public function get_reset_password_link( $user_id ){
		if( self::$glSettings['m-reset-pw'] !== "yes" ) return;
		$link = esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) );

	}


	public static function redirect_reset_password_link() {

		if ( isset( $_GET['action'] ) && $_GET['action'] === 'reset_password' && isset( $_GET['key'] ) && ( isset( $_GET['id'] ) || isset( $_GET['login'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// If available, get $user_id from query string parameter for fallback purposes.
			if ( isset( $_GET['login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user    = get_user_by( 'login', sanitize_user( wp_unslash( $_GET['login'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user_id = $user ? $user->ID : 0;
			} else {
				$user_id = absint( $_GET['id'] );
			}

			self::set_reset_password_cookie( sprintf( '%d:%s', $user_id, wp_unslash( $_GET['key'] ) ) );

			wp_safe_redirect( add_query_arg( 'reset_password', 'true', apply_filters( 'xoo_el_redirect_rp_link', get_site_url() ) ) );

			exit;
		}
	}


	/**
	 * Set or unset the cookie.
	 *
	 * @param string $value Cookie value.
	 */
	public static function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		$rp_path   = isset( $_SERVER['REQUEST_URI'] ) ? current( explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; // WPCS: input var ok, sanitization ok.

		if ( $value ) {
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		}
	}


	public static function remove_query_args(){
		if( ( isset( $_GET['reset_password'] ) || isset( $_GET['show-reset-form'] ) ) && is_user_logged_in() ){
			wp_safe_redirect( esc_url( remove_query_arg( array( 'reset_password', 'show-reset-form' ) ) ) );
		}
	}



	public static function newuser_notification_email( $wp_new_user_notification_email, $user, $blogname ){

		$key = get_password_reset_key( $user );

		if ( is_wp_error( $key ) ) {
			return $wp_new_user_notification_email;
		}
 		/* translators: %s: User login. */
		$message  = sprintf( __( 'Username: %s', 'easy-login-woocommerce' ), $user->user_login ) . "\r\n\r\n";
		$message .= __( 'To set your password, visit the following address:', 'easy-login-woocommerce' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "?action=reset_password&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . ">\r\n";

		$wp_new_user_notification_email['message'] = $message;

		return $wp_new_user_notification_email;
	}


	public static function lost_password_url( $default_url = '' ){
		// Avoid loading too early.
		if ( !class_exists( 'woocommerce' ) || ! did_action( 'init' ) ) {
			return $default_url;
		}

		if( wc_get_page_id( 'myaccount' ) > 0 ){
			return wc_get_page_permalink( 'myaccount' );
		}
		else{
			return get_site_url();
		}
		
	}

	public static function edit_wc_lost_password_url( $url, $endpoint, $value, $permalink ){
		if( $endpoint === 'lost-password' ){
			$url = self::lost_password_url();
		}
		return $url;
	}


}


Xoo_El_Form_Handler::init();


?>