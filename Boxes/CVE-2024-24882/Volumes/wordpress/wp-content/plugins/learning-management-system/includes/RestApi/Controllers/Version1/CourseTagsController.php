<?php
/**
 * REST API course tags controller
 *
 * Handles requests to the courses/tags endpoint.
 *
 * @category API
 * @package  Masteriyo\RestApi
 * @since    1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

use Masteriyo\RestApi\Controllers\Version1\RestTermsController;
use Masteriyo\Models\CourseTag;

defined( 'ABSPATH' ) || exit;

/**
 * REST API course tags controller class.
 *
 * @package Masteriyo\RestApi
 *
 * @extends RestTermsController
 */
class CourseTagsController extends RestTermsController {

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
	protected $rest_base = 'courses/tags';

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'course_tag';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'course_tag';

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_tag';

	/**
	 * Get the Tag schema, conforming to JSON Schema.
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
					'description' => __( 'Tag name', 'masteriyo' ),
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
		$course_tag = masteriyo( 'course_tag' );

		if ( 0 !== $id ) {
			$course_tag->set_id( $id );
			$course_tag_repo = masteriyo( 'course_tag.store' );
			$course_tag_repo->read( $course_tag );
		}

		// Term title.
		if ( isset( $request['name'] ) ) {
			$course_tag->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Term description.
		if ( isset( $request['description'] ) ) {
			$course_tag->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Term slug.
		if ( isset( $request['slug'] ) ) {
			$course_tag->set_slug( $request['slug'] );
		}

		// Term order.
		if ( isset( $request['term_order'] ) ) {
			$course_tag->set_term_order( $request['slug'] );
		}

		return $course_tag;
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
			$course_tag = masteriyo( 'course_tag' );
			$course_tag->set_id( $id );
			$course_tag_repo = masteriyo( 'course_tag.store' );
			$course_tag_repo->read( $course_tag );
		} catch ( \Exception $e ) {
			return false;
		}

		return $course_tag;
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
		$data    = $this->get_course_tag_data( $object, $context );

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
	 * Get course tag data.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\CourseTag $course_tag Course instance.
	 * @param string    $context    Request context. Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_tag_data( CourseTag $course_tag, $context = 'view' ) {
		$data = array(
			'id'          => $course_tag->get_id(),
			'name'        => $course_tag->get_name( $context ),
			'slug'        => $course_tag->get_slug( $context ),
			'link'        => $course_tag->get_permalink( $context ),
			'taxonomy'    => $course_tag->get_taxonomy( $context ),
			'description' => 'view' === $context ? wpautop( do_shortcode( $course_tag->get_description() ) ) : $course_tag->get_description( $context ),
			'count'       => $course_tag->get_count( $context ),
		);

		/**
		 * Filter course tags rest response data.
		 *
		 * @since 1.4.10
		 *
		 * @param array $data Course tag data.
		 * @param Masteriyo\Models\CourseTag $course_tag Course tag object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 * @param Masteriyo\RestApi\Controllers\Version1\CourseTagsController $controller REST course tags controller object.
		 */
		return apply_filters( "masteriyo_rest_response_{$this->object_type}_data", $data, $course_tag, $context, $this );
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
