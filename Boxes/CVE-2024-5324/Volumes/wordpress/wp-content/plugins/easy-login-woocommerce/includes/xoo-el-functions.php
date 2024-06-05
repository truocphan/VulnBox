<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!function_exists('array_is_list')) {
    function array_is_list(array $arr)
    {
        if ($arr === []) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }
}

if ( ! function_exists( 'xoo_recursive_parse_args' ) ) {
	function xoo_recursive_parse_args( $args, $defaults ) {
		$new_args = (array) $defaults;

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $new_args[ $key ] ) && !array_is_list( $value ) ) {
				$new_args[ $key ] = xoo_recursive_parse_args( $value, $new_args[ $key ] );
			}
			else {
				$new_args[ $key ] = $value;
			}
		}

		return $new_args;
    }
}

//Menu items filter
if( !function_exists( 'xoo_el_nav_menu_items' ) ):
	function xoo_el_nav_menu_items( $items ) {

		if( empty( $items ) || !is_array( $items ) ) return;


		$actions_classes = array(
			'xoo-el-login-tgr',
			'xoo-el-reg-tgr',
			'xoo-el-lostpw-tgr',
			'xoo-el-logout-menu',
			'xoo-el-myaccount-menu',
			'xoo-el-username-menu',
			'xoo-el-firstname-menu'
		);

		$user = wp_get_current_user();

		foreach ( $items as $key => $item ) {

			$classes = $item->classes;

			if( !empty( $action_class = array_values( array_intersect( $actions_classes, $classes ) ) ) ){

				$action_class = $action_class[0];

				if( is_user_logged_in() ){

					if( $action_class === "xoo-el-myaccount-menu" ){
						//do nothing
						continue;
					}
					elseif( $action_class === "xoo-el-logout-menu" ){
						if( $item->url ) continue;
						$glSettings = xoo_el_helper()->get_general_option();
						$logout_redirect = !empty( $glSettings['m-red-logout'] ) ? $glSettings['m-red-logout'] : $_SERVER['REQUEST_URI'];
						$item->url = wp_logout_url($logout_redirect);
					}
					elseif( $action_class === "xoo-el-firstname-menu"){
						
						$name = !$user->user_firstname ? $user->user_login : $user->user_firstname;
						$item->title = get_avatar($user->ID).str_replace( 'firstname' , $name , $item->title );
						if( class_exists('woocommerce') ){
							$item->url 	 = wc_get_page_permalink( 'myaccount' );
						}
					}
					elseif( $action_class === "xoo-el-username-menu"){
						$item->title = get_avatar($user->ID).str_replace( 'username' , $user->user_login , $item->title );
						if( class_exists('woocommerce') ){
							$item->url 	 = wc_get_page_permalink( 'myaccount' );
						}
					}
					else{
						unset($items[$key]);
					}

				}
				else{
					if( $action_class === "xoo-el-logout-menu" || $action_class === "xoo-el-myaccount-menu" ||  $action_class === "xoo-el-username-menu"  || $action_class === "xoo-el-firstname-menu"){
						unset($items[$key]);
					}

				}

			}
		}

		return $items;
	}
	add_filter('wp_nav_menu_objects','xoo_el_nav_menu_items',11);
endif;


//Add notice
function xoo_el_add_notice( $notice_type = 'error', $message = '', $notice_class = null ){

	$classes = 'xoo-el-notice-'.$notice_type.' '.$notice_class;

	$html = '<div class="'.$classes.'">'.$message.'</div>';
	
	return apply_filters('xoo_el_notice_html',$html,$message,$classes);
}

//Print notices
function xoo_el_notice_container( $form, $args ){

	global $limit_login_attempts_obj;

	$notices = '';

	if($form === 'login' && !xoo_el_is_limit_login_ok() ){
		$notices .= '<div class="xoo-el-lla-notice"><div class="xoo-el-notice-error">'.$limit_login_attempts_obj->error_msg().'</div></div>';
	}

	$notices .= '<div class="xoo-el-notice"></div>';

	echo apply_filters( 'xoo_el_notice_container', wp_kses_post( $notices ), $form );

}

