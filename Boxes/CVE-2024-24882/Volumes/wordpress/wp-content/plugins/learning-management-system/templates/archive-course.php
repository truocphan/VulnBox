<?php

/**
 * The Template for displaying course archives, including the main courses page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/archive-course.php.
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

masteriyo_custom_header( 'courses' );

$enable_custom_template = masteriyo_string_to_bool( masteriyo_get_setting( 'course_archive.custom_template.enable' ) );
$template_source        = masteriyo_get_setting( 'course_archive.custom_template.template_source' );
$template_id            = masteriyo_get_setting( 'course_archive.custom_template.template_id' );

if ( $enable_custom_template && $template_source && $template_id ) {
	/**
	 * Fires when rendering a custom template for the Course Archive page.
	 *
	 * @since 1.6.12
	 *
	 * @param string $template_source
	 * @param integer $template_id
	 */
	do_action( 'masteriyo_course_archive_page_custom_template_render', $template_source, $template_id );
} else {
	/**
	 * Wrapper div opening.
	 *
	 * @since 1.0.0
	 */
	echo '<div class="masteriyo-w-100 masteriyo-container">';

	/**
	 * Fires before rendering header in course archive.
	 *
	 * @hooked masteriyo_course_search_form - 10
	 * @hooked masteriyo_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked masteriyo_breadcrumb - 20
	 * @hooked MASTERIYO_Structured_Data::generate_website_data() - 30
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_before_main_content' );

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
		 * Action hook for rendering course archive description.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_archive_description' );
		?>
	</header>

	<?php
	/**
	 * Fires after rendering header in course archive.
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_after_archive_header' );
	?>

	<?php if ( ! empty( trim( masteriyo_get_page_content( masteriyo_get_page_id( 'courses' ) ) ) ) ) : ?>
	<div class="masteriyo-courses--content">
		<?php masteriyo_the_page_content( masteriyo_get_page_id( 'courses' ) ); ?>
	</div>
	<?php endif; ?>

	<div class="masteriyo-course-list-display-section">

	<?php

	/**
	 * Fires before course loop in course archive template.
	 *
	 * Fires regardless of whether there are courses to be displayed or not.
	 *
	 * @since 1.5.39
	 */
	do_action( 'masteriyo_before_course_archive_loop' );

	if ( masteriyo_course_loop() ) {

		/**
		 * Fires before course loop in course archive template.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_before_courses_loop' );

		masteriyo_course_loop_start();

		if ( masteriyo_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Fires for each item in course loop before rendering its template.
				 *
				 * @since 1.0.0
				 */
				do_action( 'masteriyo_courses_loop' );

				\masteriyo_get_template_part( 'content', 'course' );
			}
		}

		masteriyo_course_loop_end();

		/**
		 * Fires after course loop in course archive template.
		 *
		 * @hooked masteriyo_pagination - 10
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_after_courses_loop' );
	} else {
		/**
		 * Fires when there is not course to display in course archive.
		 *
		 * @hooked masteriyo_no_courses_found - 10
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_no_courses_found' );
	}

	echo '</div>';

	/**
	 * Fires after rendering course archive main content.
	 *
	 * @hooked masteriyo_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_after_main_content' );

	/**
	 * Action hook for rendering sidebar in course archive.
	 *
	 * @hooked masteriyo_get_sidebar - 10
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_sidebar' );

	/**
	 * Wrapper div closing.
	 *
	 * @since 1.0.0
	 */
	echo '</div>';
}

masteriyo_custom_footer( 'courses' );
