<?php

if( class_exists( 'Xoo_Aff_fields' ) ){

	class Xoo_El_Aff_Fields{

		public $fields;

		public function __construct(){
			add_action( 'xoo_aff_easy-login-woocommerce_add_predefined_fields', array( $this, 'add_el_predefined_fields' ) );
			add_filter( 'xoo_aff_easy-login-woocommerce_before_fields_update', array( $this, 'manage_password_field' ) );
			add_filter( 'xoo_aff_easy-login-woocommerce_default_field_settings', array( $this, 'modify_default_field_settings' ) );
			add_filter( 'xoo_aff_easy-login-woocommerce_field_setting_options', array( $this, 'add_custom_settings_option' ) );
		}


		public function manage_password_field( $fields ){
			
			if( $fields[ 'xoo_el_reg_pass' ]['settings']['active'] === "no" ){
				$fields['xoo_el_reg_pass_again']['settings']['active'] = "no";
			}
			return $fields;

		}


		public function add_el_predefined_fields(){
			$this->fields = xoo_el()->aff->fields;
			$this->predefined_field_username();
			$this->predefined_field_useremail();
			$this->predefined_field_firstname();
			$this->predefined_field_lastname();
			$this->predefined_field_userpassword();
			$this->predefined_field_userpasswordagain();
			$this->predefined_mailchimp_subscribe();
			$this->predefined_field_terms();
			$this->login_predefined_field_useremail();
			$this->login_predefined_field_userpassword();
			$this->lostpw_predefined_field_useremail();
			$this->resetpw_predefined_field_password();
			$this->resetpw_predefined_field_passwordagain();
			$this->predefined_field_single();
		}


		


		public function predefined_field_username(){

			$field_type_id = $field_id = 'xoo_el_reg_username';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'User Name',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-user',
				)
			);
				
			$setting_options = array(
				'active' => array(
					'value' => 'no'
				),
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-user-plus'
				),
				'placeholder' => array(
					'value' => 'Username',
				),
				'minlength' => array(
					'value' => 3,
				),
				'maxlength' => array(
					'value' => 20,
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
					'required' 	=> 'yes',
				)
			);

		}
		
		public function predefined_field_useremail(){

			$field_type_id = $field_id = 'xoo_el_reg_email';

			$this->fields->add_type(
				$field_type_id,
				'email',
				'User Email',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-at',
				)
			);

			$setting_options = array(	
				'active' => array(
					'value' => 'yes'
				),
				'required' 	=> array(
					'value' => 'yes'
				),
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-at'
				),
				'placeholder' => array(
					'value' => 'Email',
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
				)			
			);

		}

		public function predefined_field_userpassword(){

			$field_type_id = $field_id = 'xoo_el_reg_pass';

			$this->fields->add_type(
				$field_type_id,
				'password',
				'Password',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-key'
				)
			);

			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-key'
				),
				'placeholder' => array(
					'value' => 'Password',
				),
				'minlength' => array(
					'value' => 6,
				),
				'maxlength' => array(
					'value' => 20,
				),
				'password_visibility',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);


			$settings_value = array(
				'active' 	=> 'yes',
				'required' 	=> 'yes',
				'unique_id' 	=> $field_id,
			);

			$active_setting = array(
				'active' => array(
					'info' => 'If disabled, a password will be sent to user email address.'
				)
			);

			$setting_options = array_merge( $active_setting, $setting_options );

			unset( $settings_value['active'] );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_type_id,
				$field_id,
				$settings_value
			);

		}

		public function predefined_field_userpasswordagain(){

			$field_type_id = $field_id = 'xoo_el_reg_pass_again';

			$this->fields->add_type(
				$field_type_id,
				'password',
				'Confirm Password',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-key'
				)
			);

			$setting_options = array(
				'active',
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-key'
				),
				'placeholder' => array(
					'value' => 'Confirm Password',
				),
				'password_visibility',
				'unique_id' => array(	
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'required' 	=> 'yes',
					'unique_id' 	=> $field_id,
				)	
			);

		}


		public function predefined_field_firstname(){

			$field_type_id = $field_id = 'xoo_el_reg_fname';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'First Name',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-font',
				)
			);

			$setting_options = array(
				'active',
				'required' => array(				
					'value' => 'yes'
				),
				'label',
				'cols' => array(
					'value' => 'onehalf'
				),
				'icon' => array(
					'value' => 'far fa-user'
				),
				'placeholder' => array(
					'value' => 'First Name',
				),
				'minlength',
				'maxlength',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->merge_with_wc_fields_setting_option( $field_type_id );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
				)
			);

		}


		public function predefined_field_lastname(){

			$field_type_id = $field_id = 'xoo_el_reg_lname';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'Last Name',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-font',
				)
			);

			$setting_options = array(
				'active',
				'required' => array(
					'value' => 'yes'
				),
				'label',
				'cols' => array(
					'value' => 'onehalf'
				),
				'icon' => array(
					'value' => 'far fa-user'
				),
				'placeholder' => array(
					'value' => 'Last Name',
				),
				'minlength',
				'maxlength',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->merge_with_wc_fields_setting_option( $field_type_id );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => 'xoo_el_reg_lname',
				)
			);

		}


		public function predefined_mailchimp_subscribe(){

			$mailchimp_id = false;

			if( function_exists( 'run_mailchimp_woocommerce' ) ){
				$mailchimp_id = 'mailchimp_woocommerce_newsletter';
			}

			if( function_exists('mc4wp') ){
				$mailchimp_id = 'mc4wp-subscribe';
			}

			//If no mailchimp plugin, return
			if( !$mailchimp_id ) return;

			$field_type_id = $field_id = $mailchimp_id;

			$this->fields->add_type(
				$field_type_id,
				'checkbox_single',
				'Mailchimp Newsletter',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-envelope',
				)
			);

			$setting_options = array(				
				'active',
				'required' => array(
					'value' => 'no'
				),
				'label',
				'placeholder' => array(
					'value' => 'Subscribe to our newsletter',
				),
				'cols',
				'checkbox_single' => array(
					'value' 	=>array(
						'yes' => array(
							'value' 	=> 'yes',
							'label' 	=> 'Subscribe to our newsletter',
							'checked' 	=> false
						)
					)
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
				)
			);
		}


		public function predefined_field_terms(){

			$field_type_id = $field_id = 'xoo_el_reg_terms';

			$this->fields->add_type(
				$field_type_id,
				'checkbox_single',
				'Terms & Conditions',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-check-square',
				)
			);


			$privacyLink = class_exists( 'woocommerce' ) && function_exists( 'wc_privacy_policy_page_id' ) && wc_privacy_policy_page_id() ? get_page_link( wc_privacy_policy_page_id() ) : 'privacy-policy';

			$setting_options = array(				
				'active',
				'required' => array(
					'value' => 'yes'
				),
				'label',
				'placeholder' => array(
					'value' => 'The Terms and Conditions',
				),
				'cols',
				'checkbox_single' => array(
					'value' 	=>array(
						'yes' => array(
							'value' 	=> 'yes',
							'label' 	=> 'I accept the <a href="'.$privacyLink.'" target="_blank"> Terms of Service and Privacy Policy </a>',
							'checked' 	=> false
						)
					)
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
				)
			);

		}


		public function predefined_field_single(){

			$field_type_id = $field_id = 'xoo-el-sing-user';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'Single Field Pattern',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'is_sortable' 	=> 'no',
					'icon' 			=> 'fas fa-hand-point-right',
				)
			);
				
			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-user-plus'
				),
				'placeholder' => array(
					'value' => __( 'Username / Email', 'easy-login-woocommerce' ),
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);


			$this->fields->add_setting(
				'xoo_el_username',
				'Allow Login with username',
				'checkbox',
				$field_type_id,
				array(
					'priority' 	=> 5,
					'value' 	=> 'yes',
					'info' 		=> 'Allow users to login with the username'
				)
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' 	=> $field_id,
					'required' 		=> 'yes',
					'active' 		=> 'yes',
					'elType'		=> 'single',
					'autocomplete' 	=> 'username'
				),
				990
			);

		}




		public function login_predefined_field_useremail(){

			$field_type_id = $field_id = 'xoo-el-username';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'Login - Username/Email',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'is_sortable' 	=> 'no',
					'icon' 			=> 'fas fa-user',
				)
			);
				
			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-user-plus'
				),
				'placeholder' => array(
					'value' => __( 'Username / Email', 'easy-login-woocommerce' ),
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' 	=> $field_id,
					'required' 		=> 'yes',
					'active' 		=> 'yes',
					'elType'		=> 'login',
					'autocomplete' 	=> 'username'
				),
				1000
			);

		}


		public function login_predefined_field_userpassword(){

			$field_type_id = $field_id = 'xoo-el-password';

			$this->fields->add_type(
				$field_type_id,
				'password',
				'Login - Password',
				array(
					'is_selectable' => 'no',
					'is_sortable' 	=> 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-key'
				)
			);

			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-key'
				),
				'placeholder' => array(
					'value' => __( 'Password', 'easy-login-woocommerce' ),
				),
				'password_visibility',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);


			$settings_value = array(
				'active' 		=> 'yes',
				'required' 		=> 'yes',
				'unique_id' 	=> $field_id,
				'elType'		=> 'login',
				'autocomplete' => 'current-password',
			);

			unset( $settings_value['active'] );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_type_id,
				$field_id,
				$settings_value,
				1010
			);

		}


		public function resetpw_predefined_field_password(){

			$field_type_id = $field_id = 'xoo-el-rp-pass';

			$this->fields->add_type(
				$field_type_id,
				'password',
				'Reset Password - New Password',
				array(
					'is_selectable' => 'no',
					'is_sortable' 	=> 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-key'
				)
			);

			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-key'
				),
				'placeholder' => array(
					'value' => __( 'New Password', 'easy-login-woocommerce' ),
				),
				'minlength' => array(
					'value' => 6,
				),
				'maxlength' => array(
					'value' => 20,
				),
				'password_visibility',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);


			$settings_value = array(
				'active' 		=> 'yes',
				'required' 		=> 'yes',
				'unique_id' 	=> $field_id,
				'elType'		=> 'resetpw'
			);

			unset( $settings_value['active'] );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_type_id,
				$field_id,
				$settings_value,
				1020
			);

		}


		public function resetpw_predefined_field_passwordagain(){

			$field_type_id = $field_id = 'xoo-el-rp-pass-again';

			$this->fields->add_type(
				$field_type_id,
				'password',
				'Reset Password - Confirm Password',
				array(
					'is_selectable' => 'no',
					'is_sortable' 	=> 'no',
					'can_delete'	=> 'no',
					'icon' 			=> 'fas fa-key'
				)
			);

			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-key'
				),
				'placeholder' => array(
					'value' => __( 'Confirm Password', 'easy-login-woocommerce' ),
				),
				'password_visibility',
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);


			$settings_value = array(
				'active' 		=> 'yes',
				'required' 		=> 'yes',
				'unique_id' 	=> $field_id,
				'elType'		=> 'resetpw'
			);

			unset( $settings_value['active'] );

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_type_id,
				$field_id,
				$settings_value,
				1030
			);

		}


		public function lostpw_predefined_field_useremail(){

			$field_type_id = $field_id = 'user_login';

			$this->fields->add_type(
				$field_type_id,
				'text',
				'Lost Password - Username/Email',
				array(
					'is_selectable' => 'no',
					'can_delete'	=> 'no',
					'is_sortable' 	=> 'no',
					'icon' 			=> 'fas fa-user',
				)
			);
				
			$setting_options = array(
				'label',
				'cols',
				'icon' => array(
					'value' => 'fas fa-user-plus'
				),
				'placeholder' => array(
					'value' => __( 'Username / Email', 'easy-login-woocommerce' ),
				),
				'unique_id' => array(
					'disabled' => 'disabled',
				),
				'class'
			);

			$this->fields->create_field_settings(
				$field_type_id,
				$setting_options
			);

			$this->fields->add_field(
				$field_id,
				$field_type_id,
				array(
					'unique_id' => $field_id,
					'required' 	=> 'yes',
					'active' 	=> 'yes',
					'elType' 	=> 'lostpw'
				),
				1040
			);

		}



		public function merge_with_wc_fields_setting_option( $field_type_id ){
			if( !class_exists( 'woocommerce' ) ) return;
			$this->fields->add_setting(
				'xoo_el_merge_wc_field',
				'Auto fill woocommerce billing & shipping fields',
				'checkbox',
				$field_type_id,
				array(
					'priority' 	=> 100,
					'value' 	=> 'yes'
				)
			);
		}

		//Add option to show field on woocommerce my account page
		public function modify_default_field_settings( $settings ){

			
			foreach ( $settings as $setting_id => $setting_options ) {
				if( class_exists( 'woocommerce' ) ){
					$settings[ $setting_id ][] = 'display_myacc';
				}
				$settings[ $setting_id ][] = 'display_usercol';
			}
			
			
			return $settings;

		}


		public function add_custom_settings_option( $setting_options ){

			//Show on my account page
			if( class_exists( 'woocommerce' ) ){
				$setting_options['display_myacc'] = array(
					'type' 		=> 'checkbox',
					'id'		=> 'display_myacc',
					'section' 	=> 'basic',	
					'title' 	=> 'Show on WC myaccount page',
					'width'		=> 'half',
					'value'		=> 'yes',
					'priority' 	=> 15
				);
			}

			$setting_options['display_usercol'] = array(
				'type' 		=> 'checkbox',
				'id'		=> 'display_usercol',
				'section' 	=> 'basic',	
				'title' 	=> 'Add under user column',
				'width'		=> 'half',
				'value'		=> 'yes',
				'info' 		=> 'Under wp-admin -> users table, this field will be added as a column.',
				'priority' 	=> 20
			);
			
			return $setting_options;
		}

	}


}

new Xoo_El_Aff_Fields();
?>