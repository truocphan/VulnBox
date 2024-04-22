<?php
/**
 * Audio player component
 *
 * @var int|string $id
 * @var string $title
 * @var string $link
 * @var string $icon
 * @var string $style
 * @var string $size
 * @var bool $target
 * @var bool $login
 * @var bool $hidden
 *
 * masterstudy-button_icon-prev|next - for icon direction
 * masterstudy-button_style-primary|secondary|tertiary|outline|danger - for style change
 * masterstudy-button_size-sm|md - for size change
 * masterstudy-button_loading - for loading animation
 * masterstudy-button_disabled - for "disabled" style
 *
 * @package masterstudy
 */

$data_id       = ! empty( $id ) ? $id : '';
$preload       = ! empty( $preload ) ? '' : 'none';
$src           = ! empty( $src ) ? $src : '';
$player_class  = ! empty( $dark_mode ) ? ' masterstudy-audio-player_dark-mode' : '';
$player_class .= ! empty( $hidden ) ? ' masterstudy-audio-player_hidden' : '';
$player_class .= ! empty( $size ) ? ' masterstudy-audio-player_size-' . $size : ' masterstudy-audio-player_size-md';

wp_enqueue_style( 'masterstudy-audio-player' );
wp_enqueue_script( 'masterstudy-audio-player' );
?>
<div class="masterstudy-audio-player <?php echo esc_attr( $player_class ); ?>" data-id="<?php echo esc_attr( $data_id ); ?>">
	<audio preload="<?php echo esc_attr( $preload ); ?>" src="<?php echo esc_url( $src ); ?>">
		<source src="<?php echo esc_url( $src ); ?>" type="audio/mpeg">
		<source src="<?php echo esc_url( $src ); ?>" type="audio/webm">
		<source src="<?php echo esc_url( $src ); ?>" type="audio/ogg">
		<source src="<?php echo esc_url( $src ); ?>" type="audio/wav">
	</audio>
	<div class="masterstudy-audio-player__holder">
		<div class="masterstudy-audio-player__loading">
			<div class="masterstudy-audio-player__loading-spinner"></div>
		</div>
		<div class="masterstudy-audio-player__play-pause-btn" aria-label="Play" role="button">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="24" viewBox="0 0 18 24" fill="currentColor">
				<path fill="currentColor" fill-rule="evenodd" d="M18 12L0 24V0" class="masterstudy-audio-player__play-pause-btn__icon"/>
			</svg>
		</div>
	</div>
	<div class="masterstudy-audio-player__controls">
		<span class="masterstudy-audio-player__controls-current-time" aria-live="off" role="timer">00:00</span>
		<div class="masterstudy-audio-player__controls-slider masterstudy-audio-player__slider" data-direction="horizontal">
			<div class="masterstudy-audio-player__controls-progress" aria-label="Time Slider" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" role="slider">
				<div class="masterstudy-audio-player__pin masterstudy-audio-player__progress-pin" data-method="rewind"></div>
			</div>
		</div>
		<span class="masterstudy-audio-player__controls-total-time">00:00</span>
	</div>
	<div class="masterstudy-audio-player__volume">
		<div class="masterstudy-audio-player__volume-button" aria-label="Open Volume Controls" role="button">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path class="masterstudy-audio-player__volume-speaker" fill="currentColor" fill-rule="evenodd" d="M14.667 0v2.747c3.853 1.146 6.666 4.72 6.666 8.946 0 4.227-2.813 7.787-6.666 8.934v2.76C20 22.173 24 17.4 24 11.693 24 5.987 20 1.213 14.667 0zM18 11.693c0-2.36-1.333-4.386-3.333-5.373v10.707c2-.947 3.333-2.987 3.333-5.334zm-18-4v8h5.333L12 22.36V1.027L5.333 7.693H0z"/>
			</svg>
			<span class="masterstudy-audio-player__message-offscreen">
				<?php echo esc_html__( 'Press Enter or Space to show volume slider.', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
		</div>
		<div class="masterstudy-audio-player__volume-controls hidden">
			<div class="masterstudy-audio-player__volume-slider masterstudy-audio-player__slider" data-direction="vertical">
				<div class="masterstudy-audio-player__volume-progress" aria-label="Volume Slider" aria-valuemin="0" aria-valuemax="100" aria-valuenow="81" role="slider">
					<div class="masterstudy-audio-player__pin masterstudy-audio-player__volume-pin" data-method="changeVolume"></div>
				</div>
				<span class="masterstudy-audio-player__message-offscreen">
					<?php echo esc_html__( 'Use Up/Down Arrow keys to increase or decrease volume.', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
			</div>
		</div>
	</div>
	<div class="masterstudy-audio-player__download">
		<a class="masterstudy-audio-player__download-link" href="<?php echo esc_url( $src ); ?>" download="" aria-label="Download" role="button">
			<svg width="20" height="20" fill="currentColor" enable-background="new 0 0 29.978 29.978" version="1.1" viewBox="0 0 29.978 29.978" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
				<path d="m25.462 19.105v6.848h-20.947v-6.848h-4.026v8.861c0 1.111 0.9 2.012 2.016 2.012h24.967c1.115 0 2.016-0.9 2.016-2.012v-8.861h-4.026z"/>
				<path d="m14.62 18.426l-5.764-6.965s-0.877-0.828 0.074-0.828 3.248 0 3.248 0 0-0.557 0-1.416v-8.723s-0.129-0.494 0.615-0.494h4.572c0.536 0 0.524 0.416 0.524 0.416v8.742 1.266s1.842 0 2.998 0c1.154 0 0.285 0.867 0.285 0.867s-4.904 6.51-5.588 7.193c-0.492 0.495-0.964-0.058-0.964-0.058z"/>
			</svg>
		</a>
	</div>
</div>
