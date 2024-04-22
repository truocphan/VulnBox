<?php
/**
 * @var int $course_id
 * @var int $price
*/

if ( is_user_logged_in() ) :
	$settings    = get_option( 'stm_lms_settings' );
	$theme_fonts = $settings['course_player_theme_fonts'] ?? false;
	if ( empty( $theme_fonts ) ) {
		wp_enqueue_style( 'masterstudy-buy-button-group-courses-fonts' );
	}
	wp_enqueue_style( 'masterstudy-buy-button-group-courses' );
	?>
	<div class="masterstudy-button-enterprise">
		<div class="masterstudy-button-enterprise__title"><span><?php echo esc_html__( 'For Business', 'masterstudy-lms-learning-management-system-pro' ); ?></span></div>
		<div class="masterstudy-button-enterprise__button"
			data-lms-params='<?php echo wp_json_encode( compact( 'course_id' ) ); ?>'
			data-lms-modal="buy-enterprise"
			data-target=".stm-lms-modal-buy-enterprise">
			<span><?php echo esc_html__( 'Buy for group', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
		</div>
	</div>
	<?php
endif;
