<?php
/**
 * REST API course Categories controller
 *
 * Handles requests to the courses/categories endpoint.
 *
 * @author   WooThemes
 * @category API
 * @package Masteriyo\RestApi
 * @since    1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\RestApi\Controllers\Version1\RestTermsController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API course Categories controller class.
 *
 * @package Masteriyo\RestApi
 * @extends RestTermsController
 */
class CourseCategoriesController extends RestTermsController {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'courses/categories';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_cat';

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_cat';

	/**
	 * Get the Category schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->taxonomy,
			'type'       => 'object',
			'properties' => array(
				'id'          => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'        => array(
					'description' => __( 'Category name', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'slug'        => array(
					'description' => __( 'An alphanumeric identifier for the resource unique to its type.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_title',
					),
				),
				'parent_id'   => array(
					'description' => __( 'The ID for the parent of the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'description' => array(
					'description' => __( 'HTML description of the resource.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'display'     => array(
					'description' => __( 'Category archive display type.', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'default',
					'enum'        => array( 'default', 'courses', 'subcategories', 'both' ),
					'context'     => array( 'view', 'edit' ),
				),
				'term_order'  => array(
					'description' => __( 'Term order, used to custom sort the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'count'       => array(
					'description' => __( 'Number of published courses for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}


	/**
	 * Prepare a single course for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		global $masteriyo_container;

		$id         = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_cat = masteriyo( 'course_cat' );

		if ( 0 !== $id ) {
			$course_cat->set_id( $id );
			$course_cat_repo = masteriyo( 'course_cat.store' );
			$course_cat_repo->read( $course_cat );
		}

		// Term title.
		if ( isset( $request['name'] ) ) {
			$course_cat->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Term description.
		if ( isset( $request['description'] ) ) {
			$course_cat->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Term slug.
		if ( isset( $request['slug'] ) ) {
			$course_cat->set_slug( $request['slug'] );
		}

		// Term order.
		if ( isset( $request['term_order'] ) ) {
			$course_cat->set_term_order( $request['slug'] );
		}

		// Term parent id.
		if ( isset( $request['parent_id'] ) ) {
			$course_cat->set_parent_id( $request['parent_id'] );
		}

		// Term display type.
		if ( isset( $request['display'] ) ) {
			$course_cat->set_display( $request['display'] );
		}

		// Featured image.
		if ( isset( $request['featured_image'] ) ) {
			$course_cat->set_featured_image( $request['featured_image'] );
		}

		return $course_cat;
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int|WP_Term|Model $object Object ID or WP_Term or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Term' ) ? $object->term_id : $object->get_id();
			}
			$course_cat = masteriyo( 'course_cat' );
			$course_cat->set_id( $id );
			$course_cat_repo = masteriyo( 'course_cat.store' );
			$course_cat_repo->read( $course_cat );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_cat;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.0
	 *
	 * @param  Masteriyo\Database\Model $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_course_cat_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Masteriyo\Database\Model $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get course category data.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseCategory $course_cat Course instance.
	 * @param string $context Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_cat_data( $course_cat, $context = 'view' ) {
		$data = array(
			'id'             => $course_cat->get_id(),
			'name'           => $course_cat->get_name( $context ),
			'slug'           => $course_cat->get_slug( $context ),
			'link'           => $course_cat->get_permalink( $context ),
			'taxonomy'       => $course_cat->get_taxonomy( $context ),
			'description'    => 'view' === $context ? wpautop( do_shortcode( $course_cat->get_description() ) ) : $course_cat->get_description( $context ),
			'parent_id'      => $course_cat->get_parent_id( $context ),
			'count'          => $course_cat->get_count( $context ),
			'display'        => $course_cat->get_display( $context ),
			'featured_image' => $course_cat->get_featured_image( $context ),
		);

		/**
		 * Filter course categories rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Course category data.
		 * @param Masteriyo\Models\CourseCategory $course_cat Course category object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\CoursesController $controller REST course categories controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $course_cat, $context, $this );
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.0
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		if ( ! isset( $query_args['course'] ) ) {
			return parent::get_objects( $query_args );
		}

		$terms       = get_the_terms( $query_args['course'], $this->taxonomy );
		$total_terms = count( $terms );

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $terms ) ),
			'total'   => (int) $total_terms,
			'pages'   => (int) ceil( $total_terms / (int) $query_args['posts_per_page'] ),
		);
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.0.0
	 *
	 * @param array $objects Course categories data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Course categories query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'        => $query_results['total'],
				'pages'        => $query_results['pages'],
				'current_page' => $query_args['paged'],
				'per_page'     => $query_args['number'],
			),
		);
	}

}
