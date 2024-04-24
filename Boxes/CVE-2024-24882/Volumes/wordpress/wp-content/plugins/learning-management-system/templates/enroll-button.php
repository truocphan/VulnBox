<?php
/**
 * "Add to Cart" button.
 *
 * @version 1.0.0
*/

use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Notice;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! $course->is_purchasable() ) {
	return;
}

/**
 * Fires before rendering enroll/add-to-cart button.
 *
 * @since 1.0.0
 * @since 1.5.12 Added $course parameter.
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_before_add_to_cart_button', $course );

?>

<?php if ( masteriyo_can_start_course( $course ) ) : ?>
	<?php if ( $progress && CourseProgressStatus::COMPLETED === $progress->get_status() ) : ?>
		<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="<?php echo esc_attr( $class ); ?>">
			<?php echo wp_kses_post( $course->single_course_completed_text() ); ?>
		</a>
	<?php elseif ( $progress && CourseProgressStatus::PROGRESS === $progress->get_status() ) : ?>
		<a href="<?php echo esc_url( $course->continue_course_url( $progress ) ); ?>" target="_blank" class="<?php echo esc_attr( $class ); ?>">
			<?php echo wp_kses_post( $course->single_course_continue_text() ); ?>
		</a>
	<?php else : ?>
		<a href="<?php echo esc_url( $course->start_course_url() ); ?>" target="_blank" class="<?php echo esc_attr( $class ); ?>">
		<?php echo wp_kses_post( $course->single_course_start_text() ); ?>
		</a>
	<?php endif; ?>
<?php else : ?>
	<a href="<?php echo esc_url( $course->add_to_cart_url() ); ?>" class="<?php echo esc_attr( $class ); ?>">
		<?php echo wp_kses_post( $course->add_to_cart_text() ); ?>
	</a>
<?php endif; ?>
<?php

if ( 0 !== $course->get_enrollment_limit() && 0 === $course->get_available_seats() && ! masteriyo_can_start_course( $course ) ) {
	masteriyo_display_notice(
		esc_html__( 'Sorry, students limit reached. Course closed for enrollment.', 'masteriyo' ),
		Notice::WARNING
	);
}

/**
 * Fires after rendering enroll/add-to-cart button.
 *
 * @since 1.0.0
 * @since 1.5.12 Added $course parameter.
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_after_add_to_cart_button', $course );
