<?php
/**
 * @var $has_sale_price
 * @var $id
 * @var $price
 * @var $sale_price
 * @var $author_id
 * @var $style
 * @var $featured
 * @var $image_size
 */

$classes   = array( $has_sale_price, $style );
$classes[] = ( ! empty( $featured ) && 'on' === $featured ) ? 'is_featured' : '';

$image_params = array(
	'id'       => $id,
	'featured' => $featured,
);

if ( ! empty( $image_size ) ) {
	$image_params['img_size'] = $image_size;
}

if ( ! empty( $img_container_height ) ) {
	$image_params['img_container_height'] = $img_container_height;
}

?>

<div class="stm_lms_courses__single stm_lms_courses__single_animation <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<div class="stm_lms_courses__single__inner">

		<?php STM_LMS_Templates::show_lms_template( 'courses/parts/image', $image_params ); ?>

		<div class="stm_lms_courses__single--inner">

			<?php STM_LMS_Templates::show_lms_template( 'courses/parts/terms', array( 'id' => $id ) ); ?>

			<?php STM_LMS_Templates::show_lms_template( 'courses/parts/title' ); ?>

			<?php
			$is_course_coming_soon = STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $id );
			if ( $is_course_coming_soon ) {
				STM_LMS_Templates::show_lms_template(
					'global/coming_soon',
					array(
						'course_id' => $id,
						'mode'      => 'card',
					),
				);
			} else {
				?>
				<div class="stm_lms_courses__single--meta">
					<?php STM_LMS_Templates::show_lms_template( 'courses/parts/rating', array( 'id' => $id ) ); ?>
					<?php do_action( 'stm_lms_archive_card_price', compact( 'price', 'sale_price', 'id' ) ); ?>
				</div>
				<?php
			}
			?>

		</div>

		<?php
		STM_LMS_Templates::show_lms_template(
			'courses/parts/course_info',
			array_merge(
				array(
					'post_id' => $id,
				),
				compact( 'sale_price', 'price', 'author_id', 'id' )
			)
		);
		?>

	</div>

</div>
