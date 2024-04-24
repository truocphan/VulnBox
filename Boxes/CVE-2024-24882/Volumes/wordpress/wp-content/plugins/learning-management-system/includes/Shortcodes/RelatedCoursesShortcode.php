<?php
/**
 * Shortcode for related courses.
 *
 * @since 1.6.12
 *
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * RelatedCoursesShortcode class.
 *
 * Implements a shortcode for displaying related courses.
 */
class RelatedCoursesShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.6.12
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_related_courses';

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.6.12
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'course_id' => 0,
	);

	/**
	 * Get shortcode content.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_content() {
		$attr = $this->get_attributes();

		$course = masteriyo_get_course( absint( $attr['course_id'] ) );

		if ( is_null( $course ) || is_wp_error( $course ) ) {
			return esc_html__( 'Please provide a valid course id in the "course_id" attribute.', 'masteriyo' );
		}

		masteriyo_setup_course_data( absint( $attr['course_id'] ) );

		ob_start();

		masteriyo_get_template( 'content-related-posts.php' );

		return ob_get_clean();
	}
}
