<?php
/**
 * The Template for displaying course reviews stats in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/reviews-stats.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.5
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div class="masteriyo-stab--treviews">
	<div class="masteriyo-stab-rs">
		<span class="masteriyo-icon-svg masteriyo-flex masteriyo-rstar">
			<?php masteriyo_render_stars( $course->get_average_rating() ); ?>
		</span>

		<span class="masteriyo-rnumber">
			<?php echo esc_html( masteriyo_round( $course->get_average_rating(), 1 ) ); ?> <?php esc_html_e( 'out of', 'masteriyo' ); ?> <?php echo esc_html( masteriyo_get_max_course_rating() ); ?>
		</span>
	</div>
</div>
<p class="masteriyo-stab--turating">
	<span>
		<?php
			printf(
				/* translators: %d: Course comments count */
				esc_html( _nx( '%s user rating', '%s user ratings', $course->get_review_count(), 'Course reviews', 'masteriyo' ) ),
				esc_html( number_format_i18n( $course->get_review_count() ) )
			);
			?>
	</span>
</p>
<?php
