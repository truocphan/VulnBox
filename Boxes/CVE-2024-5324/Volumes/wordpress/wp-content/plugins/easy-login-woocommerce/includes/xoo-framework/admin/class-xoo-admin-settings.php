<?php

class Xoo_Admin{

	public $data 		= array();

	public $tabs 		= array();

	public $sections 	= array();

	public $settings  	= array();

	public $raw_settings = array();

	public $tabPriority = 10;

	public $helper;

	public $settings_slug = '';

	public $viewsPath = '';

	public $hasPRO = false;

	public $capability = 'manage_options';

	public function __construct( $helper ){
		$this->helper 			= $helper;
		$this->settings_slug 	= $this->helper->slug . '-settings';

		if( is_dir( $this->helper->path .'/admin/views' ) ){
			$this->viewsPath = $this->helper->path .'/admin/views';
		}

		$this->hooks();
	}

	public function is_settings_page(){
		return isset( $_GET['page'] ) && $_GET['page'] === $this->settings_slug;
	}

	public function is_settings_page_request(){
		return isset( $_POST['slug'] ) && $_POST['slug'] === $this->helper->slug;
	}

	public function hooks(){
		
		if( $this->is_settings_page_request() ){
			add_action( 'wp_ajax_xoo_admin_settings_save', array( $this, 'save_settings' ), 5 );
			add_action( 'wp_ajax_xoo_admin_settings_export', array( $this, 'export_settings' ) );
			add_action( 'wp_ajax_xoo_admin_settings_import', array( $this, 'import_settings' ) );
		}


		add_action( 'init', array( $this, 'reset_settings' ) );
		add_action( 'init', array( $this, 'save_default_settings' ) );

		if( $this->is_settings_page() ){

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts') );

			add_action( 'wp_loaded', array( $this, 'set_info_tab' ) );
			add_action( 'xoo_tab_page_start', array( $this, 'info_tab_data' ), 10, 2 );
			add_action( 'xoo_tab_page_start', array( $this, 'shortcode_info' ), 20, 2 );
			
		}
	}

	public function export_settings(){

		$options = $_POST['options'];

		$data = array();

		foreach ( $options as $option_key ) {
			$data[ $option_key ] = get_option( $option_key, true);
		}

		wp_send_json( $data );

	}

	public function import_settings(){
		
		$settings  = $_POST['import'];
	
		$options = json_decode( html_entity_decode( stripslashes ($settings ) ), true );

		foreach ( $options as $key => $value ) {
			update_option( $key, $value );
		}
			
		die();

	}

	//Add info tab
	public function set_info_tab(){
		$this->register_tab( 'Info', 'info', '' );
	}

	public function info_tab_data( $tab_id, $tab_data ){
		if( $tab_id !== 'info' ) return;
		echo $this->helper->get_outdated_section();
		?>
		<p>
			<h3>How to translate or change text?</h3>
			<h4>The easiest method is to use a plugin called <a href="https://wordpress.org/plugins/loco-translate/" target="__blank">Loco translate</a>. You can easily change text from your dashboard</h4>
		</p>
		<?php
	}

	public function shortcode_info( $tab_id, $tab_data ){
		if( $tab_id !== 'info' || !$this->viewsPath || !file_exists( $this->viewsPath.'/settings/shortcode-info.php' ) ) return;
		$args = array(
			'shortcodes' => include $this->viewsPath.'/settings/shortcode-info.php'
		);
		$this->helper->get_template( '/admin/templates/global/info-shortcode.php', $args, XOO_FW_DIR );
	}

	public function save_default_settings(){

		if( !current_user_can( $this->capability ) ) return;

		foreach ( $this->settings as $tab_id => $sections ) {

			if( !isset( $this->tabs[ $tab_id ][ 'option_key' ] ) ) continue;

			$option_key = $this->tabs[ $tab_id ][ 'option_key' ];

			$savedOptions = (array) get_option( $option_key, true );

			foreach ( $sections as $settings ) {
				foreach ( $settings as $setting_id => $setting_data ) {
					if( isset( $savedOptions[ $setting_id ] ) ) continue;
					$savedOptions[ $setting_id ] = isset( $setting_data['default'] ) ? $setting_data['default'] : '';
				}
			}

			update_option( $option_key, $savedOptions );
		}

	}


