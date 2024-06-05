<?php
if ( ! function_exists( 'get_editable_roles' ) ) {
	require_once ABSPATH . 'wp-admin/includes/user.php';
}
$editable_roles = array_reverse( get_editable_roles() );
foreach ( $editable_roles as $role_id => $role_data) {
	$user_roles[$role_id] = translate_user_role( $role_data['name'] );
}
$user_roles = apply_filters( 'xoo_el_admin_user_roles', $user_roles );

$localizeTexts = version_compare( get_option( 'xoo-el-version' ) , '2.5', '<' );

$settings = array(

	/** MAIN **/
	array(
		'callback' 		=> 'links',
		'title' 		=> 'Fields',
		'id' 			=> 'fake',
		'section_id' 	=> 'gl_main',
		'args' 			=> array(
			'options' 	=> array(
				admin_url('admin.php?page=xoo-el-fields') => 'Manage'
			)
		)
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Enable Registration',
		'id' 			=> 'm-en-reg',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'yes',
	),

	array(
		'callback' 		=> 'select',
		'title' 		=> 'User Role',
		'id' 			=> 'm-user-role',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => $user_roles
		),
		'default' 		=> class_exists( 'woocommerce' ) ? 'customer' : 'subscriber',
		'desc' 			=> 'Register users with role'
	),

	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Auto Login User on Sign up',
		'id' 			=> 'm-auto-login',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'yes',
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Handle Reset Password',
		'id' 			=> 'm-reset-pw',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'yes',
		'desc' 			=> 'If checked, allow users to set a new password in form.'
	),


	array(
		'callback' 		=> 'select',
		'title' 		=> 'Form Pattern',
		'id' 			=> 'm-form-pattern',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => array(
				'separate' 	=> 'Separate Login and register forms',
				'single' 	=> 'Single Field Form',
			)
		),
		'default' 		=> 'separate',
		'desc' 			=> 'Single field form will navigate users to login/signup depending on the data.'
	),



	array(
		'callback' 		=> 'select',
		'title' 		=> 'Navigation Pattern',
		'id' 			=> 'm-nav-pattern',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => array(
				'tabs' 		=> 'Header Tabs',
				'links' 	=> 'Footer Links',
				'disable' 	=> 'Disable'
			)
		),
		'default' 		=> 'tabs',
		'desc' 			=> 'Choose a way to switch between login and registration form.'
	),


);


if( class_exists( 'woocommerce' ) ){
	$settings[] = array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Replace myaccount form',
		'id' 			=> 'm-en-myaccount',
		'section_id' 	=> 'gl_wc',
		'default' 		=> 'yes',
		'desc' 			=> 'If checked , this will replace woocommerce myaccount page form.'
	);

	$settings[] = array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Replace checkout login form',
		'id' 			=> 'm-en-chkout',
		'section_id' 	=> 'gl_wc',
		'default' 		=> 'yes',
		'desc' 			=> 'If checked & login on checkout is enabled, this will replace login form.'
	);

	$settings[] = array(
		'callback' 		=> 'textarea',
		'title' 		=> 'Replace form shortcode',
		'id' 			=> 'm-myacc-sc',
		'section_id' 	=> 'gl_wc',
		'default' 		=> '[xoo_el_inline_form active="login"]',
		'desc' 			=> 'Above two forms will be replaced with this shortcode. See info tab for shortcode details',
		'args' 			=> array(
			'rows' => 2,
			'cols' => 60,
			'custom_attributes' => array(
				'spellcheck' => 'false',
			)
		)
	);
}


