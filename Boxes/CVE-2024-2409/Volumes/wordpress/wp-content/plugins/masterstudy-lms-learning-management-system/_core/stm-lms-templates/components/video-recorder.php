<?php
/**
 * Video recorder component
 *
 * @var string $id
 * @var string $title
 * @var string $text
 *
 * masterstudy-alert_dark-mode - for dark mode
 * masterstudy-alert_open - for open alert
 *
 * @package masterstudy
 */

wp_enqueue_style( 'masterstudy-video-recorder' );
?>
<div class="masterstudy-video__recorder masterstudy-video__recorder_hidden" data-recording="false" data-recorded="false">
	<video class="masterstudy-video__recorder-player" controls></video>
	<video class="masterstudy-video__recorder-video"></video>

	<div class="masterstudy-video__recorder-controls masterstudy-video__recorder-controls_top">
		<a class="masterstudy-video__recorder-btn  masterstudy-video__recorder-btn_primary masterstudy-video__recorder-btn__delete">
			<svg xmlns="http://www.w3.org/2000/svg" class="masterstudy-video__recorder-btn__icon" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
				<path d="M15.2 3.20061H11.2V1.86461C11.1812 1.35247 10.9602 0.868661 10.5853 0.519248C10.2104 0.169835 9.7122 -0.0166711 9.2 0.00061447H6.8C6.2878 -0.0166711 5.78965 0.169835 5.41474 0.519248C5.03984 0.868661 4.81877 1.35247 4.8 1.86461V3.20061H0.8C0.587827 3.20061 0.384344 3.2849 0.234315 3.43493C0.0842854 3.58496 0 3.78844 0 4.00062C0 4.21279 0.0842854 4.41627 0.234315 4.5663C0.384344 4.71633 0.587827 4.80061 0.8 4.80061H1.6V13.6006C1.6 14.2371 1.85286 14.8476 2.30294 15.2977C2.75303 15.7478 3.36348 16.0006 4 16.0006H12C12.6365 16.0006 13.247 15.7478 13.6971 15.2977C14.1471 14.8476 14.4 14.2371 14.4 13.6006V4.80061H15.2C15.4122 4.80061 15.6157 4.71633 15.7657 4.5663C15.9157 4.41627 16 4.21279 16 4.00062C16 3.78844 15.9157 3.58496 15.7657 3.43493C15.6157 3.2849 15.4122 3.20061 15.2 3.20061ZM6.4 11.2006C6.4 11.4128 6.31571 11.6163 6.16569 11.7663C6.01566 11.9163 5.81217 12.0006 5.6 12.0006C5.38783 12.0006 5.18434 11.9163 5.03431 11.7663C4.88429 11.6163 4.8 11.4128 4.8 11.2006V8.00062C4.8 7.78844 4.88429 7.58496 5.03431 7.43493C5.18434 7.2849 5.38783 7.20061 5.6 7.20061C5.81217 7.20061 6.01566 7.2849 6.16569 7.43493C6.31571 7.58496 6.4 7.78844 6.4 8.00062V11.2006ZM6.4 1.86461C6.4 1.73661 6.568 1.60061 6.8 1.60061H9.2C9.432 1.60061 9.6 1.73661 9.6 1.86461V3.20061H6.4V1.86461ZM11.2 11.2006C11.2 11.4128 11.1157 11.6163 10.9657 11.7663C10.8157 11.9163 10.6122 12.0006 10.4 12.0006C10.1878 12.0006 9.98434 11.9163 9.83432 11.7663C9.68429 11.6163 9.6 11.4128 9.6 11.2006V8.00062C9.6 7.78844 9.68429 7.58496 9.83432 7.43493C9.98434 7.2849 10.1878 7.20061 10.4 7.20061C10.6122 7.20061 10.8157 7.2849 10.9657 7.43493C11.1157 7.58496 11.2 7.78844 11.2 8.00062V11.2006Z" fill="currentColor"/>
			</svg>
			<span class="masterstudy-video__recorder__btn-text">
				<?php echo esc_html__( 'Delete', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
	</div>
	<div class="masterstudy-video__recorder-controls masterstudy-video__recorder-controls_bottom">
		<a class="masterstudy-video__recorder-btn masterstudy-video__recorder-btn__microphone masterstudy-video__recorder-btn_primary  masterstudy-video__recorder-btn__microphone">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
				<path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z"/>
				<path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
			</svg>
			<span class="masterstudy-video__recorder__status-text">
				<?php echo esc_html__( 'Recording', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
			<span class="masterstudy-video__recorder__timeline">00:00</span>
		</a>
		<a class="masterstudy-video__recorder-btn masterstudy-video__recorder-btn__record masterstudy-video__recorder-btn_primary ">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="currentColor">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M2.66675 3.65479C1.56218 3.65479 0.666748 4.55022 0.666748 5.65479V12.3215C0.666748 13.4261 1.56218 14.3215 2.66675 14.3215H8.66675C9.77135 14.3215 10.6667 13.4261 10.6667 12.3215V5.65479C10.6667 4.55022 9.77135 3.65479 8.66675 3.65479H2.66675ZM15.3334 6.67824V11.298C15.3334 11.8633 14.8752 12.3215 14.3099 12.3215C14.1079 12.3215 13.9103 12.2617 13.7422 12.1496L12.2969 11.1861C12.1115 11.0624 12.0001 10.8543 12.0001 10.6313V7.34491C12.0001 7.12201 12.1115 6.91385 12.2969 6.79021L13.7422 5.82667C13.9103 5.71459 14.1079 5.65479 14.3099 5.65479C14.8752 5.65479 15.3334 6.11301 15.3334 6.67824Z" fill="currentColor"/>
			</svg>
			<span class="masterstudy-video__recorder__btn-text">
				<?php echo esc_html__( 'Start record', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
	</div>
</div>