	public function reset_settings(){


		if( !current_user_can( $this->capability ) ) return;

		if( !isset( $_GET['reset'] ) || !isset( $_GET['page'] ) || $this->settings_slug !== $_GET['page'] ) return;

			// Check for nonce security      
		if ( !wp_verify_nonce( $_GET['reset'], 'reset' ) ) {
			die('cheating');
		}


		foreach ( $this->settings as $tab_id => $sections ) {

			if( !isset( $this->tabs[ $tab_id ][ 'option_key' ] ) ) continue;

			update_option( $this->tabs[ $tab_id ][ 'option_key' ], array() );

		}

		wp_safe_redirect( esc_url( remove_query_arg( 'reset' ) ) );

	}


	public function save_settings(){

		// Check for nonce security      
		if ( !wp_verify_nonce( $_POST['xoo_ff_nonce'], 'xoo-ff-nonce' ) ) {
			die('cheating');
		}

		if( !current_user_can( $this->capability ) ) return;

		$formData = array();
		$parseFormData = parse_str( $_POST['form'], $formData );

		$formData = apply_filters( 'xoo_admin_settings_'.$this->helper->slug.'_save_data', $formData );

		do_action( 'xoo_admin_settings_'.$this->helper->slug.'_before_saving', $formData );

		foreach ( $formData as $option_key => $option_data ) {
			$option_data = stripslashes_deep( $option_data );
			update_option( $option_key, $option_data );
		}

		do_action( 'xoo_admin_settings_'.$this->helper->slug.'_saved', $formData );

		wp_send_json(array(
			'error' 	=> 0,
			'notice' 	=> 'Settings Saved',
		));
	}




