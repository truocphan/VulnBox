<?php
/**
 * Show hide course list components.
 *
 * @since 1.6.13
 *
 * @package Masteriyo
 */

namespace Masteriyo\ShowHideComponents;

use Masteriyo\Abstracts\ShowHideCourseComponents;

class ShowHideCategoryCourseComponents extends ShowHideCourseComponents {

	/**
	 * Show hide category course list components.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	protected function get_prefix_selector(): string {
		return '.masteriyo-course-category-page';
	}

	/**
	 * Should print if category course page.
	 *
	 * @since 1.6.13
	 *
	 * @return bool
	 */
	protected function should_print(): bool {
		return is_tax( 'course_cat' );
	}

}

