<?php
namespace Frontend_Admin\Actions;

use Frontend_Admin\Plugin;
use Frontend_Admin\Classes\ActionBase;
use Frontend_Admin\Forms\Actions;
use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Query_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ActionPost' ) ) :

	class ActionPost extends ActionBase {


		public function get_name() {
			return 'post';
		}

		public function get_label() {
			return __( 'Post', 'acf-frontend-form-element' );
		}

		public function get_fields_display( $form_field, $local_field, $element = '' ) {
			$field_appearance = isset( $form_field['field_taxonomy_appearance'] ) ? $form_field['field_taxonomy_appearance'] : 'checkbox';
			$field_add_term   = isset( $form_field['field_add_term'] ) ? $form_field['field_add_term'] : 0;

			switch ( $form_field['field_type'] ) {
				case 'title':
					$local_field['type'] = 'post_title';
					break;
				case 'slug':
					$local_field['type']              = 'post_slug';
					$local_field['wrapper']['class'] .= ' post-slug-field';
					break;
				case 'content':
					$local_field['type']       = 'post_content';
					$local_field['field_type'] = isset( $form_field['editor_type'] ) ? $form_field['editor_type'] : 'wysiwyg';
					break;
				case 'featured_image':
					$local_field['type']          = 'featured_image';
					$local_field['default_value'] = empty( $form_field['default_featured_image']['id'] ) ? '' : $form_field['default_featured_image']['id'];
					break;
				case 'excerpt':
					$local_field['type'] = 'post_excerpt';
					break;
				case 'author':
					$local_field['type'] = 'post_author';
					break;
				case 'published_on':
					$local_field['type'] = 'post_date';
					break;
				case 'menu_order':
					$local_field['type'] = 'menu_order';
					break;
				case 'taxonomy':
					$taxonomy                       = isset( $form_field['field_taxonomy'] ) ? $form_field['field_taxonomy'] : 'category';
					$local_field['type']            = 'taxonomy';
					$local_field['taxonomy']        = $taxonomy;
					$local_field['field_type']      = $field_appearance;
					$local_field['allow_null']      = 0;
					$local_field['add_term']        = $field_add_term;
					$local_field['load_post_terms'] = 1;
					$local_field['save_terms']      = 1;
					$local_field['custom_taxonomy'] = true;
					break;
				case 'categories':
					$local_field['type']            = 'taxonomy';
					$local_field['taxonomy']        = 'category';
					$local_field['field_type']      = $field_appearance;
					$local_field['allow_null']      = 0;
					$local_field['add_term']        = $field_add_term;
					$local_field['load_post_terms'] = 1;
					$local_field['save_terms']      = 1;
					$local_field['custom_taxonomy'] = true;
					break;
				case 'tags':
					$local_field['type']            = 'taxonomy';
					$local_field['taxonomy']        = 'post_tag';
					$local_field['field_type']      = $field_appearance;
					$local_field['allow_null']      = 0;
					$local_field['add_term']        = $field_add_term;
					$local_field['load_post_terms'] = 1;
					$local_field['save_terms']      = 1;
					$local_field['custom_taxonomy'] = true;
					break;
				case 'post_type':
					$local_field['type']          = 'post_type';
					$local_field['field_type']    = isset( $form_field['role_appearance'] ) ? $form_field['role_appearance'] : 'select';
					$local_field['layout']        = isset( $form_field['role_radio_layout'] ) ? $form_field['role_radio_layout'] : 'vertical';
					$local_field['default_value'] = isset( $form_field['default_post_type'] ) ? $form_field['default_post_type'] : 'post';
					if ( isset( $form_field['post_type_field_options'] ) ) {
						$local_field['post_type_options'] = $form_field['post_type_field_options'];
					}
					break;
			}
			return $local_field;
		}

		public function get_default_fields( $form, $action = '' ) {
			switch ( $action ) {
				case 'delete':
					$default_fields = array(
						'delete_post',
					);
					break;
				case 'status':
					$default_fields = array(
						'post_status',
						'submit_button',
					);
					break;
				case 'new':
					$default_fields = array(
						'post_title',
						'post_content',
						'post_excerpt',
						'featured_image',
						'post_status',
						'submit_button',
					);
					break;
				default:
					$default_fields = array(
						'post_to_edit',
						'post_title',
						'post_excerpt',
						'featured_image',
						'post_status',
						'submit_button',
					);
					break;
			}
			return $this->get_valid_defaults( $default_fields, $form );
		}

		public function get_form_builder_options( $form ) {
			 $options = array(
				 array(
					 'key'               => 'save_to_post',
					 'field_label_hide'  => 1,
					 'type'              => 'select',
					 'instructions'      => __( 'If there is a Post to Edit field in the form, these settings will be overwritten.', 'acf-frontend-form-element' ),
					 'required'			 => 0,           
					 'conditional_logic' => 0,
					 'choices'           => array(
						 'edit_post'      => __( 'Edit Post', 'acf-frontend-form-element' ),
						 'new_post'       => __( 'New Post', 'acf-frontend-form-element' ),
						 'duplicate_post' => __( 'Duplicate Post', 'acf-frontend-form-element' ),
					 ),
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'new_post_type',
					 'label'             => __( 'Post Type', 'acf-frontend-form-element' ),
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_post',
								 'operator' => '==',
								 'value'    => 'new_post',
							 ),
						 ),
					 ),
					 'choices'           => acf_get_pretty_post_types(),
					 'default_value'     => false,
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'post_to_edit',
					 'label'             => __( 'Post', 'acf-frontend-form-element' ),
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_post',
								 'operator' => '!=',
								 'value'    => 'new_post',
							 ),
						 ),
					 ),
					 'choices'           => array(
						 'current_post' => __( 'Current Post', 'acf-frontend-form-element' ),
						 'url_query'    => __( 'URL Query', 'acf-frontend-form-element' ),
						 'select_post'  => __( 'Specific Post', 'acf-frontend-form-element' ),
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
					 'key'               => 'url_query_post',
					 'label'             => __( 'URL Query Key', 'acf-frontend-form-element' ),
					 'type'              => 'text',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_post',
								 'operator' => '!=',
								 'value'    => 'new_post',
							 ),
							 array(
								 'field'    => 'post_to_edit',
								 'operator' => '==',
								 'value'    => 'url_query',
							 ),
						 ),
					 ),
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'select_post',
					 'label'             => __( 'Specific Post', 'acf-frontend-form-element' ),
					 'name'              => 'select_post',
					 'prefix'            => 'form',
					 'type'              => 'post_object',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_post',
								 'operator' => '!=',
								 'value'    => 'new_post',
							 ),
							 array(
								 'field'    => 'post_to_edit',
								 'operator' => '==',
								 'value'    => 'select_post',
							 ),
						 ),
					 ),
					 'post_type'         => '',
					 'taxonomy'          => '',
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'return_format'     => 'object',
					 'ui'                => 1,
				 ),
			 );

			 return $options;
		}

		public function load_data( $form, $objects ) {
			if ( empty( $form['save_to_post'] ) ) {
				return $form;
			}
			switch ( $form['save_to_post'] ) {
				case 'new_post':
					if ( isset( $objects['post'] ) && feadmin_can_edit_post( $objects['post'], $form ) ) {
						$form['post_id']      = $objects['post'];
						$form['save_to_post'] = 'edit_post';
					} else {
						$form['post_id'] = 'add_post';
					}
					if ( ! empty( $form['new_post_terms'] ) ) {
						if ( $form['new_post_terms'] == 'select_terms' ) {
							$form['post_terms'] = $form['new_terms_select'];
						}
						if ( $form['new_post_terms'] == 'current_term' ) {
							$form['post_terms'] = get_queried_object()->term_id;
						}
					}
					break;
				case 'edit_post':
				case 'duplicate_post':
				case 'delete_post':
					if ( empty( $form['post_to_edit'] ) ) {
						$form['post_to_edit'] = 'current_post';
					}

					global $post;

					if ( $form['post_to_edit'] == 'select_post' ) {
						if ( ! empty( $form['select_post'] ) ) {
							$form['post_id'] = $form['select_post'];
						} else {
							if ( isset( $form['post_select'] ) ) {
								$form['post_id'] = $form['post_select'];
							}
						}
					}
					if ( $form['post_to_edit'] == 'url_query' ) {
						if ( isset( $_GET[ $form['url_query_post'] ] ) ) {
							$form['post_id'] = absint( $_GET[ $form['url_query_post'] ] );
						}
					}
					if ( $form['post_to_edit'] == 'current_post' && isset( $post->ID ) ) {
						$form['post_id'] = $post->ID;
					}

					if ( empty( $form['post_id'] ) ) {
						$form['post_id'] = 'none';
					}

					break;
			}
			return $form;
		}

		public function before_posts_query() {
			$fields = array(
				array(
					'key'        => 'select_post',
					'prefix'     => 'form',
					'type'       => 'post_object',
					'post_type'  => '',
					'taxonomy'   => '',
					'allow_null' => 0,
					'multiple'   => 0,
					'ui'         => 1,
				),
				array(
					'key'        => 'select_product',
					'prefix'     => 'form',
					'type'       => 'post_object',
					'post_type'  => 'product',
					'taxonomy'   => '',
					'allow_null' => 0,
					'multiple'   => 0,
					'ui'         => 1,
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
				'section_edit_post',
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
			if ( ! empty( $widget->form_defaults['save_to_post'] ) ) {
				$type = $widget->form_defaults['save_to_post'];
			}else{
				$type = 'edit_post';
			}

			$condition = array();			

			if( 'delete_post' !== $widget->get_name() ){
				$args = array(
					'label'   => __( 'Post', 'acf-frontend-form-element' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'edit_post'      => __( 'Edit Post', 'acf-frontend-form-element' ),
						'new_post'       => __( 'New Post', 'acf-frontend-form-element' ),
						'duplicate_post' => __( 'Duplicate Post', 'acf-frontend-form-element' ),
					),
					'default' => $type,
				);
				
				$condition['save_to_post'] = array( 'edit_post', 'delete_post', 'duplicate_post' );

				$widget->add_control( 'save_to_post', $args );
			}

			// add option to determine when the post will be save: 1. on form submit
			// 2. when user confirms email  3. when admin approves submission
			// 4. on woocommerce purchase

			
			$widget->add_control(
				'post_to_edit',
				array(
					'label'     => __( 'Specific Post', 'acf-frontend-form-element' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'current_post',
					'options'   => array(
						'current_post' => __( 'Current Post', 'acf-frontend-form-element' ),
						'url_query'    => __( 'Url Query', 'acf-frontend-form-element' ),
						'select_post'  => __( 'Specific Post', 'acf-frontend-form-element' ),
					),
					'condition' => $condition,
				)
			);

			$condition['post_to_edit'] = 'url_query';
			$widget->add_control(
				'url_query_post',
				array(
					'label'       => __( 'URL Query', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'post_id', 'acf-frontend-form-element' ),
					'default'     => __( 'post_id', 'acf-frontend-form-element' ),
					'required'    => true,
					'description' => __( 'Enter the URL query parameter containing the id of the post you want to edit', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);
			$condition['post_to_edit'] = 'select_post';
			$widget->add_control(
				'post_select',
				array(
					'label'       => __( 'Post', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( '18', 'acf-frontend-form-element' ),
					'description' => __( 'Enter the post ID', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);

			unset( $condition['post_to_edit'] );
			$condition['save_to_post'] = 'new_post';

			$post_type_choices = feadmin_get_post_type_choices();

			$widget->add_control(
				'new_post_type',
				array(
					'label'       => __( 'New Post Type', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => 'post',
					'options'     => $post_type_choices,
					'condition'   => $condition,
				)
			);
			$widget->add_control(
				'new_post_terms',
				array(
					'label'       => __( 'New Post Terms', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => 'post',
					'options'     => array(
						'current_term' => __( 'Current Term', 'acf-frontend-form-element' ),
						'select_terms' => __( 'Specific Term', 'acf-frontend-form-element' ),
					),
					'condition'   => $condition,
				)
			);

			$condition['new_post_terms'] = 'select_terms';
			if ( ! class_exists( 'ElementorPro\Modules\QueryControl\Module' ) ) {
				$widget->add_control(
					'new_terms_select',
					array(
						'label'       => __( 'Terms', 'acf-frontend-form-element' ),
						'type'        => Controls_Manager::TEXT,
						'placeholder' => __( '18, 12, 11', 'acf-frontend-form-element' ),
						'description' => __( 'Enter the a comma-seperated list of term ids', 'acf-frontend-form-element' ),
						'condition'   => $condition,
					)
				);
			} else {
				$widget->add_control(
					'new_terms_select',
					array(
						'label'        => __( 'Terms', 'acf-frontend-form-element' ),
						'type'         => Query_Module::QUERY_CONTROL_ID,
						'label_block'  => true,
						'autocomplete' => array(
							'object'  => Query_Module::QUERY_OBJECT_TAX,
							'display' => 'detailed',
						),
						'multiple'     => true,
						'condition'    => $condition,
					)
				);
			}

			unset( $condition['new_post_terms'] );

			$condition['save_to_post'] = array( 'new_post', 'edit_post', 'duplicate_post' );

			$widget->add_control(
				'new_post_status',
				array(
					'label'       => __( 'Post Status', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => 'no_change',
					'options'     => array(
						'draft'   => __( 'Draft', 'acf-frontend-form-element' ),
						'private' => __( 'Private', 'acf-frontend-form-element' ),
						'pending' => __( 'Pending Review', 'acf-frontend-form-element' ),
						'publish' => __( 'Published', 'acf-frontend-form-element' ),
					),
					'condition'   => $condition,
				)
			);

		}

		public function get_core_fields() {
			 return array(
				 'post_title',
				 'post_slug',
				 'post_status',
				 'post_content',
				 'post_author',
				 'post_excerpt',
				 'post_date',
				 'post_type',
				 'menu_order',
				 'allow_comments',
			 );
		}

		public function run( $form, $step = false ) {
			$record = $form['record'];

			if ( empty( $record['post'] ) || empty( $record['fields']['post'] ) ) {
				return $form;
			}

			$post_id = $record['post'];

			// allow for custom save
			$post_id = apply_filters( 'acf/pre_save_post', $post_id, $form );

			$post_to_edit = array();

			if ( isset( $form['step_index'] ) ) {
				$current_step = $form['fields']['steps'][ $form['step_index'] ];
			}

			$old_status        = '';
			$post_to_duplicate = false;

			switch ( $form['save_to_post'] ) {
				case 'edit_post':
					if ( get_post_type( $post_id ) == 'revision' && isset( $record['status'] ) && $record['status'] == 'publish' ) {
						$revision_id = $post_id;
						$post_id     = wp_get_post_parent_id( $revision_id );
						wp_delete_post_revision( $revision_id );
					}
					$old_status         = get_post_field( 'post_status', $post_id );
					$post_to_edit['ID'] = $post_id;
					break;
				case 'new_post':
					if ( $post_id == 'add_post' ) {
						 $post_to_edit['ID']        = 0;
						 $post_to_edit['post_type'] = $form['new_post_type'];
					} else {
						$post_to_edit['ID'] = $post_id;
					}
					if ( ! empty( $current_step['overwrite_settings'] ) ) {
						$post_to_edit['post_type'] = $current_step['new_post_type'];
					}
					break;
				case 'duplicate_post':
					$post_to_duplicate           = get_post( $post_id );
					$post_to_edit                = get_object_vars( $post_to_duplicate );
					$post_to_edit['ID']          = 0;
					$post_to_edit['post_author'] = get_current_user_id();
					break;
				default:
					return $form;
			}

			$metas = array();

			$core_fields = $this->get_core_fields();

			if ( ! empty( $record['fields']['post'] ) ) {
				foreach ( $record['fields']['post'] as $name => $_field ) {
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

					$submit_key = $field_type == 'post_slug' ? 'post_name' : $field_type;

					if ( 'post_title' == $field_type && ! empty( $field['custom_slug'] ) ) {
						$post_to_edit['post_name'] = sanitize_title( $field['value'] );
					}

					$post_to_edit[ $submit_key ] = $field['value'];
				}
			}

			if ( $form['save_to_post'] == 'duplicate_post' ) {
				if ( $post_to_edit['post_name'] == $post_to_duplicate->post_name ) {
					$post_name = sanitize_title( $post_to_edit['post_title'] );
					if ( ! feadmin_slug_exists( $post_name ) ) {
						   $post_to_edit['post_name'] = $post_name;
					} else {
						 $post_to_edit['post_name'] = feadmin_duplicate_slug( $post_to_duplicate->post_name );
					}
				}
			}

			if ( empty( $post_to_edit['post_status'] ) ) {
				if ( isset( $current_step ) && empty( $form['last_step'] ) && empty( $current_step['overwrite_settings'] ) ) {
					$post_to_edit['post_status'] = 'auto-draft';
				} else {
					if ( isset( $record['status'] ) && $record['status'] == 'draft' ) {
						   $post_to_edit['post_status'] = 'draft';
					} else {
						$status = 'draft';
						if ( isset( $form['new_post_status'] ) ) {
							$status = $form['new_post_status'];
						}

						if ( ! empty( $current_step['overwrite_settings'] ) ) {
							$status = $current_step['new_post_status'];
						}

						if ( $status != 'no_change' ) {
							$post_to_edit['post_status'] = $status;
						} elseif ( empty( $old_status ) || $old_status == 'auto-draft' ) {
							$post_to_edit['post_status'] = 'publish';
						}
					}
				}
			}

			$form = $this->save_post( $form, $post_to_edit, $metas, $post_to_duplicate );
			return $form;
		}

		public function save_post( $form, $post_to_edit, $metas, $post_to_duplicate ) {
			if ( $post_to_edit['ID'] == 0 ) {
				$post_to_edit['meta_input'] = array(
					'admin_form_source' => str_replace( '_', '', $form['id'] ),
				);
				if ( empty( $post_to_edit['post_title'] ) ) {
					$post_to_edit['post_title'] = '(no-name)';
				}

				if ( isset( $form['approval'] ) ) {
					if ( empty( $post_to_edit['post_author'] ) ) {
						$post_to_edit['post_author'] = $form['submitted_by'];
					}
					/*
					 if( empty( $post_to_edit['post_date'] ) ){
					$post_to_edit['post_date'] = $form['submitted_on'];
					} */
				}

				$post_id = wp_insert_post( $post_to_edit );
				$GLOBALS['admin_form']['record']['post'] = $post_id;
				$form['record']['post'] = $post_id;
			} else {
				$post_id = $post_to_edit['ID'];
				wp_update_post( $post_to_edit );
				update_metadata( 'post', $post_id, 'admin_form_edited', $form['id'] );
			}

			if ( isset( $form['post_terms'] ) && $form['post_terms'] != '' ) {
				$new_terms = $form['post_terms'];
				if ( is_string( $new_terms ) ) {
					$new_terms = explode( ',', $new_terms );
				}
				if ( is_array( $new_terms ) ) {
					foreach ( $new_terms as $term_id ) {
						   $term = get_term( $term_id );
						if ( $term ) {
							wp_set_object_terms( $post_id, $term->term_id, $term->taxonomy, true );
						}
					}
				}
			}

			if ( $form['save_to_post'] == 'duplicate_post' ) {
				$taxonomies = get_object_taxonomies( $post_to_duplicate->post_type );
				foreach ( $taxonomies as $taxonomy ) {
					$post_terms = wp_get_object_terms( $post_to_duplicate->ID, $taxonomy, array( 'fields' => 'slugs' ) );
					wp_set_object_terms( $post_id, $post_terms, $taxonomy, false );
				}

				global $wpdb;
				$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_to_duplicate->ID" );
				if ( count( $post_meta_infos ) != 0 ) {
					$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
					foreach ( $post_meta_infos as $meta_info ) {
						   $meta_key        = $meta_info->meta_key;
						   $meta_value      = addslashes( $meta_info->meta_value );
						   $sql_query_sel[] = "SELECT $post_id, '$meta_key', '$meta_value'";
					}
					$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
					$wpdb->query( $sql_query );
				}
			}

			if ( ! empty( $metas ) ) {
				foreach ( $metas as $meta ) {
					acf_update_value( $meta['_input'], $post_id, $meta );
				}
			}

			do_action( 'frontend_admin/save_post', $form, $post_id );
			do_action( 'acf_frontend/save_post', $form, $post_id );
			return $form;
		}

		public function __construct() {
			 add_filter( 'acf_frontend/save_form', array( $this, 'save_form' ), 4 );
			add_action( 'wp_ajax_acf/fields/post_object/query', array( $this, 'before_posts_query' ), 4 );
		}

	}

	fea_instance()->local_actions['post'] = new ActionPost();

endif;
