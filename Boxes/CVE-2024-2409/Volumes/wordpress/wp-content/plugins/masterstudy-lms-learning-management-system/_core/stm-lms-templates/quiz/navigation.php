<?php
/**
 * @var $post_id
 * @var $item_id
 */

use \MasterStudy\Lms\Repositories\CurriculumMaterialRepository;

$material_ids = ( new CurriculumMaterialRepository() )->get_course_materials( $post_id );

if ( ! empty( $material_ids ) ) :
	if ( in_array( $item_id, $material_ids, true ) ) {
		$current_lesson_id = array_search( $item_id, $material_ids, true );
		$prev_lesson       = $material_ids[ $current_lesson_id - 1 ] ?? null;
		$next_lesson       = $material_ids[ $current_lesson_id + 1 ] ?? null;
	} ?>

	<div class="stm-lms-lesson_navigation">

		<div class="stm-lms-lesson_navigation_side stm-lms-lesson_navigation_prev">
			<?php if ( $prev_lesson ) : ?>
				<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $prev_lesson ) ); ?>">
					<?php echo esc_html( get_the_title( $prev_lesson ) ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="stm-lms-lesson_navigation_side stm-lms-lesson_navigation_next">
			<?php if ( $next_lesson ) : ?>
				<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $next_lesson ) ); ?>">
					<?php echo esc_html( get_the_title( $next_lesson ) ); ?>
				</a>
			<?php endif; ?>
		</div>

	</div>
	<?php
endif;