add_action( 'xoo_el_before_form', 'xoo_el_notice_container',10, 2 );

//Is limit login ok
function xoo_el_is_limit_login_ok(){
	global $limit_login_attempts_obj;
	//return if limit login plugin doesn't exist
	if( !$limit_login_attempts_obj ) return true;

	return $limit_login_attempts_obj->is_limit_login_ok();

}


//Inline Form Shortcode
if( !function_exists( 'xoo_el_inline_form' ) ){
	function xoo_el_inline_form_shortcode($user_atts){

		if( is_user_logged_in() ) return;

		if( !isset( $user_atts['forms'] ) ){
			$defaultForms = 'login';
			if( xoo_el_helper()->get_general_option('m-en-reg') === 'yes' ){
				$defaultForms .= ',register';
			}
		}
		else{
			$defaultForms = 'login,register';
		}

		$atts = shortcode_atts( array(
			'navstyle'  		=> xoo_el_helper()->get_general_option( 'm-nav-pattern' ),
			'pattern' 			=> xoo_el_helper()->get_general_option( 'm-form-pattern' ),
			'active' 			=> '',
			'tabs' 				=> 'login,register',
			'forms' 			=> $defaultForms,
			'login_redirect' 	=> '',
			'register_redirect' => '',

		), $user_atts, 'xoo_el_inline_form');

		//On change of attribute from tabs to forms
		$atts['tabs'] = $atts['forms'];

		$tabs = array_map( 'trim', explode(',', $atts['tabs']) );

		if( $atts['pattern'] === 'single' ){
			$active = 'single';
		}
		else{
			if( !in_array( 'login', $tabs ) || !in_array( 'register', $tabs ) ){
				if( !in_array( 'login', $tabs ) ){
					$active = 'register';
				}
				if( !in_array( 'register', $tabs ) ){
					$active = 'login';
				}
			}
			elseif( $atts['active'] ){
				$active = $atts['active'];
			}
			elseif( !empty( $tabs ) && $tabs[0] ) {
				$active = $tabs[0];
			}
			else{
				$active = 'login';
			}
		}
		

		$args = array(
			'form_active' 	=> $active,
			'return' 		=> true,
			'tabs' 			=> $tabs,
			'navstyle' 		=> $atts['navstyle']
		);

		$args['forms']['single']['enable'] = $atts['pattern'] === 'single' ? 'yes' : 'no';
		$args['forms']['register']['enable'] = in_array( 'register', $tabs ) ? 'yes' : 'no';

		if( $atts['login_redirect'] ){
			$args['forms']['login']['redirect'] = $atts['login_redirect'] === 'same' ? $_SERVER['REQUEST_URI'] : sanitize_url( $atts['login_redirect'] );
		}

		if( $atts['register_redirect'] ){
			$args['forms']['register']['redirect'] = $atts['register_redirect'] === 'same' ? $_SERVER['REQUEST_URI'] : sanitize_url( $atts['register_redirect'] );
		}


		return xoo_el_get_form( $args );

	}
	add_shortcode( 'xoo_el_inline_form', 'xoo_el_inline_form_shortcode' );
}


