<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $reviewing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$editor_id = "stm_lms_assignment__{$reviewing['id']}";

$q = new WP_Query(
	array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-assignments',
		'post__in'       => array( $item_id ),
	)
);

$actual_link = STM_LMS_Assignments::get_current_url();

$attachments = STM_LMS_Assignments::uploaded_attachments( $reviewing['id'] );

stm_lms_register_script(
	'assignment_edit',
	array(),
	false,
	"stm_lms_editor_id = '{$editor_id}'; 
    stm_lms_draft_id = {$reviewing['id']}; 
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

			<div class="assignment_status pending heading_font">
				<div class="inner">
					<i class="fa fa-hourglass-start"></i>
					<span><?php esc_html_e( 'Your assignment pending review', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				</div>
			</div>

			<div class="stm_lms_assignment__edit">

				<div class="assignment_approved_content">
					<?php echo wp_kses_post( $reviewing['content'] ); ?>
				</div>

				<?php STM_LMS_Templates::show_lms_template( 'course/parts/assignment_parts/file_loader', array( 'readonly' => true ) ); ?>

			</div>

		<?php endwhile; ?>
	</div>

	<?php
endif;
