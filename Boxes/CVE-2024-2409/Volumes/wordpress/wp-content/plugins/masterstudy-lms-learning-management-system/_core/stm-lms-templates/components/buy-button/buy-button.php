<?php
/**
 * @var int $post_id
 * @var int $item_id
 * @var bool $has_access
 * @var bool $prerequisite_preview
 * @var bool $hide_group_course
 * @var bool $dark_mode
 */

wp_enqueue_style( 'masterstudy-buy-button' );
wp_enqueue_script( 'masterstudy-buy-button' );
wp_localize_script(
	'masterstudy-buy-button',
	'masterstudy_buy_button_data',
	array(
		'ajax_url'        => admin_url( 'admin-ajax.php' ),
		'get_nonce'       => wp_create_nonce( 'stm_lms_add_to_cart' ),
		'get_guest_nonce' => wp_create_nonce( 'stm_lms_add_to_cart_guest' ),
		'item_id'         => $post_id,
	)
);

$has_course      = STM_LMS_User::has_course_access( $post_id, $item_id, false );
$course_price    = STM_LMS_Course::get_course_price( $post_id );
$only_membership = get_post_meta( $post_id, 'not_single_sale', true );

if ( isset( $has_access ) ) {
	$has_course = $has_access;
}

$button_classes = array(
	'masterstudy-buy-button',
	$course_price ? '' : 'masterstudy-buy-button-centred',
	$dark_mode ? 'masterstudy-buy-button_style-dark-mode' : '',
);

$is_prerequisite_passed = true;

if ( class_exists( 'STM_LMS_Prerequisites' ) ) {
	$is_prerequisite_passed = STM_LMS_Prerequisites::is_prerequisite( true, $post_id );
}

do_action( 'stm_lms_before_button_mixed', $post_id );

if ( apply_filters( 'stm_lms_before_button_stop', false, $post_id ) && false === $has_course ) {
	return false;
}

if ( class_exists( 'STM_LMS_Courses_Pro' ) && method_exists( 'STM_LMS_Courses_Pro', 'affiliate_course' ) ) {
	$is_affiliate = STM_LMS_Courses_Pro::affiliate_course( $post_id );
} else {
	$is_affiliate = false;
}

if ( ! $is_affiliate ) {
	if ( ( $has_course || ( empty( $course_price ) && ! $only_membership ) ) && $is_prerequisite_passed ) :
		/* Including the button template for free courses */
		STM_LMS_Templates::show_lms_template(
			'components/buy-button/free-courses/free-courses',
			array(
				'post_id'        => $post_id,
				'button_classes' => $button_classes,
			)
		);
	else :
		/* Including the button template for paid courses */
		STM_LMS_Templates::show_lms_template(
			'components/buy-button/paid-courses/paid-courses',
			array(
				'post_id'              => $post_id,
				'button_classes'       => $button_classes,
				'prerequisite_preview' => $prerequisite_preview,
				'only_membership'      => $only_membership,
			)
		);
	endif;

	if ( $is_prerequisite_passed && empty( $hide_group_course ) ) {
		/* Displaying the button for the Group Courses addon */
		do_action( 'masterstudy_group_course_button', $post_id );
	}
}