function xoo_el_get_form( $args = array() ){

	$glSettings 	= xoo_el_helper()->get_general_option();

	$defaults = array(
		'display' 		=> 'inline',
		'form_active' 	=> 'login',
		'return' 		=> false,
		'navstyle' 		=> $glSettings['m-nav-pattern'],
		'forms' => array(
			'single' 		=> array(
				'enable' 	=> $glSettings['m-form-pattern'] === 'single' ? 'yes' : 'no'
			),
			'login' 		=> array(
				'enable' 	=> 'yes'
			),
			'register' 		=> array(
				'enable' => $glSettings['m-en-reg']
			),
			'lostpw' 		=> array(
				'enable' 	=> 'yes'
			),
			'resetpw' 		=> array(
				'enable' 	=> 'yes'
			),
		),
		'tabs' 	=> array(
			'login', 'register',
		)
	);

	$args = xoo_recursive_parse_args( $args, $defaults );


	if( $args['forms']['single']['enable'] === 'yes' ){
		$defaults['form_active'] = 'single';
	}

	$unsetTabs = array();

	foreach ($args['forms'] as $key => $form ) {
		if( $form['enable'] !== 'yes' ){
			$unsetTabs[] = $key;
		}
	}

	$args['tabs'] = array_diff( $args['tabs'], $unsetTabs );

	if( $glSettings['m-reset-pw'] === "yes" && ( isset( $_GET['reset_password'] ) || isset( $_GET['show-reset-form'] ) ) ){

		$args['form_active'] = 'resetpw';

		$user = new WP_Error();

		if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {  // @codingStandardsIgnoreLine

			list( $rp_id, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ); // @codingStandardsIgnoreLine
			$userdata               = get_userdata( absint( $rp_id ) );
			$rp_login               = $userdata ? $userdata->user_login : '';
			$user                   = check_password_reset_key( $rp_key, $rp_login );

			$args['forms']['resetpw'] = array_merge( array(
				'user' 		=> $user,
				'rp_login'	=> $rp_login,
				'rp_key' 	=> $rp_key 
			), $args['forms']['resetpw'] );

		}

	}
	else{
		unset( $args['forms'][ 'resetpw' ] );
	}


	return xoo_el_helper()->get_template( 'xoo-el-form.php', array( 'args' => $args ), '', $args['return'] );
}

//Override woocommerce form login template
function xoo_el_override_myaccount_login_form( $located, $template_name, $args, $template_path, $default_path ){

	if( $template_name === 'myaccount/form-login.php' && xoo_el_helper()->get_general_option( 'm-en-myaccount' ) === "yes" ){
		$located = xoo_el_helper()->locate_template( 'xoo-el-wc-form-login.php', XOO_EL_PATH.'/templates/' );
	}
	return $located;
}
add_filter( 'wc_get_template', 'xoo_el_override_myaccount_login_form', 99999, 5 );



//Override woocommerce form login template
function xoo_el_override_wc_login_form( $located, $template_name, $args, $template_path, $default_path ){

	$glSettings 	  	= xoo_el_helper()->get_general_option();
	$enable_myaccount 	= $glSettings['m-en-myaccount'];
	$enable_checkout 	= $glSettings['m-en-chkout'];

	if( ( $template_name === 'myaccount/form-login.php' && $enable_myaccount === "yes" ) || ( $template_name === 'global/form-login.php' && $enable_checkout === "yes" ) ){
		$located = xoo_el_helper()->locate_template( 'xoo-el-wc-form-login.php', XOO_EL_PATH.'/templates/' );
	}
	return $located;
}
add_filter( 'wc_get_template', 'xoo_el_override_wc_login_form', 99999, 5 );


function xoo_el_get_myaccount_fields(){

	return xoo_el_fields()->get_fields('myaccount');

}





//Add fields to woocommerce account edit page
function xoo_el_myaccount_details(){

	$fields = xoo_el_fields()->get_fields('myaccount');

	if( empty($fields) ) return;

	$args = array(
		'icon' 			=> false,
		'validation' 	=> 'yes',
	);

	$user_id = get_current_user_id();

	$first_half = false;

	foreach ( $fields as $field_id => $field_data ) {

		$args[ 'cont_class'] = array( 'xoo-aff-myacc-field', 'woocommerce-form-row', 'form-row', 'xoo-aff-'.$field_data['input_type'] );

		if( isset( $field_data['settings']['cols'] ) ){
			if( $field_data['settings']['cols'] === 'one' ){
				$args['cont_class'][] = 'form-row-wide';
				$first_half = false;
			}
			else{
				if( $first_half ){
					$args['cont_class'][] = 'form-row-last';
					$first_half = false;
				}
				else{
					$args['cont_class'][] = 'form-row-first';
					$first_half = true;
				}
			}
		}
		
		$field_value = get_user_meta( $user_id, $field_id, true );

		$args['value'] = $field_value;

		xoo_el()->aff->fields->get_field_html( $field_id, $args );

	}

}
add_action('woocommerce_edit_account_form','xoo_el_myaccount_details', 10);


