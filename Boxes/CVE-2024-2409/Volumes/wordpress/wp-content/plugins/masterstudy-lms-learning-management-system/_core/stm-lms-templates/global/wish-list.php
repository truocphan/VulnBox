<?php
/**
 * @var $course_id
 */


stm_lms_register_style( 'wishlist' );
stm_lms_register_script( 'wishlist' );
if ( ! is_user_logged_in() ) {
	wp_enqueue_script( 'jquery.cookie' );
}

$wishlisted = STM_LMS_User::is_wishlisted( $course_id );

if ( is_user_logged_in() ) { ?>
	<div class="stm-lms-wishlist"
		data-add="<?php esc_html_e( 'Add to Wishlist', 'masterstudy-lms-learning-management-system' ); ?>"
		data-add-icon="far fa-heart"
		data-remove="<?php esc_html_e( 'Remove from Wishlist', 'masterstudy-lms-learning-management-system' ); ?>"
		data-remove-icon="fa fa-heart"
		data-id="<?php echo intval( $course_id ); ?>">
		<?php if ( $wishlisted ) { ?>
			<i class="fa fa-heart"></i>
			<span><?php esc_html_e( 'Remove from Wishlist', 'masterstudy-lms-learning-management-system' ); ?></span>
		<?php } else { ?>
			<i class="far fa-heart"></i>
			<span><?php esc_html_e( 'Add to Wishlist', 'masterstudy-lms-learning-management-system' ); ?></span>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="stm-lms-wishlist">
		<a href="<?php echo esc_url( STM_LMS_User::login_page_url() ); ?>">
			<i class="far fa-heart"></i>
			<span><?php esc_html_e( 'Add to Wishlist', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
	</div>
	<?php
}
