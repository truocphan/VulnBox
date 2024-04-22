<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $lesson_type
 */

use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;

$material_ids     = ( new CurriculumMaterialRepository() )->get_course_materials( $post_id );
$current_material = ( new CurriculumMaterialRepository() )->find_by_course_lesson( $post_id, $item_id );

if ( ! empty( $current_material ) ) :
	if ( 'stm-quizzes' === $current_material->post_type ) {
		if ( STM_LMS_Quiz::quiz_passed( $item_id ) ) {
			$completed       = 'completed';
			$completed_label = esc_html__( 'Passed', 'masterstudy-lms-learning-management-system' );
		} else {
			$completed       = '';
			$completed_label = '';
		}
	} else {
		$completed       = ( STM_LMS_Lesson::is_lesson_completed( '', $post_id, $item_id ) ) ? 'completed' : 'uncompleted';
		$completed_label = 'completed' === $completed
			? esc_html__( 'Completed', 'masterstudy-lms-learning-management-system' )
			: esc_html__( 'Complete', 'masterstudy-lms-learning-management-system' );
	}

	$current_lesson_id = array_search( $item_id, $material_ids, true );
	$prev_lesson       = $material_ids[ $current_lesson_id - 1 ] ?? null;
	$next_lesson       = $material_ids[ $current_lesson_id + 1 ] ?? null;
	$completed_label   = apply_filters( 'stm_lms_completed_label', $completed_label, $item_id, $post_id );
	$lesson_style      = STM_LMS_Options::get_option( 'lesson_style', 'default' );
	?>

	<div class="stm-lms-lesson_navigation heading_font <?php esc_attr_e( 'Completed', 'masterstudy-lms-learning-management-system' ); ?>" data-completed="<?php esc_html_e( 'Completed', 'masterstudy-lms-learning-management-system' ); ?>">

		<div class="stm-lms-lesson_navigation_side stm-lms-lesson_navigation_prev">
			<?php
			if ( ! empty( $prev_lesson ) ) :
				$prev_section = STM_LMS_Lesson::get_lesson_info( $post_id, $prev_lesson );
				if ( 'classic' === $lesson_style && 'stream' !== $lesson_type && 'zoom_conference' !== $lesson_type ) :
					?>
					<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $prev_lesson ) ); ?>">
						<i class="lnr lnr-arrow-left"></i>
						<span>
						<?php esc_html_e( 'Prev lesson', 'masterstudy-lms-learning-management-system' ); ?>
						</span>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $prev_lesson ) ); ?>">
						<i class="lnr lnr-chevron-left"></i>
						<span>
							<?php if ( ! empty( $prev_section['section'] ) ) : ?>
								<span class="stm_lms_section_text">
									<?php echo esc_html( $prev_section['section'] ); ?>
								</span>
							<?php endif; ?>
							<span>
								<?php echo esc_html( get_the_title( $prev_lesson ) ); ?>
							</span>
						</span>
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $completed_label ) ) : ?>
			<div <?php echo esc_attr( apply_filters( 'stm_lms_navigation_complete_atts', '', $item_id ) ); ?> class="<?php echo esc_attr( apply_filters( 'stm_lms_navigation_complete_class', 'stm-lms-lesson_navigation_complete', $item_id ) ); ?>">
				<a href="#" class="btn btn-default stm_lms_complete_lesson <?php echo esc_attr( $completed ); ?>" data-course="<?php echo intval( $post_id ); ?>" data-lesson="<?php echo intval( $item_id ); ?>">
					<span><?php echo esc_html( sanitize_text_field( $completed_label ) ); ?></span>
				</a>
			</div>
		<?php endif; ?>

		<div class="stm-lms-lesson_navigation_side stm-lms-lesson_navigation_next">
			<?php
			if ( ! empty( $next_lesson ) ) :
				$next_section = STM_LMS_Lesson::get_lesson_info( $post_id, $next_lesson );
				if ( 'classic' === $lesson_style && 'stream' !== $lesson_type && 'zoom_conference' !== $lesson_type ) :
					?>
					<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $next_lesson ) ); ?>">
						<span>
							<?php esc_html_e( 'Next lesson', 'masterstudy-lms-learning-management-system' ); ?>
						</span>
						<i class="lnr lnr-arrow-right"></i>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( STM_LMS_Lesson::get_lesson_url( $post_id, $next_lesson ) ); ?>">
						<span>
							<?php if ( ! empty( $next_section['section'] ) ) : ?>
								<span class="stm_lms_section_text">
									<?php echo esc_html( $next_section['section'] ); ?>
								</span>
							<?php endif; ?>
							<span><?php echo esc_html( get_the_title( $next_lesson ) ); ?></span>
						</span>
						<i class="lnr lnr-chevron-right"></i>
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>

	</div>

	<?php
endif;
