<?php

class Xoo_Aff{

	public $plugin_slug, $admin_page_slug, $fields, $admin;

	public function __construct( $plugin_slug, $admin_page_slug ){

		$this->plugin_slug = $plugin_slug;
		$this->admin_page_slug = $admin_page_slug;

		$this->includes();
		$this->hooks();
		$this->init();
		
	}

	public function hooks(){
		add_action( 'init', array( $this, 'on_install' ), 1 );
	}

	public function includes(){

		include_once XOO_AFF_DIR.'/includes/xoo-aff-functions.php';
		include_once XOO_AFF_DIR.'/admin/class-xoo-aff-fields.php';
		include_once XOO_AFF_DIR.'/admin/class-xoo-aff-admin.php';

	}

	public function init(){

		$this->fields 		= new Xoo_Aff_Fields( $this );
		$this->admin 		= new Xoo_Aff_Admin( $this );
		
	}


	public function is_fields_page(){
		return is_admin() && isset( $_GET['page'] ) && $_GET['page'] === $this->admin_page_slug;
	}


	public function is_fields_page_ajax_request(){
		return isset( $_POST['xoo_aff_plugin_action'] ) && $_POST['xoo_aff_plugin_action'] === $this->plugin_slug;
	}


	//Enqueue scripts from the main plugin
	public function enqueue_scripts(){

		$strategy 		= array( 'strategy' => 'defer' );

		$sy_options 	= get_option( $this->admin->settings->get_option_key( 'general' ) );

		wp_enqueue_style( 'xoo-aff-style', XOO_AFF_URL.'/assets/css/xoo-aff-style.css', array(), XOO_AFF_VERSION) ;

		if( $sy_options['s-show-icons'] === "yes" ){
			wp_enqueue_style( 'xoo-aff-font-awesome5', XOO_AFF_URL.'/lib/fontawesome5/css/all.min.css' );
		}


		$fields = $this->fields->get_fields_data();

		$has_date = $has_meter = $has_phonecode = false;

		if( !empty( $fields ) ){

			foreach ( $fields as $field_id => $field_data) {

				if( !isset( $field_data['input_type'] ) ) continue;

				switch ( $field_data['input_type'] ) {
					case 'date':
						$has_date = true;
						break;

					case 'password':
						if( isset( $field_data['settings']['strength_meter'] ) && $field_data['settings']['strength_meter'] === "yes" ){
							$has_meter = true;
						}
						break;

					case 'phone_code':
						$has_phonecode = true;
						break;
				}

			}

		}

		if( $has_phonecode ){
			wp_enqueue_style( 'xoo-aff-flags', XOO_AFF_URL.'/countries/flags.css', array(), XOO_AFF_VERSION );
		}

		if( $has_meter ){
			wp_enqueue_script( 'password-strength-meter' );
		}

		if( $has_date ){
			wp_enqueue_style( 'jquery-ui-css', XOO_AFF_URL.'/lib/jqueryui/uicss.css' );
			wp_enqueue_script('jquery-ui-datepicker');
		}



		if( !wp_style_is( 'select2' ) ){
			wp_enqueue_style( 'select2', XOO_AFF_URL.'/lib/select2/select2.css');
		}

		if( !wp_script_is( 'select2' ) ){
			wp_enqueue_script( 'select2', XOO_AFF_URL.'/lib/select2/select2.js', array('jquery'), XOO_AFF_VERSION, $strategy ); // Main JS
		}

		wp_enqueue_script( 'xoo-aff-js', XOO_AFF_URL.'/assets/js/xoo-aff-js.js', array( 'jquery' ), XOO_AFF_VERSION, $strategy );
		
		wp_localize_script('xoo-aff-js','xoo_aff_localize',array(
			'adminurl'  			=> admin_url().'admin-ajax.php',
			'countries' 			=> json_encode( include XOO_AFF_DIR.'/countries/countries.php' ),
			'states' 				=> json_encode( include XOO_AFF_DIR.'/countries/states.php' ),
			'password_strength' 	=> array(
				'min_password_strength' => apply_filters( 'xoo_aff_min_password_strength', 3 ),
				'i18n_password_error'   => esc_attr__( 'Please enter a stronger password.', $this->plugin_slug ),
				'i18n_password_hint'    => esc_attr( wp_get_password_hint() ),
			)
		));

		$inline_style = xoo_aff_get_template( 'xoo-aff-inline-style.php',  XOO_AFF_DIR.'/includes/templates/', array( 'sy_options' => $sy_options ), true );

		wp_add_inline_style( 'xoo-aff-style', $inline_style );

	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}


	public function on_install(){

		$db_version = get_option( 'xoo_aff_'.$this->plugin_slug.'_version' );
		
		if( version_compare( $db_version, XOO_AFF_VERSION , '<' ) ){
			$this->fields->set_defaults();
			update_option( 'xoo_aff_'.$this->plugin_slug.'_version', XOO_AFF_VERSION );
		}
	}

}


?>