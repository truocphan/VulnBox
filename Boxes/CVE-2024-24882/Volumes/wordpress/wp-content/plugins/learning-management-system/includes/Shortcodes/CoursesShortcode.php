<?php
/**
 * Shortcode for listing courses.
 *
 * @since 1.0.6
 * @class CoursesShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;
use Masteriyo\Query\CourseQuery;

defined( 'ABSPATH' ) || exit;

class CoursesShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.6
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_courses';

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.0.6
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'count'           => 12,
		'columns'         => 3,
		'category'        => null,
		'ids'             => array(),
		'exclude_ids'     => array(),
		'per_page'        => -1,
		'order'           => 'DESC',
		'orderby'         => 'date',
		'show_pagination' => 'off',
	);

	/**
	 * Get shortcode content.
	 *
	 * @since 1.0.6
	 *
	 * @return string
	 */
	public function get_content() {
		$attr = $this->get_attributes();

		// Get the current page URL.
		$current_url  = $_SERVER['REQUEST_URI'];
		$current_page = $this->get_page_from_url( $current_url ); // Retrieve the current page number from the URL query parameter.
		$is_paginate  = 'on' === $attr['show_pagination'] ? true : false;

		$args         = array(
			'limit'          => absint( $attr['count'] ),
			'order'          => sanitize_text_field( $attr['order'] ),
			'orderby'        => sanitize_text_field( $attr['orderby'] ),
			'category'       => $this->parse_values_attribute( $attr['category'], ',', 'trim' ),
			'include'        => $this->parse_values_attribute( $attr['ids'], ',', 'absint' ),
			'exclude'        => $this->parse_values_attribute( $attr['exclude_ids'], ',', 'absint' ),
			'posts_per_page' => $is_paginate ? absint( $attr['per_page'] ) : -1,
			'paginate'       => true,
			'page'           => absint( $current_page ), // Add the current page number to the query args.
			'offset'         => ( absint( $current_page ) - 1 ) * absint( $attr['per_page'] ), // Calculate the offset based on the current page.
		);
		$course_query = new CourseQuery( $args );
		$result       = $course_query->get_courses();

		/**
		 * Filters courses that will be displayed in courses shortcode.
		 *
		 * @since 1.0.6
		 *
		 * @param Masteriyo\Models\Course[] $courses The courses objects.
		 */
		$courses = apply_filters( 'masteriyo_shortcode_courses_result', $result->courses );
		masteriyo_set_loop_prop( 'columns', absint( $attr['columns'] ) );

		\ob_start();

		echo '<div class="masteriyo-w-100">';

		if ( $result->total ) {
			$original_course = isset( $GLOBALS['course'] ) ? $GLOBALS['course'] : null;

			/**
			 * Fires before course loop in courses shortcode.
			 *
			 * @since 1.0.6
			 *
			 * @param array $attr Shortcode attributes.
			 * @param \Masteriyo\Models\Course[] $courses The courses objects.
			 */
			do_action( 'masteriyo_shortcode_before_courses_loop', $attr, $courses );

			masteriyo_course_loop_start();

			foreach ( $courses as $course ) {
				$GLOBALS['course'] = $course;

				\masteriyo_get_template_part( 'content', 'course' );
			}

			$GLOBALS['course'] = $original_course;

			masteriyo_course_loop_end();

			/**
			 * Fires after course loop in courses shortcode.
			 *
			 * @since 1.0.6
			 *
			 * @param array $attr Shortcode attributes.
			 * @param \Masteriyo\Models\Course[] $courses The courses objects.
			 */
			do_action( 'masteriyo_shortcode_after_courses_loop', $attr, $courses );

			masteriyo_reset_loop();
		} else {
			/**
			 * Fires when there is no course to display in courses shortcode.
			 *
			 * @since 1.0.6
			 */
			do_action( 'masteriyo_shortcode_no_courses_found' );
		}

		echo '</div>';

		if ( $is_paginate ) {
			echo wp_kses(
				paginate_links(
					array(
						'type'      => 'list',
						'prev_text' => masteriyo_get_svg( 'left-arrow' ),
						'next_text' => masteriyo_get_svg( 'right-arrow' ),
						'total'     => $result->max_num_pages,
						'current'   => $current_page,
					)
				),
				'masteriyo_pagination'
			);
		}

		return \ob_get_clean();
	}

	/**
	 * Retrieves the page number from a given URL.
	 *
	 * @since 1.6.12
	 *
	 * @param string $url The URL to extract the page number from.
	 *
	 * @return int The extracted page number. Defaults to 1 if not found.
	 */
	protected function get_page_from_url( $url ) {
		$page_number = 1; // Default page number.

		// Parse the URL path.
		$url_parts = wp_parse_url( $url );
		$path      = $url_parts['path'];

		// Split the path by '/'.
		$path_parts = explode( '/', trim( $path, '/' ) );

		// Find the index of 'page' in the path.
		$page_index = array_search( 'page', $path_parts, true );

		if ( false !== $page_index && isset( $path_parts[ $page_index + 1 ] ) ) {
			// Get the page number.
			$page_number = absint( $path_parts[ $page_index + 1 ] );
		}

		// Page number not found.
		return $page_number;
	}

}
