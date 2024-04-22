<?php

/**
 * @var boolean $dark_mode
 *
 * masterstudy-dark-mode-button_style-dark - for dark mode
 */

wp_enqueue_style( 'masterstudy-dark-mode-button' );
wp_enqueue_script( 'masterstudy-dark-mode-button' );
wp_localize_script(
	'masterstudy-dark-mode-button',
	'mode_data',
	array(
		'nonce'     => wp_create_nonce( 'masterstudy_lms_dark_mode' ),
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'dark_mode' => $dark_mode,
	)
);
?>

<span class="masterstudy-dark-mode-button <?php echo esc_attr( $dark_mode ? 'masterstudy-dark-mode-button_style-dark' : '' ); ?>"></span>
