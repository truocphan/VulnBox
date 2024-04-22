<?php
/**
 *
 * @var $columns
 * @var $title
 * @var $posts_per_page
 * @var $wishlist
 * @var $select_bundles
 */
$posts_per_page = ( ! empty( $posts_per_page ) ) ? intval( $posts_per_page ) : 9;
$page           = get_query_var( 'page', 1 );
$public         = true;
$wishlist       = ( ! empty( $wishlist ) ) ? $wishlist : array();

if ( ! empty( $select_bundles ) ) {
	$wishlist = explode( ',', $select_bundles );
}

$args = array(
	'posts_per_page' => $posts_per_page,
	'post_status'    => array( 'publish' ),
	'stm_lms_page'   => $page,
	'author'         => '',
); ?>

<div class="stm_lms_my_course_bundles__vc">
	<?php
	STM_LMS_Templates::show_lms_template(
		'bundles/card/php/list',
		compact( 'wishlist', 'columns', 'title', 'args', 'public' )
	);
	?>
</div>