function xoo_el_save_myaccount_details( $user_id  ){

	$fields = xoo_el_fields()->get_fields('myaccount');

	if( empty( $fields ) ) return;

	foreach ( $fields as $field_id => $field_data ) {
		
		$settings = $field_data[ 'settings' ];
		if( empty( $settings ) ) continue;

		//If active & required & empty user input , throw error
		if( $settings['active'] === "yes" && $settings['required'] === "yes" &&  ( !isset( $_POST[ $field_id ] ) || trim( $_POST[$field_id ] ) == '' )  ){

			$label = isset( $settings['label'] ) && trim( $settings['label'] ) ? trim( $settings['label'] ) : trim( $settings['placeholder'] );

			switch ( $field_data['input_type'] ) {
				case 'checkbox_single':
					$error= sprintf( esc_attr__( 'Please check %s', 'easy-login-woocommerce' ), $label );
					break;
				
				default:
					$error = sprintf( esc_attr__( '%s cannot be empty', 'easy-login-woocommerce' ), $label );
					break;
			}
			
			wc_add_notice( $error, 'error' );
		}
		else{
			if( is_array( $_POST[ $field_id ] ) ){
				$field_value = array_map( 'sanitize_text_field', $_POST[ $field_id ] );
			}
			else{
				$field_value = sanitize_text_field( $_POST[ $field_id ] );
			}
			update_user_meta( $user_id, $field_id, $field_value );
		}
	}

}
add_action('woocommerce_save_account_details','xoo_el_save_myaccount_details', 10);


function xoo_el_register_generate_password(){
	if( !class_exists( 'woocommerce' ) ) return;
	$aff = xoo_el()->aff->fields;
	$fields = $aff->get_fields_data();
	if( isset( $fields['xoo_el_reg_pass'] ) && $fields['xoo_el_reg_pass']['settings']['active'] === "no" ){
		add_filter( 'pre_option_woocommerce_registration_generate_password', function(){ return 'yes'; } );
	}
}
add_action( 'init', 'xoo_el_register_generate_password' );


//Auto fil woocommerce fields
function xoo_el_autofill_wc_fields( $customer_id, $customer_data ){
	if( !class_exists( 'woocommerce' ) ) return;
	$customer = new Wc_Customer( $customer_id );
	if( !$customer ) return;

	$aff = xoo_el()->aff->fields;
	$fields = $aff->get_fields_data();
	$firstname = isset( $fields['xoo_el_reg_fname'] ) ? $fields['xoo_el_reg_fname'] : false;
	$lastname = isset( $fields['xoo_el_reg_lname'] ) ? $fields['xoo_el_reg_lname'] : false;

	if( $firstname ){
		if( isset( $firstname['settings']['xoo_el_merge_wc_field'] ) && $firstname['settings']['xoo_el_merge_wc_field'] === "yes" ){
			update_user_meta( $customer_id, 'billing_first_name', $customer->get_first_name() );
			update_user_meta( $customer_id, 'shipping_first_name', $customer->get_first_name() );
		}
	}

	if( $lastname ){
		if( isset( $lastname['settings']['xoo_el_merge_wc_field'] ) && $lastname['settings']['xoo_el_merge_wc_field'] === "yes" ){
			update_user_meta( $customer_id, 'billing_last_name', $customer->get_last_name() );
			update_user_meta( $customer_id, 'shipping_last_name', $customer->get_last_name() );
		}
	}
}
add_action( 'xoo_el_created_customer', 'xoo_el_autofill_wc_fields', 10, 2 );


function xoo_el_redirect_endpoints(){
	
	if( xoo_el_helper()->get_general_option( 'm-ep-success' ) !== "yes" ) return;

	add_filter( 'xoo_el_login_redirect', function( $redirect ){
		return add_query_arg( 'login', 'success', $redirect );
	} );
	 
	add_filter( 'xoo_el_register_redirect', function( $redirect ){
		return add_query_arg( 'login', 'success', $redirect );
	} );
}
add_action( 'init', 'xoo_el_redirect_endpoints' );


