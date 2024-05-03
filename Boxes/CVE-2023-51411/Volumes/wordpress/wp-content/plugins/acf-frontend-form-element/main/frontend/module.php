<?php
namespace Frontend_Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Frontend' ) ) :

	class Frontend {

		/** @var array Contains an array of field type instances */
		var $field_types = array();

		public function extra_field_setting( $field ) {
			global $post;
			if ( isset( $post->post_type ) && $post->post_type == 'acf-field-group' ) {
				acf_render_field_setting(
					$field,
					array(
						'label'        => __( 'Show On Frontend Only' ),
						'instructions' => __( 'Lets you hide the field on the backend to avoid duplicate fields.', 'acf-frontend-form-element' ),
						'name'         => 'only_front',
						'type'         => 'true_false',
						'ui'           => 1,
						'conditions'   => array(
							array(
								'field'    => 'frontend_admin_display_mode',
								'operator' => '!=',
								'value'    => 'hidden',
							),
						),
					),
					true
				);

			}

			acf_render_field_setting( $field, array(
				'label'			=> __('Display Mode'),
				'instructions'	=> __( 'Lets you show the editable field or display the value only. You may also hide the field, which is useful if you need to pass hidden data', 'acf-frontend-form-element' ),
				'name'			=> 'frontend_admin_display_mode',
				'type'			=> 'select',
				'choices'		=> array(
					'edit'	=> __( 'Edit', 'acf-frontend-form-element' ),
					'read_only'	=> __( 'Read Only', 'acf-frontend-form-element' ),
					'hidden'	=> __( 'Hidden', 'acf-frontend-form-element' ),
				)
			), true );

			$short_key = str_replace( 'field_', '', $field['key'] );
			$icon_path = '<span class="dashicons dashicons-admin-page"></span>';
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Field Shortcode', 'acf-frontend-form-element' ),
					'instructions' => '',
					'message'      => '<code>[frontend_admin field=' . $short_key . ' edit=false]</code>' . sprintf(
						'<button type="button" class="copy-shortcode" data-prefix="frontend_admin field" data-value="%1$s">%2$s %3$s</button>',
						$short_key,
						$icon_path,
						__( 'Copy Code', 'acf-frontend-form-element' )
					),
					'type'         => 'message',
					'name'         => 'shortcode_message',
				),
				true
			);
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'No Value Messge', 'acf-frontend-form-element' ),
					'instructions' => __( 'Appears in shortcode when field returns no value. If left blank nothing will show.', 'acf-frontend-form-element' ),
					'type'         => 'textarea',
					'name'         => 'no_values_message',
					'rows'         => 3,
				),
				true
			);
		}

		public function extra_field_group_setting( $group ) {
			global $post;
			$short_key = str_replace( 'group_', '', $group['key'] );
			if( empty( $group['no_values_message'] ) ){
				$group['no_values_message'] = '';
			}
			$icon_path = '<span class="dashicons dashicons-admin-page"></span>';
			acf_render_field_wrap(
				array(
					'label'        => __( 'Group Shortcode', 'acf-frontend-form-element' ),
					'instructions' => '',
					'message'      => '<code>[frontend_admin group=' . $short_key . ' edit=false]</code>' . sprintf(
						'<button type="button" class="copy-shortcode" data-prefix="frontend_admin group" data-value="%1$s">%2$s %3$s</button>',
						$short_key,
						$icon_path,
						__( 'Copy Code', 'acf-frontend-form-element' )
					),
					'type'         => 'message',
					'name'         => 'shortcode_message',
				),
				'div',
				'field'
			);
			acf_render_field_wrap(
				array(
					'label'        => __( 'No Value Messge', 'acf-frontend-form-element' ),
					'instructions' => __( 'Appears in shortcode when field returns no value. If left blank nothing will show. Each individual field has this setting as well and that will overwrite this.', 'acf-frontend-form-element' ),
					'type'         => 'textarea',
					'name'         => 'no_values_message',
					'rows'         => 3,	
					'prefix'       => 'acf_field_group',
					'value'        => $group['no_values_message'],
				),
				'div',
				'field'
			);
		}

		public function hide_frontend_admin_fields( $groups ) {
			 global $post;

			if ( isset( $post->post_type ) && $post->post_type == 'acf-field-group' ) {
				unset( $groups[ __( 'Form', 'acf-frontend-form-element' ) ] );
				unset( $groups[ __( 'Mailchimp', 'acf-frontend-form-element' ) ] );
			}

			unset( $groups['frontend-admin-hidden'] );

			return $groups;
		}


		/**
		 * Update the dynamic value of a field.
		 *
		 * @param mixed  $value   The new value for the field.
		 * @param int    $post_id The ID of the post to update the field for. Default is false.
		 * @param string $field   The field to update. Default is false.
		 */
		public function update_dynamic_value( $value, $post_id = false, $field = false ) {
			if ( empty( $field['default_value'] ) || ! is_string( $field['default_value'] ) ) {
				return $value;
			}

			if ( ! $field['value'] && isset( $field['default_value'] ) && is_string( $field['default_value'] ) && strpos( $field['default_value'], '[' ) !== false ) {
				$dynamic_value = fea_instance()->dynamic_values->get_dynamic_values( $field['default_value'] );
				if ( $dynamic_value ) {
					$value = $dynamic_value;
				}
			}

			return $value;
		}

		
		public function exclude_groups( $field_group ) {
			if ( empty( $field_group['frontend_admin_group'] ) ) {
				return $field_group;
			} elseif ( is_admin() ) {
				if ( function_exists( 'get_current_screen' ) ) {
					$current_screen = get_current_screen();
					if ( isset( $current_screen->post_type ) && $current_screen->post_type == 'admin_form' ) {
						return $field_group;
					} else {
						return null;
					}
				}
			}

		}

		public function load_invisible_field( $field ) {
			if ( empty( $field['invisible'] ) ) {
				return $field;
			}

			$field['frontend_admin_display_mode'] = 'hidden';
			unset( $field['invisible'] );
			acf_update_field( $field );
			return $field;
		}

		public function before_validation() {
			if ( isset( $_POST['_acf_field_id'] ) ) {
				acf_add_local_field(
					array(
						'key'    => 'frontend_admin_post_type',
						'label'  => __( 'Post Type', 'acf-frontend-form-element' ),
						'name'   => 'frontend_admin_post_type',
						'type'   => 'post_type',
						'layout' => 'vertical',
					)
				);
			}
		}
		public function skip_validation() {
			if ( isset( $_POST['_acf_status'] ) && $_POST['_acf_status'] != 'publish' ) {
				acf_reset_validation_errors();
			}

		}

		public function enqueue_scripts( $hook_suffix ) {
			if ( $hook_suffix != 'frontend_admin_form' && ! current_user_can( 'manage_options' ) ) {
				return;
			}

			acf_enqueue_scripts();
			acf_localize_text(
				array(
					'Copy Code'   => __( 'Copy Code', 'acf-frontend-form-element' ),
					'Code Copied' => __( 'Code Copied', 'acf-frontend-form-element' ),
				)
			);
			wp_enqueue_style( 'fea-modal' );
			wp_enqueue_script( 'fea-public' );
			wp_enqueue_script( 'fea-modal' );
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'fea-icon' );

			do_action( 'frontend_admin/forms/enqueue_scripts' );
		}

		public function feadata( $form = false ) {
			if ( current_user_can( 'manage_options' ) || $form ) {
				global $wp_version;
				acf_localize_data(
					array(
						'admin_url'   => admin_url(),
						'ajaxurl'     => admin_url( 'admin-ajax.php' ),
						'nonce'       => wp_create_nonce( 'acf_nonce' ),
						'acf_version' => acf_get_setting( 'version' ),
						'wp_version'  => $wp_version,
						'browser'     => acf_get_browser(),
						'locale'      => acf_get_locale(),
						'rtl'         => is_rtl(),
						'screen'      => acf_get_form_data( 'screen' ),
						'post_id'     => acf_get_form_data( 'post_id' ),
						'validation'  => acf_get_form_data( 'validation' ),
						'editor'      => acf_is_block_editor() ? 'block' : 'classic',
					)
				);
				  // Print inline script.
				  printf( "<script>\n%s\n</script>\n", 'feadata = ' . wp_json_encode( acf_get_instance( 'ACF_Assets' )->data ) . ';' );
			}
		}

		public function prepare_field_display( $field ) {
			if ( empty( $field['frontend_admin_display_mode'] ) ) {
				return $field;
			}
			$mode = $field['frontend_admin_display_mode'];

			if ( $mode == 'hidden' ) {
				if ( isset( $field['wrapper']['class'] ) ) {
					$field['wrapper']['class'] .= ' acf-hidden';
				} else {
					$field['wrapper']['class'] = 'acf-hidden';
				}
			}

			if ( $mode == 'read_only' ) {
				$field['acf_wrap'] = true;
				echo wp_kses_post( fea_instance()->dynamic_values->display_field( $field ) );
				return false;
			}

			return $field;
		}

		public function prepare_field_backend( $field ) {
			// bail early if no 'admin_only' setting
			if ( empty( $field['only_front'] ) ) {
				return $field;
			}

			$render = true;
			// return false if is admin (removes field)
			if ( is_admin() && ! wp_doing_ajax() ) {
				$render = false;
			}
			

			if ( ! $render ) {
				return false;
			}

			// return\
			return $field;
		}


		public function include_forms_as_groups( $groups ) {
			if ( ! empty( $GLOBALS['only_acf_field_groups'] ) ) {
				return $groups;
			}

			$forms = get_posts(
				array(
					'post_type'      => 'admin_form',
					'posts_per_page' => '-1',
					'post_status'    => 'publish',
				)
			);
			foreach ( $forms as $form ) {
				$field_group = (array) maybe_unserialize( $form->post_content );

				// update attributes
				$field_group['ID']         = $form->ID;
				$field_group['title']      = $form->post_title;
				$field_group['key']        = $form->ID;
				$field_group['menu_order'] = $form->menu_order;
				$field_group['active']     = in_array( $form->post_status, array( 'publish', 'auto-draft' ) );
				$field_group['location']   = array(
					array(
						array(
							'param'    => '',
							'operator' => '',
							'value'    => '',
						),
					),
				);

				acf_add_local_field_group( $field_group );

			}

			return $groups;
		}

				/*
		*  register_field_type
		*
		*  This function will register a field type instance
		*
		*  @type    function
		*  @date    6/07/2016
		*  @since   5.4.0
		*
		*  @param   $class (string)
		*  @return  n/a
		*/

		function register_field_type( $field, $acf_support = false ) {
			$field = str_replace( '-', '_', $field );
			if( ! class_exists( 'Frontend_Admin\Field_Types\\' . $field ) ) return;

			$class = 'Frontend_Admin\Field_Types\\' . $field;

			if( $acf_support ){
				if( function_exists( 'acf_register_field_type' ) ){
					acf_register_field_type( $class );
				}
			}else{
				$instance = new $class( [ 'fea' => true ] );
				$this->field_types[ $field ] = $instance;
			}
		}


		public function include_field_types() {
			 include_once 'walkers/related-terms-walker.php';

			// general field types
			$general = array(
				'field-base',
				'text',
				'radio',
				'checkbox',
				'email',
				'link',
				'number',
				'range',
				'password',
				'select',
				'textarea',
				'true-false',
				'url',
				'related-items',
			);
			foreach ( $general as $type ) {
				include_once 'fields/general/class-' . $type . '.php';
				$this->register_field_type( $type );
			}
			

			global $frontend_admin_field_types;
			if ( ! empty( $frontend_admin_field_types ) ) {
				foreach ( $frontend_admin_field_types as $group => $fields ) {
					$path = "fields/$group/";
					if( isset( $fields['path'] ) ){
						$path = $fields['path'] . $group . '/';
						unset( $fields['path'] );
					}
					foreach ( $fields as $field ) {
						$file = $path . "class-$field.php";
						
						include_once $file;
						$this->register_field_type( $field, true );
					}
				}
			}

			do_action( 'frontend_admin/forms/field_types' );

		}

		public function find_field_type_group( $type ) {
			$type = str_replace( '_', '-', $type );
			global $frontend_admin_field_types;
			if ( ! empty( $frontend_admin_field_types ) ) {
				foreach ( $frontend_admin_field_types as $group => $fields ) {
					if ( in_array( $type, $fields ) ) {
						return $group;
					}
				}
			}

			return false;
		}

		public function load_acf_scripts() {
			global $fea_scripts;
			if ( ! is_admin() && $fea_scripts == 'admin' ) {
				if ( empty( $fea_scripts_loaded ) ) {
					$this->enqueue_scripts( 'frontend_admin_form' );
					$this->feadata( true );
				}
			}
			wp_enqueue_style( 'fea-public' );


		}
		public function hide_field_name_setting() {
			 global $post;

			if ( empty( $post->post_type ) ) {
				return;
			}

			if ( 'acf-field-group' == $post->post_type || 'admin_form' == $post->post_type ) {
				wp_enqueue_script( 'fea-copy-code' );
				global $frontend_admin_field_types;
				if( ! empty( $frontend_admin_field_types ) ){
					echo '<style>';
					foreach( $frontend_admin_field_types as $group => $fields ){			
						if( $group == 'mailchimp' ) continue;
						if( $group == 'general' ) {
							$fields = array( 'fields-select', 'form-step' );
						}
						foreach( $fields as $field ){
							echo '.acf-field-object-' .esc_html( $field ). ' .acf-field-setting-name,.acf-field-object-' .esc_html( $field ). ' .acf-field-setting-custom_fields_save{display:none}.acf-field-object-' .esc_html( $field ).  ' .li-field-name{visibility:hidden}';
						}	
					}
					$basic_settings = array( 'name', 'instructions', 'required', 'wrapper', 'frontend_admin_display_mode', 'field_label_hide', 'only_front' );
					foreach( $basic_settings as $setting ){
						$setting = esc_html( $setting );
						echo ".acf-field-object-form-step .acf-field-setting-{$setting}, .acf-field-object-submit-button .acf-field-setting-{$setting}, .acf-field-object-save-progress .acf-field-setting-{$setting}, .acf-field-object-fields-select .acf-field-setting-{$setting}{display:none}";
						echo ".acf-field-object-form-step .acf-field-setting-{$setting}, .acf-field-object-save-progress .acf-field-setting-{$setting}, .acf-field-object-fields-select .acf-field-setting-{$setting}{display:none}";
					}
					echo '.acf-field-object-form-step .acf-field-setting-custom_fields_save, .acf-field-object-submit-button .acf-field-setting-custom_fields_save, .acf-field-object-save-progress .acf-field-setting-custom_fields_save{display:none}';
					echo '.acf-field-object-form-step[data-step="1"] .acf-field-setting-prev_button_text,.acf-field-object-form-step .acf-field-setting-name,.acf-field-object-submit-button .acf-field-setting-label,.acf-field-object-save-progress .acf-field-setting-label,.acf-field-object-delete-post .acf-field-setting-label,.acf-field-object-delete-term .acf-field-setting-label,.acf-field-object-delete-user .acf-field-setting-label,.acf-field-object-delete-product .acf-field-setting-label,.acf-field-object-custom-terms .acf-field-setting-ui{display:none}';

					echo '</style>';
				}
			}
		}

		public function get_field_types() {
			 $field_types = array(
				'general' => array(
					'submit-button',
					'save-progress',
					'time',
					'date',
					'datetime-input',
					'color',
					'related-terms',
					'plans',
					'text-editor',
					'blocks-editor',
					'custom-terms',
					'delete-object',
					'upload-file',
					'upload-image',
					'upload-files',
					'list-items',
					'fields-select',
					'recaptcha',
				),
				 'post' => array(
					 'post-to-edit',
					 'post-title',
					 'post-content',
					 'post-excerpt',
					 'post-slug',
					 'post-status',
					 'featured-image',
					 'post-type',
					 'post-date',
					 'post-author',
					 'menu-order',
					 'allow-comments',
					 'delete-post',
				 ),
				 'user' => array(
					 'username',
					 'user-email',
					 'user-password',
					 'user-password-confirm',
					 'first-name',
					 'last-name',
					 'nickname',
					 'display-name',
					 'user-url',
					 'user-bio',
					 'role',
					 'delete-user',
				 ),
				 'term' => array(
					 'term-name',
					 'term-slug',
					 'term-description',
					 'delete-term',
				 ),
			 );

			 $field_types = apply_filters( 'frontend_admin/field_types', $field_types );


			 return $field_types;
		}


		public function echo_after_input( $field ) {
			if ( ! empty( $field['after_input'] ) ) {
				echo wp_kses_post( $field['after_input'] );
			}
		}



		public function __construct() {
			 global $frontend_admin_field_types;
			$frontend_admin_field_types = $this->get_field_types();

			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ), 6 );
			// add_filter( 'acf/load_field_groups', array( $this, 'include_forms_as_groups' ), 5 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'wp_footer', array( $this, 'load_acf_scripts' ) );
			add_action( 'admin_footer', array( $this, 'load_acf_scripts' ) );
			add_action( 'admin_footer', array( $this, 'hide_field_name_setting' ) );

			add_filter( 'acf/prepare_field', array( $this, 'prepare_field_display' ), 3 );
			add_filter( 'acf/prepare_field', array( $this, 'prepare_field_backend' ), 3 );

			add_action( 'acf/render_field', array( $this, 'echo_after_input' ) );
			// Add field settings by type
			add_action( 'acf/render_field_settings', array( $this, 'extra_field_setting' ), 15 );
			add_action( 'acf/render_field_group_settings', array( $this, 'extra_field_group_setting' ), 15 );

			add_filter( 'acf/get_field_types', array( $this, 'hide_frontend_admin_fields' ) );

			add_filter( 'acf/update_value', array( $this, 'update_dynamic_value' ), 17, 3 );

			add_filter( 'acf/load_field_group', array( $this, 'exclude_groups' ) );
			add_filter( 'acf/load_field', array( $this, 'load_invisible_field' ) );

			add_action( 'acf/validate_save_post', array( $this, 'before_validation' ), 1 );
			add_action( 'acf/validate_save_post', array( $this, 'skip_validation' ), 999 );

			include_once __DIR__ . '/forms/classes/form-submit.php';
			include_once __DIR__ . '/forms/classes/form-display.php';
			include_once __DIR__ . '/forms/classes/limit-submit.php';

			include_once __DIR__ . '/forms/classes/permissions.php';
			include_once __DIR__ . '/forms/classes/shortcodes.php';
			include_once __DIR__ . '/forms/actions/action-base.php';

			// actions
			include_once __DIR__ . '/forms/actions/user.php';
			include_once __DIR__ . '/forms/actions/post.php';
			include_once __DIR__ . '/forms/actions/term.php';
			include_once __DIR__ . '/forms/actions/options.php';
			// require_once( __DIR__ . '/forms/actions/comment.php' );

			do_action( 'frontend_admin/forms/included_files' );
		}

	}
	fea_instance()->frontend = new Frontend();

endif;
