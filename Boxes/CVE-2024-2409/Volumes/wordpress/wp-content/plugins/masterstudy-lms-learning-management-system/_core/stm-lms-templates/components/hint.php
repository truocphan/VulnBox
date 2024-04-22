<?php

/**
 * @var string|object $content
 * @var string $side
 * @var boolean $dark_mode
 *
 * masterstudy-hint_dark-mode- for dark mode
 * masterstudy-hint_side-left|masterstudy-hint_side-right - for side
 */

wp_enqueue_style( 'masterstudy-hint' );
wp_enqueue_script( 'masterstudy-hint' );
?>

<div class="masterstudy-hint <?php echo esc_attr( $dark_mode ? 'masterstudy-hint_dark-mode' : '' ); ?> <?php echo esc_attr( $side ? 'masterstudy-hint_side-' . $side : '' ); ?>">
	<span class="masterstudy-hint__icon"></span>
	<div class="masterstudy-hint__popup">
		<div class="masterstudy-hint__text">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	</div>
</div>
