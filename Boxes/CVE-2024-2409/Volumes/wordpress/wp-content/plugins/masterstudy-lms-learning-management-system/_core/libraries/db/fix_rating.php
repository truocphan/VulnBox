<?php

add_action( 'admin_menu', 'stm_lms_register_my_custom_submenu_page' );

function stm_lms_register_my_custom_submenu_page() {
	add_submenu_page(
		'tools.php',
		esc_html__( 'LMS course ratings', 'masterstudy-lms-learning-management-system' ),
		esc_html__( 'LMS course ratings', 'masterstudy-lms-learning-management-system' ),
		'manage_options',
		'stm_lms_fixing_rating_tool',
		'stm_lms_fixing_rating_tool'
	);
}

function stm_lms_fixing_rating_tool() {
	$offset = ( isset( $_GET['page_offset'] ) ) ? intval( $_GET['page_offset'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$args = array(
		'post_type'      => 'stm-courses',
		'posts_per_page' => 1,
		'offset'         => $offset,
	);

	$course = '';

	$q = new WP_Query( $args );

	$total = $q->found_posts;

	if ( $total > $offset ) {

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$course_id = get_the_ID();

				/* translators: %s title */
				$course = sprintf( __( '<p>Rating for course "%s" refreshed</p>', 'masterstudy-lms-learning-management-system' ), get_the_title() ); // phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings

				$args = array(
					'post_type'      => 'stm-reviews',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => 'review_course',
							'compare' => '=',
							'value'   => intval( $course_id ),
						),
					),
				);

				$q = new WP_Query( $args );

				$marks = array();

				if ( $q->have_posts() ) {
					while ( $q->have_posts() ) {
						$q->the_post();

						$review_id = get_the_ID();

						$mark = get_post_meta( $review_id, 'review_mark', true );
						$user = get_post_meta( $review_id, 'review_user', true );

						$marks[ $user ] = $mark;

					}
				}

				$rates = STM_LMS_Course::course_average_rate( $marks );

				update_post_meta( $course_id, 'course_mark_average', $rates['average'] );
				update_post_meta( $course_id, 'course_marks', $marks );

				$transient_name = STM_LMS_Instructor::transient_name( get_post_field( 'post_author', $course_id ), 'rating' );
				delete_transient( $transient_name );

			}
		}

		wp_reset_postdata();
	} else {
		// phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings
		$course = __( '<p>All course ratings have been refreshed</p>', 'masterstudy-lms-learning-management-system' );
	}

	?>

	<div class="wrap">
		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

		<?php echo wp_kses_post( $course ); ?>

		<?php
		if ( $total > $offset ) :
			?>
			<p>
				<?php esc_html_e( 'Refreshing next course rating...', 'masterstudy-lms-learning-management-system' ); ?>
			</p>
			<script>
				(function ($) {
					$(window).on('load', function () {
						var url = "<?php // phpcs:ignore Squiz.PHP.EmbeddedPhp
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo add_query_arg(
										array(
											'page'        => 'stm_lms_fixing_rating_tool',
											'page_offset' => $offset + 1,
										),
										admin_url( 'tools.php' )
									);
									// phpcs:ignore Squiz.PHP.EmbeddedPhp
									?>";
						window.location.href = url;
					});
				})(jQuery);
			</script>
		<?php endif; ?>
	</div>
	<?php
}

add_action( 'admin_head-edit.php', 'addCustomImportButton' );

function addCustomImportButton() {
	global $current_screen;

	if ( 'stm-courses' !== $current_screen->post_type ) {
		return;
	}

	?>
	<script>
		(function ($) {
			$(document).ready(function ($) {
				var url = "<?php echo add_query_arg( 'page', 'stm_lms_fixing_rating_tool', admin_url( 'tools.php' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
				$($(".wrap a.page-title-action")[0])
					.after("<a href='" + url + "' class='add-new-h2'><?php esc_html_e( 'Refresh ratings', 'masterstudy-lms-learning-management-system' ); ?></a>");
			});
		})(jQuery);
	</script>
	<?php
}
