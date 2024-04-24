<?php

/**
 * The template for displaying navigation in course archive page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/course-pagination.php.
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

echo wp_kses(
	paginate_links(
		array(
			'type'      => 'list',
			'prev_text' => masteriyo_get_svg( 'left-arrow' ),
			'next_text' => masteriyo_get_svg( 'right-arrow' ),
		)
	),
	'masteriyo_pagination'
);
