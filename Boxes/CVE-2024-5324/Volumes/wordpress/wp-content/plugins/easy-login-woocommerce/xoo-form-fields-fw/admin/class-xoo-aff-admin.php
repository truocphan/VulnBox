<?php

class Xoo_Aff_Admin{

	public $aff;
	public $settings;

	public function __construct( Xoo_Aff $aff ){
		$this->aff 		= $aff;
		$this->includes();
		$this->settings = new Xoo_Aff_Settings( $aff );
		$this->hooks();
	}

	public function includes(){
		require_once XOO_AFF_DIR.'/admin/settings/class-xoo-aff-settings.php';
	}

	public function hooks(){

		add_filter( 'xoo_aff_export_options', array( $this, 'export_field_options' ), 10, 2 );

		if( !$this->aff->is_fields_page() ) return;

		add_action( 'admin_footer', array( $this, 'custom_admin_css') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); 
		add_action( 'admin_footer', array( $this, 'templates') );
	}


	public function export_field_options( $options, $slug ){

		if( $slug === $this->aff->plugin_slug ){
			$options[ $this->aff->fields->db_field ] = array(
				'title' 		=> 'Fields',
				'option_key' 	=> $this->aff->fields->db_field 
			);
			$options[ 'xoo-aff-'.$this->aff->plugin_slug.'-general-options' ] = array(
				'title' 		=> 'Fields Style',
				'option_key' 	=> 'xoo-aff-'.$this->aff->plugin_slug.'-general-options'
			);
		}
		return $options;
	}


	public function enqueue_scripts(){

		wp_enqueue_style( 'jquery-ui-css', XOO_AFF_URL.'/lib/jqueryui/uicss.css' ); // Jquery UI CSS
		wp_enqueue_style( 'xoo-aff-fa', XOO_AFF_URL.'/lib/fontawesome5/css/all.min.css' ); //Font Awesome
		wp_enqueue_style( 'xoo-aff-fa-picker', XOO_AFF_URL.'/lib/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css', array(), XOO_AFF_VERSION, 'all' ); //Font Awesome Icon Picker
		wp_enqueue_style( 'xoo-aff-admin-style', XOO_AFF_URL . '/admin/assets/css/xoo-aff-admin-style.css', array(), XOO_AFF_VERSION, 'all' );

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_script( 'xoo-aff-fa-pickers', XOO_AFF_URL.'/lib/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js', array( 'jquery'), XOO_AFF_VERSION, false );

		wp_enqueue_script( 'xoo-aff-admin-js', XOO_AFF_URL . '/admin/assets/js/xoo-aff-admin-js.js', array( 'jquery','wp-color-picker', 'wp-util'), XOO_AFF_VERSION, false );
		wp_localize_script( 'xoo-aff-admin-js', 'xoo_aff_localize', array(
			'ajax_url' 		=> admin_url().'admin-ajax.php',
			'submit_nonce'	=> wp_create_nonce( 'xoo-aff-submit-nonce' ),
			'addField' 		=> apply_filters( 'xoo_aff_add_fields', true, $this->aff )
		));
	}


	public function templates(){
		include XOO_AFF_DIR.'/admin/templates/xoo-aff-template-scripts.php';
	}

	//Called by main plugin to display settings
	public function display_layout(){

		do_action( 'xoo_aff_before_displaying_fields' );

		$args = array(
			'sidebar_template' => xoo_aff_get_template( 'xoo-aff-field-selector.php',  XOO_AFF_DIR.'/admin/templates/', array(
				'field_types' 	=> $this->aff->fields->get_field_types(),
				'aff' 			=> $this->aff
			), true ),
		);
		xoo_aff_get_template( 'xoo-aff-page-markup.php',  XOO_AFF_DIR.'/admin/templates/', $args );
	}

	//Add css to admin footer
	public function custom_admin_css(){
		?>
		<style type="text/css">
			p.xoo-aff-description {font-style: italic;}

			.xoo-aff-input-group label {
			    display: block;
			    margin-bottom: 10px;
			}

			.xoo-aff-group input[type="checkbox"], .xoo-aff-group input[type="radio"] {
			    margin-right: 10px;
			}
		</style>
		<?php
	}



	public function display_page(){

		$tabs = array();

		$tabs['fields'] = array(
			'id' 	=> 'fields',
			'title' => 'Fields'
		);

		$tabs = array_merge( $tabs, $this->settings->tabs );

		$args = array(
			'tabs' 				=> $tabs,
			'admin_page_slug' 	=> $this->aff->admin_page_slug,
			'aff' 				=> $this->aff
		);
		xoo_el_helper()->get_template( "xoo-aff-page-display.php", $args, XOO_AFF_DIR.'/admin/templates/' );
	}

}


?>