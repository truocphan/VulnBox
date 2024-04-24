<?php
/**
 * The Template for displaying instructor archive page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/instructor-courses.php.
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

masteriyo_custom_header( 'instructor-courses' );

/**
 * Wrapper div opening.
 *
 * @since 1.4.3
 */
echo '<div class="masteriyo-w-100 masteriyo-container">';

/**
 * Fires before rendering header in instructor's course archive template.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_before_instructor_archive_header' );

?>
<header class="masteriyo-courses-header">
	<?php
	/**
	 * Filters boolean: true if page title should be shown.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $bool true if page title should be shown.
	 */
	if ( apply_filters( 'masteriyo_show_page_title', true ) ) :
		?>
		<h1 class="masteriyo-courses-header__title page-title">
			<?php masteriyo_page_title(); ?>
		</h1>
	<?php endif; ?>

	<?php
	/**
	 * Action hook for rendering description in instructor's course archive.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_instructor_archive_description' );
	?>
</header>

<?php
/**
 * Fires after rendering header in instructor's course archive template.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_after_instructor_archive_header' );
?>

<?php
if ( masteriyo_course_loop() ) {

	/**
	 * Fires before courses loop in instructor's course archive.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_before_instructor_archive_loop' );

	masteriyo_course_loop_start();

	if ( masteriyo_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Fires for each item in course loop of instructor's course archive.
			 *
			 * @since 1.4.3
			 */
			do_action( 'masteriyo_instructor_archive_loop' );

			\masteriyo_get_template_part( 'content', 'course' );
		}
	}

	masteriyo_course_loop_end();

	/**
	 * Fires after course loop in instructor's course archive.
	 *
	 * @hooked masteriyo_pagination - 10
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_after_instructor_archive_loop' );
} else {
	/**
	 * Fires when there is no course to display in instructor's course archive.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_no_courses_found' );
}

/**
 * Fires after rendering main content in instructor's course archive.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_after_instructor_archive_main_content' );

/**
 * Wrapper div closing.
 *
 * @since 1.4.3
 */
echo '</div>';

masteriyo_custom_footer( 'instructor-courses' );
