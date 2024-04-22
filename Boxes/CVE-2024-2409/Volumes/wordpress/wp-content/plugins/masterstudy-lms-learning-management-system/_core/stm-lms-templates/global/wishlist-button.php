<?php
$icon       = ( empty( $icon ) ) ? 'lnr lnr-heart' : esc_attr( $icon );
$class      = ( empty( $class ) ) ? '' : esc_attr( $class );
$user_login = is_user_logged_in();
?>

<div class="stm_lms_wishlist_button <?php echo $user_login ? 'logged-in' : 'not-logged-in'; ?>">
	<a href="<?php echo $user_login ? esc_url( STM_LMS_User::wishlist_url() ) : esc_url( STM_LMS_User::login_page_url() ); ?>" data-text="<?php esc_html_e( 'Favorites', 'masterstudy-lms-learning-management-system' ); ?>">
		<i class="<?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $class ); ?>"></i>
	</a>
</div>
