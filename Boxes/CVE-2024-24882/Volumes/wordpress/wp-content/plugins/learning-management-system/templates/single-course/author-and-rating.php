<?php
/**
 * The Template for displaying Author and rating for single course
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/author-and-rating.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.5.9
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Fires before rendering author and rating section in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_single_course_author_and_rating' );

?>
<div class="masteriyo-course--content__rt">
	<?php if ( $author ) : ?>
	<div class="masteriyo-course-author">
		<a href="<?php echo esc_url( $author->get_course_archive_url() ); ?>">
			<img src="<?php echo esc_attr( $author->profile_image_url() ); ?>"
				alt="<?php echo esc_attr( $author->get_display_name() ); ?>"
				title="<?php echo esc_attr( $author->get_display_name() ); ?>"
			>
			<?php /* translators: %s: Username */ ?>
			<!-- Do not multiline below code, as it will create space around the display name. -->
			<span class="masteriyo-course-author--name"><?php echo esc_html( $author->get_display_name() ); ?></span>
		</a>
	</div>
	<?php endif; ?>

		<?php
		/**
		 * Fire after masteriyo course author.
		 *
		 * @since 1.5.10
		 *
		 * @param \Masteriyo\Models\Course $course Course object.
		 */
		do_action( 'masteriyo_after_course_author', $course );
		?>

<?php if ( $course->is_review_allowed() ) : ?>
	<span class="masteriyo-icon-svg masteriyo-rating">
		<?php masteriyo_format_rating( $course->get_average_rating(), true ); ?> <?php echo esc_html( masteriyo_format_decimal( $course->get_average_rating(), 1, true ) ); ?> (<?php echo esc_html( $course->get_review_count() ); ?>)
	</span>
<?php endif; ?>
</div>
<?php

/**
 * Fires after rendering author and rating section in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_single_course_author_and_rating' );
