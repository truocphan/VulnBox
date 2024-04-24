<?php
/**
 * The Template for displaying course main contents like curriculum, reviews etc in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/main-content.php.
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

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Fires before rendering main content in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_single_course_content' );

?>
<div class="masteriyo-single-course--main__content">
	<?php
	/**
	 * Action hook for rendering single course page's main content.
	 *
	 * @hooked masteriyo_single_course_tab_handles - 10
	 * @hooked masteriyo_single_course_overview - 20
	 * @hooked masteriyo_single_course_curriculum - 30
	 * @hooked masteriyo_single_course_reviews - 40
	 *
	 * @since 1.0.5
	 */
	do_action( 'masteriyo_single_course_main_content', $course );
	?>
</div>
<?php

/**
 * Fires after rendering main content in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_single_course_content' );
