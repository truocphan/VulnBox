<?php
/**
 * @var $has_sale_price
 * @var $id
 * @var $price
 * @var $sale_price
 * @var $author_id
 * @var $style
 */

$classes = array( $has_sale_price, $style );

$level    = get_post_meta( $id, 'level', true );
$duration = get_post_meta( $id, 'duration_info', true );
$lectures = STM_LMS_Course::curriculum_info( $id );

?>

<div class="stm_lms_courses__single stm_lms_courses__single_animation <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<div class="stm_lms_courses__single__inner">

		<div class="stm_lms_courses__single__inner__image">

			<?php
			STM_LMS_Templates::show_lms_template(
				'courses/parts/image',
				array(
					'id'                   => $id,
					'img_size'             => $image_size ?? '370x200',
					'img_container_height' => $img_container_height ?? '',
				)
			);

			STM_LMS_Templates::show_lms_template(
				'global/wish-list',
				array(
					'course_id' => $id,
				)
			);
			?>

		</div>

		<div class="stm_lms_courses__single--inner">

			<?php STM_LMS_Templates::show_lms_template( 'courses/parts/title' ); ?>

			<div class="stm_lms_courses__single--info_meta">
				<?php STM_LMS_Templates::show_lms_template( 'courses/parts/meta', compact( 'level', 'duration', 'lectures' ) ); ?>
			</div>
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
				<div class="stm_lms_courses__single--info_meta">
					<?php
					do_action(
						'stm_lms_archive_card_price',
						compact(
							'price',
							'sale_price',
							'id'
						)
					);
					?>
					<a href="<?php the_permalink(); ?>"
						class="button"><?php echo esc_html__( 'Preview', 'masterstudy-lms-learning-management-system' ); ?>
					</a>
				</div>
				<?php
			}
			?>

		</div>

	</div>

</div>
