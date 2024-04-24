<?php
/**
 * The Template for displaying all single course detail
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/content-single-course.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $course;

// Ensure visibility.
if ( empty( $course ) || ! $course->is_visible() ) {
	return;
}

/**
 * Fires before rendering single course page content.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_single_course_content' );

?>
<div id="course-<?php the_ID(); ?>" class="masteriyo-single-course">
	<div class="masteriyo-col-8">
		<div class="masteriyo-single-course--main masteriyo-course--content">
			<?php
			/**
			 * Action hook for rendering single course page content.
			 *
			 * @hooked masteriyo_single_course_featured_image - 10
			 * @hooked masteriyo_single_course_categories - 20
			 * @hooked masteriyo_single_course_title - 30
			 * @hooked masteriyo_single_course_author_and_rating - 40
			 * @hooked masteriyo_template_single_course_main_content - 50
			 *
			 * @since 1.0.5
			 */
			do_action( 'masteriyo_single_course_content', $course );
			?>
		</div>
	</div>

	<div class="masteriyo-col-4">
		<aside class="masteriyo-single-course--aside masteriyo-course--content">
			<?php
			/**
			 * Action hook for rendering sidebar in single course page.
			 *
			 * @hooked masteriyo_single_course_price_and_enroll_button - 10
			 * @hooked masteriyo_single_course_stats - 20
			 * @hooked masteriyo_single_course_highlights - 30
			 *
			 * @since 1.0.5
			 */
			do_action( 'masteriyo_single_course_sidebar_content', $course );
			?>
		</aside>
	</div>
</div>
<?php
/**
 * Fires after rendering single course page content.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_single_course_content' );
