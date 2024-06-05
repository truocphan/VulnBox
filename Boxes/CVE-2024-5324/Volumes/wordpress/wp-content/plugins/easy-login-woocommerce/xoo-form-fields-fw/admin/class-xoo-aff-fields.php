<?php

class Xoo_Aff_Fields{

	public $types = array(), $sections = array(), $settings = array(),  $fields = array(), $setting_options = array(), $cached_field_html_args_for_value;
	public $plugin_slug;
	protected $field_priority = 10;
	/* Xoo_Aff $aff */
	public $aff;
	public $db_field;


	public function __construct( Xoo_Aff $aff ){
		$this->aff = $aff;
		$this->plugin_slug = $this->aff->plugin_slug;
		$this->db_field = str_replace( '-', '_', 'xoo_aff_'.$this->plugin_slug.'_fields' );
		$this->init_hooks();
	}


	public function init_hooks(){
		if( $this->aff->is_fields_page() || $this->aff->is_fields_page_ajax_request() || ( isset( $_GET['page'] ) && $_GET['page'] === 'mlang_strings' ) ){
			add_action( 'admin_footer', array( $this, 'release_variables' ) );
			add_action( 'init', array( $this, 'set_defaults' ), 0 );
		}
		if( current_user_can( 'manage_options' ) ){

			if( isset( $_POST['is_ajax'] ) && $_POST['is_ajax'] === 'yes' ){
				add_action( 'wp_ajax_xoo_aff_save_settings', array( $this, 'save_settings') );
			}
			else{
				add_action( 'admin_init', array( $this, 'save_settings' ), 99 );
			}
			
			
			add_action( 'wp_ajax_xoo_aff_reset_settings', array( $this, 'reset_settings') );
		}
	}


	/**
	 * Get all fields data
	 *
	 * @param 	string 		$format 		Format type Array | Json
	*/

	public function get_fields_data( $format = 'array' ){

		$data = get_option( $this->db_field );

		if( $format === 'array' ){
			$data = json_decode( $data, true );
		}

		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_data', $data );
		
	}


	/**
	 * Get single field data
	 *
	 * @param 	string 		$field_id 	Field ID
	*/
	public function get_field_data( $field_id ){

		$fields_data = $this->get_fields_data();

		$data = isset( $fields_data[ $field_id ] ) ? $fields_data[ $field_id ] : false;

		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_'.$field_id.'_data', $data , $field_id );

	}


	//Get field types
	public function get_field_types(){
		return $this->types;
	}


	//Get front end fields html
	public function get_fields_layout( $args = array() ){
		
		echo '<div class="xoo-aff-fields">';

		$fields = $this->get_fields_data();

		if( empty( $fields ) ){
			echo '<p>No fields found, please go to settings & save/reset fields</p>';
			return;
		}

		foreach ( $fields as $field_id => $field_data ){
			$this->get_field_html( $field_id );
		}

		echo '</div>';

	}

	public function get_field_label( $field_id ){

		if( is_array( $field_id ) ){
			$field = $field_id;
		}
		else{
			$field = $this->get_field_data( $field_id );
		}

		$settings = $field['settings'];

		if( isset( $settings['label'] ) && $settings['label'] ){
			$label = $settings['label'];
		}elseif ( isset( $settings['placeholder'] ) && $settings['placeholder'] ) {
			$label = $settings['placeholder'];
		}else{
			$label = $settings['unique_id'];
		}

		return $label;

	}


	public function get_field_value_label( $field_id, $field_value ){
		
		if( !isset( $this->cached_field_html_args_for_value[ $field_id ] ) ){
			$args = $this->get_field_html_args( $field_id, $field_value );
			$this->cached_field_html_args_for_value[ $field_id ] = $args;
		}
		else{
			$args = $this->cached_field_html_args_for_value[ $field_id ];
		}

		if( isset( $args['options'] ) && $args['input_type'] !== 'checkbox_single' ){
			$options = $args['options'];
			if( is_array( $field_value ) ){
				foreach ( $field_value as $key => $value ) {
					if( !isset( $options[ $value ] ) ) continue;
					$field_value[ $key ] = $options[ $value ];
				}
			}
			else{
				if( isset( $options[ $field_value ] ) ){
					return $options[ $field_value ];
				}
			}
		}

		if( is_array( $field_value ) ){
			$field_value = implode( ", ", $field_value );
		}
		return $field_value;
	}


