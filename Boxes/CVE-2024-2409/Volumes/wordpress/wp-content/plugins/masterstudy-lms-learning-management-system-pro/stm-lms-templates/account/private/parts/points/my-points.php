<?php
/**
 * @var $user
 */

stm_lms_register_style( 'my-points' );

$incompleted = count( stm_lms_get_incompleted_user_points( $user['id'] ) );

stm_lms_completed_points( $user['id'] );

?>

<div class="stm-lms-my-points">

	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo STM_LMS_Point_System::display_point_image();
	?>
	<div class="points-inner">
		<span>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo STM_LMS_Point_System::display_points( STM_LMS_Point_System::total_points( $user['id'] ) );
			?>
			<?php if ( ! empty( $incompleted ) ) : ?>
				<span><?php echo intval( $incompleted ); ?></span>
			<?php endif; ?>
		</span>
		<a href="<?php echo esc_url( ms_plugin_user_account_url( 'points-history' ) ); ?>">
			<?php esc_html_e( 'Earnings History', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>
	</div>
</div>
