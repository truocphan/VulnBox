<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'related_items' ) ) :

	class related_items extends Field_Base {


		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since   5.0.0
		*
		*  @param   n/a
		*  @return  n/a
		*/

		function initialize() {

			// vars
			$this->name     = 'related_items';
			$this->label    = __( 'Relationship', 'acf' );
			$this->category = 'relational';
			$this->defaults = array(
				'post_type'     => array(),
				'taxonomy'      => array(),
				'min'           => 0,
				'max'           => 0,
				'filters'       => array( 'search', 'post_type', 'taxonomy' ),
				'elements'      => array(),
				'return_format' => 'object',
			);

			$fields = array( 'relationship', 'post_object' );
			add_action( 'wp_ajax_acf/fields/form_fields/query', array( $this, 'form_fields_query' ) );

			foreach ( $fields as $field ) {
				add_filter( "acf/load_field/type=$field", array( $this, 'load_relationship_field' ) );
				add_action( "acf/render_field_settings/type=$field", array( $this, 'add_edit_field' ) );
				add_action( "acf/render_field/type=$field", array( $this, 'add_post_button' ), 10 );
				if ( $field == 'relationship' ) {
					add_filter( "acf/fields/$field/result", array( $this, 'edit_post_button' ), 10, 4 );
					add_filter( "acf/fields/$field/query", array( $this, 'relationship_query' ), 10, 3 );
				}
			}

			// extra
			add_action( 'wp_ajax_acf/fields/relationship/query', array( $this, 'ajax_query' ) );
			add_action( 'wp_ajax_nopriv_acf/fields/relationship/query', array( $this, 'ajax_query' ) );

		}


		/*
		*  input_admin_enqueue_scripts
		*
		*  description
		*
		*  @type    function
		*  @date    16/12/2015
		*  @since   5.3.2
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function input_admin_enqueue_scripts() {

			// localize
			acf_localize_text(
				array(
					// 'Minimum values reached ( {min} values )' => __('Minimum values reached ( {min} values )', 'acf'),
					'Maximum values reached ( {max} values )' => __( 'Maximum values reached ( {max} values )', 'acf' ),
					'Loading'          => __( 'Loading', 'acf' ),
					'No matches found' => __( 'No matches found', 'acf' ),
				)
			);
		}

		

		/*
		*  ajax_query
		*
		*  description
		*
		*  @type    function
		*  @date    24/10/13
		*  @since   5.0.0
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function ajax_query() {

			// validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			// get choices
			$response = $this->get_ajax_query( $_POST );

			// return
			acf_send_ajax_results( $response );

		}


		/*
		*  get_ajax_query
		*
		*  This function will return an array of data formatted for use in a select2 AJAX response
		*
		*  @type    function
		*  @date    15/10/2014
		*  @since   5.0.9
		*
		*  @param   $options (array)
		*  @return  (array)
		*/

		function get_ajax_query( $options = array() ) {

			// defaults
			$options = wp_parse_args(
				$options,
				array(
					'post_id'   => 0,
					's'         => '',
					'field_key' => '',
					'paged'     => 1,
					'post_type' => '',
					'taxonomy'  => '',
				)
			);

			// load field
			$field = acf_get_field( $options['field_key'] );
			if ( ! $field ) {
				return false;
			}

			// vars
			$results   = array();
			$args      = array();
			$s         = false;
			$is_search = false;

			// paged
			$args['posts_per_page'] = 20;
			$args['paged']          = intval( $options['paged'] );

			// search
			if ( $options['s'] !== '' ) {

				// strip slashes (search may be integer)
				$s = wp_unslash( strval( $options['s'] ) );

				// update vars
				$args['s'] = $s;
				$is_search = true;

			}

			// post_type
			if ( ! empty( $options['post_type'] ) ) {

				$args['post_type'] = acf_get_array( $options['post_type'] );

			} elseif ( ! empty( $field['post_type'] ) ) {

				$args['post_type'] = acf_get_array( $field['post_type'] );

			} else {

				$args['post_type'] = acf_get_post_types();

			}

			// taxonomy
			if ( ! empty( $options['taxonomy'] ) ) {

				// vars
				$term = acf_decode_taxonomy_term( $options['taxonomy'] );

				// tax query
				$args['tax_query'] = array();

				// append
				$args['tax_query'][] = array(
					'taxonomy' => $term['taxonomy'],
					'field'    => 'slug',
					'terms'    => $term['term'],
				);

			} elseif ( ! empty( $field['taxonomy'] ) ) {

				// vars
				$terms = acf_decode_taxonomy_terms( $field['taxonomy'] );

				// append to $args
				$args['tax_query'] = array(
					'relation' => 'OR',
				);

				// now create the tax queries
				foreach ( $terms as $k => $v ) {

					$args['tax_query'][] = array(
						'taxonomy' => $k,
						'field'    => 'slug',
						'terms'    => $v,
					);

				}
			}

			// filters
			$args = apply_filters( 'acf/fields/relationship/query', $args, $field, $options['post_id'] );
			$args = apply_filters( 'acf/fields/relationship/query/name=' . $field['name'], $args, $field, $options['post_id'] );
			$args = apply_filters( 'acf/fields/relationship/query/key=' . $field['key'], $args, $field, $options['post_id'] );

			// get posts grouped by post type
			$groups = acf_get_grouped_posts( $args );

			// bail early if no posts
			if ( empty( $groups ) ) {
				return false;
			}

			// loop
			foreach ( array_keys( $groups ) as $group_title ) {

				// vars
				$posts = acf_extract_var( $groups, $group_title );

				// data
				$data = array(
					'text'     => $group_title,
					'children' => array(),
				);

				// convert post objects to post titles
				foreach ( array_keys( $posts ) as $post_id ) {

					$posts[ $post_id ] = $this->get_post_title( $posts[ $post_id ], $field, $options['post_id'] );

				}

				// order posts by search
				if ( $is_search && empty( $args['orderby'] ) && isset( $args['s'] ) ) {

					$posts = acf_order_by_search( $posts, $args['s'] );

				}

				// append to $data
				foreach ( array_keys( $posts ) as $post_id ) {

					$data['children'][] = $this->get_post_result( $post_id, $posts[ $post_id ] );

				}

				// append to $results
				$results[] = $data;

			}

			// add as optgroup or results
			if ( count( $args['post_type'] ) == 1 ) {

				$results = $results[0]['children'];

			}

			// vars
			$response = array(
				'results' => $results,
				'limit'   => $args['posts_per_page'],
			);

			// return
			return $response;

		}


		/*
		*  get_post_result
		*
		*  This function will return an array containing id, text and maybe description data
		*
		*  @type    function
		*  @date    7/07/2016
		*  @since   5.4.0
		*
		*  @param   $id (mixed)
		*  @param   $text (string)
		*  @return  (array)
		*/

		function get_post_result( $id, $text ) {

			// vars
			$result = array(
				'id'   => $id,
				'text' => $text,
			);

			// return
			return $result;

		}


		/*
		*  get_post_title
		*
		*  This function returns the HTML for a result
		*
		*  @type    function
		*  @date    1/11/2013
		*  @since   5.0.0
		*
		*  @param   $post (object)
		*  @param   $field (array)
		*  @param   $post_id (int) the post_id to which this value is saved to
		*  @return  (string)
		*/

		function get_post_title( $post, $field, $post_id = 0, $is_search = 0 ) {

			// get post_id
			if ( ! $post_id ) {
				$post_id = acf_get_form_data( 'post_id' );
			}

			// vars
			$title = acf_get_post_title( $post, $is_search );

			// featured_image
			if ( acf_in_array( 'featured_image', $field['elements'] ) ) {

				// vars
				$class     = 'thumbnail';
				$thumbnail = acf_get_post_thumbnail( $post->ID, array( 17, 17 ) );

				// icon
				if ( $thumbnail['type'] == 'icon' ) {

					$class .= ' -' . $thumbnail['type'];

				}

				// append
				$title = '<div class="' . $class . '">' . $thumbnail['html'] . '</div>' . $title;

			}

			// filters
			$title = apply_filters( 'acf/fields/relationship/result', $title, $post, $field, $post_id );
			$title = apply_filters( 'acf/fields/relationship/result/name=' . $field['_name'], $title, $post, $field, $post_id );
			$title = apply_filters( 'acf/fields/relationship/result/key=' . $field['key'], $title, $post, $field, $post_id );

			// return
			return $title;

		}


		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param   $field - an array holding all the field's data
		*
		*  @type    action
		*  @since   3.6
		*  @date    23/01/13
		*/

		function render_field( $field ) {

			// vars
			$post_type = acf_get_array( $field['post_type'] );
			$taxonomy  = acf_get_array( $field['taxonomy'] );
			$filters   = acf_get_array( $field['filters'] );

			// filters
			$filter_count             = count( $filters );
			$filter_post_type_choices = array();
			$filter_taxonomy_choices  = array();

			// post_type filter
			if ( in_array( 'post_type', $filters ) ) {

				$filter_post_type_choices = array(
					'' => __( 'Select post type', 'acf' ),
				) + acf_get_pretty_post_types( $post_type );
			}

			// taxonomy filter
			if ( in_array( 'taxonomy', $filters ) ) {

				$term_choices            = array();
				$filter_taxonomy_choices = array(
					'' => __( 'Select taxonomy', 'acf' ),
				);

				// check for specific taxonomy setting
				if ( $taxonomy ) {
					$terms        = acf_get_encoded_terms( $taxonomy );
					$term_choices = acf_get_choices_from_terms( $terms, 'slug' );

					// if no terms were specified, find all terms
				} else {

					// restrict taxonomies by the post_type selected
					$term_args = array();
					if ( $post_type ) {
						$term_args['taxonomy'] = acf_get_taxonomies(
							array(
								'post_type' => $post_type,
							)
						);
					}

					// get terms
					$terms        = acf_get_grouped_terms( $term_args );
					$term_choices = acf_get_choices_from_grouped_terms( $terms, 'slug' );
				}

				// append term choices
				$filter_taxonomy_choices = $filter_taxonomy_choices + $term_choices;

			}

			// div attributes
			$atts = array(
				'id'             => $field['id'],
				'class'          => "acf-relationship {$field['class']}",
				'data-min'       => $field['min'],
				'data-max'       => $field['max'],
				'data-s'         => '',
				'data-paged'     => 1,
				'data-post_type' => '',
				'data-taxonomy'  => '',
			);

			?>
<div <?php echo acf_esc_attrs( $atts ); ?>>
	
			<?php
			acf_hidden_input(
				array(
					'name'  => $field['name'],
					'value' => '',
				)
			);
			?>
	
			<?php

			/* filters */
			if ( $filter_count ) :
				?>
	<div class="filters -f<?php echo esc_attr( $filter_count ); ?>">
				<?php

				/* search */
				if ( in_array( 'search', $filters ) ) :
					?>
		<div class="filter -search">
					<?php
					acf_text_input(
						array(
							'placeholder' => __( 'Search...', 'acf' ),
							'data-filter' => 's',
						)
					);
					?>
		</div>
					<?php
			endif;

				/* post_type */
				if ( in_array( 'post_type', $filters ) ) :
					?>
		<div class="filter -post_type">
					<?php
					acf_select_input(
						array(
							'choices'     => $filter_post_type_choices,
							'data-filter' => 'post_type',
						)
					);
					?>
		</div>
					<?php
			endif;

				/* post_type */
				if ( in_array( 'taxonomy', $filters ) ) :
					?>
		<div class="filter -taxonomy">
					<?php
					acf_select_input(
						array(
							'choices'     => $filter_taxonomy_choices,
							'data-filter' => 'taxonomy',
						)
					);
					?>
		</div>
				<?php endif; ?>		
	</div>
			<?php endif; ?>
	
	<div class="selection">
		<div class="choices">
			<ul class="acf-bl list choices-list"></ul>
		</div>
		<div class="values">
			<ul class="acf-bl list values-list">
				<?php
				if ( ! empty( $field['value'] ) ) :

					// get posts
					$posts = acf_get_posts(
						array(
							'post__in'  => $field['value'],
							'post_type' => $field['post_type'],
						)
					);

						// loop
					foreach ( $posts as $post ) :
						?>
					<li>
						<?php
							acf_hidden_input(
								array(
									'name'  => $field['name'] . '[]',
									'value' => $post->ID,
								)
							);
						?>
						<span data-id="<?php echo esc_attr( $post->ID ); ?>" class="acf-rel-item">
								<?php echo acf_esc_html( $this->get_post_title( $post, $field ) ); ?>
							<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>
						</span>
					</li>
						<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
			<?php
		}


		/*
		*  render_field_settings()
		*
		*  Create extra options for your field. This is rendered when editing a field.
		*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
		*
		*  @type    action
		*  @since   3.6
		*  @date    23/01/13
		*
		*  @param   $field  - an array holding all the field's data
		*/
		function render_field_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Post Type', 'acf' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'post_type',
					'choices'      => acf_get_pretty_post_types(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All post types', 'acf' ),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Taxonomy', 'acf' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'taxonomy',
					'choices'      => acf_get_taxonomy_terms(),
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All taxonomies', 'acf' ),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filters', 'acf' ),
					'instructions' => '',
					'type'         => 'checkbox',
					'name'         => 'filters',
					'choices'      => array(
						'search'    => __( 'Search', 'acf' ),
						'post_type' => __( 'Post Type', 'acf' ),
						'taxonomy'  => __( 'Taxonomy', 'acf' ),
					),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Return Format', 'acf' ),
					'instructions' => '',
					'type'         => 'radio',
					'name'         => 'return_format',
					'choices'      => array(
						'object' => __( 'Post Object', 'acf' ),
						'id'     => __( 'Post ID', 'acf' ),
					),
					'layout'       => 'horizontal',
				)
			);
		}

		/**
		 * Renders the field settings used in the "Validation" tab.
		 *
		 * @since 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		function render_field_validation_settings( $field ) {
			$field['min'] = empty( $field['min'] ) ? '' : $field['min'];
			$field['max'] = empty( $field['max'] ) ? '' : $field['max'];

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Minimum posts', 'acf' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'min',
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Maximum posts', 'acf' ),
					'instructions' => '',
					'type'         => 'number',
					'name'         => 'max',
				)
			);
		}

		/**
		 * Renders the field settings used in the "Presentation" tab.
		 *
		 * @since 6.0
		 *
		 * @param array $field The field settings array.
		 * @return void
		 */
		function render_field_presentation_settings( $field ) {
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Elements', 'acf' ),
					'instructions' => __( 'Selected elements will be displayed in each result', 'acf' ),
					'type'         => 'checkbox',
					'name'         => 'elements',
					'choices'      => array(
						'featured_image' => __( 'Featured Image', 'acf' ),
					),
				)
			);
		}

		/*
		*  format_value()
		*
		*  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
		*
		*  @type    filter
		*  @since   3.6
		*  @date    23/01/13
		*
		*  @param   $value (mixed) the value which was loaded from the database
		*  @param   $post_id (mixed) the $post_id from which the value was loaded
		*  @param   $field (array) the field array holding all the field options
		*
		*  @return  $value (mixed) the modified value
		*/

		function format_value( $value, $post_id, $field ) {

			// bail early if no value
			if ( empty( $value ) ) {

				return $value;

			}

			// force value to array
			$value = acf_get_array( $value );

			// convert to int
			$value = array_map( 'intval', $value );

			// load posts if needed
			if ( $field['return_format'] == 'object' ) {

				// get posts
				$value = acf_get_posts(
					array(
						'post__in'  => $value,
						'post_type' => $field['post_type'],
					)
				);

			}

			// return
			return $value;

		}


		/*
		*  validate_value
		*
		*  description
		*
		*  @type    function
		*  @date    11/02/2014
		*  @since   5.0.0
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function validate_value( $valid, $value, $field, $input ) {

			// default
			if ( empty( $value ) || ! is_array( $value ) ) {

				$value = array();

			}

			// min
			if ( count( $value ) < $field['min'] ) {

				$valid = _n( '%1$s requires at least %2$s selection', '%1$s requires at least %2$s selections', $field['min'], 'acf' );
				$valid = sprintf( $valid, $field['label'], $field['min'] );

			}

			// return
			return $valid;

		}


		/**
		 *  update_value()
		 *
		 *  This filter is applied to the $value before it is updated in the db
		 *
		 *  @type    filter
		 *  @since   3.6
		 *  @date    23/01/13
		 *
		 *  @param   $value - the value which will be saved in the database
		 *  @param   $post_id - the $post_id of which the value will be saved
		 *  @param   $field - the field array holding all the field options
		 *
		 *  @return  $value - the modified value
		 */
		function update_value( $value, $post_id, $field ) {
			// Bail early if no value.
			if ( empty( $value ) ) {
				return $value;
			}

			// Format array of values.
			// - ensure each value is an id.
			// - Parse each id as string for SQL LIKE queries.
			if ( acf_is_sequential_array( $value ) ) {
				$value = array_map( 'acf_idval', $value );
				$value = array_map( 'strval', $value );

				// Parse single value for id.
			} else {
				$value = acf_idval( $value );
			}

			// Return value.
			return $value;
		}

		/**
		 * Validates relationship fields updated via the REST API.
		 *
		 * @param bool  $valid
		 * @param int   $value
		 * @param array $field
		 *
		 * @return bool|WP_Error
		 */
		public function validate_rest_value( $valid, $value, $field ) {
			return acf_get_field_type( 'post_object' )->validate_rest_value( $valid, $value, $field );
		}

		/**
		 * Return the schema array for the REST API.
		 *
		 * @param array $field
		 * @return array
		 */
		public function get_rest_schema( array $field ) {
			$schema = array(
				'type'     => array( 'integer', 'array', 'null' ),
				'required' => ! empty( $field['required'] ),
				'items'    => array(
					'type' => 'integer',
				),
			);

			if ( empty( $field['allow_null'] ) ) {
				$schema['minItems'] = 1;
			}

			if ( ! empty( $field['min'] ) ) {
				$schema['minItems'] = (int) $field['min'];
			}

			if ( ! empty( $field['max'] ) ) {
				$schema['maxItems'] = (int) $field['max'];
			}

			return $schema;
		}

		/**
		 * @see \acf_field::get_rest_links()
		 * @param mixed      $value The raw (unformatted) field value.
		 * @param int|string $post_id
		 * @param array      $field
		 * @return array
		 */
		public function get_rest_links( $value, $post_id, array $field ) {
			$links = array();

			if ( empty( $value ) ) {
				return $links;
			}

			foreach ( (array) $value as $object_id ) {
				if ( ! $post_type = get_post_type( $object_id ) or ! $post_type = get_post_type_object( $post_type ) ) {
					continue;
				}
				$rest_base = acf_get_object_type_rest_base( $post_type );
				$links[]   = array(
					'rel'        => $post_type->name === 'attachment' ? 'acf:attachment' : 'acf:post',
					'href'       => rest_url( sprintf( '/wp/v2/%s/%s', $rest_base, $object_id ) ),
					'embeddable' => true,
				);
			}

			return $links;
		}

		/**
		 * Apply basic formatting to prepare the value for default REST output.
		 *
		 * @param mixed      $value
		 * @param string|int $post_id
		 * @param array      $field
		 * @return mixed
		 */
		public function format_value_for_rest( $value, $post_id, array $field ) {
			return acf_format_numerics( $value );
		}

		public function add_edit_field( $field ) {
			$users        = get_users();
			$label        = __( 'Dynamic', 'acf-frontend-form-element' );
			$user_choices = array( $label => array( 'current_user' => __( 'Current User', 'acf-frontend-form-element' ) ) );
			// Append.
			if ( $users ) {
				$user_label                  = __( 'Users', 'acf-frontend-form-element' );
				$user_choices[ $user_label ] = array();
				foreach ( $users as $user ) {
					$user_text = $user->user_login;
					// Add name.
					if ( $user->first_name && $user->last_name ) {
						   $user_text .= " ({$user->first_name} {$user->last_name})";
					} elseif ( $user->first_name ) {
							  $user_text .= " ({$user->first_name})";
					}
					$user_choices[ $user_label ][ $user->ID ] = $user_text;
				}
			}
			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Filter by Post Author', 'acf-frontend-form-element' ),
					'instructions' => '',
					'type'         => 'select',
					'name'         => 'post_author',
					'choices'      => $user_choices,
					'multiple'     => 1,
					'ui'           => 1,
					'allow_null'   => 1,
					'placeholder'  => __( 'All Users', 'acf-frontend-form-element' ),
				)
			);

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Add and Edit Posts' ),
					'instructions' => __( 'Allow posts to be created and edited whilst editing', 'acf-frontend-form-element' ),
					'name'         => 'add_edit_post',
					'type'         => 'true_false',
					'ui'           => 1,
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'      => __( 'Limit Editing to Post Author' ),
					'name'       => 'post_author_edit',
					'type'       => 'true_false',
					'ui'         => 1,
					'conditions' => array(
						array(
							'field'    => 'add_edit_post',
							'operator' => '==',
							'value'    => '1',
						),
					),
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'         => __( 'Add Post Button' ),
					'name'          => 'add_post_button',
					'type'          => 'text',
					'default_value' => __( 'Add Post' ),
					'placeholder'   => __( 'Add Post' ),
					'conditions'    => array(
						array(
							'field'    => 'add_edit_post',
							'operator' => '==',
							'value'    => '1',
						),
					),
				)
			);
			acf_render_field_setting(
				$field,
				array(
					'label'         => __( 'Form Container Width' ),
					'name'          => 'form_width',
					'type'          => 'number',
					'prepend'       => 'px',
					'default_value' => 600,
					'placeholder'   => 600,
					'conditions'    => array(
						array(
							'field'    => 'add_edit_post',
							'operator' => '==',
							'value'    => '1',
						),
					),
				)
			);

			$templates_options = array( 'current' => __( 'Current Form/Field Group', 'acf-frontend-form-element' ) );
			if ( isset( $field['post_form_template'] ) ) {
				$selected = $field['post_form_template'];
			} else {
				$selected = array();
			}

			acf_render_field_setting(
				$field,
				array(
					'label'        => __( 'Forms/Field Groups' ),
					'name'         => 'post_form_template',
					'instructions' => '<div>' . __( 'Select an existing field group or form or the current field group or form', 'acf-frontend-form-element' ) . '</div>',
					'type'         => 'select',
					'ajax'         => 1,
					'multiple'     => 1,
					'placeholder'  => __( 'Default', 'acf-frontend-form-element' ),
					'ajax_action'  => 'acf/fields/form_fields/query',
					'choices'      => $this->get_selected_fields( $selected ),
					'ui'           => 1,
					'conditions'   => array(
						array(
							'field'    => 'add_edit_post',
							'operator' => '==',
							'value'    => '1',
						),
					),
				)
			);

		}
		/*
		*  get_selected_fields
		*
		*  This function will return an array of choices data for Select2
		*
		*  @type    function
		*  @date    17/06/2016
		*  @since   5.3.8
		*
		*  @param   $value (mixed)
		*  @return  (array)
		*/

		function get_selected_fields( $value ) {
			// vars
			$choices = array();

			// bail early if no $value
			if ( empty( $value ) ) {
				return $choices;
			}

			// force value to array
			$value = acf_get_array( $value );

			// loop
			foreach ( $value as $v ) {
				if ( $v == 'current' ) {
					$choices['current'] = __( 'Current Form/Field Group', 'acf-frontend-form-element' );
				} else {
					$choices[ $v ] = feadmin_get_selected_field( $v );
				}
			}

			// return
			return $choices;

		}

		public function load_relationship_field( $field ) {
			if ( ! isset( $field['add_edit_post'] ) ) {
				return $field;
			}

			if ( isset( $field['form_width'] ) ) {
				$field['wrapper']['data-form_width'] = $field['form_width'];
			}

			if ( empty( $field['post_form_template'] ) ) {
				$field['post_form_template'] = '';
			}

			return $field;
		}

		public function edit_post_button( $title, $post, $field, $post_id ) {
			if ( ! empty( $field['add_edit_post'] ) ) :
				if ( ! empty( $field['post_author_edit'] )
					&& get_current_user_id() != $post->post_author
				) {
					return $title;
				}
				$title .= '<a href="#" class="acf-icon -pencil small dark edit-rel-post render-form" data-name="edit_item"></a>';
			endif;
			return $title;
		}

		public function add_post_button( $field ) {
			if ( ! empty( $field['add_edit_post'] ) ) :
				$add_post_button = ( $field['add_post_button'] ) ? $field['add_post_button'] : __( 'Add Post', 'acf-frontend-form-element' );
				?>
				<div class="margin-top-10 acf-actions">
					<a class="add-rel-post acf-button button button-primary render-form" href="#" data-name="add_item"><?php esc_html_e( $add_post_button ); ?></a>
				</div>
				
				<?php
			endif;
		}

		public function relationship_query( $args, $field, $post_id ) {
			if ( ! isset( $field['post_author'] ) ) {
				return $args;
			}

			$post_author = acf_get_array( $field['post_author'] );

			if ( in_array( 'current_user', $post_author ) ) {
				$key                 = array_search( 'current_user', $post_author );
				$post_author[ $key ] = get_current_user_id();
			}

			$args['author__in'] = $post_author;

			return $args;
		}

		/*
		*  form_fields_query
		*
		*  description
		*
		*  @type    function
		*  @date    17/06/2016
		*  @since   5.3.8
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function form_fields_query() {
			 // validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			// disable field to allow clone fields to appear selectable
			acf_disable_filter( 'fields_select' );

			// options
			$options = acf_parse_args(
				$_POST,
				array(
					'post_id' => 0,
					'paged'   => 0,
					's'       => '',
					'title'   => '',
					'fields'  => array(),
				)
			);
			// vars
			$results = array();
			if ( $options['paged'] == 1 && ! $options['s'] ) {
				$results = array(
					array( 'text' => __( 'Default', 'acf-frontend-form-element' ) ),
					array(
						'id'   => 'current',
						'text' => __( 'Current Form/Field Group', 'acf-frontend-form-element' ),
					),
				);
			}
			$s           = false;
			$i           = -1;
			$limit       = 20;
			$range_start = $limit * ( $options['paged'] - 1 );  // 0,  20, 40
			$range_end   = $range_start + ( $limit - 1 );         // 19, 39, 59

			// search
			if ( $options['s'] !== '' ) {

				// strip slashes (search may be integer)
				$s = wp_unslash( strval( $options['s'] ) );

			}

			$args = array(
				'post_type'      => array( 'acf-field-group' ),
				'posts_per_page' => '-1',
				'post_status'    => 'any',
			);

			// load groups
			$field_groups = get_posts( $args );
			$field_group  = false;

			// bail early if no field groups
			if ( empty( $field_groups ) ) {
				die();
			}

			// move current field group to start
			foreach ( $field_groups as $k => $group ) {

				// check ID
				if ( $k->ID !== $options['post_id'] ) {
					continue;
				}

				// extract field group and move to start
				$field_group = acf_extract_var( $field_groups, $k );

				// field group found, stop looking
				break;

			}

			// if field group was not found, this is a new field group (not yet saved)
			if ( ! $field_group ) {

				$field_group = array(
					'ID'    => $options['post_id'],
					'title' => $options['title'],
					'key'   => '',
				);

			}

			// move current field group to start of list
			array_unshift( $field_groups, $field_group );

			// loop
			foreach ( $field_groups as $field_group ) {

				// vars
				$fields   = false;
				$ignore_s = false;
				$data     = array(
					'text'     => $field_group->post_title,
					'children' => array(),
				);

				// get fields
				if ( $field_group->ID == $options['post_id'] ) {

					$fields = $options['fields'];

				} else {

					$fields = acf_get_fields( $field_group->ID );
					$fields = acf_prepare_fields_for_import( $fields );

				}

				// bail early if no fields
				if ( ! $fields ) {
					continue;
				}

				// show all children for field group search match
				if ( $s !== false && stripos( $data['text'], $s ) !== false ) {

					$ignore_s = true;

				}

				$i++;

				// bail early if $i is out of bounds
				if ( $i < $range_start || $i > $range_end ) {
					continue;
				}
				// populate children
				$data['children'][] = array(
					'id'   => $field_group->ID,
					'text' => sprintf( __( 'All fields from %s', 'acf-frontend-form-element' ), $field_group->post_title ),
				);
				foreach ( $fields as $field ) {
					// bail ealry if no key (fake field group or corrupt field)
					if ( ! $field ) {
						continue;
					}

					// vars
					$text = false;

					// bail early if is search, and $text does not contain $s
					if ( $s !== false && ! $ignore_s ) {

						// get early
						$text = feadmin_get_selected_field( $child );

						// search
						if ( stripos( $text, $s ) === false ) {
							continue;
						}
					}

					// $i
					$i++;

					// bail early if $i is out of bounds
					if ( $i < $range_start || $i > $range_end ) {
						continue;
					}

					$data['children'][] = array(
						'id'   => $field['ID'],
						'text' => $field['label'],
					);

				}

				// bail early if no children
				// - this group contained fields, but none shown on this page
				if ( empty( $data['children'] ) ) {
					continue;
				}

				// append
				$results[] = $data;

				// end loop if $i is out of bounds
				// - no need to look further
				if ( $i > $range_end ) {
					break;
				}
			}
			// return
			acf_send_ajax_results(
				array(
					'results' => $results,
					'limit'   => $limit,
				)
			);

		}

	}



endif; // class_exists check

?>