	public function enqueue_scripts() {

		do_action( 'xoo_as_enqueue_scripts', $this->helper->slug );

		//Select2 CSS file
	    wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');
	    //select2js
	    wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');
		
		wp_enqueue_media(); // media gallery
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'xoo-admin-style', XOO_FW_URL . '/admin/assets/css/xoo-admin-style.css', array(), XOO_FW_VERSION, 'all' );
		wp_enqueue_script( 'xoo-admin-js', XOO_FW_URL . '/admin/assets/js/xoo-admin-js.js', array( 'jquery','wp-color-picker'), XOO_FW_VERSION, false );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_localize_script( 'xoo-admin-js', 'xoo_admin_params', array(
			'adminurl'  => admin_url().'admin-ajax.php',
			'nonce' 	=> wp_create_nonce('xoo-ff-nonce'),
			'slug' 		=> $this->helper->slug 
		) );

	}

	public function register_menu_page( $args = array() ){

		$args = wp_parse_args( $args, array(
			'title' 		=> 'Settings',
			'menu_title' 	=> 'Settings',
			'capability' 	=> $this->capability,
			'slug' 			=> $this->settings_slug,
			'callback' 		=> array( $this,'settings_page_markup' ),
			'position' 		=> null,
			'icon' 			=> '',
			'has_submenu' 	=> false,
		) );

		extract( $args );

		add_menu_page(
			$title,
			$menu_title,
			$capability,
			$slug,
			$callback,
			$icon,
			$position
		);

		if( $has_submenu ){
			add_submenu_page(
				$slug,
				'Settings',
				'Settings',
	    		$capability,
	    		$slug,
	    		$callback
	    	);
		}
	}

	public function register_as_submenu_page( $args = array() ){
		$args = wp_parse_args( $args, array(
			'parent_slug' 	=> 'settings',
			'title' 		=> 'Settings',
			'menu_title' 	=> 'Settings',
			'capability' 	=> $this->capability,
			'slug' 			=> $this->settings_slug,
			'callback' 		=> array( $this,'settings_page_markup' ),
			'position' 		=> null,
		) );

		extract( $args );

		add_submenu_page(
			$parent_slug,
			$title,
			$menu_title,
    		$capability,
    		$slug,
    		$callback
    	);

	}

	public function register_tab( $title, $id, $option_key = '', $pro = 'no', $args = array() ){

		$args = wp_parse_args(
			$args,
			array(
				'priority' => ''
			)
		);

		$priority 	= $args['priority'];
		unset( $args['priority'] );

		$this->tabs[ $id ] = array(
			'title' 		=> $title,
			'id' 			=> $id,
			'option_key' 	=> $option_key,
			'priority' 		=> $priority,
			'pro' 			=> $pro,
			'args' 			=> $args
		); 
	}

	public function register_section( $title, $id, $tab_id, $desc = '', $pro = 'no', $args = array() ){

		$args = wp_parse_args(
			$args,
			array(
				'priority' => ''
			)
		);

		$priority 	= $args['priority'];
		unset( $args['priority'] );

		$this->sections[ $tab_id ][ $id ] = array(
			'title' 	=> $title,
			'id' 		=> $id,
			'tab' 		=> $tab_id,
			'priority' 	=> $priority,
			'desc' 		=> $desc,
			'pro' 		=> $pro,
			'args' 		=> $args
		);
	}

	public function register_setting( $callback, $title, $id, $section_id, $tab_id, $default = '', $desc = '', $pro = 'no', $args = array() ){

		if( !isset( $this->tabs[ $tab_id ] ) || !isset( $this->sections[ $tab_id ][ $section_id ] ) ) return;

		if( $pro === "yes" ){
			$this->hasPRO = true;
		}

		$args = wp_parse_args(
			$args,
			array(
				'priority' 	=> ''
			)
		);

		$priority 	= $args['priority'];

		unset( $args['priority'] );

		$this->settings[ $tab_id ][ $section_id ][ $id ] = $this->raw_settings[] = array(
			'callback' 		=> $callback,
			'title' 		=> $title,
			'id' 			=> $id,
			'section_id' 	=> $section_id,
			'tab_id' 		=> $tab_id,
			'priority' 		=> $priority ,
			'default' 		=> $default,
			'desc' 			=> $desc,
			'pro' 			=> $pro,
			'args' 			=> $args
		);
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



	public function sort(){

		//Sort Tabs
		$this->tabs = $this->sort_by_priority( $this->tabs );

		//Sort Section
		foreach ( $this->sections as $tab_id => $sections ) {

			$priority = 10;

			foreach ( $sections as $section_id => $section_data ) {
				if( !$section_data['priority'] ){
					$this->sections[ $tab_id ][ $section_id ]['priority'] = $priority;
					$priority += 10;
				}
			}


			$this->sections[ $tab_id ] = $this->sort_by_priority( $this->sections[ $tab_id ] );
		}


		//Sorting settings by tabs & sections
		$sorted_settings = array();

		foreach ( $this->tabs as $tab_id => $tab_data ) {

			if( !isset( $this->settings[ $tab_id ] ) ) continue;

			$sorted_settings[ $tab_id ] = $this->settings[ $tab_id ];

			if( !isset( $this->sections[ $tab_id ] ) ) continue;

			foreach ( $this->sections[ $tab_id ] as $section_id => $section_data ) {

				if( !isset( $this->settings[ $tab_id ][ $section_id ] ) ) continue;

				$sorted_settings[ $tab_id ][ $section_id ] = $this->settings[ $tab_id ][ $section_id ];
			}

		}

		$this->settings = $sorted_settings;


		foreach ( $this->settings as $tab_id => $sections ) {

			foreach ( $sections as $section_id => $settings ) {

				$priority = 10;

				foreach ( $settings as $setting_id => $setting_data ) {

					if( !$setting_data['priority'] ){
						$this->settings[ $tab_id ][ $section_id ][ $setting_id ]['priority'] = $priority;
						$priority += 10;
					}
				}

				$this->settings[ $tab_id ][ $section_id ] = $this->sort_by_priority( $this->settings[ $tab_id ][ $section_id ] );

			}

		}

	}


	public function auto_generate_settings(){

		if( !is_dir( $this->viewsPath ) ) return;

		$tabs 		= (array) include $this->viewsPath.'/tabs.php';
		$sections 	= (array) include $this->viewsPath.'/sections.php';

		if( empty( $tabs ) || empty( $sections ) ) return;

		//Register Tabs
		foreach ( $tabs as $tab_id => $tab_data ) {
			 $this->register_tab(
			 	$tab_data['title'],
			 	$tab_data['id'],
			 	$tab_data['option_key'],
			 	isset( $tab_data['pro'] ) ? $tab_data['pro'] : 'no',
			 	isset( $tab_data['args'] ) ? $tab_data['args'] : array()
			 );
		}

		//Register Sections
		foreach ( $sections as $section_data ) {
			$this->register_section(
			 	$section_data['title'],
			 	$section_data['id'],
			 	$section_data['tab'],
			 	isset( $section_data['desc'] ) ? $section_data['desc'] : '',
			 	isset( $section_data['pro'] ) ? $section_data['pro'] : 'no',
			 	isset( $section_data['args'] ) ? $section_data['args'] : array()
			 );
		}

		//Register Settings
		$settings_folder = $this->viewsPath.'/settings';

		if( !is_dir( $settings_folder ) ) return;

		$settings_files = scandir( $settings_folder );

		foreach ( $settings_files as $setting_file ) {

			$tabID = pathinfo( $setting_file , PATHINFO_FILENAME );
			if( !isset( $tabs[ $tabID ] ) ) continue;
			$tab_settings = (array) include $settings_folder .'/'. $setting_file;

			foreach ( $tab_settings as $setting_data ) {
				$this->register_setting(
					$setting_data['callback'],
				 	$setting_data['title'],
				 	$setting_data['id'],
				 	$setting_data['section_id'],
				 	$tabID,
				 	isset( $setting_data['default'] ) ? $setting_data['default'] : '',
				 	isset( $setting_data['desc'] ) ? $setting_data['desc'] : '',
				 	isset( $setting_data['pro'] ) ? $setting_data['pro'] : 'no',
				 	isset( $setting_data['args'] ) ? $setting_data['args'] : array()
				);
			}

		}

	}


	public function settings_page_markup(){

		$this->sort();

		$args = array(
			'adminObj' 	=> $this,
			'settings' 	=> $this->settings,
			'tabs' 		=> $this->tabs,
			'hasPRO' 	=> $this->hasPRO
		);

		$args = apply_filters( 'xoo_admin_settings_output_args', $args, $this->helper->slug, $this );

		$this->helper->get_template( '/admin/templates/xoo-admin-settings-output.php', $args, XOO_FW_DIR  );
	}


	public function get_setting_upload_markup( $id, $value = '' ){
		$args = array(
			'id' => $id,
			'value' => $value
		);
		return $this->helper->get_template( '/admin/templates/global/setting-upload.php', $args, XOO_FW_DIR, true  );
	}


	public function create_settings_html( $tab_id ){

		if( !isset( $this->settings[ $tab_id ] ) ) return;

		$html = '';

		$tab_settings 	= $this->settings[ $tab_id ];
		$option_key 	= $this->tabs[ $tab_id ]['option_key'];
		$option_value 	= (array) get_option( $option_key, true );

		foreach ( $tab_settings as $section_id => $settings ) {
				
			$section_container 	= '<div class="%1$s">%2$s</div>';
			$section_settings  	= $section_heading = '';
			$section_data 		= $this->sections[ $tab_id ][ $section_id ];

			foreach ( $settings as $setting_id => $setting_data ) {
				$id 	= $option_key.'['.$setting_id.']';
				$value 	= isset( $option_value[ $setting_id ] ) ? $option_value[ $setting_id ] : null;
				$section_settings .= $this->get_setting_html( $id, $setting_data, $value );
			}

			if( $section_settings ){

				$section_heading = '<span class="xoo-asc-head xoo-asc-'.$section_id.'">'.$section_data['title'].'</span>';

				if( $section_data['desc'] ){
					$section_heading .= '<span class="xoo-asc-desc">'.$section_data['desc'].'</span>';
				}

			}

			$section_class = array(
				'xoo-ass-'.$tab_id.'-'.$section_id
			);

			if( $section_data['pro'] === "yes" ){
				$section_class[] = 'xoo-ass-pro-sec';
			}

			$html .= sprintf( $section_container, implode( " ", $section_class ) , $section_heading . $section_settings );

		} 

		echo $html;

	}


	public function get_setting_html( $field_id, $field_args, $value = null ){

		$field_args = wp_parse_args( $field_args, array(
			'callback' 			=> 'text',
			'default' 			=> '',
			'desc' 				=> '',
			'pro' 				=> 'no',
			'label_class' 		=> array(),
			'container_class' 	=> array(),
		) );

		extract( $field_args );

		if ( is_null( $value ) ) {
			$value = $default;
		}

		$custom_attributes = isset( $args['custom_attributes'] ) ? $args['custom_attributes'] : array();

		if( $callback === 'sortable' && isset( $args['sort_options'] ) ){
			$custom_attributes['data-options'] = $args['sort_options'];
		}

		if( $callback === 'select' && isset( $args['select2box'] ) ){
			$custom_attributes['data-select2box'] = $args['select2box'] ? 'yes' : 'no';
			if( isset( $args['multiple'] ) && $args['multiple'] ){
				$custom_attributes['multiple'] = '';
			}
			$container_class[] = 'xoo-as-select2box';
		}


		$custom_attributes_html = array();

		if ( ! empty( $custom_attributes ) && is_array( $custom_attributes ) ) {
			foreach ( $custom_attributes as $attribute => $attribute_value ) {
				$attribute_value = is_array( $attribute_value ) ? json_encode( $attribute_value ) : $attribute_value;
				$custom_attributes_html[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		$custom_attributes 	= implode( ' ', $custom_attributes_html );

		$container_class = array_merge( $container_class, array(
			'xoo-as-setting', 'xoo-as-'.$callback
		) );

		if( $pro === "yes" ){
			$container_class[] = 'xoo-as-is-pro';
		}

		if( $callback === 'radio' && isset( $args['options'], $args['has_asset'] ) ){
			$container_class[] = 'xoo-as-has-asset';
		}

		$label_class = array_merge( $label_class, array(
			'xoo-as-label'
		) );



		$field_container = '<div class="%1$s" data-setting="%3$s">%2$s</div>';

		$field = '';
		switch ( $callback ) {

			case 'text':
			case 'number':
				$field .= '<input type="'.$callback.'" name="'.$field_id.'" value="'.$value.'" '.$custom_attributes.'>';
				break;

			case 'textarea':
				$rows  	= isset( $args['rows'] ) ? $args['rows'] : 4;
				$cols 	= isset( $args['cols'] ) ? $args['cols'] : 50;
				$field .= '<textarea name="'.$field_id.'" rows="'.$rows.'" cols="'.$cols.'" '.$custom_attributes.'>'.$value.'</textarea>';
				break;

			case 'color':
				$field .= '<input type="text" name="'.$field_id.'" class="xoo-as-color-input" value="'.$value.'" '.$custom_attributes.'>';
				break;

			case 'checkbox':
				$field 	= '<label class="xoo-as-switch">';
				$field .= '<input type="hidden" name="'.$field_id.'" value="no">';
				$field .= '<input name='.$field_id.' type="checkbox" value="yes" '.checked( $value, 'yes', false ).' '.$custom_attributes.'>';
				$field .= '<span class="xoo-as-slider"></span>';
				$field .= '</label>';
				break;

			case 'checkbox_list':

				if( !isset( $args['options'] ) || empty( $args['options'] ) ) break;

				$field .= '<input name="'.$field_id.'[]" type="hidden" value="">';

				foreach ( $args['options'] as $option_key => $option_label ) {

					$checked 	= 	is_array( $value ) && in_array( $option_key, $value ) ? 'checked' : '';
					$pro_class 	= 	is_array( $pro ) && in_array( $option_key, $pro ) ? 'xoo-as-is-pro' : '';

					$checkbox_list = '<label class="%1$s">%2$s</label>';

					$list_html  = '<input name="'.$field_id.'[]" type="checkbox" value="'.$option_key.'" '.$checked.'>';
					$list_html .= '<span>'.$option_label.'</span>';

					$field .= sprintf( $checkbox_list, $pro_class, $list_html );

				}

				break;


			case 'select':

				if( !isset( $args['options'] ) || empty( $args['options'] ) ) break;

				$select 	= '<select name="%1$s" '.$custom_attributes.'>%2$s</select>';
				$options 	= '';


				foreach ( $args['options'] as $option_key => $option_label ) {
					$selected = is_array( $value ) ? in_array( $option_key , $value ) : $value === $option_key;
					$selected = $selected ? 'selected' : '';
					$options .= '<option value="'.$option_key.'" '.$selected.' data-cc="asd">'.$option_label.'</option>';
				}

				$select_field_id = isset( $args['multiple'] ) && $args['multiple'] ? $field_id.'[]' : $field_id;

				$field .= sprintf( $select, $select_field_id, $options );

				break;


			case 'radio':

				if( !isset( $args['options'] ) || empty( $args['options'] ) ) break;

				$has_asset 		= isset( $args['has_asset'] );
				$asset_type 	= isset( $args['asset_type'] ) ? $args['asset_type'] : 'text';

				foreach ( $args['options'] as $option_key => $option_label ) {

					$radio_list 		= '<label>%1$s</label>';

					$list_html  		= '<input name="'.$field_id.'" type="radio" value="'.$option_key.'" '.checked( $value, $option_key, false ).'>';

					$label_container 	= '<span class="xoo-as-radio-label">%s</span>';
					$label_html 		= '';

					if( $has_asset ){

						if( $asset_type === 'icon' ){
							$label_html .= '<span class="xoo-as-ra-icon '.$option_key.'"></span>';
						}elseif ( $asset_type === 'image' ) {
							$label_html .= '<img src="'.$option_key.'">'.$option_label;
						}
						else{
							$label_html .= $option_label;
						}
						
					}
					else{
						$label_html .= $option_label;
					}
						
					$list_html .= sprintf( $label_container, $label_html );	

					$field .= sprintf( $radio_list, $list_html );

				}
				
				break;


			case 'links':

				if( !isset( $args['options'] ) || empty( $args['options'] ) ) break;

				foreach ( $args['options'] as $url => $label ) {
					$field .= sprintf( '<a href="%1$s">%2$s</a>', $url, $label );
				}

				break;


			case 'sortable':

				if( !isset( $args['options'] ) || empty( $args['options'] ) ) break;

				$sortedOptions = array();

				foreach ( $value as $optionKey ) {
					$sortedOptions[ $optionKey ] = $args['options'][$optionKey];
				}

				$sort_container = '<ul data-id="%1$s" class="%2$s" '.$custom_attributes.'>%3$s</ul>';
				$sort_children  = '';


				foreach ( $sortedOptions as $option_key => $option_label ) {

					$child 	= '<li>%1$s %2$s</li>';
					$input 	= '<input name="'.$field_id.'[]" type="hidden" value="'.$option_key.'">';

					$sort_children .= sprintf( $child, $option_label, $input );

				}

				$display = isset( $args['display'] ) ? $args['display'] : 'vertical';

				$field .= sprintf( $sort_container, $field_id, $display.' xoo-as-sortable-list', $sort_children );
				
				break;

			case 'upload':
				$field .= $this->get_setting_upload_markup( $field_id, $value );
			
			default:
				# code...
				break;
		}

		$field = apply_filters( 'xoo_admin_setting_field_callback_html', $field, $field_id, $value, $args );

		if( $desc ){
			$field .= '<span class="xoo-as-desc">'.$desc.'</span>';
		}

		$label = '<div class="xoo-as-label">'.$title.'</div>';
		$field = $label.'<div class="xoo-as-field">'.$field.'</div>';

		$container_class 	= implode( ' ' , $container_class );
		$field 				= sprintf( $field_container, $container_class, $field, $callback );

		return apply_filters( 'xoo_admin_setting_field', $field, $field_id, $value, $args );

	}

}