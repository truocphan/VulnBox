<?php

/**
 * @var string $title
 * @var string $id
 * @var string $type
 * @var object $data
 * @var string $link
 * @var string $style
 * @var boolean $dark_mode
 *
 * masterstudy-nav-button_dark-mode - for dark mode
 * masterstudy-nav-button_type-prev|next - for link direction
 * masterstudy-nav-button_style-primary|secondary - for link style change
 * masterstudy-nav-button_last - if last link without redirect
 * masterstudy-nav-button_laoding - for loading animation
 */

wp_enqueue_style( 'masterstudy-nav-button' );

$data_json        = wp_json_encode( $data );
$data_attr_format = htmlspecialchars( $data_json, ENT_QUOTES, 'UTF-8' );
$dark_mode_class  = $dark_mode ? 'masterstudy-nav-button_dark-mode' : '';
$type_class       = 'masterstudy-nav-button_type-' . $type;
$style_class      = 'masterstudy-nav-button_style-' . $style;
$last_btn_class   = empty( $link ) ? 'masterstudy-nav-button_last' : '';
?>

<a href="<?php echo esc_url( $link ); ?>"
	data-id="<?php echo esc_attr( ! empty( $id ) ? $id : '' ); ?>"
	class="masterstudy-nav-button <?php echo esc_attr( "$dark_mode_class $type_class $style_class $last_btn_class " . apply_filters( 'masterstudy_lms_course_player_complete_button_class', '' ) ); ?>"
	data-query="<?php echo esc_attr( $data_attr_format ); ?>">
	<span class="masterstudy-nav-button__title"><?php echo esc_html( $title ); ?></span>
</a>
