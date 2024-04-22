<?php

/**
 * @var $title
 * @var $progress
 * @var $dark_mode
 *
 * masterstudy-progress_dark-mode - for dark mode
 * masterstudy-progress_hidden    - to hide progress bar
 */

wp_enqueue_style( 'masterstudy-progress' );
$progress_class  = ! empty( $dark_mode ) ? ' masterstudy-progress_dark-mode' : '';
$progress_class .= ! empty( $is_hidden ) ? ' masterstudy-progress_hidden' : '';
?>

<div class="masterstudy-progress<?php echo esc_attr( $progress_class ); ?>">
	<div class="masterstudy-progress__bars">
		<span class="masterstudy-progress__bar-empty"></span>
		<span class="masterstudy-progress__bar-filled" style="width:<?php echo esc_html( $progress ); ?>%"></span>
	</div>
	<div class="masterstudy-progress__title">
		<?php echo esc_html( $title ) . ':'; ?>
		<span class="masterstudy-progress__percent"><?php echo esc_html( $progress ); ?></span>%
	</div>
</div>
