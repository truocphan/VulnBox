<?php
/**
 * @var $post_id
 * @var $item_id
 */

stm_lms_register_script( 'lessons-stream', array( 'jquery-ui-resizable' ) );
stm_lms_register_script( 'lessons' );
stm_lms_register_style( 'lesson_sequential_drip_content' );
?>
	<script>
		var cf7_custom_image = '<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/';
		var daysStr = '<?php esc_html_e( 'Days', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var hoursStr = '<?php esc_html_e( 'Hours', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var minutesStr = '<?php esc_html_e( 'Minutes', 'masterstudy-lms-learning-management-system-pro' ); ?>';
		var secondsStr = '<?php esc_html_e( 'Seconds', 'masterstudy-lms-learning-management-system-pro' ); ?>';
	</script>
<?php
wp_enqueue_script( 'jquery.countdown' );

stm_lms_register_style( 'countdown/style_1' );
?>

<div class="container stream-starts-soon">
	<h3 class="text-center">
		<?php
		printf(
		/* translators: %s: tiitle */
			esc_html__( '%s starts in', 'masterstudy-lms-learning-management-system-pro' ),
			esc_html( get_the_title( $item_id ) )
		);
		?>
	</h3>

	<div class="stm_countdown text-center"
		dir="ltr"
		data-timer="<?php echo esc_attr( STM_LMS_Sequential_Drip_Content::lesson_start_time( $item_id, $post_id ) * 1000 ); ?>"
		id="countdown_<?php echo esc_attr( $item_id ); ?>"></div>
</div>