function xoo_elext(){

	$defaults = wp_kses_allowed_html( 'post' );

	$allowed = array(
		'input' 		=> array(
			'class' 		=> array(),
			'name' 			=> array(),
			'placeholder' 	=> array(),
			'type' 			=> array(),
			'id' 			=> array(),
			'value' 		=> array(),
			'disabled' 		=> array(),
			'minlength' 	=> array(),
			'maxlength' 	=> array(),
			'checked' 		=> array(),
			'min' 			=> array(),
			'max' 			=> array(),
			'required'		=> array()
		),
		'select' 		=> array(
			'class' 		=> array(),
			'name' 			=> array(),
			'type' 			=> array(),
			'id' 			=> array(),
			'disabled' 		=> array(),
		),
		'option' => array(
			'value' 	=> array(),
			'selected' 	=> array(),
		),
	);

	return array_merge( $defaults, $allowed );

}


function xoo_el_popup_form(){

	$tabs  = xoo_el_helper()->get_general_option( 'popup-forms' );

	$args = array(
		'form_class'	=> 'xoo-el-form-popup',
		'display' 		=> 'popup',
		'form_active' 	=> xoo_el_helper()->get_general_option( 'm-form-pattern' ) === 'single' ? 'single' : xoo_el_helper()->get_general_option( 'ao-default-form' ),
		'tabs' 			=> $tabs,
	);

	xoo_el_get_form( $args );
}



function xoo_el_header_image(){

	$image = esc_html( xoo_el_helper()->get_style_option( 'sy-head-img' ) );

	if( !$image ) return;

	?>
	<img src="<?php echo $image ?>" placeholder="logo" class="xoo-el-head-img">
	<?php

}
add_action( 'xoo_el_before_header', 'xoo_el_header_image' );


function xoo_el_nav_footer_links( $form, $args ){

	?>

	<?php if( $form === 'login' && in_array( 'register' , $args['tabs'] ) && isset( $args['navstyle'] ) && $args['navstyle'] === 'links' ): ?>
		<span class="xoo-el-reg-tgr xoo-el-nav-ft"><?php _e( "Don't have an account? Signup now", 'easy-login-woocommerce' ) ?></span>
	<?php endif; ?>

	<?php if( $form === 'register' && in_array( 'login' , $args['tabs'] ) && isset( $args['navstyle'] ) && $args['navstyle'] === 'links' ): ?>
		<span class="xoo-el-login-tgr xoo-el-nav-ft"><?php _e( "Already a member? Login", 'easy-login-woocommerce' ) ?></span>
	<?php endif; ?>


	<?php if( $form === 'lostpw' && isset( $args['navstyle'] ) && $args['navstyle'] === 'links' ): ?>
		<span class="xoo-el-login-tgr xoo-el-nav-ft"><i class="xoo-el-icon-arrow-left2"></i></span>
	<?php endif; ?>

	<?php

}
add_action( 'xoo_el_after_form', 'xoo_el_nav_footer_links',10, 2 );


function xoo_el_add_inline_form_redirects( $form, $args ){

	if( $args['display'] !== 'inline' ) return;

	if( $form === 'login' && isset( $args['forms']['login'] ) && isset( $args['forms']['login']['redirect'] ) ){
		$redirect = $args['forms']['login']['redirect'];
	}

	if( $form === 'register' && isset( $args['forms']['register'] ) && isset( $args['forms']['register']['redirect'] ) ){
		$redirect = $args['forms']['register']['redirect'];
	}

	if( isset( $redirect ) ){
		?>
		<input type="hidden" name="xoo_el_redirect" value="<?php echo esc_url( $redirect ); ?>">
		<?php
	}

}
add_action( 'xoo_el_form_end', 'xoo_el_add_inline_form_redirects', 10, 2 );

?>