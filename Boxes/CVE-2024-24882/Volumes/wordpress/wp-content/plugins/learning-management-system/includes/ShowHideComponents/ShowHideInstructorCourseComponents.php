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

defined( 'ABSPATH' ) || exit;


class ShowHideInstructorCourseComponents extends ShowHideCourseComponents {

	/**
	 * Show hide instructor course list components.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	protected function get_prefix_selector(): string {
		return '.masteriyo-courses-page ';
	}

	/**
	 * Should print if instructor course page.
	 *
	 * @since 1.6.13
	 *
	 * @return bool
	 */
	protected function should_print(): bool {
		return masteriyo_is_courses_page();
	}

}

