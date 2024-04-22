<?php

/**
 * @var $course_id
 * @var $mode
 */
$coming_soon                    = get_post_meta( $course_id, 'coming_soon_status', true );
$coming_soon_start_date         = get_post_meta( $course_id, 'coming_soon_date', true );
$coming_soon_email_notification = get_post_meta( $course_id, 'coming_soon_email_notification', true );
$is_course_coming_soon          = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $course_id );

if ( ! $is_course_coming_soon || empty( $coming_soon_start_date ) ) {
	return;
}
$course_start_time     = masterstudy_lms_coming_soon_start_time( $course_id );
$course_start_datetime = new DateTime( "@$course_start_time" );
$current_datetime      = new DateTime();
$time_difference       = $course_start_datetime->getTimestamp() - $current_datetime->getTimestamp();
if ( $time_difference < 0 ) {
	update_post_meta( $course_id, 'coming_soon_status', false );
	update_post_meta( $course_id, 'coming_soon_show_course_price', true );
	update_post_meta( $course_id, 'coming_soon_show_course_details', true );
	update_post_meta( $course_id, 'coming_soon_preordering', true );

	return;
}

stm_lms_register_style( 'coming_soon/coming_soon' );
stm_lms_register_script( 'coming-soon', array( 'jquery-ui-resizable' ) );
wp_localize_script(
	'stm-lms-coming-soon',
	'stm_coming_soon_ajax_variable',
	array(
		'url'       => admin_url( 'admin-ajax.php' ),
		'course_id' => $course_id,
		'is_logged' => is_user_logged_in(),
		'nonce'     => wp_create_nonce( 'masterstudy-lms-coming-soon-nonce' ),
	)
);

$coming_soon_message = get_post_meta( $course_id, 'coming_soon_message', true );
$count_down_id       = "countdown_$course_id";
$start_time          = intval( masterstudy_lms_coming_soon_start_time( $course_id ) );

$user_subscribed   = false;
$subscribed_emails = get_post_meta( $course_id, 'coming_soon_student_emails', true );
if ( empty( $subscribed_emails ) ) {
	$user_subscribed = false;
} else {
	$user_subscribed = in_array( wp_get_current_user()->user_email, array_column( $subscribed_emails, 'email' ), true );
}

if ( 'card' === $mode ) {
	?>
	<div class="coming-soon-card-countdown-container">
		<div class="coming-soon-card-details">
			<?php esc_html_e( 'Coming soon:', 'masterstudy-lms-learning-management-system' ); ?>
			<span>
				<?php echo esc_html( date( 'd.m.Y', $start_time ) ); ?>
			</span>
		</div>
		<?php
		STM_LMS_Templates::show_lms_template(
			'components/countdown',
			array(
				'id'         => $count_down_id,
				'start_time' => $start_time,
				'dark_mode'  => false,
				'style'      => 'default',
			),
		);
		?>
	</div>
	<?php
} else {
	?>
	<div class="masterstudy-lms-coming-soon-container">
		<div class="coming-soon-countdown-container">
			<?php
			STM_LMS_Templates::show_lms_template(
				'components/countdown',
				array(
					'id'         => $count_down_id,
					'start_time' => $start_time,
					'dark_mode'  => false,
					'style'      => 'default',
				),
			);
			if ( $coming_soon_email_notification ) {
				if ( $user_subscribed ) {
					$notify_classes = 'coming-soon-notify-alert notify-me';
				} else {
					$notify_classes = 'coming-soon-notify-alert';
				}
				?>
				<div class="<?php echo esc_attr( $notify_classes ); ?>">
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/notices/notify.svg' ); ?>" class="notify-me">
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/notices/disable.svg' ); ?>" class="notified">
				</div>
				<?php
			}
			?>
		</div>

		<div class="coming-soon-notify-container">
			<input type="text" placeholder="Enter your email" required>
			<button
				class="coming-soon-notify-me btn"> <?php esc_html_e( 'Notify me', 'masterstudy-lms-learning-management-system' ); ?> </button>
		</div>
		<div class="coming-soon-heading">
			<?php echo esc_html( $coming_soon_message ); ?>
		</div>
	</div>
	<?php
	STM_LMS_Templates::show_lms_template( 'global/notify-modal' );
}
?>
