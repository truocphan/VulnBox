<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $passed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$editor_id = "stm_lms_assignment__{$passed['id']}";

$q = new WP_Query(
	array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-assignments',
		'post__in'       => array( $item_id ),
	)
);

STM_LMS_Assignments::student_view_update( $passed['id'] );

$actual_link = STM_LMS_Assignments::get_current_url();

$attachments = STM_LMS_Assignments::uploaded_attachments( $passed['id'] );

stm_lms_register_script(
	'assignment_edit',
	array(),
	false,
	"stm_lms_editor_id = '{$editor_id}'; 
    stm_lms_draft_id = {$passed['id']}; 
    stm_lms_assignment_translations = {'delete' : '" . esc_html__( 'Delete File?', 'masterstudy-lms-learning-management-system-pro' ) . "'}
    stm_lms_assignment_files = " . wp_json_encode( $attachments ) . ''
);

if ( $q->have_posts() ) :
	?>
	<div class="stm-lms-course__assignment stm-lms-course__assignment-pending">
		<?php
		while ( $q->have_posts() ) :
			$q->the_post();

			STM_LMS_Templates::show_lms_template(
				'course/parts/assignment_parts/task',
				array(
					'item_id' => $item_id,
					'content' => get_the_content(),
				)
			);
			?>

			<div class="assignment_status passed heading_font">
				<div class="inner">
					<i class="fa fa-check"></i>
					<span>
						<?php esc_html_e( 'You passed assignment.', 'masterstudy-lms-learning-management-system-pro' ); ?>
					</span>
				</div>
			</div>

			<?php
			if ( ! empty( $passed['meta'] ) && ! empty( $passed['meta']['editor_comment'] ) && ! empty( $passed['meta']['editor_comment'][0] ) ) {
				STM_LMS_Templates::show_lms_template(
					'course/parts/assignment_parts/comment',
					array(
						'comment'   => $passed['meta']['editor_comment'][0],
						'editor_id' => $passed['editor_id'],
					)
				);
			}
			?>

			<div class="stm_lms_assignment__edit">

				<div class="assignment_approved_content">
					<?php echo wp_kses_post( $passed['content'] ); ?>
				</div>

				<?php STM_LMS_Templates::show_lms_template( 'course/parts/assignment_parts/file_loader', array( 'readonly' => true ) ); ?>

			</div>

		<?php endwhile; ?>
	</div>

	<?php
endif;