	public function get_settings_with_options(){
		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_setting_with_options', array(
			'select_list',
			'checkbox_list',
			'checkbox_single',
			'radio', 
		) );
	}


	public function get_field_html_args( $field_id, $args = array() ){

		$data = $this->get_field_data( $field_id );
	
		if( !$data || empty( $data ) || !isset( $data['input_type'] )  || !isset( $data['settings'] ) ) return;

		$input_type = $data['input_type'];
		$settings 	= $data['settings'];

		$defaults = array(
			'active' 			=> 'yes',
			'return' 			=> false,
			'input_type' 		=> $input_type,
			'placeholder' 		=> '',
			'label' 			=> '',
			'cont_class' 		=> array(
				'xoo-aff-group',
				'xoo-aff-cont-'.$input_type
			),
			'class'				=> array(),
			'custom_attributes' => array(), //key => value pair
		);

		$inputs_autocomplete = array(
			'password' 	=> 'new-password',
			'email' 	=> 'email',
			'country' 	=> 'country',
			'phone' 	=> 'tel',
			'date' 		=> 'off'
		);

		//setting default autocomplete
		if( isset( $inputs_autocomplete[ $data['input_type'] ] ) ){
			$defaults['autocomplete'] = $inputs_autocomplete[ $data['input_type'] ];
		}

		$defaults = wp_parse_args(  $data['settings'], $defaults  );

		$args = wp_parse_args( $args, $defaults );

		//Handle strings passed as array
		$handle_strings = array( 'class', 'cont_class' );
		foreach ( $handle_strings as $arg_key ) {

			if( is_string( $args[ $arg_key ] ) ){
				$args[ $arg_key ] = (array) explode( ',',  $args[ $arg_key ] );

				//Cleaning up class names
				$i=0;
				foreach ($args[$arg_key] as $class_name ) {
					if( !trim( $class_name ) ){
						unset( $args[$arg_key][$i] );
					}
					$i++;
				}
			}

		}

		if( isset( $settings['cols'] ) && $settings['cols'] ){
			$args['cont_class'][] = $settings['cols'];
		}

			
		//Field setting value which is used as add_field_form options
		$settings_with_options = $this->get_settings_with_options();


		//Converting to options format
		foreach ( $settings_with_options as $setting_key ) {

			if( isset( $settings[ $setting_key ] ) ){

				$value = $setting_key === 'checkbox_list' ? array() : '';

				foreach ( $settings[$setting_key] as $option_key => $option_data ) {

					$args['options'][$option_key] = $option_data['label'];

					if( !isset( $args['value'] ) && isset( $option_data['checked'] ) && $option_data['checked'] === 'checked' ){

						if( $setting_key === 'checkbox_list' ){
							$value[] = $option_key;
						}
						else{
							$value = $option_key;
						}

					}	

				}

				if( !isset( $args['value'] ) ){
					$args['value'] = $value;
				}
				

				unset( $data[ 'settings' ][ $setting_key ] );

				break;

			}

		}


		//Setting up value
		if( !isset( $args['value'] ) ){
			$args['value'] = isset( $args['default'] ) ? $args['default'] : '';
		}


		//Password
		if( $input_type === 'password' && isset( $settings['strength_meter'] ) && $settings['strength_meter'] === "yes" ){
			$args['custom_attributes']['check_strength'] = "yes";
			if( isset( $settings['strength_meter_pass'] ) ){
				$args['custom_attributes']['strength_pass'] = $settings['strength_meter_pass'];
			}
		}

		
		//Countries
		if( $input_type === 'country' ){
			$countries = $this->get_field_countries( $field_id );
			$args['options'] = $countries;
		}

		//Phone code
		if( $input_type === 'phone_code' ){
			$args['options'] = $this->get_field_phone_codes( $field_id );
		}

		//States
		if( $input_type === 'states' || $input_type === 'phone_code' ){
			if( isset( $settings['for_country_id'] ) && $settings['for_country_id'] ){
				$args['custom_attributes'][ 'data-country_field' ] = $settings['for_country_id'];
			}
		}

		//Set state options if there is a value, otherwise display via JS on country select
		if(  $input_type === 'states' ){
			$args['custom_attributes'][ 'data-default_state' ] = $args['value'];
		}


		//Class
		if( isset( $settings['class'] ) ){
			$data['settings']['class'] = explode( ',' , $settings['class'] );
			//cleaning up
			$i = 0;
			foreach ( $data['settings']['class'] as $class ) {
				if( !trim( $class ) ){
					unset( $data['settings']['class'][$i] );
				}
				$i++;
			}
		}

		//Adding required class
		if( isset( $settings['required'] ) && $settings['required'] === 'yes' ){
			$args['cont_class'][] = 'xoo-aff-cont-required';
		}


		$args = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_field_args', $args );

		return $args;
	}

	//Single field HTML
	public function get_field_html( $field_id, $args = array() ){



		$args = $this->get_field_html_args( $field_id, $args );
		
		if( isset( $args['active'] ) && $args['active'] !== 'yes' ) return;

		return $this->get_input_html(
			$field_id,
			$args
		);

	}


	//Set defaults
	public function set_defaults(){

		//Get default field settings
		if( empty( $this->setting_options ) ){
			$this->setting_options = include XOO_AFF_DIR.'/admin/defaults/field-setting-options.php';
		}

		//Field Types
		$this->set_default_field_types();
		//Field sections
		$this->set_default_field_sections();
		//Field settings
		$this->set_default_field_settings();

		do_action( 'xoo_aff_'.$this->plugin_slug.'_add_predefined_fields', $this ); //Hook for adding predefined fields


		if( !$this->get_fields_data() ){
			$raw_fields = $this->fields;
		}
		else{
			$raw_fields = $this->get_fields_data();
		}

		$prepared_fields = $this->prepare_fields( $raw_fields );

		update_option( $this->db_field, json_encode( $prepared_fields ) );

	}

	//Save settings
	public function save_settings(){

		if( !$this->aff->is_fields_page_ajax_request() ) return;

		if( !isset( $_POST['submit_nonce'] ) || !wp_verify_nonce( $_POST['submit_nonce'], 'xoo-aff-submit-nonce' ) ){
			wp_die( 'Cheating.' );
		}

		$fields = xff_wp_kses_post( json_decode( stripslashes( $_POST['xoo_aff_data'] ), true) );

		$fields = $this->sort_by_priority( $fields );

		$this->update_db_fields( $fields );

		if( $_POST['is_ajax'] === 'yes' ){
			wp_send_json( array(
				'success' => 1
			) );

			die();
		}
		
	}

	//Reset settings
	public function reset_settings(){

		if( !isset( $_POST['submit_nonce'] ) || !wp_verify_nonce( $_POST['submit_nonce'], 'xoo-aff-submit-nonce' ) ){
			wp_die( 'Cheating.' );
		}

		if( !$this->aff->is_fields_page_ajax_request() ) return;

		delete_option( $this->db_field );

		wp_send_json(array(
			'success' => 1
		));

	}

	protected function set_default_priority(){
		$this->field_priority += 10;
		return $this->field_priority;
	}

	public function sort_by_priority( $data = array() ){
		if( !is_array( $data ) || empty( $data ) ) return $data;
		uasort( $data, function( $a, $b ){
			if( $a['priority'] === $b['priority'] ){
				return 0;
			}
			return $a['priority'] > $b['priority']  ? 1 : -1;
		});
		return $data;
	}


	//Verify fields before save // complete missing settings value // Sort fields by priority
	public function prepare_fields( $fields = array() ){

		if( empty( $fields ) ) return;

		$fields_before = $this->fields;

		$nonDeletableFields = $this->get_nondeleteable_fields();

		//Push undeletable fields
		$fields = array_merge( $nonDeletableFields, $fields );

		//Loop field ids
		foreach ( $fields as $field_id => $field_data ) {

			if( strlen( $field_id ) < 8 ){
				unset( $fields[ $field_id ] );
				continue;
			}
			
			$type = $field_data['field_type'];

			//If field type not set, continue
			if( !isset( $this->settings[ $type ] ) ){
				unset( $fields[ $field_id ] );
				continue;
			}



			//Force default field values
			if( isset( $fields_before[ $field_id ] ) ){
				$fields[ $field_id ]['settings'] = array_merge( $field_data['settings'], $fields_before[ $field_id ]['settings'] ); 
			}

			//Loop all field settings of this type
			foreach ( $this->settings[ $type ] as $setting_id => $setting_data ) {
				//If setting value is not available in POST data, set default setting value	
				if( !isset( $field_data['settings'][ $setting_id ] ) ){
					$fields[ $field_id ][ 'settings' ][ $setting_id ] = $setting_data[ 'value' ];
				}

				//if setting value needs sorting
				if( isset( $setting_data['sort'] ) && $setting_data['sort'] === "yes" ){
					$fields[ $field_id ][ 'settings' ][ $setting_id ] = $this->sort_by_priority( $field_data['settings'][ $setting_id ] );
				}

			}

			$this->register_field_strings( $field_id );

		}

		$fields = $this->sort_by_priority( $fields );
		return $fields;

	}

	public function get_nondeleteable_fields(){

		$fields = $this->fields;
		$types 	= $this->types;
		$nonDeletableFields = array();

		foreach ( $fields as $field_id => $field_data ) {
			//If this field is not deletable, add field to database value
			if( $types[ $fields[ $field_id ]['field_type'] ]['can_delete'] !== "yes" ){
				$nonDeletableFields[ $field_id ] = $fields[ $field_id ];
			}
		}

		return $nonDeletableFields;

	}


	//Update db fields
	public function update_db_fields( $fields ){

		if( is_array( $fields ) ){
			$fields = json_encode( $fields );
		}
		else{
			json_decode( $fields );
 			if( json_last_error() != JSON_ERROR_NONE ){
 				return;
 			}
		}


		$fields = $this->prepare_fields( json_decode( $fields, true ) );


		$fields = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_before_fields_update', $fields );

		update_option( $this->db_field, json_encode( $fields ) );

		$this->fields = $fields;
	}



	//Print variables to javascript
	public function release_variables(){
		?>
		<script type="text/javascript">
			var xoo_aff_fields_layout 	= <?php echo json_encode( $this->create_fields_layout_for_js() ); ?>;
			var xoo_aff_field_types 	= <?php echo json_encode( $this->types ); ?>;
			var xoo_aff_field_sections 	= <?php echo json_encode( $this->sections ); ?>;
			var xoo_aff_db_fields		= <?php echo $this->get_fields_data('json'); ?>;
			var xoo_aff_plugin_info 	= <?php echo json_encode( array(
				'admin_page_slug' 	=> $this->aff->admin_page_slug,
				'plugin_slug' 		=> $this->aff->plugin_slug
			) ); ?>
		</script>
		<?php
	}


	/**
	 * Add fields
	 *
	 * @param  	string 		$field_id 		Unique Field ID
	 * @param 	string 		$field_type 	Field type
	 * @param 	array 		$settings 		Set settings value
	 * @param 	int 		$field_priority Priority in which field displays
	*/
	public function add_field( $field_id, $field_type, $settings, $field_priority = null ){

		if( !isset( $this->types[ $field_type ] ) || empty( $settings ) ) return; // Return if field type doesn't exist
		$field_type_data = $this->types[ $field_type ];
		
		$this->fields[ $field_id ] = array(
			'field_type' => $field_type,
			'input_type' => $field_type_data[ 'type' ],
			'settings' 	 => $settings,
			'priority'	 => !$field_priority ? $this->set_default_priority() : $field_priority
		) ;
	}


	/**
	 * Add section to field settings
	 *
	 * @param  	string 		$id 		Unique section ID
	 * @param 	string 		$title 		Title
	 * @param 	string 		$priority 	Priority
	 * @param 	array 		$args 		Extra args 
	*/
	public function add_section( $id, $title, $priority = 10, $args = array() ){
		$this->sections[ $id ] = wp_parse_args(
			$args,
			array(
				'id' 		=> $id,
				'title' 	=> $title,
				'priority' 	=> $priority
			)
		);
	}



	/**
	 * Add Field type
	 *
	 * @param  	string 		$id 		Unique Field Type ID
	 * @param 	string 		$input_type Field Input type
	 * @param 	string 		$title 		Field Type Title
	 * @param 	array 		$args 		Extra args 
	*/
	public function add_type( $id, $input_type, $title, $args = array() ){
		$this->types[ $id ] = wp_parse_args(
			$args,
			array(
				'id' 			=> $id,
				'type' 			=> $input_type,
				'title' 		=> $title,
				'icon' 			=> 'fas fa-smile',
				'is_selectable' => 'yes',
				'can_delete' 	=> 'yes',
				'is_sortable' 	=> 'yes',
			)
		);
	}


	/**
	 * Add Field Setting
	 *
	 * @param  	string 		$id 			Unique setting ID
	 * @param 	string 		$type 			Field Input type
	 * @param 	string 		$section  		Under section
	 * @param 	string 		$field_type_id 	Add to field ID
	 * @param 	string 		$title 			Setting Title
	 * @param 	array 		$args 			Extra args 
	*/
	public function add_setting( $id, $title, $type, $field_type_id, $args = array() ){

		if( !isset( $this->types[ $field_type_id ] ) ) return;

			$this->settings[ $field_type_id ][ $id ] = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_setting_add', wp_parse_args(
				$args,
				array(
					'id' 		=> $id,
					'title' 	=> $title,
					'type' 	 	=> $type,
					'section' 	=> 'basic',
					'value' 	=> '',
					'width' 	=> 'half',
					'disabled'  => '',
					'priority' 	=> '',
				)
			)
		);

	}

	/**
	  * Create fields layout for JS
	  *
	 */

	private function create_fields_layout_for_js(){

		$sort_by_sections 	= array();
		$fields_settings 	= $this->settings;

		if( empty( $fields_settings ) ) return;


		foreach ( $fields_settings as $field_type_id => $settings ) {
			$priority = 10;
			foreach ( $settings as $setting_id => $setting_data ) {
				if( !isset( $this->sections[ $setting_data['section'] ] ) ) continue; // if section name not found skip
				//Adding priority
				if( !isset( $setting_data['priority'] ) || !$setting_data['priority'] ){
					$setting_data['priority'] = $priority;
					$priority += 10;
				}
				$sort_by_sections[ $field_type_id ][ $setting_data['section'] ][] = $setting_data;
			}
		}

		$fields_layout = array();

		foreach ( $sort_by_sections as $field_type_id => $section_settings ) {
			foreach ( $section_settings as $section_id => $settings ) {
				$section = (array) $this->sections[ $section_id ];
				$section['type'] = 'section';
				$fields_layout[ $field_type_id ][] = $section;
				$fields_layout[ $field_type_id ] = array_merge( $fields_layout[ $field_type_id ], $this->sort_by_priority( $settings ) ) ;
			}
		}
		
		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_fields_layout', $fields_layout );

	}


	/** 
	  * Create field settings for 
	  *
	  * @param array 	$settings_data		Parse setting data & create settings
	*/

	public function create_field_settings( $field_type_id, $field_setting_options = array() ){

		if( empty( $field_setting_options ) ) return;

		$setting_options = $this->setting_options;

		if( !isset( $this->types[ $field_type_id ] ) ) return;

		foreach ( $field_setting_options as $setting_option_id => $setting ) {

			//Check if value is passed
			if( is_integer( $setting_option_id ) ){
				$setting_option_id = $setting;
				$setting = array();
			}

			if( !isset( $setting_options[ $setting_option_id ] ) ) continue;

			$setting = wp_parse_args(
				$setting,
				$setting_options[$setting_option_id]
			);
			
			if( !isset( $this->sections[ $setting['section'] ] ) ) continue;

			$this->add_setting( $setting['id'], $setting['title'], $setting['type'], $field_type_id, $setting );

		}

	}


	/**
	 * Get Countries by field ID
	 *
	 * @param  	string 		$field_id 		Field ID
	*/
	public function get_field_countries( $field_id ){

		$field_data = $this->get_field_data( $field_id );
		if( !$field_data  ) return;

		$countries 			= include XOO_AFF_DIR.'/countries/countries.php';
		$list_country 		= 'all';
		$settings 			= $selected_countries = array();

		if( isset( $field_data['settings'] ) ){
			$settings = $field_data['settings'];
		}

		if( isset( $settings['country_list'] ) ){
			$list_country = $settings['country_list'];
		}

		if( $list_country !== 'all' && isset( $settings['country_choose'] ) && !empty( $settings['country_choose'] ) ){
			$selected_countries = array_keys( $settings['country_choose'] );
		}

		switch ( $list_country ) {
			case 'all':
				$show_countries = $countries;
				break;

			case 'all_but':
				$show_countries = array_diff_key( $countries, array_flip( $selected_countries ) );
				break;

			case 'only':
				$show_countries = array_intersect_key( $countries, array_flip( $selected_countries ) );
				break;
			
			default:
				$show_countries = $countries;
				break;
		}

		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'_countries', $show_countries, $field_data );

	}


	/**
	 * Get Phone Codes by field ID
	 *
	 * @param  	string 		$field_id 		Field ID
	*/
	public function get_field_phone_codes( $field_id ){

		$countries 			= $this->get_field_countries( $field_id );
		$all_phone_codes 	= include XOO_AFF_DIR.'/countries/phone.php';

		$phone_codes = array_intersect_key( $all_phone_codes, $countries ); 

		return apply_filters( 'xoo_aff_'.$this->plugin_slug.'phone_codes', $phone_codes, $this->get_field_data( $field_id ) );

	}

	/**
	 * Get States by country Code
	 *
	 * @param  	string 		$country_code 		Country Code
	*/
	public function get_country_states( $country_code = '' ){
		$all_states = (array) include XOO_AFF_DIR.'/countries/states.php';
		if( $country_code ){
			return isset( $all_states[ $country_code ] ) ? $all_states[ $country_code ] : array();
		}
		else{
			return $all_states;
		}
	}


	/**
	 * Get HTML input
	 *
	 * @param  	string 		$field_id 		Field ID
	 * @param  	array 		$args 			Input options
	*/
	public function get_input_html( $field_id, $args ){

		if( !$field_id ) return;

		$defaults = array(
			'input_type' 		=> 'text',
			'value' 			=> null,
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'minlength' 		=> false,
			'max'         		=> false,
			'min' 				=> false,
			'step' 				=> false,
			'required'          => 'no',
			'autocomplete'      => false,
			'class'             => array(),
			'label_class'       => array(),
			'cont_class'		=> array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'autofocus'         => '',
			'priority'          => '',
			'icon' 				=> '',
		);


		//Providing date defaults
		if( $args['input_type'] === "date" ){
			$args['custom_attributes']['data-date'] = array(
				'dateFormat' => isset( $args['date_format'] ) ? $args['date_format'] : 'dd/mm/yy',
				'changeMonth' => true,
				'changeYear'  => true,
				'yearRange' => 'c-100:c+10',
			);
		}


		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_input_args', $args );

		$input_type = $args['input_type'];

		//Handle strings passed as array
		$handle_strings = array( 'class', 'label_class', 'cont_class' );
		foreach ( $handle_strings as $arg_key ) {

			if( is_string( $args[ $arg_key ] ) ){
				$args[ $arg_key ] = (array) explode( ',',  $args[ $arg_key ] );

				//Cleaning up class names
				$i=0;
				foreach ($args[$arg_key] as $class_name ) {
					if( !trim( $class_name ) ){
						unset( $args[$arg_key][$i] );
					}
					$i++;
				}
			}

		}

		// Custom attribute handling.
		$custom_attributes   = array();

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( $args['minlength'] ) {
			$args['custom_attributes']['minlength'] = absint( $args['minlength'] );
		}


		if ( $args['max'] ) {
			$args['custom_attributes']['max'] = $args['max'];
		}

		if ( $args['min'] ) {
			$args['custom_attributes']['min'] = $args['min'];
		}

		if ( $args['step'] ) {
			$args['custom_attributes']['step'] = $args['step'];
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['required'] === "yes" ) {
			$args['class'][] = 'xoo-aff-required';
			$args['custom_attributes']['required'] = '	';
		}

		if( isset( $args['rows'] ) ){
			$args['custom_attributes']['rows'] = $args['rows'];
		}


		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$attribute_value = is_array( $attribute_value ) ? json_encode( $attribute_value ) : $attribute_value;
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if( $input_type === 'date' ){
			$args['class'][] = 'xoo-aff-input-date';
		}

		$args['class'] = array_merge( $args['class'], array(
			'xoo-aff-'.$input_type
		) );

		$args['cont_class'][] = $field_id.'_cont';


		$custom_attributes 	= implode( ' ', $custom_attributes );
		$placeholder 		= esc_attr( $args['placeholder'] );
		$value 				= is_string( $args['value'] ) ? esc_attr( $args['value'] ) : $args['value'];
		$class 				= esc_attr( implode( ' ', $args['class'] ) );
		$cont_class 		= esc_attr( implode( ' ', $args['cont_class'] ) );

		//WPML Translation
		$placeholder 	= $this->translate_registered_string( $placeholder, $field_id.'_'.'placeholder' );
		$label 			= $this->translate_registered_string( $args['label'], $field_id.'_'.'placeholder' );
		if( !empty( $args['options'] ) & in_array( $input_type , $this->get_settings_with_options() ) ){
			foreach ( $args['options'] as $option_key => $option_label ) {
				$args['options'][ $option_key ] = $this->translate_registered_string( $option_label, $field_id.'_'.$input_type.'_'.$option_key );
			}
		}

		$field_html = '';

		$field_html = '<div class="'.$cont_class.'">'; //Open DIV parent 1

		if( $args['label'] ){
			$field_html .= '<label for='.$field_id.' class="xoo-aff-label">'.$args['label'].'</label>';
		}
		
		//Show Icons
		if( $args['icon'] ){
			$field_html .= '<div class="xoo-aff-input-group">'; //Open DIV parent 2
			$field_html .= '<span class="xoo-aff-input-icon '.esc_attr( $args['icon'] ).'"></span>';
		}

		switch ( $input_type ) {
			
			case 'password':
			case 'email':
			case 'number':
			case 'tel':
				$field_html .= '<input type="' . $input_type . '" class="' . $class . '" name="' . $field_id . '" placeholder="' . $placeholder . '"  value="' . $value . '" ' . $custom_attributes . '/>';

				if( isset( $args['password_visibility'] ) && $args['password_visibility'] === 'yes' ){
					$field_html .= '<div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div>';
				}

				break;

			case 'text':
			case 'date':
			case 'phone':
				$field_html .= '<input type="text" class="' . $class . '" name="' . $field_id . '" placeholder="' . $placeholder . '"  value="' . $value . '" ' . $custom_attributes . '/>';
				break;

			case 'textarea':
				$field_html .= '<textarea class="' . $class . '" name="' . $field_id . '" placeholder="' . $placeholder . '" ' . $custom_attributes . '>'. $value .'</textarea>';
				break;


			case 'checkbox_list':
			case 'checkbox_single':

				if( !empty( $args['options'] ) ){

					$field_id = $input_type === 'checkbox_single' ? $field_id : $field_id.'[]';
					$checkbox_html = '<div class="'.$class.'">';

					foreach ( $args['options'] as $option_value => $option_label ) {

						if( $value === $option_value ){
							$checked = 'checked';
						}
						else{
							$checked = is_array( $value ) && in_array( $option_value , $value ) ? 'checked' : '';
						}

						$checkbox_html .= '<label>'; 
						$checkbox_html .= '<input type="checkbox" name="'.$field_id.'" class="' . $class .'" value="'.$option_value.'" '.$checked.'>'.wp_kses_post( $option_label );
						$checkbox_html .= '</label>';
					}

					$checkbox_html .= '</div>';
					$field_html .= $checkbox_html;
				}
				
				break;

			case 'radio':

				if( !empty( $args['options'] ) ){

					$radio_html = '<div class="'.$class.'">';

					foreach ( $args['options'] as $option_value => $option_label ) {

						$checked = $option_value === $value ? 'checked' : '';

						$radio_html .= '<label>'; 
						$radio_html .= '<input type="radio" name="'.$field_id.'" class="' . $class .'" value="'.$option_value.'" '.$checked.'>'.wp_kses_post( $option_label );
						$radio_html .= '</label>';
					}

					$radio_html .= '</div>';
					$field_html .= $radio_html;
				}
				break;

			case 'select_list':
			case 'country':
			case 'states':

				$select_html = '<select class="'.$class.'" name="'.$field_id.'" '.$custom_attributes.'>';

				if( $placeholder ){
					$select_html .= '<option value="placeholder" disabled selected>'.wp_kses_post( $placeholder ).'</option>';
				}

				foreach ( $args['options'] as $option_value => $option_label ) {
					$selected = $value === $option_value ? 'selected' : '';
					$select_html .= '<option value="'.$option_value.'" '.$selected.'>'.wp_kses_post( $option_label ).'</option>';
				}

				$select_html .= '</select>';
				$field_html .= $select_html;
				
				break;

			case 'phone_code':

				$select_html = '<select class="'.$class.'" name="'.$field_id.'" '.$custom_attributes.'>';

				if( $placeholder ){
					$select_html .= '<option value="placeholder" disabled selected>'.esc_attr__( $placeholder, $this->plugin_slug ).'</option>';
				}

				foreach ( $args['options'] as $country_code => $country_phone_code ) {
					$selected = $value === $country_phone_code ? 'selected' : '';
					$select_html .= '<option data-country_code="'.$country_code.'" value="'.$country_phone_code.'" '.$selected.'>'.esc_attr__( $country_code, $this->plugin_slug ).' '.$country_phone_code.'</option>';
				}

				$select_html .= '</select>';
				$field_html .= $select_html;

				break;
		}

		$field_html = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_input_html', $field_html, $args ); // near input

		if( trim( $args['description'] ) ){
			$field_html .= '<p class="xoo-aff-description">'.esc_attr__( $args['description'], $this->plugin_slug ) .'</p>';
		}

		if( $args['icon'] ){
			$field_html .= '</div>';
		}

		$field_html .= '</div>';

		$field_html = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_'.$input_type.'_html', $field_html, $args ); // Specific Type

		$field_html = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_html', $field_html, $args ); // general

		if( $args['return'] ){
			return $field_html;
		}
		else{
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $field_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

	}



	/**
	 * Add default field types
	*/
	public function set_default_field_types(){
		$types = include XOO_AFF_DIR.'/admin/defaults/field-types.php';

		foreach ( $types as $type ) {
			$args = isset( $type[3] ) ? $type[3] : array();
			$this->add_type( $type[0], $type[1], $type[2], $args );
		}
	}

	/**
	 * Set default field sections
 	*/
	public function set_default_field_sections(){

		$this->add_section( 'basic', 'Basic Settings', 10 );
		$this->add_section( 'advanced', 'Advanced Settings', 20 );
	}


	/**
	 * Set default field settings
 	*/
	public function set_default_field_settings(){

		$field_settings = include XOO_AFF_DIR.'/admin/defaults/field-settings.php';

		foreach ( $field_settings as $field_type_id => $field_setting_options ) {
			$this->create_field_settings( $field_type_id, $field_setting_options );
		}

	}


	public static function sort_settings_by_section( $settings = array() ){

		if( empty( $settings ) ) return;

		$section_sorted = array();

		foreach ( $settings as $setting_id => $setting_data ) {
			$section_sorted[ $setting_data['section'] ][ $setting_id ] = $setting_data;
		}
		return $section_sorted;
	}


	public function register_field_strings( $field_id ){

		if( !class_exists( 'SitePress' ) && !function_exists('pll_register_string') ) return; //support for wpml and polylang

		$field_data = $this->get_field_data( $field_id );

		if( !$field_data ) return;

		$type = $field_data['field_type'];

		//If field type not set, continue
		if( !isset( $this->settings[ $type ] ) ) return;

		foreach ( $this->settings[ $type ] as $setting_id => $setting_data ) {

			$setting_value = $field_data['settings'][ $setting_id ];

			//WPML translate
			if( isset( $setting_data['translate'] ) && $setting_data['translate'] === "yes" && !empty( $setting_value ) ){

				//Check if setting has options
				if( is_array( $setting_value ) ){
					foreach ( $setting_value as $option_key => $option_data ) {
						if( !isset( $option_data['label'] ) || !$option_data['label'] ) break;
						$this->register_string_for_translation( $option_data['label'], $field_id.'_'.$setting_id.'_'.$option_key  );
					}
				}
				else{
					$this->register_string_for_translation( $setting_value, $field_id.'_'.$setting_id ); 
				}
			}
		}

		
	}


	public function register_string_for_translation( $string, $string_name ){

		//WPML
		if( class_exists( 'SitePress' ) ){
			do_action(
				'wpml_register_single_string',
				'easy-login-woocommerce',
				$string_name,
				$string
			);
		}

		//Polylang
		if( function_exists('pll_register_string') ){
			pll_register_string( $string_name, $string, 'easy-login-woocommerce-fields' );
		}
	}

	public function translate_registered_string( $string, $string_name ){

		//WPML
		if( class_exists( 'SitePress' ) ){
			return apply_filters(
				'wpml_translate_single_string',
				$string,
				'easy-login-woocommerce',
				$string_name
			);
		}

		//Polylang
		if( function_exists( 'pll__' ) ){
			return pll__( $string );
		}
		

		return $string;
	}


	public function validate_submitted_field_values( $values = array(), $do_not_validate_ids = array() ){

		$errors = new WP_Error();

		//If no values are provided , use POST
		if( empty( $values ) ){
			$values = $_POST;
		}

		$fields = $this->get_fields_data();

		if( empty( $fields ) ) return;

		$fieldValues = array();

		foreach ( $fields as $field_id => $field_data ) {

			$settings = $field_data[ 'settings' ];

			if( empty( $settings ) || in_array( $field_id , $do_not_validate_ids ) || $settings['active'] !== "yes") continue;

			//Field Validation
			$userVal 	= isset( $values[ $field_id ] ) ? ( is_array( $_POST[ $field_id ] ) ? array_map( 'sanitize_text_field', $_POST[ $field_id ] ) : esc_attr( trim( $values[ $field_id ] ) ) ) : '';
			$label 		= isset( $settings['label'] ) && trim( $settings['label'] ) ? trim( $settings['label'] ) : trim( $settings['placeholder'] );

			//If required and value is empty
			if( $settings['required'] === "yes" &&  !$userVal ){

				switch ( $field_data['input_type'] ) {
					case 'checkbox_single':
						$errors->add( 'not-checked', sprintf( esc_attr__( 'Please check %s.', 'easy-login-woocommerce' ), $label ), $field_id );
						break;
					
					default:
						$errors->add( 'empty', sprintf( esc_attr__( '%s cannot be empty.', 'easy-login-woocommerce' ), $label ), $field_id );
						break;
				}	

			}

			//Check min characters
			if( isset( $settings['minlength'] ) && !empty( $settings['minlength'] ) && strlen( $userVal ) < (int) $settings['minlength']  ){
				$errors->add( 'minlen', sprintf( esc_attr__( '%s needs to be minimum %s characters.', 'easy-login-woocommerce' ), $label, $settings['minlength'] ), $field_id );
			}

			//Check max characters
			if( isset( $settings['maxlength'] ) && !empty( $settings['maxlength'] ) && strlen( $userVal ) > (int) $settings['maxlength']  ){
				$errors->add( 'maxlen', sprintf( esc_attr__( '%s cannot exceed %s characters.', 'easy-login-woocommerce' ), $label, $settings['maxlength'] ), $field_id );
			}

			//Check min value
			if( isset( $settings['min'] ) && !empty( $settings['min'] ) && ( float ) $userVal < (float) $settings['min']  ){
				$errors->add( 'min', sprintf( esc_attr__( '%s should be minimum %s.', 'easy-login-woocommerce' ), $label, $settings['min'] ), $field_id );
			}

			//Check max value
			if( isset( $settings['max'] ) && !empty( $settings['max'] ) && ( float ) $userVal > (float) $settings['max']  ){
				$errors->add( 'max', sprintf( esc_attr__( '%s cannot be more than %s.', 'easy-login-woocommerce' ), $label, $settings['max'] ), $field_id );
			}

			//Check Step
			if( isset( $settings['step'] ) && $settings['step'] !== 'any' && ( (float) $userVal % (float) $settings['step'] ) !== 0 ){
				$errors->add( 'step', sprintf( esc_attr__( '%s should be in multiple of %s.', 'easy-login-woocommerce' ), $label, $settings['step'] ), $field_id );
			}

			$errors = apply_filters( 'xoo_aff_'.$this->plugin_slug.'_validate_field', $errors, $field_id, $userVal );

			$fieldValues[ $field_id ] = $userVal;

		}

		return $errors->has_errors() ? $errors : $fieldValues;

	}

	
}

?>