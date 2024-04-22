<?php

/**
 * @var int $id
 * @var string $title
 * @var string $link
 * @var string $icon
 * @var string $style
 * @var string $size
 * @var bool $login
 *
 * masterstudy-button_icon-prev|next - for icon direction
 * masterstudy-button_style-primary|secondary|tertiary|outline|danger - for style change
 * masterstudy-button_size-sm|md - for size change
 * masterstudy-button_loading - for loading animation
 * masterstudy-button_disabled - for "disabled" style
 */

wp_enqueue_style( 'masterstudy-button' );

$data        = isset( $id ) ? ' data-id=' . $id : '';
$icon_class  = isset( $icon_position ) ? ' masterstudy-button_icon-' . $icon_position : '';
$icon_class .= isset( $icon_name ) ? ' masterstudy-button_icon-' . $icon_name : '';
$target      = isset( $target ) ? 'target=' . $target : '';
$login       = isset( $login ) ? 'register' === $login ? 'data-authorization-modal=register' : 'data-authorization-modal=login' : '';
if ( ! empty( $login ) ) {
	wp_enqueue_script( 'vue-resource.js' );
	stm_lms_register_style( 'login' );
	stm_lms_register_style( 'register' );
	enqueue_login_script();
	enqueue_register_script();
}
?>

<a
	href="<?php echo esc_url( $link ); ?>"
	class="masterstudy-button <?php echo esc_attr( 'masterstudy-button_style-' . $style . ' masterstudy-button_size-' . $size . $icon_class ); ?>"
	<?php echo esc_attr( $login . $data ); ?>
>
	<span class="masterstudy-button__title"><?php echo esc_html( $title ); ?></span>
</a>
