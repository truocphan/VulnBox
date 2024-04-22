<?php
/**
 * @var $subscription
 * @var $needs_approval
 */

?>

<div class="stm_lms_use_membership_popup">

	<h2>
		<?php
		printf(
		/* translators: %s: membership name */
			esc_html__( 'Your current membership is "%s"', 'masterstudy-lms-learning-management-system' ),
			esc_html( $subscription['name'] )
		);
		?>
	</h2>
	<?php if ( $subscription['used_quotas'] + 1000001 !== $subscription['quotas_left'] ) : ?>
		<p>
			<?php
			printf(
			/* translators: %s: number */
				wp_kses( __( 'Membership quotas left: <strong>%s</strong>', 'masterstudy-lms-learning-management-system' ), stm_lms_allowed_html() ),
				esc_html( $subscription['quotas_left'] )
			);
			?>
		</p>
	<?php endif; ?>
	
	<?php if ( $subscription['quotas_left'] && ! $needs_approval ) : ?>
		<a href="#"
		   class="btn btn-default"
		   data-lms-usemembership=""
		   data-lms-course="<?php echo intval( $subscription['course_id'] ); ?>">
			<span>
				<?php esc_html_e( 'Enroll with membership', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
	<?php elseif ( $subscription['quotas_left'] && $needs_approval ) : ?>
		<span>
			<strong><?php esc_html_e( 'You will be able to enroll courses after your membership has been approved.', 'masterstudy-lms-learning-management-system' ); ?></strong>
		</span>
	<?php else : ?>
		<a href="<?php echo esc_url( STM_LMS_Subscriptions::level_url() ); ?>"
		   class="btn btn-default">
			<span>
				<?php esc_html_e( 'Buy Membership', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
	<?php endif; ?>
</div>
