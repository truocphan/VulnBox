<?php $wishlist_title = ( is_user_logged_in() ) ? __( 'My Wishlist', 'masterstudy-lms-learning-management-system' ) : __( 'Wishlist', 'masterstudy-lms-learning-management-system' ); ?>
<h2><?php echo esc_html( $wishlist_title ); ?></h2>
<?php
if ( ! empty( $_COOKIE['stm_lms_wishlist'] ) ) {
	$wishlist = sanitize_text_field( wp_unslash( $_COOKIE['stm_lms_wishlist'] ) );
	$args     = array(
		'per_row'  => 4,
		'post__in' => explode( ',', $wishlist ),
	);
	STM_LMS_Templates::show_lms_template( 'courses/grid', array( 'args' => $args ) );
} else {
	?>
	<h4><?php printf( wp_kses( 'Wishlist will be available after <a href="%s">registration.</a>', 'masterstudy-lms-learning-management-system' ), esc_url( add_query_arg( 'mode', 'register', STM_LMS_User::login_page_url() ) ) ); ?></h4>
<?php } ?>
