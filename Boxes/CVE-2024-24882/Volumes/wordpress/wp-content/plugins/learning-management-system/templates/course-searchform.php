<?php

/**
 * The template for displaying course search form
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/course-searchform.php.
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

?>
<form role="search" method="get" class="masteriyo-course-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="masteriyo-course-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>">
		<?php esc_html_e( 'Search for:', 'masteriyo' ); ?>
	</label>
	<span class="masteriyo-course-search__icon">
		<?php masteriyo_get_svg( 'search', true ); ?>
	</span>
	<input type="search" id="masteriyo-course-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field masteriyo-input" placeholder="<?php echo esc_attr__( 'Search courses&hellip;', 'masteriyo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'masteriyo' ); ?>">
		<?php echo esc_html_x( 'Search', 'submit button', 'masteriyo' ); ?>
	</button>
	<input type="hidden" name="post_type" value="mto-course" />
</form>
