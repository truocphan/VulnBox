<?php

class Xoo_Aff_Settings{

	public $aff;

	public $tabs = array();

	public $options = array();

	public $callbacks;

	public function __construct( $aff ){
		$this->aff = $aff;
		$this->prepare();
		$this->hooks();
	}

	public function hooks(){
		add_action( 'admin_init', array( $this, 'generate_settings' ), 5 );
		add_action( 'admin_enqueue_scripts', array($this,'enqueue_scripts') );
	}

	public function prepare(){
		$this->callbacks = require_once XOO_AFF_DIR.'/admin/settings/class-xoo-aff-options-callbacks.php';
		$this->set_tabs();
		$this->set_options();
	}

	public function set_tabs(){
		$this->tabs = array(
			'general' => array(
				'id' 			=> 'general',
				'title' 		=> 'Settings'
			)
		);
	}

	public function set_options(){

		if( empty( $this->tabs ) ) return;

		foreach ( $this->tabs as $tab_id => $tab_data ) {
			if( file_exists( XOO_AFF_DIR.'/admin/settings/options/'.$tab_id.'.php' ) )
			$this->options[ $this->get_option_key( $tab_id ) ] = include XOO_AFF_DIR.'/admin/settings/options/'.$tab_id.'.php'; 
		}

		//Set default options
		foreach ( $this->options as $option_name => $settings ) {

			$option_value = (array) get_option( $option_name );

			foreach ( $settings as $id => $setting ) {
				if( $setting['type'] === 'setting' && !isset( $option_value[ $id ] ) && isset( $setting['default'] ) ){
					$option_value[ $id ] = $setting['default' ];
				}
			}

			update_option( $option_name, $option_value );

		}

	}

	public function get_option_key( $option_slug ){
		return 'xoo-aff-'.$this->aff->plugin_slug.'-'.$option_slug.'-options';
	}

	public function generate_settings(){

		foreach ( $this->options as $option_name => $option_settings ) {

			register_setting( $option_name, $option_name );

			foreach ( $option_settings as $setting_id => $setting ) {

				if( !isset( $setting['id'] ) || !isset( $setting['type'] ) || !isset( $setting['callback'] ) ) {
					continue;
				}


				//Check for callback functions
				if( is_callable( array( $this->callbacks, $setting['callback'] ) ) ){
					$callback = array( $this->callbacks, $setting['callback'] );
				}
				elseif ( is_callable( $setting['callback'] ) ) {
					$callback = $setting['callback'];
				}
				else{
					continue;
				}


				$title = isset( $setting['title'] ) ? $setting['title'] : null;

			
				//Add a section
				if( $setting['type'] === 'section' ){

					$section_args = array(
						'id' 		=> $setting['id'],
						'title' 	=> $title,
						'callback' 	=> $callback,
						'page' 		=> $this->aff->admin_page_slug
					);

					$section_args = apply_filters( 'xoo_aff_setting_section_args', $section_args, $this );
					
					call_user_func_array( 'add_settings_section', array_values( $section_args ) );

				}

				//Add a setting field
				elseif( $setting['type'] === 'setting' ){

					$args 					= $setting;
					$args['option_name'] 	= $option_name;

					$setting_args 	= array(
						'id' 		=> $setting['id'],
						'title' 	=> $title,
						'callback' 	=> $callback,
						'page' 		=> $this->aff->admin_page_slug,
						'section' 	=> $setting['section'],
						'args' 		=> $args
					);

					$setting_args = apply_filters( 'xoo_aff_setting_args', $setting_args, $this );
					
					call_user_func_array( 'add_settings_field', array_values( $setting_args ) );

				}

			}

		}

	}


	public function enqueue_scripts($hook) {


		//Enqueue Styles only on plugin settings page
		if( !isset( $_GET['page'] ) || $_GET['page'] !== $this->aff->admin_page_slug || ( isset( $_GET['tab'] ) && $_GET['tab'] === 'fields' ) ) return;
		
		wp_enqueue_media(); // media gallery
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( 'xoo-aff-admin-settings-style', XOO_AFF_URL . '/admin/assets/css/xoo-aff-admin-settings-style.css', array(), XOO_AFF_VERSION, 'all' );
		wp_enqueue_script( 'xoo-aff-admin-settings-js', XOO_AFF_URL . '/admin/assets/js/xoo-aff-admin-settings-js.js', array( 'jquery','wp-color-picker'), XOO_AFF_VERSION, false );
		wp_localize_script('xoo-aff-admin-settings-js','xoo_aff_admin_settings_localize',array(
			'adminurl'  => admin_url().'admin-ajax.php',
		));


	}


	public function display_page(){
		$args = array(
			'tabs' 				=> $this->tabs,
			'admin_page_slug' 	=> $this->aff->admin_page_slug,
			'aff' 				=> $this->aff
		);
		xoo_el_helper()->get_template( "xoo-aff-admin-display.php", $args, XOO_AFF_DIR.'/admin/templates/' );
	}


}