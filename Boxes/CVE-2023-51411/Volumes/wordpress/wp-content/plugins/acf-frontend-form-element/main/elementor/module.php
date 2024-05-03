<?php

namespace Frontend_Admin;

use Elementor\Core\Settings\Manager as SettingsManager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'Frontend_Admin\Elementor' ) ) :

	class Elementor {
		public $form_widgets         = array();
		public $elementor_categories = array();

		public static function find_element_recursive( $elements, $widget_id ) {
			foreach ( $elements as $element ) {
				if ( $widget_id == $element['id'] ) {
					return $element;
				}

				if ( ! empty( $element['elements'] ) ) {
					$element = self::find_element_recursive( $element['elements'], $widget_id );

					if ( $element ) {
						return $element;
					}
				}
			}

			return false;
		}

		public function widgets() {
			 $widget_list      = array(
				 'general' => array(
					'acf-form' => 'ACF_Form',
					'edit_button'   => 'Edit_Button_Widget',
				 ),
			 );

			$widget_list = array_merge(
				$widget_list,
				array(
					'posts' => array(
						'edit_post'      => 'Edit_Post_Widget',
						'new_post'       => 'New_Post_Widget',
						'duplicate_post' => 'Duplicate_Post_Widget',
						'delete_post'    => 'Delete_Post_Widget',
					),
					'terms' => array(
						'edit_term'   => 'Edit_Term_Widget',
						'new_term'    => 'New_Term_Widget',
						'delete_term' => 'Delete_Term_Widget',
					),
					'users' => array(
						'edit_user'   => 'Edit_User_Widget',
						'new_user'    => 'New_User_Widget',
						'delete_user' => 'Delete_User_Widget',
					),
				)
			);

			$widget_list = apply_filters( 'frontend_admin/elementor/widget_types', $widget_list );

			 $elementor = $this->get_elementor_instance();

			 foreach ( $widget_list as $folder => $widgets ) {
				 foreach ( $widgets as $filename => $classname ) {
					 include_once __DIR__ . "/widgets/$folder/$filename.php";
					 $classname = 'Frontend_Admin\Elementor\Widgets\\' . $classname;
					 $elementor->widgets_manager->register( new $classname() );
				 }
			 }
		

			 do_action( 'frontend_admin/widget_loaded' );

		}

		public function documents(){
			$elementor = $this->get_elementor_instance();

			include_once __DIR__ . '/widgets/general/form-container.php';
			$elementor->documents->register_document_type( 'form_container', 'Frontend_Admin\Elementor\Widgets\FormContainer' );
		} 


		public function widget_categories( $elements_manager ) {
			$categories = array(
				'frontend-admin-general' => array(
					'title' => __( 'FRONTEND SITE MANAGEMENT', 'acf-frontend-form-element' ),
					'icon'  => 'fa fa-plug',
				),
			);

			foreach ( $categories as $name => $args ) {
				$this->elementor_categories[ $name ] = $args;
				$elements_manager->add_category( $name, $args );
			}

		}

		public function dynamic_tags( $dynamic_tags ) {
			\Elementor\Plugin::$instance->dynamic_tags->register_group(
				'frontend-admin-user-data',
				array(
					'title' => 'User',
				)
			);
			include_once __DIR__ . '/dynamic-tags/user-local-avatar.php';
			include_once __DIR__ . '/dynamic-tags/author-local-avatar.php';

			$dynamic_tags->register( new DynamicTags\User_Local_Avatar_Tag() );
			$dynamic_tags->register( new DynamicTags\Author_Local_Avatar_Tag() );
		}

		public function frontend_scripts() {
			wp_enqueue_style( 'fea-modal' );
			wp_enqueue_style( 'acf-global' );
			wp_enqueue_script( 'fea-modal' );
		}
		public function editor_scripts() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '-min';

			wp_enqueue_style( 'fea-icon', FEA_URL . 'assets/css/icon' . $min . '.css', array(), FEA_VERSION );
			wp_enqueue_style( 'fea-editor', FEA_URL . 'assets/css/editor' . $min . '.css', array(), FEA_VERSION );

			wp_enqueue_script( 'fea-editor', FEA_URL . 'assets/js/editor' . $min . '.js', array(), FEA_VERSION, true );

			wp_enqueue_style( 'acf-global' );
		}

		public function get_the_widget( $form_id ) {
			if ( is_array( $form_id ) ) {
				$widget_id = $form_id[0];
				$post_id   = $form_id[1];
			} else {
				return false;
			}

			if ( isset( $post_id ) ) {
				$elementor = $this->get_elementor_instance();

				$document = $elementor->documents->get( $post_id );

				if ( $document ) {
					$form = $this->find_element_recursive( $document->get_elements_data(), $widget_id );
				}

				if ( ! empty( $form['templateID'] ) ) {
					$template = $elementor->documents->get( $form['templateID'] );

					if ( $template ) {
						$global_meta = $template->get_elements_data();
						$form        = $global_meta[0];
					}
				}

				if ( ! $form ) {
					return false;
				}

				$widget = $elementor->elements_manager->create_element_instance( $form );

				return $widget;
			}
		}

		function get_elementor_instance() {
			 return \Elementor\Plugin::$instance;
		}

		function get_current_post_id() {
			$el = $this->get_elementor_instance();
			if ( isset( $el->documents ) ) {
				$current_page = $el->documents->get_current();
				if ( isset( $current_page ) ) {
					return $el->documents->get_current()->get_main_id();
				}
			}
			return get_the_ID();
		}

		public function get_form_widget( $key ){

			if ( strpos( $key, ':elementor_' ) === false ) {
				return $key;
			}
	
			// Get Template/page id and widget id
			$ids = explode( ':elementor_', $key );
	
			// If there is no widget id, there is no reason to continue 
			if( empty( $ids[1] ) ) return $key; 
			

			$form = json_decode( get_post_meta( $ids[0], '_elementor_data', true ), true );
			$widget = $this->get_widget_by_id( $form, $ids[1] );

			if( isset( $widget['settings'] ) ){
				$settings = $widget['settings'];

				if( isset( $settings['form_title'] ) ){
					$settings['title'] = $settings['form_title'];
				}else{
					$settings['title'] = '';
				}
				$settings['ID'] = $ids[0];
				$settings['id'] = $widget['id'];
				$settings['elementor_widget'] = true;
				$settings = $this->get_form_structure( $settings, $widget['id'] );
				return $settings;
			}
			return [];
	
		}
		function get_widget_by_id($arr, $id){
			foreach($arr as $child_array){
				if($child_array['id'] == $id){
					return $child_array;
				}else{
					if( ! empty( $child_array['elements'] ) ){
						$widget = $this->get_widget_by_id( $child_array['elements'], $id );
						if( $widget ) return $widget;
					}
				}
			}
			return null;
		}

		public function get_form_args( $settings, $wg_id ){
			global $fea_instance;
			$current_post_id = $fea_instance->elementor->get_current_post_id();

			if ( ! empty( $settings['admin_forms_select'] ) ) {
				$form_args = $fea_instance->form_display->get_form( $settings['admin_forms_select'] );
			} else {
				$fields = false;

				$form_attributes = [ 'data-widget' => $wg_id ];

				$instructions = isset( $settings['field_instruction_position'] ) ? $settings['field_instruction_position'] : '';
				$form_args = array(
					'id'                    => 'elementor_' .$wg_id,
					'ID'					=> $current_post_id,
					'form_attributes'       => $form_attributes,
					'default_submit_button' => 1,
					'submit_value'          => $settings['submit_button_text'],
					'instruction_placement' => $instructions,
					'html_submit_spinner'   => '',
					'label_placement'       => 'top',
					'field_el'              => 'div',
					'kses'                  => empty( $settings['allow_unfiltered_html'] ),
					'html_after_fields'     => '',
				);
				$form_args = $this->get_settings_to_pass( $form_args, $settings );

				if ( isset( $fea_instance->remote_actions ) ) {
					foreach ( $fea_instance->remote_actions as $name => $action ) {
						if ( ! empty( $settings['more_actions'] ) && in_array( $name, $settings['more_actions'] ) && ! empty( $settings[ "{$name}s_to_send" ] ) ) {
							$form_args[ "{$name}s" ] = $settings[ "{$name}s_to_send" ];
						}
					}
				}

				if ( empty( $settings['hide_field_labels'] ) && isset( $settings['field_label_position'] ) ) {
					$form_args['label_placement'] = $settings['field_label_position'];
				}
				if ( isset( $settings['no_reload'] ) && $settings['no_reload'] == 'true' || isset( $ajax_submit ) ) {
					$form_args['ajax_submit'] = true;
				}

			}

			if ( isset( $settings['show_in_modal'] ) ) {
				$form_args['_in_modal'] = true;
			}

			return $form_args;

		}

		public function get_settings_to_pass( $form_args, $settings ) {
			$form_args = array_merge( $form_args, $settings );	
			
			if( isset( $settings['show_success_message'] ) ){
				$form_args['show_update_message'] = $settings['show_success_message'];
			}

			return $form_args;
		}
		
		public function get_form_structure( $form, $wg_id ) {
			$form = $this->get_form_args( $form, $wg_id );
			
			if ( empty( $form['fields_selection'] ) ) {
				return $form;
			}
	
			$wg_id = str_replace( 'elementor_', '', $form['id'] );
	
			$form['fields'] = array();
	
			if ( isset( $form['fields_selection'] ) ) {
				foreach ( $form['fields_selection'] as $ind => $form_field ) {
					$form_field = wp_parse_args( $form_field, [
						'field_label_on' => 1,
						'field_label' => '',
						'field_type' => 'acf_fields',
						'field_instruction'  => '',
						'field_required'      => 0,
						'field_placeholder'   => '',
						'field_hidden'		  => 0,
						'field_default_value' => '',
						'field_disabled'      => 0,
						'field_readonly'      => 0,
						'minimum'           => 0,
						'maximum'           => 0,
						'prepend'       => '',
						'append'        => '',
						'fields_select' => [],
						'fields_select_exclude' => [],	
					] );

					$local_field = false;
					$acf_field_groups = false;
					$acf_fields = false;

					if( 'ACF_fields' == $form_field['field_type'] ){
						if( $form_field['fields_select'] ){
							foreach( $form_field['fields_select'] as $selected ){
								$selected_field = acf_maybe_get_field( $selected );

								if( $selected_field ){
									$selected_field['class'] .= 'elementor-repeater-item-' . $form_field['_id'];
									$form['fields'][$selected] = $selected_field;
								}
							}
						}
					}elseif( 'ACF_field_groups' == $form_field['field_type'] ){
						if( $form_field['field_groups_select'] ){
							if( empty( $form_field['fields_select_exclude'] ) ){
								$form_field['fields_select_exclude'] = [];
							}
							foreach( $form_field['field_groups_select'] as $group ){
								$selected_fields = acf_get_fields( $group );
								if( $selected_fields ){
									foreach( $selected_fields as $selected ){
										if( in_array( $selected['key'], $form_field['fields_select_exclude'], true ) ) continue;
		
										$selected['class'] .= 'elementor-repeater-item-' . $form_field['_id'];
										$form['fields'][$selected['key']] = $selected;
									}
								}
							}
						}
					}else{
						switch ( $form_field['field_type'] ) {							
							case 'column':
								if ( $form_field['endpoint'] == 'true' ) {
									$fields[] = array(
										'column' => 'endpoint',
									);
								} else {
									$column = array(
										'column' => $form_field['_id'],
									);
									if ( $form_field['nested'] ) {
										$column['nested'] = true;
									}
		
									$fields[] = $column;
								}
								break;
							case 'tab':
								if ( $form_field['endpoint'] == 'true' ) {
									$fields[] = array(
										'tab' => 'endpoint',
									);
								} else {
									$tab      = array(
										'tab' => $form_field['_id'],
									);
									$fields[] = $tab;
								}
								break;
							case 'recaptcha':
								$local_field = array(
									'key'          => $wg_id . '_' . $form_field['field_type'] . '_' . $form_field['_id'],
									'type'         => 'recaptcha',
									'wrapper'      => array(
										'class' => '',
										'id'    => '',
										'width' => '',
									),
									'required'     => 0,
									'version'      => $form_field['recaptcha_version'],
									'v2_theme'     => $form_field['recaptcha_theme'],
									'v2_size'      => $form_field['recaptcha_size'],
									'site_key'     => $form_field['recaptcha_site_key'],
									'secret_key'   => $form_field['recaptcha_secret_key'],
									'disabled'     => 0,
									'readonly'     => 0,
									'v3_hide_logo' => $form_field['recaptcha_hide_logo'],
								);
								break;
							case 'step':
								$local_field         = acf_get_valid_field( $form_field );
								$local_field['type'] = 'form_step';
								$local_field['key']  = $local_field['name'] = $wg_id . '_' . $form_field['field_type'] . '_' . $form_field['_id'];
								break;
							default:
								if ( isset( $form_field['__dynamic__'] ) ) {
									$form_field = $this->parse_tags( $form_field );
								}
								$default_value = $form_field['field_default_value'];
								$local_field   = array(
									'label'         => '',
									'wrapper'       => array(
										'class' => '',
										'id'    => '',
										'width' => '',
									),
									'instructions'  => $form_field['field_instruction'],
									'required'      => ( $form_field['field_required'] ? 1 : 0 ),
									'placeholder'   => $form_field['field_placeholder'],
									'default_value' => $default_value,
									'disabled'      => $form_field['field_disabled'],
									'readonly'      => $form_field['field_readonly'],
									'min'           => $form_field['minimum'],
									'max'           => $form_field['maximum'],
									'prepend'       => $form_field['prepend'],
									'append'        => $form_field['append'],
								);
		
								if ( isset( $data_default ) ) {
									$local_field['wrapper']['data-default']       = $data_default;
									$local_field['wrapper']['data-dynamic_value'] = $default_value;
								}
		
								if ( $form_field['field_hidden'] ) {
									$local_field['frontend_admin_display_mode'] = 'hidden';
								}
		
								if ( $form_field['field_type'] == 'message' ) {
									$local_field['type']    = 'message';
									$local_field['message'] = $form_field['field_message'];
									$local_field['name']    = $local_field['key'] = $wg_id . '_' . $form_field['_id'];
								}
		
								break;
						}
		
						if ( isset( $local_field ) ) {
							foreach ( feadmin_get_field_type_groups() as $name => $group ) {
		
								if ( in_array( $form_field['field_type'], array_keys( $group['options'] ) ) ) {
									$action_name = explode( '_', $name )[0];
									if ( isset( fea_instance()->local_actions[ $action_name ] ) ) {
										$action   = fea_instance()->local_actions[ $action_name ];
										$local_field = $action->get_fields_display(
											$form_field,
											$local_field,
											$wg_id
										);
		
										if ( isset( $form_field['field_label_on'] ) ) {
											$field_label          = ucwords( str_replace( '_', ' ', $form_field['field_type'] ) );
											$local_field['label'] = ( $form_field['field_label'] ? $form_field['field_label'] : $field_label );
										}
		
										if ( isset( $local_field['type'] ) ) {
		
											if ( $local_field['type'] == 'number' ) {
												$local_field['placeholder']   = $form_field['number_placeholder'];
												$local_field['default_value'] = $form_field['number_default_value'];
											}
		
											if ( $form_field['field_type'] == 'taxonomy' ) {
												$taxonomy            = ( isset( $form_field['field_taxonomy'] ) ? $form_field['field_taxonomy'] : 'category' );
												$local_field['name'] = $wg_id . '_' . $taxonomy;
												$local_field['key']  = $wg_id . '_' . $taxonomy;
											} else {
												$local_field['name'] = $wg_id . '_' . $form_field['field_type'];
												$local_field['key']  = $wg_id . '_' . $form_field['field_type'];
											}
										}
		
										if ( ! empty( $form_field['default_terms'] ) ) {
											$local_field['default_terms'] = $form_field['default_terms'];
										}
									}
									break;
								}
							}
						}
						if ( isset( $local_field['label'] ) ) {
		
							if ( empty( $form_field['field_label_on'] ) ) {
								$local_field['field_label_hide'] = 1;
							} else {
								$local_field['field_label_hide'] = 0;
							}
						}
		
						if ( isset( $form_field['button_text'] ) && $form_field['button_text'] ) {
							$local_field['button_text'] = $form_field['button_text'];
						}
		
						$local_field['key'] = 'field_' . $wg_id . $form_field['_id'];
						$local_field['name'] = $local_field['key'];
						$local_field['wrapper']['class'] = ' elementor-repeater-item-' . $form_field['_id'];
		
						$form['fields'][ $local_field['key'] ] = $local_field;
							
					}
				}
			}
			unset( $form['fields_selection'] );
	
			//error_log( print_r( $form['fields'],true) );
			return $form;
		}

		public function parse_tags( $settings ) {
			$dynamic_tags = $settings['__dynamic__'];
			foreach ( $dynamic_tags as $control_name => $tag ) {
				$settings[ $control_name ] = $tag;
			}
			return $settings;
		}

		public function delete_ghost_fields(){
			global $wpdb;
			$wpdb->delete( $wpdb->prefix . 'posts', [ 'post_type' => 'acf-field', 'post_parent' => 0 ] );
		
		}

		public function __construct() {
			include_once __DIR__ . '/classes/content_tab.php';
			include_once __DIR__ . '/classes/modal.php';

			add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categories' ) );
			add_action( 'elementor/widgets/register', array( $this, 'widgets' ) );
			//add_action( 'elementor/documents/register', array( $this, 'documents' ) );

			add_action( 'elementor/dynamic_tags/register', array( $this, 'dynamic_tags' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'frontend_scripts' ) );
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

			add_filter( 'frontend_admin/forms/get_form', [ $this, 'get_form_widget' ] );

			add_filter( 'frontend_admin/elementor/form/args', [ $this, 'get_form_structure' ], 10, 2 );

			add_action( 'init', array( $this, 'delete_ghost_fields' ) );

		}
	}

	fea_instance()->elementor = new Elementor();

endif;
