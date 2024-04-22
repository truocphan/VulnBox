<?php
/**
 * @var string $post_id
 * @var array  $membership_list
 *
 * data-masterstudy-modal="masterstudy-membership-modal" - js trigger
 */

$data = apply_filters( 'masterstudy_membership_modal_data', $post_id, $membership_list );

wp_enqueue_style( 'masterstudy-membership-modal' );
wp_enqueue_script( 'masterstudy-membership-trigger' );
wp_enqueue_script( 'masterstudy-membership-add-to-cart' );
?>
<div class="masterstudy-membership-modal<?php echo $data['dark_mode'] ? ' masterstudy-membership-modal-dark-mode' : ''; ?>">
	<div class="masterstudy-membership-modal__wrapper">
		<div class="masterstudy-membership-modal__header">
			<h2 class="masterstudy-membership-modal__header-title">
				<?php
				printf(
					/* translators: %s: membership name */
					esc_html__( 'Your current membership is "%s"', 'masterstudy-lms-learning-management-system' ),
					esc_html( $data['subscription']['name'] )
				);
				?>
			</h2>
			<span class="masterstudy-membership-modal__header-title-close stmlms-close"></span>
		</div>
		<div class="masterstudy-membership-modal__content">
			<div class="masterstudy-membership">
				<div class="masterstudy-membership__name">
					<?php
					printf(
						/* translators: %s: Number of plans */
						wp_kses( __( 'Membership quotas left: <strong>%s</strong>', 'masterstudy-lms-learning-management-system' ), stm_lms_allowed_html() ),
						esc_html( $data['subscription']['quotas_left'] )
					);
					?>
				</div>
				<?php
				if ( $data['subscription']['quotas_left'] && ! $data['needs_approval'] ) :
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title' => __( 'Enroll with Membership', 'masterstudy-lms-learning-management-system' ),
							'link'  => '#',
							'style' => 'primary',
							'size'  => 'sm',
							'id'    => intval( $data['subscription']['course_id'] ),
						)
					);
				elseif ( $data['subscription']['quotas_left'] && $data['needs_approval'] ) :
					?>
					<div class="masterstudy-membership__message"><?php esc_html_e( 'You will be able to enroll courses after your membership has been approved.', 'masterstudy-lms-learning-management-system' ); ?></div>
					<?php
				else :
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title' => __( 'Buy Membership', 'masterstudy-lms-learning-management-system' ),
							'link'  => esc_url( STM_LMS_Subscriptions::level_url() ),
							'style' => 'primary',
							'size'  => 'sm',
						)
					);
				endif;
				?>
			</div>
		</div>
	</div>
	<div class="masterstudy-membership-modal__close"></div>
</div>
