<?php
/**
 * @var $wishlist
 * @var $columns
 * @var $title
 * @var $args
 * @var $public
 */

stm_lms_register_script( 'bundles/card' );

$public = ( ! empty( $public ) ) ? $public : false;

$columns = ( ! empty( $columns ) ) ? $columns : '3';

$args = ( ! empty( $args ) ) ? $args : array();

$bundles = STM_LMS_My_Bundles::get_bundles(
	wp_parse_args(
		$args,
		array(
			'post__in'       => $wishlist,
			'posts_per_page' => - 1,
		)
	),
	$public
);


$bundles_list = ( ! empty( $bundles['posts'] ) ) ? $bundles['posts'] : array();
$courses      = ( ! empty( $bundles['courses'] ) ) ? $bundles['courses'] : array();
$pages        = ( ! empty( $bundles['pages'] ) ) ? $bundles['pages'] : 1;

if ( ! empty( $bundles_list ) ) :

	$list_classes = array( "stm_lms_my_course_bundles__list_{$columns}" );
	if ( $pages > 1 ) {
		$list_classes[] = 'stm_lms_my_course_bundles__list_paged';
	}
	?>

	<?php if ( ! empty( $title ) ) : ?>
	<h4 class="stm_lms_my_course_bundles__title"><?php echo esc_html( $title ); ?></h4>
<?php endif; ?>

	<div class="stm_lms_my_course_bundles__list <?php echo esc_attr( implode( ' ', $list_classes ) ); ?>">
		<?php foreach ( $bundles_list as $bundle ) : ?>
			<?php STM_LMS_Templates::show_lms_template( 'bundles/card/php/main', compact( 'bundle', 'courses' ) ); ?>
		<?php endforeach; ?>
	</div>

	<?php STM_LMS_Templates::show_lms_template( 'bundles/card/php/pagination', compact( 'pages' ) ); ?>

	<?php
endif;
