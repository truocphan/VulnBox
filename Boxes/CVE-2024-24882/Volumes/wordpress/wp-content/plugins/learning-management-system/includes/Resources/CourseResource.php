<?php
/**
 * Resource handler for Course data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

use Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Course data.
 *
 * @since 1.6.9
 */
class CourseResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Course $course
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $course, $context = 'view' ) {
		$author = masteriyo_get_user( $course->get_author_id( $context ) );

		if ( ! is_wp_error( $author ) ) {
			$author = array(
				'id'           => $author->get_id(),
				'display_name' => $author->get_display_name(),
				'avatar_url'   => $author->profile_image_url(),
			);
		}

		/**
		 * Filters short description of course.
		 *
		 * @since 1.6.9
		 *
		 * @param string $short_description Short description of course.
		 */
		$short_description = 'view' === $context ? apply_filters( 'masteriyo_short_description', $course->get_short_description() ) : $course->get_short_description();

		$data = array(
			'id'                 => $course->get_id(),
			'name'               => wp_specialchars_decode( $course->get_name( $context ) ),
			'slug'               => $course->get_slug( $context ),
			'permalink'          => $course->get_permalink(),
			'preview_permalink'  => $course->get_preview_link(),
			'status'             => $course->get_status( $context ),
			'description'        => 'view' === $context ? wpautop( do_shortcode( $course->get_description() ) ) : $course->get_description( $context ),
			'short_description'  => $short_description,
			'reviews_allowed'    => $course->get_reviews_allowed( $context ),
			'parent_id'          => $course->get_parent_id( $context ),
			'menu_order'         => $course->get_menu_order( $context ),
			'author'             => $author,
			'date_created'       => masteriyo_rest_prepare_date_response( $course->get_date_created( $context ) ),
			'date_modified'      => masteriyo_rest_prepare_date_response( $course->get_date_modified( $context ) ),
			'featured'           => $course->get_featured( $context ),
			'price'              => $course->get_price( $context ),
			'formatted_price'    => $course->get_rest_formatted_price( $context ),
			'regular_price'      => $course->get_regular_price( $context ),
			'sale_price'         => $course->get_sale_price( $context ),
			'price_type'         => $course->get_price_type( $context ),
			'featured_image'     => $course->get_featured_image( $context ),
			'featured_image_url' => $course->get_featured_image_url( $context ),
			'students_count'     => masteriyo_count_enrolled_users( $course->get_id() ),
			'enrollment_limit'   => $course->get_enrollment_limit( $context ),
			'duration'           => $course->get_duration( $context ),
			'access_mode'        => $course->get_access_mode( $context ),
			'billing_cycle'      => $course->get_billing_cycle( $context ),
			'show_curriculum'    => $course->get_show_curriculum( $context ),
			'review_after_course_completion' => $course->get_review_after_course_completion( $context ),
			'highlights'         => $course->get_highlights( $context ),
			'edit_post_link'     => $course->get_edit_post_link(),
			'categories'         => self::get_taxonomy_terms( $course, 'cat' ),
			'tags'               => self::get_taxonomy_terms( $course, 'tag' ),
			'difficulty'         => self::get_taxonomy_terms( $course, 'difficulty' ),
			'end_date'           => $course->get_end_date( $context ),
		);

		/**
		 * Filter course data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Course data.
		 * @param \Masteriyo\Models\Course $course Course object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_course_resource_array', $data, $course, $context );
	}

	/**
	 * Get taxonomy terms if a course.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Course $course Course object.
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return array
	 */
	protected static function get_taxonomy_terms( $course, $taxonomy = 'cat' ) {
		$terms = Utils::get_object_terms( $course->get_id(), 'course_' . $taxonomy );

		$terms = array_map(
			function ( $term ) {
				return array(
					'id'   => $term->term_id,
					'name' => $term->name,
					'slug' => $term->slug,
				);
			},
			$terms
		);

		$terms = 'difficulty' === $taxonomy ? array_shift( $terms ) : $terms;

		return $terms;
	}
}
