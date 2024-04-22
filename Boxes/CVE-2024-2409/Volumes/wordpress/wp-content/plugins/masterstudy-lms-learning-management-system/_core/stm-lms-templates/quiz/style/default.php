<?php

/**
 * @var $post_id
 * @var $item_id
 * @var $last_answers
 */

$question_index = 0;
$current_screen = get_queried_object();
$source         = $current_screen->ID ?? '';
$question_ids   = array();
?>

<form class="stm-lms-single_quiz">

	<input type="hidden" name="source" value="<?php echo intval( $source ); ?>">

	<?php
	while ( $q->have_posts() ) :
		$q->the_post();
		$question_index++;
		$question_ids[] = get_the_ID();
		?>
		<span class="stm-lms-single_quiz__label"></span>
		<?php STM_LMS_Templates::show_lms_template( 'quiz/question', compact( 'item_id', 'last_answers', 'question_index' ) ); ?>
	<?php endwhile; ?>

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
