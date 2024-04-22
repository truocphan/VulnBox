<?php

/**
 * @var string $id
 * @var int $start_time
 * @var string $style
 * @var boolean $dark_mode
 *
 * masterstudy-countdown_dark-mode- for dark mode
 */

wp_enqueue_style( 'masterstudy-countdown' );
wp_enqueue_script( 'masterstudy-countdown' );
?>
<script>
	let daysStr = '<?php esc_html_e( 'Days', 'masterstudy-lms-learning-management-system' ); ?>';
	let hoursStr = '<?php esc_html_e( 'Hours', 'masterstudy-lms-learning-management-system' ); ?>';
	let minutesStr = '<?php esc_html_e( 'Minutes', 'masterstudy-lms-learning-management-system' ); ?>';
	let secondsStr = '<?php esc_html_e( 'Seconds', 'masterstudy-lms-learning-management-system' ); ?>';
</script>

<div class="masterstudy-countdown <?php echo esc_attr( $dark_mode ? 'masterstudy-countdown_dark-mode' : '' ); ?> <?php echo esc_attr( 'masterstudy-countdown_style-' . $style ); ?>"
	data-timer="<?php echo esc_attr( $start_time * 1000 ); ?>"
	id="<?php echo esc_attr( $id ); ?>">
</div>
