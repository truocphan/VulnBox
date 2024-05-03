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
if ( ! class_exists( 'ActionTerm' ) ) :

	class ActionTerm extends ActionBase {


		public function get_name() {
			return 'term';
		}

		public function get_label() {
			return __( 'Term', 'acf-frontend-form-element' );
		}

		public function get_fields_display( $form_field, $local_field ) {
			switch ( $form_field['field_type'] ) {
				case 'term_name':
					$local_field['type']        = 'term_name';
					$local_field['change_slug'] = isset( $form_field['change_slug'] ) ? $form_field['change_slug'] : 0;
					break;
				case 'term_slug':
					$local_field['type'] = 'term_slug';
					break;
				case 'term_description':
					$local_field['type'] = 'term_description';
					break;
			}
			return $local_field;
		}

		public function get_default_fields( $form, $action = '' ) {
			switch ( $action ) {
				case 'delete':
					$default_fields = array(
						'delete_term',
					);
					break;
				default:
					$default_fields = array(
						'term_name',
						'term_slug',
						'term_description',
						'submit_button',
					);
			}
			return $this->get_valid_defaults( $default_fields, $form );
		}

		public function get_form_builder_options( $form ) {
			 return array(
				 array(
					 'key'               => 'save_to_term',
					 'field_label_hide'  => 0,
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => 0,
					 'choices'           => array(
						 'edit_term' => __( 'Edit Term', 'acf-frontend-form-element' ),
						 'new_term'  => __( 'New Term', 'acf-frontend-form-element' ),
					 ),
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'new_term_taxonomy',
					 'label'             => __( 'Taxonomy', 'acf-frontend-form-element' ),
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_term',
								 'operator' => '==',
								 'value'    => 'new_term',
							 ),
						 ),
					 ),
					 'choices'           => acf_get_taxonomy_labels(),
					 'default_value'     => 'category',
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 0,
					 'return_format'     => 'value',
					 'ajax'              => 0,
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'term_to_edit',
					 'label'             => __( 'Term to Edit', 'acf-frontend-form-element' ),
					 'type'              => 'select',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_term',
								 'operator' => '==',
								 'value'    => 'edit_term',
							 ),
						 ),
					 ),
					 'choices'           => array(
						 'current_term' => __( 'Current Term', 'acf-frontend-form-element' ),
						 'url_query'    => __( 'URL Query', 'acf-frontend-form-element' ),
						 'select_term'  => __( 'Specific Term', 'acf-frontend-form-element' ),
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
					 'key'               => 'url_query_term',
					 'label'             => __( 'URL Query Key', 'acf-frontend-form-element' ),
					 'type'              => 'text',
					 'instructions'      => '',
					 'required'          => 0,
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_term',
								 'operator' => '==',
								 'value'    => 'edit_term',
							 ),
							 array(
								 'field'    => 'term_to_edit',
								 'operator' => '==',
								 'value'    => 'url_query',
							 ),
						 ),
					 ),
					 'placeholder'       => '',
				 ),
				 array(
					 'key'               => 'select_term',
					 'label'             => __( 'Specific Term', 'acf-frontend-form-element' ),
					 'name'              => 'select_term',
					 'type'              => 'select',
					 'prefix'            => 'form',
					 'instructions'      => '',
					 'required'          => 0,
					 'choices'           => acf_get_taxonomy_terms(),
					 'conditional_logic' => array(
						 array(
							 array(
								 'field'    => 'save_to_term',
								 'operator' => '==',
								 'value'    => 'edit_term',
							 ),
							 array(
								 'field'    => 'term_to_edit',
								 'operator' => '==',
								 'value'    => 'select_term',
							 ),
						 ),
					 ),
					 'allow_null'        => 0,
					 'multiple'          => 0,
					 'ui'                => 1,
				 ),

			 );
		}

		public function load_data( $form, $objects ) {
			if ( empty( $form['save_to_term'] ) ) {
				return $form;
			}

			switch ( $form['save_to_term'] ) {
				case 'new_term':
					$form['term_id']                        = 'add_term';
					$form['hidden_fields']['taxonomy_type'] = $form['new_term_taxonomy'];
					break;
				case 'edit_term':
				case 'delete_term':
					if ( empty( $form['term_to_edit'] ) ) {
						$form['term_to_edit'] = 'current_term';
					}
					if ( $form['term_to_edit'] == 'select_term' ) {
						if ( ! empty( $form['select_term'] ) ) {
							$term_id = $form['select_term'];
						} else {
							if ( isset( $form['term_select'] ) ) {
								$term_id = $form['term_select'];
							}
						}
					}
					if ( $form['term_to_edit'] == 'url_query' ) {
						if ( isset( $_GET[ $form['url_query_term'] ] ) ) {
							$term_id = absint( $_GET[ $form['url_query_term'] ] );
						}
					}
					if ( $form['term_to_edit'] == 'current_term' ) {
						$term_obj = get_queried_object();
						if ( ! empty( $term_obj->term_id ) ) {
							$term_id = $term_obj->term_id;
						} else {
							$term_id = 1;
						}
					}
					$get_term = get_term( $term_id );
					if ( empty( $term_id ) || empty( $get_term->term_id ) ) {
						$form['term_id'] = 'none';
					} else {
						$form['hidden_fields']['taxonomy_type'] = $get_term->taxonomy;
						$form['term_id']                        = $term_id;
					}

					break;
			}
			return $form;
		}

		public function register_settings_section( $widget ) {
			$widget->start_controls_section(
				'section_edit_term',
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
			if ( ! empty( $widget->form_defaults['save_to_term'] ) ) {
				$type = $widget->form_defaults['save_to_term'];
			}

			if ( $step ) {
				$condition = array(
					'field_type'         => 'step',
					'overwrite_settings' => 'true',
				);
			}
			$args = array(
				'label'   => __( 'Term', 'acf-frontend-form-element' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'edit_term' => __( 'Edit Term', 'acf-frontend-form-element' ),
					'new_term'  => __( 'New Term', 'acf-frontend-form-element' ),
				),
				'default' => $widget->get_name(),
			);
			if ( $step ) {
				$condition         = array(
					'field_type'         => 'step',
					'overwrite_settings' => 'true',
				);
				$args['condition'] = $condition;
			} else {
				$condition = array();
			}

			if ( $type ) {
				$args = array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => $type,
				);
			}

			$widget->add_control( 'save_to_term', $args );

			$condition['save_to_term'] = array( 'edit_term', 'delete_term' );

			$widget->add_control(
				'term_to_edit',
				array(
					'label'     => __( 'Term To Edit', 'acf-frontend-form-element' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'current_term',
					'options'   => array(
						'current_term' => __( 'Current Term', 'acf-frontend-form-element' ),
						'url_query'    => __( 'Url Query', 'acf-frontend-form-element' ),
						'select_term'  => __( 'Specific Term', 'acf-frontend-form-element' ),
					),
					'condition' => $condition,
				)
			);
			$condition['term_to_edit'] = 'url_query';
			$widget->add_control(
				'url_query_term',
				array(
					'label'       => __( 'URL Query', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'term_id', 'acf-frontend-form-element' ),
					'default'     => __( 'term_id', 'acf-frontend-form-element' ),
					'required'    => true,
					'description' => __( 'Enter the URL query parameter containing the id of the term you want to edit', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);
			$condition['term_to_edit'] = 'select_term';
			$widget->add_control(
				'term_select',
				array(
					'label'       => __( 'Term', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( '18', 'acf-frontend-form-element' ),
					'description' => __( 'Enter term id', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);

			$condition['save_to_term'] = 'new_term';
			unset( $condition['term_to_edit'] );
			$widget->add_control(
				'new_term_taxonomy',
				array(
					'label'       => __( 'Taxonomy', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => 'category',
					'options'     => acf_get_taxonomy_labels(),
					'condition'   => $condition,
				)
			);
		}

		public function get_core_fields() {
			 return array(
				 'term_name'        => 'name',
				 'term_slug'        => 'slug',
				 'term_description' => 'description',
			 );
		}

		public function run( $form, $step = false ) {
			$record = $form['record'];
			if ( empty( $record['term'] ) || empty( $record['fields']['term'] ) ) {
				return $form;
			}

			$term_id = $record['term'];

			// allow for custom save
			$term_id = apply_filters( 'acf/pre_save_term', $term_id, $form );

			$term_name = '(no-name)';
			$term_args = array();

			$core_fields = $this->get_core_fields();

			if ( ! empty( $record['fields']['term'] ) ) {
				foreach ( $record['fields']['term'] as $name => $_field ) {
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

					if ( ! in_array( $field_type, array_keys( $core_fields ) ) ) {
						$metas[ $field['key'] ] = $field;
						continue;
					}

					if ( $field_type == 'term_name' && $term_id == 'add_term' ) {
						$term_name = $field['value'];
					} else {
						$term_args[ $core_fields[ $field_type ] ] = $field['value'];
					}
				}
			}

			$taxonomy = ! empty( $record['taxonomy_type'] ) ? $record['taxonomy_type'] : 'category';

			if ( $form['save_to_term'] == 'new_term' ) {
				$term_data = wp_insert_term( $term_name, $taxonomy, $term_args );
				if ( is_wp_error( $term_data ) ) {
					return $form;
				} else {
					$term_id = $term_data['term_id'];
					$GLOBALS['admin_form']['record']['term'] = $term_id;
					$form['record']['term'] = $term_id;
				}
			} elseif ( is_numeric( $term_id ) ) {
				wp_update_term( $term_id, $taxonomy, $term_args );
			} else {
				return $form;
			}

			if ( ! empty( $metas ) ) {
				foreach ( $metas as $meta ) {
					acf_update_value( $meta['_input'], 'term_' . $term_id, $meta );
				}
			}

			$form['record']['term'] = $term_id;

			do_action( 'frontend_admin/save_term', $form, $term_id );
			do_action( 'acf_frontend/save_term', $form, $term_id );

			return $form;
		}

		public function __construct() {
			 add_filter( 'acf_frontend/save_form', array( $this, 'save_form' ), 4 );
		}
	}
	fea_instance()->local_actions['term'] = new ActionTerm();

endif;
