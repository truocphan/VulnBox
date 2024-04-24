<?php

/**
 * The Template for displaying course course expiration info in single course page and course archive page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/course-expiration-info.php.
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
?>

<?php if ( masteriyo_get_remaining_days_for_course_end( $course ) ) : ?>
<div class="masteriyo-course-expiration">
	<?php masteriyo_get_svg( 'course-expire-time', true ); ?>
<p class="masteriyo-course-expiration--text">
	<?php echo esc_html__( 'Expires in: ', 'masteriyo' ) . esc_html( masteriyo_get_remaining_days_for_course_end( $course, true ) ); ?>
</p>
</div>
<?php endif; ?>
