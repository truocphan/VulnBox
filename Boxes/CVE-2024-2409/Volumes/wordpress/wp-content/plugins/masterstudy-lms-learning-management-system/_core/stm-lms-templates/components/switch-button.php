<?php

/**
 * @var string $title
 * @var int $id
 * @var boolean $dark_mode
 *
 * masterstudy-switch-button_active - for active state
 * masterstudy-next-prev-button_dark-mode - for dark mode
 */

wp_enqueue_style( 'masterstudy-switch-button' );
?>

<span class="masterstudy-switch-button <?php echo esc_attr( $dark_mode ? 'masterstudy-switch-button_dark-mode' : '' ); ?>" data-id="<?php echo esc_attr( $id ); ?>">
	<div class="masterstudy-switch-button__burger">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<span class="masterstudy-switch-button__title"><?php echo esc_html( $title ); ?></span>
</span>
