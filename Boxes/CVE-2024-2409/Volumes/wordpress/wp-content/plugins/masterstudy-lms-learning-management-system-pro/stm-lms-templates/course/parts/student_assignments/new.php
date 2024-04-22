<?php
/**
 * @var $post_id
 * @var $item_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$q = new WP_Query(
	array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-assignments',
		'post__in'       => array( $item_id ),
	)
);

$actual_link = STM_LMS_Assignments::get_current_url();

if ( $q->have_posts() ) :
	?>
	<div class="stm-lms-course__assignment">
		<?php
		while ( $q->have_posts() ) :
			$q->the_post();
			?>

			<div class="clearfix">
				<?php the_content(); ?>
			</div>

			<a href="<?php echo esc_url( add_query_arg( array( 'start_assignment' => $item_id, 'course_id' => $post_id ), $actual_link ) ); // phpcs:ignore ?>"
					class="btn btn-default start_assignment">
				<?php esc_html_e( 'Start now', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</a>

		<?php endwhile; ?>
	</div>

	<?php
endif;
