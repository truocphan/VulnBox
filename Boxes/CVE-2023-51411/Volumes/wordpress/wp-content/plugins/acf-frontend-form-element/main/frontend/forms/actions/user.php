<?php
namespace Frontend_Admin\Actions;

use Frontend_Admin\Plugin;
use Frontend_Admin;
use Frontend_Admin\Classes\ActionBase;
use Frontend_Admin\Forms\Actions;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'ActionUser' ) ) :

	class ActionUser extends ActionBase {


		public function get_name() {
			return 'user';
		}

		public function get_label() {
			return __( 'User', 'acf-frontend-form-element' );
		}

		public function get_fields_display( $form_field, $local_field ) {
			switch ( $form_field['field_type'] ) {
				case 'username':
					$local_field['type']            = 'text';
					$local_field['disabled']        = isset( $form_field['allow_edit'] ) ? ! $form_field['allow_edit'] : 1;
					$local_field['custom_username'] = true;
					break;
				case 'password':
					$local_field['type']                 = 'user_password';
					$local_field['edit_password']        = isset( $form_field['edit_password'] ) ? $form_field['edit_password'] : 'Edit Password';
					$local_field['cancel_edit_password'] = isset( $form_field['cancel_edit_password'] ) ? $form_field['cancel_edit_password'] : 'Cancel';
					$local_field['force_edit']           = isset( $form_field['force_edit_password'] ) ? $form_field['force_edit_password'] : 0;
					$local_field['password_strength']    = isset( $form_field['password_strength'] ) ? $form_field['password_strength'] : 3;
					break;
				case 'confirm_password':
					$local_field['type'] = 'user_password_confirm';
					break;
				case 'email':
					$local_field['type'] = 'user_email';
					if ( ! empty( $form_field['set_as_username'] ) ) {
						$local_field['set_as_username'] = true;
					} else {
						$local_field['set_as_username'] = false;
					}
					break;
				case 'bio':
					$local_field['type'] = 'user_bio';
					break;
				case 'role':
					$local_field['type'] = 'role';
					if ( isset( $form_field['role_field_options'] ) ) {
						$local_field['role_options'] = $form_field['role_field_options'];
					}
					$local_field['field_type']    = isset( $form_field['role_appearance'] ) ? $form_field['role_appearance'] : 'radio';
					$local_field['layout']        = isset( $form_field['role_radio_layout'] ) ? $form_field['role_radio_layout'] : 'vertical';
					$local_field['default_value'] = isset( $form_field['default_role'] ) ? $form_field['default_role'] : 'subscriber';
					break;
				default:
					$local_field['type'] = $form_field['field_type'];

			}
			return $local_field;
		}

		public function get_default_fields( $form, $action = '' ) {
			switch ( $action ) {
				case 'delete':
					$default_fields = array(
						'delete_user',
					);
					break;
				default:
					$default_fields = array(
						'username',
						'user_email',
						'user_password',
						'first_name',
						'last_name',
						'role',
						'submit_button',
					);
			}

			return $this->get_valid_defaults( $default_fields, $form );
		}

		public function get_form_builder_options( $form ) {
			 return array(
				 array(
					 'key'               => 'save_to_user',
					 'field_label_hide'  => 0,
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => 0,
					 'choices'           => array(
						 'edit_user' => __( 'Edit User', 'acf-frontend-form-element' ),
						 'new_user'  => __( 'New User', 'acf-frontend-form-element' ),
					 ),
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'user_to_edit',
					 'label'             => __( 'User to Edit', 'acf-frontend-form-element' ),
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_user',
								 'operator' => '==',
								 'value'    => 'edit_user',
							 ),
						 ),
					 ),
					 'choices'           => array(
						 'current_user'   => __( 'Current User', 'acf-frontend-form-element' ),
						 'current_author' => __( 'Current Author', 'acf-frontend-form-element' ),
						 'post_author'    => __( 'Form Post Author', 'acf-frontend-form-element' ),
						 'url_query'      => __( 'URL Query', 'acf-frontend-form-element' ),
						 'select_user'    => __( 'Specific User', 'acf-frontend-form-element' ),
					 ),
					 'default_value'     => false,
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'url_query_user',
					 'label'             => __( 'URL Query Key', 'acf-frontend-form-element' ),
					 'type'              => 'text',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_user',
								 'operator' => '==',
								 'value'    => 'edit_user',
							 ),
							 array(
								 'field'    => 'user_to_edit',
								 'operator' => '==',
								 'value'    => 'url_query',
							 ),
						 ),
					 ),
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'select_user',
					 'label'             => __( 'Specific User', 'acf-frontend-form-element' ),
					 'name'              => 'select_user',
					 'prefix'            => 'form',
					 'type'              => 'user',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_user',
								 'operator' => '==',
								 'value'    => 'edit_user',
							 ),
							 array(
								 'field'    => 'user_to_edit',
								 'operator' => '==',
								 'value'    => 'select_user',
							 ),
						 ),
					 ),
					 'role'              => '',
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'return_format'     => 'object',
					 'ui'                => 1,
				 ),
			 );
		}

		public function before_users_query() {
			$fields = array(
				array(
					'key'           => 'select_user',
					'label'         => __( 'Select By User', 'acf-frontend-form-element' ),
					'type'          => 'user',
					'instructions'  => '',
					'allow_null'    => 0,
					'multiple'      => 1,
					'ajax'          => 1,
					'ui'            => 1,
					'return_format' => 'id',
				),
				array(
					'key'           => 'by_user_id',
					'label'         => __( 'Select By User', 'acf-frontend-form-element' ),
					'type'          => 'user',
					'instructions'  => '',
					'allow_null'    => 0,
					'multiple'      => 1,
					'return_format' => 'id',
				),
			);

			foreach ( $fields as $field ) {
				$field['prefix'] = 'form';
				$field['name']   = $field['key'];
				acf_add_local_field( $field );
			}

		}

		public function register_settings_section( $widget ) {

			$widget->start_controls_section(
				'section_edit_user',
				array(
					'label'     => $this->get_label(),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => array(
						'admin_forms_select' => '',
					),
				)
			);

			$this->action_controls( $widget );

			$widget->end_controls_section();
		}


		public function action_controls( $widget, $step = false, $type = '' ) {
			if ( ! empty( $widget->form_defaults['save_to_user'] ) ) {
				$type = $widget->form_defaults['save_to_user'];
			}

			$args = array(
				'label'   => __( 'User', 'acf-frontend-form-element' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'edit_user' => __( 'Edit User', 'acf-frontend-form-element' ),
					'new_user'  => __( 'New User', 'acf-frontend-form-element' ),
				),
				'default' => $widget->get_name(),
			);

			$condition = array();

			if ( $type ) {
				$args = array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => $type,
				);
			}

			$widget->add_control( 'save_to_user', $args );

			$condition['save_to_user'] = array( 'edit_user', 'delete_user' );

			$widget->add_control(
				'user_to_edit',
				array(
					'label'     => __( 'Specific User', 'acf-frontend-form-element' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => 'current_user',
					'options'   => array(
						'current_user'   => __( 'Current User', 'acf-frontend-form-element' ),
						'current_author' => __( 'Current Author', 'acf-frontend-form-element' ),
						'url_query'      => __( 'URL Query', 'acf-frontend-form-element' ),
						'select_user'    => __( 'Specific User', 'acf-frontend-form-element' ),
					),
					'condition' => $condition,
				)
			);
			$condition['user_to_edit'] = 'url_query';
			$widget->add_control(
				'url_query_user',
				array(
					'label'       => __( 'URL Query', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'user_id', 'acf-frontend-form-element' ),
					'default'     => __( 'user_id', 'acf-frontend-form-element' ),
					'description' => __( 'Enter the URL query parameter containing the id of the user you want to edit', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);
			$condition['user_to_edit'] = 'select_user';
			$widget->add_control(
				'user_select',
				array(
					'label'       => __( 'User', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( '18', 'acf-frontend-form-element' ),
					'default'     => get_current_user_id(),
					'description' => __( 'Enter user id', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);

			unset( $condition['user_to_edit'] );
			$condition['save_to_user'] = 'new_user';

			$widget->add_control(
				'new_user_role',
				array(
					'label'       => __( 'New User Role', 'acf-frontend-form-element' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => 'subscriber',
					'options'     => feadmin_get_user_roles( array( 'administrator' ) ),
					'condition'   => $condition,
				)
			);

			$widget->add_control(
				'hide_admin_bar',
				array(
					'label'        => __( 'Hide WordPress Admin Area?', 'acf-frontend-form-element' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => __( 'Hide', 'acf-frontend-form-element' ),
					'label_off'    => __( 'Show', 'acf-frontend-form-element' ),
					'return_value' => 'true',
					'condition'    => $condition,
				)
			);
			if ( ! $step ) {

				$widget->add_control(
					'login_user',
					array(
						'label'        => __( 'Log in as new user?', 'acf-frontend-form-element' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
						'label_off'    => __( 'No', 'acf-frontend-form-element' ),
						'return_value' => 'true',
						'condition'    => $condition,
					)
				);
			}

		}

		public function run( $form, $step = false ) {
			$record = $form['record'];
			if ( empty( $record['user'] ) || empty( $record['fields']['user'] ) ) {
				return $form;
			}

			$user_id = $record['user'];

			// allow for custom save
			$user_id = apply_filters( 'acf/pre_save_user', $user_id, $form );

			$username_generated = false;
			$user_to_insert     = array();
			$metas              = array();

			$core_fields = array(
				'username',
				'user_password',
				'user_email',
				'display_name',
				'user_url',
			);

			if ( ! empty( $record['fields']['user'] ) ) {
				foreach ( $record['fields']['user'] as $name => $_field ) {
					if ( ! isset( $_field['key'] ) ) {
						continue;
					}
					$field = acf_maybe_get_field( $_field['key'] );

					if ( ! $field ) {
						if( isset( $form['fields'][$_field['key']] ) ){
							$field = $form['fields'][$_field['key']];
						}else{
							continue;
						}
					}

					$field_type      = $field['type'];
					$field['value']  = $_field['_input'];
					$field['_input'] = $_field['_input'];

					if ( ! in_array( $field_type, $core_fields ) ) {
						$metas[ $field['key'] ] = $field;
						continue;
					}

					if ( ! empty( $field['set_as_username'] ) ) {
						$user_to_insert['user_login'] = $field['value'];
					}

					switch ( $field_type ) {
						case 'username':
							$user_to_insert['user_login'] = $field['value'];
							if ( empty( $user_to_insert['user_nicename'] ) ) {
								  $user_to_insert['user_nicename'] = mb_substr( $field['value'], 0, 50 );
							}
							break;
						case 'user_password':
							if ( ( is_numeric( $user_id ) && empty( $record['edit_user_password'] ) ) || empty( $field['value'] ) ) {
								continue 2;
							}
							$user_to_insert['user_pass'] = $field['value'];
							break;
						default:
							$user_to_insert[ $field_type ] = $field['value'];
					}
				}
			}
			global $wpdb;

			if ( $user_id == 'add_user' ) {
				if ( empty( $user_to_insert['user_login'] ) ) {
					$user_to_insert['user_login'] = $this->generate_username();
				}

				$user_to_insert['user_registered'] = gmdate( 'Y-m-d H:i:s' );

				if ( empty( $user_to_insert['user_pass'] ) ) {
					$user_to_insert['user_pass'] = wp_generate_password();
				}

				if ( empty( $user_to_insert['user_nicename'] ) ) {
					$user_to_insert['user_nicename'] = $user_to_insert['user_login'];
				}

				$wpdb->insert( $wpdb->users, $user_to_insert );
				$user_id = $wpdb->insert_id;

				if ( is_wp_error( $user_id ) ) {
					return $form;
				}

				$GLOBALS['admin_form']['record']['user'] = $user_id;
				$form['record']['user'] = $user_id;

				if ( isset( $form['hide_admin_bar'] ) ) {
					update_user_meta( $user_id, 'hide_admin_area', $form['hide_admin_bar'] );
				}

				if ( empty( $record['fields']['user']['nickname']['_input'] ) ) {
					$nickname = $user_to_insert['user_login'];
					if ( ! empty( $record['fields']['user']['first_name']['_input'] ) ) {
						$nickname = $record['fields']['user']['first_name']['_input'];
					}
					update_user_meta( $user_id, 'nickname', $nickname );
				}
				if ( empty( $user_to_insert['role'] ) ) {
					$user = get_user_by( 'id', $user_id );
							 // Check if there is a default role set for the form
							 // This is relevant to forms built in Elementor
					if ( ! empty( $form['new_user_role'] ) ) {
						$user->set_role( $form['new_user_role'] );
					}
					// Otherwise set the default to be the wp default
					else {
						$user->set_role( get_option( 'default_role' ) );
					}
				}
				do_action( 'user_register', $user_id, $user_to_insert );
			} else {
				if ( $user_to_insert ) {
					$old_user_data = get_userdata( $user_id );

					if ( isset( $user_to_insert['user_login'] ) && $user_to_insert['user_login'] == $old_user_data->user_login ) {
						   unset( $user_to_insert['user_login'] );
					} else {
						 $log_back_in = true;
					}

					$updated = $wpdb->update( $wpdb->users, $user_to_insert, array( 'ID' => $user_id ) );

					clean_user_cache( $user_id );

					if ( isset( $user_to_insert['user_pass'] ) ) {
						$log_back_in = true;
					}

					if ( isset( $log_back_in ) && get_current_user_id() == $user_id ) {
						 $user_object = get_user_by( 'ID', $user_id );
						if ( $user_object ) {
							$user_login = $user_object->user_login;
							wp_clear_auth_cookie();
							wp_set_current_user( $user_id, $user_login );
							wp_set_auth_cookie( $user_id, true, true );
							do_action( 'wp_login', $user_login, $user_object );
							update_user_caches( $user_object );
							$response['message_token'] = wp_generate_password();
							update_user_meta( $user_id, 'message_token', $response['message_token'] );
						}
					}
					do_action( 'profile_update', $user_id, $old_user_data, $user_to_insert );
				}
			}

			if ( isset( $form['user_manager'] ) ) {
				update_user_meta( $user_id, 'frontend_admin_manager', $form['user_manager'] );
			}

			if ( isset( $form['hide_admin_bar'] ) ) {
				update_user_meta( $user_id, 'show_admin_bar_front', ! $form['hide_admin_bar'] );
			}

			if ( ! empty( $metas ) ) {
				foreach ( $metas as $meta ) {
					acf_update_value( $meta['_input'], 'user_' . $user_id, $meta );
				}
			}

			$form['record']['user'] = $user_id;

			if ( ! empty( $form['login_user'] ) ) {
				$user = get_user_by( 'id', $user_id );
				if ( ! empty( $user->user_login ) ) {
					wp_set_current_user( $user_id, $user->user_login );
					wp_set_auth_cookie( $user_id );
				}
			}

			do_action( 'frontend_admin/save_user', $form, $user_id );
			do_action( 'acf_frontend/save_user', $form, $user_id );

			return $form;
		}

		public function load_data( $form, $objects ) {
			if ( empty( $form['save_to_user'] ) ) {
				return $form;
			}
			switch ( $form['save_to_user'] ) {
				case 'new_user':
					if ( isset( $objects['user'] ) && feadmin_can_edit_user( $objects['user'], $form ) ) {
						$form['user_id']      = $objects['user'];
						$form['save_to_user'] = 'edit_user';
					} else {
						$form['user_id'] = 'add_user';
					}
					break;
				case 'edit_user':
				case 'delete_user':
					if ( empty( $form['user_to_edit'] ) ) {
						$form['user_to_edit'] = 'current_user';
					}

					if ( $form['user_to_edit'] == 'current_author' ) {
						if ( is_author() ) {
							$author_id = get_queried_object_id();
						} else {
							$author_id = get_the_author_meta( 'ID' );
						}
						$form['user_id'] = $author_id;
					}

					if ( $form['user_to_edit'] == 'select_user' ) {
						if ( ! empty( $form['select_user'] ) ) {
							$form['user_id'] = $form['select_user'];
						} else {
							if ( isset( $form['user_select'] ) ) {
								$form['user_id'] = $form['user_select'];
							}
						}
					}
					if ( $form['user_to_edit'] == 'url_query' ) {
						if ( isset( $_GET[ $form['url_query_user'] ] ) ) {
							$form['user_id'] = absint( $_GET[ $form['url_query_user'] ] );
						}
					}
					if ( $form['user_to_edit'] == 'current_user' ) {
						$form['user_id'] = get_current_user_id();
					}

					if ( empty( $form['user_id'] ) || ! feadmin_user_exists( $form['user_id'] ) ) {
						$form['user_id'] = 'none';
					}
					break;
			}
			return $form;
		}

		public function generate_username( $prefix = '' ) {
			$new_username = uniqid( $prefix );
			if ( ! username_exists( $new_username ) ) {
				return $new_username;
			} else {
				return $this->generate_username( $prefix );
			}
		}


		public function __construct() {
			 add_filter( 'acf_frontend/save_form', array( $this, 'save_form' ), 4 );
			add_action( 'wp_ajax_acf/fields/user/query', array( $this, 'before_users_query' ), 4 );
		}
	}
	fea_instance()->local_actions['user'] = new ActionUser();

endif;
