<?php
/**
 * @var $current_user
 */

$wishlist = STM_LMS_User::get_wishlist( $current_user['id'] );

do_action( 'stm_lms_before_wishlist_list', $wishlist ); ?>

<?php
STM_LMS_Templates::show_lms_template(
	'account/private/parts/top_info',
	array(
		'title' => esc_html__( 'Wishlist', 'masterstudy-lms-learning-management-system' ),
	)
);
?>

<?php if ( ! empty( $wishlist ) ) { ?>
	<?php
	STM_LMS_Templates::show_lms_template(
		'courses/grid',
		array(
			'args' => array(
				'post__in' => $wishlist,
				'class'    => 'archive_grid',
			),
		)
	);
	?>
<?php } else { ?>
	<h4><?php esc_html_e( 'Wishlist is empty', 'masterstudy-lms-learning-management-system' ); ?></h4>
<?php } ?>

<?php do_action( 'stm_lms_after_wishlist_list', $wishlist ); ?>
