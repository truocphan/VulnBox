<?php
/**
 * The Template for displaying instructors list.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/shortcodes/instructors-list/list.php
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.7.0
 */

defined( 'ABSPATH' ) || exit;

printf( '<div class="masteriyo-instructors-list columns-%d">', esc_attr( $columns ) );

foreach ( $instructors as $instructor ) {
	/**
	 * Action hook for rendering course instructor template in instructors list shortcode.
	 *
	 * @hooked masteriyo_template_shortcode_instructors_list_item - 10
	 *
	 * @since 1.6.16
	 *
	 * @param array $args Template arguments.
	 */
	do_action(
		'masteriyo_template_shortcode_instructors_list_item',
		compact( 'instructor', 'columns', 'count' )
	);
}

echo '</div>';
