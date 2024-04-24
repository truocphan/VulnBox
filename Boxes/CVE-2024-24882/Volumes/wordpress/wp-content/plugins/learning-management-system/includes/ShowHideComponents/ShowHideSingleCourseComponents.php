<?php

/**
* Show hide single course list components.
*
* @since 1.6.13
*
* @package Masteriyo
*/
namespace Masteriyo\ShowHideComponents;

use Masteriyo\Abstracts\ShowHideCourseComponents;

defined( 'ABSPATH' ) || exit;

class SHowHideSingleCourseComponents extends ShowHideCourseComponents {

	/**
	 * Show hide single course components.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	protected function get_prefix_selector(): string {
		return '.masteriyo-single-course';
	}

	/**
	 * Should print if single course page.
	 *
	 * @since 1.6.13
	 *
	 * @return bool
	 */
	protected function should_print(): bool {
		return masteriyo_is_single_course_page();
	}

	/**
	 * Get styles to show/hide components in single course page.
	 *
	 * @since 1.6.13
	 *
	 * @return string
	 */
	protected function get_styles() {
		$components        = array(
			'.masteriyo-single-course .masteriyo-course--img-wrap' => masteriyo_get_setting( 'course_archive.components_visibility.thumbnail' ),
			'.masteriyo-single-course .difficulty-badge'   => masteriyo_get_setting( 'course_archive.components_visibility.difficulty_badge' ),
			'.masteriyo-single-course .course-featured'    => masteriyo_get_setting( 'course_archive.components_visibility.featured_ribbon' ),
			'.masteriyo-single-course .masteriyo-course--content__category' => masteriyo_get_setting( 'course_archive.components_visibility.categories' ),
			'.masteriyo-single-course .masteriyo-single-course--title' => masteriyo_get_setting( 'course_archive.components_visibility.course_title' ),
			'.masteriyo-single-course .masteriyo-course-author' => masteriyo_get_setting( 'course_archive.components_visibility.author' ),
			'.masteriyo-single-course .masteriyo-course-author img' => masteriyo_get_setting( 'course_archive.components_visibility.author_avatar' ),
			'.masteriyo-single-course .masteriyo-course-author--name' => masteriyo_get_setting( 'course_archive.components_visibility.author_name' ),
			'.masteriyo-single-course .masteriyo-course--content__description' => masteriyo_get_setting( 'course_archive.components_visibility.course_description' ),
			'.masteriyo-single-course .masteriyo-single-course--main__content' => masteriyo_get_setting( 'course_archive.components_visibility.course_description' ),
			'.masteriyo-single-course .masteriyo-single-course-stats' => masteriyo_get_setting( 'course_archive.components_visibility.metadata' ),
			'.masteriyo-single-course .duration'           => masteriyo_get_setting( 'course_archive.components_visibility.course_duration' ),
			'.masteriyo-single-course .student'            => masteriyo_get_setting( 'course_archive.components_visibility.students_count' ),
			'.masteriyo-single-course .difficulty'         => masteriyo_get_setting( 'course_archive.components_visibility.lessons_count' ),
			'.masteriyo-single-course .masteriyo-time-btn' => masteriyo_get_setting( 'course_archive.components_visibility.card_footer' ),
			'.masteriyo-single-course .masteriyo-course-price' => masteriyo_get_setting( 'course_archive.components_visibility.price' ),
			'.masteriyo-single-course .masteriyo-rating'   => masteriyo_get_setting( 'course_archive.components_visibility.rating' ),
			'.masteriyo-single-course .masteriyo-single-course--btn' => masteriyo_get_setting( 'course_archive.components_visibility.enroll_button' ),
		);
		$hidden_components = array_filter(
			$components,
			function ( $component_status ) {
				return ! $component_status;
			}
		);
		$styles            = '';

		if ( empty( $hidden_components ) ) {
			return $styles;
		}

		$styles .= implode( ',', array_keys( $hidden_components ) );
		$styles .= '{display:none !important;}';

		return $styles;
	}

}

