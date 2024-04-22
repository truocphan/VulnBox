<?php

/**
 * @var $post_id
 * @var $item_id
 * @var $last_answers
 */

$question_index = 0;
$current_screen = get_queried_object();
$source         = ( ! empty( $current_screen ) ) ? $current_screen->ID : '';
$question_ids   = array();

stm_lms_module_styles( 'quiz-pagination', 'paginated' );
stm_lms_module_scripts( 'quiz-pagination', 'paginated' );
?>

<form class="stm-lms-single_quiz">

	<input type="hidden" name="source" value="<?php echo intval( $source ); ?>">

	<div class="stm_lms_paginated_quiz_number heading_font">
		<?php esc_html_e( 'Question', 'masterstudy-lms-learning-management-system' ); ?>
		<span class="current_q">1</span><span class="total_q"> / <?php echo intval( $q->found_posts ); ?></span>
	</div>

	<?php
	while ( $q->have_posts() ) :
		$q->the_post();
		++$question_index;
		$question_ids[] = get_the_ID();
		?>
		<div class="stm_lms_paginated_quiz_question">
			<?php STM_LMS_Templates::show_lms_template( 'quiz/question', compact( 'item_id', 'last_answers', 'question_index' ) ); ?>
		</div>
	<?php endwhile; ?>

	<div class="stm_lms_paginated_quiz_pager <?php echo esc_attr( STM_LMS_Quiz::show_answers( $item_id ) ? 'answers_shown' : '' ); ?>">
		<!--JS Will insert pagination here-->
	</div>

	<?php if ( ! STM_LMS_Quiz::show_answers( $item_id ) ) : ?>
		<input type="hidden" name="question_ids" value="<?php echo esc_attr( implode( ',', $question_ids ) ); ?>"/>
		<input type="hidden" name="action" value="stm_lms_user_answers"/>
		<input type="hidden" name="quiz_id" value="<?php echo intval( $item_id ); ?>"/>
		<input type="hidden" name="course_id" value="<?php echo intval( $post_id ); ?>"/>
		<button type="submit" class="btn btn-default stm_lms_complete_lesson">
			<span><?php esc_html_e( 'Submit Quiz', 'masterstudy-lms-learning-management-system' ); ?></span>
		</button>
	<?php endif; ?>

</form>
