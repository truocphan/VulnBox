<?php
/**
 * The Template for displaying single course.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$course_id = get_the_ID();

masteriyo_custom_header( 'single-course' );

$enable_custom_template = masteriyo_string_to_bool( masteriyo_get_setting( 'single_course.custom_template.enable' ) );
$template_source        = masteriyo_get_setting( 'single_course.custom_template.template_source' );
$template_id            = masteriyo_get_setting( 'single_course.custom_template.template_id' );

if ( masteriyo_user_must_login_to_view_course( $course_id ) ) {
	masteriyo_get_template( 'account/form-login.php', $course_id );
	masteriyo_custom_footer( 'single-course' );
	return;
}

if ( $enable_custom_template && $template_source && $template_id ) {
	/**
	 * Fires when rendering a custom template for the Single Course page.
	 *
	 * @since 1.6.12
	 *
	 * @param string $template_source
	 * @param integer $template_id
	 */
	do_action( 'masteriyo_single_course_page_custom_template_render', $template_source, $template_id );
} else {
	/**
	 * Wrapper div opening.
	 *
	 * @since 1.0.0
	 */
	echo '<div class="masteriyo-w-100 masteriyo-container">';

	/**
	 * Fires before rendering single course page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_before_single_course' );

	masteriyo_get_template_part( 'content', 'single-course' );

	/**
	 * Fires after rendering single course page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'masteriyo_after_single_course' );

	/**
	 * Wrapper div closing.
	 *
	 * @since 1.0.0
	 */
	echo '</div>';
}

masteriyo_custom_footer( 'single-course' );
