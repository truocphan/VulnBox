<?php
/**
 * @var string $social_position
 */

$social_login                 = apply_filters( 'masterstudy_lms_social_login_providers', array() );
$social_addon_fb              = isset( $social_login['facebook'] ) && is_object( $social_login['facebook'] ) ? $social_login['facebook']->is_connected() : false;
$social_addon_fb_auth_url     = isset( $social_login['facebook'] ) && is_object( $social_login['facebook'] ) ? $social_login['facebook']->get_auth_url() : false;
$social_addon_google          = isset( $social_login['google'] ) && is_object( $social_login['google'] ) ? $social_login['google']->is_connected() : false;
$social_addon_google_auth_url = isset( $social_login['google'] ) && is_object( $social_login['google'] ) ? $social_login['google']->get_auth_url() : false;
$third_party_social           = false;
$third_party_social           = false;

if ( $social_addon_fb || $social_addon_google ) {
	?>
	<div id="masterstudy-authorization-social-login" class="masterstudy-authorization__social <?php echo esc_attr( 'bottom' === $social_position ? 'masterstudy-authorization__social_bottom' : '' ); ?>">
		<?php if ( $social_addon_google ) { ?>
			<a href="<?php echo esc_url( $social_addon_google_auth_url ); ?>" id="masterstudy-authorization-google-login" class="masterstudy-authorization__social-google">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/social/google.svg' ); ?>">
				<?php echo esc_html__( 'Google', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
				<?php
		}
		if ( $social_addon_fb ) {
			?>
			<a href="<?php echo esc_url( $social_addon_fb_auth_url ); ?>" id="masterstudy-authorization-fb-login" class="masterstudy-authorization__social-facebook">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/social/fb.svg' ); ?>">
				<?php echo esc_html__( 'Facebook', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
			<?php } ?>
	</div>
	<div id="masterstudy-authorization-social-register" class="masterstudy-authorization__social <?php echo esc_attr( 'bottom' === $social_position ? 'masterstudy-authorization__social_bottom' : '' ); ?>">
		<?php if ( $social_addon_google ) { ?>
			<a href="<?php echo esc_url( $social_addon_google_auth_url ); ?>" id="masterstudy-authorization-google-register" class="masterstudy-authorization__social-google">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/social/google.svg' ); ?>">
				<?php echo esc_html__( 'Google', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
			<?php
		}
		if ( $social_addon_fb ) {
			?>
			<a href="<?php echo esc_url( $social_addon_fb_auth_url ); ?>" id="masterstudy-authorization-fb-register" class="masterstudy-authorization__social-facebook">
				<img src="<?php echo esc_url( STM_LMS_URL . '/assets/icons/social/fb.svg' ); ?>">
				<?php echo esc_html__( 'Facebook', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		<?php } ?>
	</div>
	<?php
} elseif ( defined( 'NSL_PATH_FILE' ) && apply_filters( 'stm_lms_show_social_login', true ) ) {
	$third_party_social = $social_addon_fb || $social_addon_google ? false : true;
	echo do_shortcode( '[nextend_social_login]' );
}
if ( $social_addon_fb || $social_addon_google || $third_party_social ) {
	$seperator_class  = 'bottom' === $social_position ? ' masterstudy-authorization__separator_bottom' : '';
	$seperator_class .= 'register' === $type ? ' masterstudy-authorization__separator-signup' : '';
	?>
	<div class="masterstudy-authorization__separator <?php echo esc_attr( $seperator_class ); ?>">
		<span class="masterstudy-authorization__separator-line"></span>
		<span class="masterstudy-authorization__separator-title">
			<?php echo esc_html( 'register' === $type ? $titles['register']['separator'] : $titles['login']['separator'] ); ?>
		</span>
		<span class="masterstudy-authorization__separator-line"></span>
	</div>
	<?php
}
