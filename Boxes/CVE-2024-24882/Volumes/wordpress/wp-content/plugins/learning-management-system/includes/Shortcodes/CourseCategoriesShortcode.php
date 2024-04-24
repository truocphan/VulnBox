<?php
/**
 * Categories listing shortcode.
 *
 * @since 1.2.0
 * @class CourseCategoriesShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

class CourseCategoriesShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.2.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_course_categories';

	/**
	 * Shortcode default attributes.
	 *
	 * @since 1.2.0
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'count'                  => 12,
		'columns'                => 4,
		'hide_courses_count'     => 'no',
		'include_sub_categories' => 'no',
		'ids'                    => array(),
		'exclude_ids'            => array(),
		'order'                  => 'ASC',
		'orderby'                => 'name',
	);

	/**
	 * Get shortcode content.
	 *
	 * @since  1.2.0
	 *
	 * @return string
	 */
	public function get_content() {
		$categories = $this->get_categories();
		$attrs      = $this->get_attributes();
		$columns    = absint( $attrs['columns'] );

		$attrs['columns']    = 1 > $columns && 4 < $columns ? 4 : $columns;
		$attrs['categories'] = $categories;

		\ob_start();

		/**
		 * Action hook for rendering course categories template in shortcode.
		 *
		 * @since 1.2.0
		 *
		 * @param array $attr Shortcode attributes.
		 */
		do_action( 'masteriyo_template_shortcode_course_categories', $attrs );

		return \ob_get_clean();
	}

	/**
	 * Get categories to display for the shortcode.
	 *
	 * @since 1.2.0
	 *
	 * @return \Masteriyo\Models\CourseCategory[]
	 */
	protected function get_categories() {
		$attr = $this->get_attributes();
		$args = array(
			'taxonomy'   => 'course_cat',
			'order'      => sanitize_text_field( $attr['order'] ),
			'orderby'    => sanitize_text_field( $attr['orderby'] ),
			'include'    => $this->parse_values_attribute( $attr['ids'], ',', 'absint' ),
			'exclude'    => $this->parse_values_attribute( $attr['exclude_ids'], ',', 'absint' ),
			'number'     => absint( $attr['count'] ),
			'hide_empty' => false,
		);

		if ( 'no' === sanitize_text_field( $attr['include_sub_categories'] ) ) {
			$args['parent'] = 0;
		}

		$query      = new \WP_Term_Query();
		$result     = $query->query( $args );
		$categories = array_filter( array_map( 'masteriyo_get_course_cat', $result ) );

		/**
		 * Filters course categories that will be displayed in course categories shortcode.
		 *
		 * @since 1.2.0
		 *
		 * @param Masteriyo\Models\CourseCategory[] $categories The categories objects.
		 * @param array $args The WP_Term_Query query args.
		 * @param WP_Term_Query $query The query object.
		 * @param Masteriyo\Shortcodes\CourseCategoriesShortcode $shortcode The course categories shortcode object.
		 */
		return apply_filters( 'masteriyo_shortcode_course_categories', $categories, $args, $query, $this );
	}
}
