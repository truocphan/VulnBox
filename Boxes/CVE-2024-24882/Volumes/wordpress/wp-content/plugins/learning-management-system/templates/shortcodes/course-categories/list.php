<?php
/**
 * The Template for displaying course categories list.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/shortcodes/course-categories/list.php
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

printf( '<div class="masteriyo-course-categories columns-%d">', esc_attr( $columns ) );

foreach ( $categories as $category ) {
	/**
	 * Action hook for rendering course category template in course categories shortcode.
	 *
	 * @hooked masteriyo_template_shortcode_course_category - 10
	 *
	 * @since 1.2.0
	 *
	 * @param array $args Template arguments.
	 */
	do_action(
		'masteriyo_template_shortcode_course_category',
		compact( 'category', 'hide_courses_count', 'columns', 'count' )
	);
}

echo '</div>';