$popup = array(


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Login Redirect',
		'id' 			=> 'm-red-login',
		'section_id' 	=> 'gl_red',
		'default' 		=> '',
		'desc' 			=> 'Leave empty to redirect on the same page.'
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Register Redirect',
		'id' 			=> 'm-red-register',
		'section_id' 	=> 'gl_red',
		'default' 		=> '',
		'desc' 			=> 'Leave empty to redirect on the same page.'
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Logout Redirect',
		'id' 			=> 'm-red-logout',
		'section_id' 	=> 'gl_red',
		'default' 		=> '',
		'desc' 			=> 'Leave empty to redirect on the same page.'
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Success Endpoint',
		'id' 			=> 'm-ep-success',
		'section_id' 	=> 'gl_red',
		'default' 		=> 'yes',
		'desc' 			=> 'Adds (login="success" & register="success") in URL bar on login & register. Clears cache on login/register if you have cache plugin enabled'
	),

	array(
		'callback' 		=> 'checkbox_list',
		'title' 		=> 'Forms',
		'id' 			=> 'popup-forms',
		'section_id' 	=> 'gl_popup',
		'args' 			=> array(
			'options' 	=> array(
				'login' 		=> 'Login',
				'register' 		=> 'Register',
			),
		),
		'default' 	=> array(
			'login', 'register',
		)
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Prevent closing',
		'id' 			=> 'popup-force',
		'section_id' 	=> 'gl_popup',
		'default' 		=> 'no',
		'desc' 			=> 'Once popup is opened, this option will prevent user from closing it. Useful when you want to hide your website page content for guest users. You can also set "overlay opacity to 1" from style tab to completely blackout the background.'
	),



	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Auto open Popup',
		'id' 			=> 'ao-enable',
		'section_id' 	=> 'gl_ao',
		'default' 		=> 'no',
	),


	array(
		'callback' 		=> 'select',
		'title' 		=> 'Default Tab',
		'id' 			=> 'ao-default-form',
		'section_id' 	=> 'gl_ao',
		'args' 			=> array(
			'options' 		=> array(
				'login' 	=> 'Login',
				'register' 	=> 'Register',
			),
		),
		'default' 		=> 'login',
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Open once',
		'id' 			=> 'ao-once',
		'section_id' 	=> 'gl_ao',
		'default' 		=> 'no',
	),


	array(
		'callback' 		=> 'textarea',
		'title' 		=> 'On Pages',
		'id' 			=> 'ao-pages',
		'section_id' 	=> 'gl_ao',
		'default' 		=> '',
		'desc' 			=> 'Use post type/page id/slug separated by comma. For eg: 19,contact-us,shop .Leave empty for every page.'
	),

	array(
		'callback' 		=> 'number',
		'title' 		=> 'Delay',
		'id' 			=> 'ao-delay',
		'section_id' 	=> 'gl_ao',
		'default' 		=> 500,
		'desc' 			=> 'Trigger popup after seconds. 1000 = 1 second'
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Login Tab text',
		'id' 			=> 'txt-tab-login',
		'section_id' 	=> 'gl_texts',
		'default' 		=> $localizeTexts ? __( 'Login', 'easy-login-woocommerce' ) : 'Login',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Register Tab text',
		'id' 			=> 'txt-tab-reg',
		'section_id' 	=> 'gl_texts',
		'default' 		=> $localizeTexts ? __( 'Sign Up', 'easy-login-woocommerce' ) : 'Sign Up',
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Login Button text',
		'id' 			=> 'txt-btn-login',
		'section_id' 	=> 'gl_texts',
		'default' 		=> $localizeTexts ? __( 'Sign in', 'easy-login-woocommerce' ) : 'Sign in',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Register Button text',
		'id' 			=> 'txt-btn-reg',
		'section_id' 	=> 'gl_texts',
		'default' 		=> $localizeTexts ? __( 'Sign Up', 'easy-login-woocommerce' ) : 'Sign Up',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Reset password Button text',
		'id' 			=> 'txt-btn-respw',
		'section_id' 	=> 'gl_texts',
		'default' 		=> $localizeTexts ? __( 'Email Reset Link', 'easy-login-woocommerce' ) : 'Email Reset Link',
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Single Field Form Heading',
		'id' 			=> 'txt-sing-head',
		'section_id' 	=> 'gl_texts',
		'default' 		=> 'Welcome to '.esc_attr( get_bloginfo( 'name' ) ),
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Single Field Form Subtext',
		'id' 			=> 'txt-sing-subtxt',
		'section_id' 	=> 'gl_texts',
		'default' 		=> 'Log in or sign up with your email.',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Single Field Form Button text',
		'id' 			=> 'txt-btn-single',
		'section_id' 	=> 'gl_texts',
		'default' 		=> 'Continue',
	),




);

$settings = array_merge( $settings, $popup );

return apply_filters( 'xoo_el_admin_settings', $settings, 'general' );

?>