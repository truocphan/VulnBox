<?php
/**
 * @var $post_id
 * @var $item_id
 */

$meet_started = masterstudy_lms_is_google_meet_started( $item_id );
stm_lms_register_script( 'google-meet', array( 'jquery-ui-resizable' ) );
stm_lms_register_script( 'lessons' );
stm_lms_register_style( 'lesson_meet' );
$description = get_post_meta( $item_id, 'stm_gma_summary', true );
$time_zone   = get_post_meta( $item_id, 'stm_gma_timezone', true );
$start_date  = masterstudy_lms_get_google_meet_date_time( $item_id, true );
$end_date    = masterstudy_lms_get_google_meet_date_time( $item_id, false );
?>
	<script>
		var cf7_custom_image = '<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/';
		var daysStr = '<?php esc_html_e( 'Days', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var hoursStr = '<?php esc_html_e( 'Hours', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var minutesStr = '<?php esc_html_e( 'Minutes', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var secondsStr = '<?php esc_html_e( 'Seconds', 'masterstudy-lms-learning-management-system-pro' ); ?>';
	</script>

	<div class="container meet-starts-soon">
		<p class="meet-subtitle">
			<?php echo esc_html__( 'Google meet webinar', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</p>
		<h3 class="text-center">
			<?php echo esc_html( get_the_title( $item_id ) ); ?>
		</h3>
<?php if ( $meet_started ) : ?>
	<p class="meet-description">
		<?php echo esc_html( $description ); ?>
	</p>
	<?php
else :
	wp_enqueue_script( 'jquery.countdown' );
	stm_lms_register_style( 'countdown/style_1' );
	?>

	<div class="stm_countdown text-center" data-timer="<?php echo esc_attr( masterstudy_lms_google_meet_start_time( $item_id ) * 1000 ); ?>" id="countdown_<?php echo esc_attr( $item_id ); ?>"></div>
<?php endif; ?>
	<div class="meet-info">
		<p><span><?php echo esc_html__( 'Starts:', 'masterstudy-lms-learning-management-system-pro' ); ?></span> <?php echo esc_html( $start_date ); ?></p>
		<p><span><?php echo esc_html__( 'Ends:', 'masterstudy-lms-learning-management-system-pro' ); ?></span><?php echo esc_html( $end_date ); ?></p>
		<p><span><?php echo esc_html__( 'Timezone: ', 'masterstudy-lms-learning-management-system-pro' ); ?></span><?php echo esc_html( $time_zone ); ?></p>
	</div>
	<a href="<?php echo esc_url( get_post_meta( $item_id, 'google_meet_link', true ) ); ?>" target="_blank" class="btn btn-default meet-join-meeting">
		<?php esc_html_e( 'Join meeting', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</a>
	<div class="meet-info">
		<p>
			<span>
				<?php echo esc_html__( 'Host Email:', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</span>
		</p>
		<p>
		<?php echo esc_html( get_the_author_meta( 'user_email', get_post_field( 'post_author', $post_id ) ) ); ?>
		</p>
	</div>
</div>
