<?php
/**
 * @var int $course_id
*/
$user         = STM_LMS_User::get_current_user();
$user_points  = STM_LMS_Point_System::total_points( $user['id'] );
$course_price = STM_LMS_Point_System::course_price( $course_id );
$settings     = get_option( 'stm_lms_settings' );
$theme_fonts  = $settings['course_player_theme_fonts'] ?? false;
if ( empty( $theme_fonts ) ) {
	wp_enqueue_style( 'masterstudy-buy-button-points-fonts' );
}
wp_enqueue_style( 'masterstudy-buy-button-points' );
wp_enqueue_script( 'masterstudy-buy-button-points' );
wp_localize_script(
	'masterstudy-buy-button-points',
	'masterstudy_buy_button_points',
	array(
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'get_nonce' => wp_create_nonce( 'stm_lms_buy_for_points' ),
		'course_id' => $course_id,
		'translate' => array(
			'confirm' => sprintf(
				/* translators:  %1$s Course ID %2$s Course Price */
				esc_html__( 'Do you really want to buy %1$s for %2$s?', 'masterstudy-lms-learning-management-system-pro' ),
				get_the_title( $course_id ),
				STM_LMS_Point_System::display_points( $course_price )
			),
		),
	)
);

$classes = array( 'masterstudy-points' );
if ( $user_points < $course_price ) {
	$classes[] = 'masterstudy-points-not-enough-points';
}

$distribution = sprintf(
	'<span class="masterstudy-points__icon" data-href="%s"><i class="fa fa-question-circle"></i></span>',
	esc_url( ms_plugin_user_account_url( 'points-distribution' ) )
);

if ( ! empty( $course_price ) ) :
	?>
	<a href="#"
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		data-course="<?php echo esc_attr( $course_id ); ?>"
	>
		<?php echo wp_kses_post( STM_LMS_Point_System::display_point_image() ); ?>
		<span class="masterstudy-points__info">
		<span class="masterstudy-points__price"><?php echo esc_html( STM_LMS_Point_System::display_points( $course_price ) ); ?></span>
			<span class="masterstudy-points__text">
				<?php
				if ( $user_points < $course_price ) {
					printf(
						/* translators:  %1$s Points %2$s Distribution */
						esc_html__( 'You need %1$s. %2$s', 'masterstudy-lms-learning-management-system-pro' ),
						wp_kses_post( STM_LMS_Point_System::display_points( $course_price - $user_points ) ),
						wp_kses_post( $distribution )
					);
				} else {
					printf(
						/* translators:  %1$s Points %2$s Distribution */
						esc_html__( 'You have %1$s. %2$s', 'masterstudy-lms-learning-management-system-pro' ),
						wp_kses_post( STM_LMS_Point_System::display_points( $user_points ) ),
						wp_kses_post( $distribution )
					);
				}
				?>
			</span>
		</span>
	</a>
	<?php
endif;
