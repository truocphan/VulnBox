<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly ?>

<?php
/**
 * @var $post_id
 * @var $item_id
 */

$post_id = $post_id ?? '';
stm_lms_register_style( 'quiz' );
stm_lms_register_script( 'quiz' );
wp_localize_script(
	'stm-lms-quiz',
	'quiz_data_vars',
	compact(
		'post_id',
		'item_id'
	)
);
wp_add_inline_script(
	'stm-lms-quiz',
	"var stm_lms_lesson_id = {$item_id}; var stm_lms_course_id = {$post_id}"
);
wp_localize_script(
	'stm-lms-quiz',
	'stm_lms_quiz_vars',
	array(
		'prevent_submit' => apply_filters( 'stm_lms_prevent_quiz_submit', 0 ),
		'confirmation'   => esc_html__( 'Once you submit, you will no longer be able to change your answers. Are you sure you want to submit the quiz?', 'masterstudy-lms-learning-management-system' ),
	)
);

$current_screen        = get_queried_object();
$source                = ( ! empty( $current_screen ) ) ? $current_screen->ID : '';
if ( empty( $post_id ) ) {
	$post_id = $source;
}

if ( ! empty( $item_id ) ) :

	/*Start Quiz*/

	$q = new WP_Query(
		array(
			'posts_per_page' => 1,
			'post_type'      => 'stm-quizzes',
			'post__in'       => array( $item_id ),
		)
	);

	if ( $q->have_posts() ) :
		$quiz_meta = STM_LMS_Helpers::parse_meta_field( $item_id );
		$user          = apply_filters( 'user_answers__user_id', STM_LMS_User::get_current_user(), $source );
		$last_quiz     = STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_last_quiz( $user['id'], $item_id, array( 'progress' ) ) );
		$progress      = ( ! empty( $last_quiz['progress'] ) ) ? $last_quiz['progress'] : 0;
		$passing_grade = ( ! empty( $quiz_meta['passing_grade'] ) ) ? $quiz_meta['passing_grade'] : 0;
		$passed        = $progress >= $passing_grade && ! empty( $progress );

		$classes = array();
		if ( $passed ) {
			$classes[] = 'passed';
		}
		if ( ! empty( $last_quiz ) && ! $passed ) {
			$classes[] = 'not-passed';
		}
		if ( STM_LMS_Quiz::show_answers( $item_id ) ) {
			$classes[] = 'show_answers';
		}
		if ( ! empty( $last_quiz ) ) {
			$classes[] = 'retaking';
		}

		$duration  = STM_LMS_Quiz::get_quiz_duration( $item_id );
		$classes[] = ( empty( $duration ) ) ? 'no-timer' : 'has-timer';

		?>
		<div class="stm-lms-course__lesson-content <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$meta_quiz = STM_LMS_Helpers::parse_meta_field( get_the_ID() );
				?>

				<div class="stm-lms-course__lesson-html_content">
					<?php
					ob_start();
					the_content();
					$content = ob_get_clean();
					$content = str_replace( '../../', site_url() . '/', $content );
					/* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
					echo stm_lms_filtered_output( $content );
					?>
				</div>

				<?php
				if ( ! empty( $meta_quiz['questions'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'quiz/quiz',
						array_merge( $meta_quiz, compact( 'post_id', 'item_id', 'last_quiz', 'source' ) )
					);
				}
				?>

				<?php
				STM_LMS_Templates::show_lms_template(
					'quiz/results',
					compact( 'quiz_meta', 'last_quiz', 'progress', 'passing_grade', 'passed', 'item_id' )
				);
				?>

			<?php endwhile; ?>
		</div>

	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
	<?php
endif;
