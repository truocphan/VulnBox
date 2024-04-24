<?php
/**
 * REST API course difficulties controller
 *
 * Handles requests to the courses/difficulties endpoint.
 *
 * @category API
 * @package  Masteriyo\RestApi
 * @since    1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\RestApi\Controllers\Version1\RestTermsController;
use Masteriyo\Models\CourseDifficulty;

defined( 'ABSPATH' ) || exit;

/**
 * REST API course difficulties controller class.
 *
 * @package Masteriyo\RestApi
 *
 * @extends RestTermsController
 */
class CourseDifficultiesController extends RestTermsController {

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
	protected $rest_base = 'courses/difficulties';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_difficulty';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'course_difficulty';

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_difficulty';

	/**
	 * Get the Difficulty schema, conforming to JSON Schema.
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
					'description' => __( 'Difficulty name.', 'masteriyo' ),
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
				'description' => array(
					'description' => __( 'HTML description of the resource.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_filter_post_kses',
					),
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
				'color'       => array(
					'description' => __( 'Difficulty color.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
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
		$id                = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$course_difficulty = masteriyo( 'course_difficulty' );

		if ( 0 !== $id ) {
			$course_difficulty->set_id( $id );
			$course_difficulty_repo = masteriyo( 'course_difficulty.store' );
			$course_difficulty_repo->read( $course_difficulty );
		}

		// Term title.
		if ( isset( $request['name'] ) ) {
			$course_difficulty->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Term description.
		if ( isset( $request['description'] ) ) {
			$course_difficulty->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Term slug.
		if ( isset( $request['slug'] ) ) {
			$course_difficulty->set_slug( $request['slug'] );
		}

		// Term order.
		if ( isset( $request['term_order'] ) ) {
			$course_difficulty->set_term_order( $request['slug'] );
		}

		// Term color.
		if ( isset( $request['color'] ) ) {
			$course_difficulty->set_color( wp_filter_post_kses( $request['color'] ) );
		}

		return $course_difficulty;
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
			$course_difficulty = masteriyo( 'course_difficulty' );
			$course_difficulty->set_id( $id );
			$course_difficulty_repo = masteriyo( 'course_difficulty.store' );
			$course_difficulty_repo->read( $course_difficulty );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_difficulty;
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
		$data    = $this->get_course_difficulty_data( $object, $context );

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
	 * Get course difficulty data.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseDifficulty $course_difficulty Course instance.
	 * @param string           $context    Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_difficulty_data( CourseDifficulty $course_difficulty, $context = 'view' ) {
		$data = array(
			'id'          => $course_difficulty->get_id(),
			'name'        => $course_difficulty->get_name( $context ),
			'slug'        => $course_difficulty->get_slug( $context ),
			'link'        => $course_difficulty->get_permalink( $context ),
			'taxonomy'    => $course_difficulty->get_taxonomy( $context ),
			'description' => 'view' === $context ? wpautop( do_shortcode( $course_difficulty->get_description() ) ) : $course_difficulty->get_description( $context ),
			'count'       => $course_difficulty->get_count( $context ),
			'color'       => $course_difficulty->get_color( $context ),
		);

		/**
		 * Filter course difficulty rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Course difficulty data.
		 * @param Masteriyo\Models\Course $course_difficulty Course difficulty object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\CourseDifficultiesController $controller REST course difficulties controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $course_difficulty, $context, $this );
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
}
