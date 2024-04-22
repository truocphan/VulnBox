<?php

wp_enqueue_style( 'masterstudy-audio-recorder' );
wp_enqueue_style( 'masterstudy-audio-player' );
?>
<div class="masterstudy-audio__recorder masterstudy-audio__recorder_hidden">
	<?php
	STM_LMS_Templates::show_lms_template(
		'components/audio-player',
		array(
			'preload' => false,
			'src'     => '',
		)
	);
	?>
	<div class="masterstudy-audio__recorder-on-recording">
		<div class="masterstudy-audio__recorder-state">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
				<path d="M8 13A5 5 0 1 0 8 3a5 5 0 0 0 0 10z" fill="currentColor"></path>
				<path d="" fill="currentColor"></path>
			</svg>
			<span class="masterstudy-audio__recorder-state__text">
				<?php echo esc_html__( 'Recording', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</div>
		<div class="masterstudy-audio__recorder-progress">
			<div class="masterstudy-audio__recorder-progress__visualizer"></div>
			<div class="masterstudy-audio__recorder-progress__timeline">00:00</div>
		</div>
	</div>

	<div class="masterstudy-audio__recorder-controls">
		<a href="#" class="masterstudy-audio__recorder-btn masterstudy-audio__recorder-btn_pause" aria-disabled="true">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
				<path d="M0 0 V14 H3.11111 V0 H0 Z M7.70776 0 V14 H10.81887 V0 H7.70776 Z" fill="currentColor"></path>
				<path d="" fill="currentColor"></path>
			</svg>
			<span class="masterstudy-audio__recorder-btn__text">
				<?php echo esc_html__( 'Pause', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
		<a href="#" class="masterstudy-audio__recorder-btn masterstudy-audio__recorder-btn_start masterstudy-audio__recorder-btn_primary" aria-disabled="false">
			<svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="currentColor">
				<path d="M8.49992 10.6668C9.97058 10.6668 11.1666 9.47016 11.1666 8.00016V4.00016C11.1666 2.5295 9.97058 1.3335 8.49992 1.3335C7.02925 1.3335 5.83325 2.5295 5.83325 4.00016V8.00016C5.83325 9.47016 7.02925 10.6668 8.49992 10.6668ZM13.1666 8.00016V6.66683C13.1666 6.49002 13.0963 6.32045 12.9713 6.19542C12.8463 6.0704 12.6767 6.00016 12.4999 6.00016C12.3231 6.00016 12.1535 6.0704 12.0285 6.19542C11.9035 6.32045 11.8333 6.49002 11.8333 6.66683V8.00016C11.8333 9.83816 10.3379 11.3335 8.49992 11.3335C6.66192 11.3335 5.16659 9.83816 5.16659 8.00016V6.66683C5.16659 6.49002 5.09635 6.32045 4.97132 6.19542C4.8463 6.0704 4.67673 6.00016 4.49992 6.00016C4.32311 6.00016 4.15354 6.0704 4.02851 6.19542C3.90349 6.32045 3.83325 6.49002 3.83325 6.66683V8.00016C3.83325 10.3468 5.57525 12.2882 7.83325 12.6135V13.3335H5.83325C5.65644 13.3335 5.48687 13.4037 5.36185 13.5288C5.23682 13.6538 5.16659 13.8234 5.16659 14.0002C5.16659 14.177 5.23682 14.3465 5.36185 14.4716C5.48687 14.5966 5.65644 14.6668 5.83325 14.6668H11.1666C11.3434 14.6668 11.513 14.5966 11.638 14.4716C11.763 14.3465 11.8333 14.177 11.8333 14.0002C11.8333 13.8234 11.763 13.6538 11.638 13.5288C11.513 13.4037 11.3434 13.3335 11.1666 13.3335H9.16658V12.6135C11.4246 12.2882 13.1666 10.3468 13.1666 8.00016Z"></path>
			</svg>
			<span class="masterstudy-audio__recorder-btn__text">
				<?php echo esc_html__( 'Record audio', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
		<a href="#" class="masterstudy-audio__recorder-btn masterstudy-audio__recorder-btn_stop" aria-disabled="true">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
				<path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"></path>
			</svg>
			<span class="masterstudy-audio__recorder-btn__text">
				<?php echo esc_html__( 'Stop', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</a>
	</div>
</div>
