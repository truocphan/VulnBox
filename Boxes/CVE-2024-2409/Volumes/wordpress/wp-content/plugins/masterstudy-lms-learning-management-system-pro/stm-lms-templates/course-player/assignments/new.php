<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var array $data
 */

if ( empty( $data['theme_fonts'] ) ) {
	wp_enqueue_style( 'masterstudy-course-player-assignments-fonts' );
}
wp_enqueue_style( 'masterstudy-course-player-assignments' );
?>
<div class="masterstudy-course-player-assignments">
	<div class="masterstudy-course-player-assignments__content">
		<?php echo ! empty( $data['user_id'] ) ? wp_kses_post( $data['content'] ) : '<p>' . esc_html__( 'To begin an assignment, you need to register or log in with an existing account', 'masterstudy-lms-learning-management-system-pro' ) . '</p>'; ?>
	</div>
	<?php if ( ! empty( $data['user_id'] ) ) { ?>
		<div class="masterstudy-course-player-assignments__button">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/button',
				array(
					'id'            => 'masterstudy-course-player-assignments-start-button',
					'title'         => __( 'Start Assignment', 'masterstudy-lms-learning-management-system-pro' ),
					'link'          => add_query_arg(
						array(
							'start_assignment' => $item_id,
							'course_id'        => $post_id,
						),
						$data['actual_link'] ?? '',
					),
					'style'         => 'primary',
					'size'          => 'md',
					'icon_position' => '',
					'icon_name'     => '',
				)
			);
			?>
		</div>
	<?php } ?>
</div>
