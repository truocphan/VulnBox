<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $unpassed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$editor_id = "stm_lms_assignment__{$unpassed['id']}";

$q = new WP_Query(
	array(
		'posts_per_page' => 1,
		'post_type'      => 'stm-assignments',
		'post__in'       => array( $item_id ),
	)
);

STM_LMS_Assignments::student_view_update( $unpassed['id'] );

$actual_link = STM_LMS_Assignments::get_current_url();
$attachments = STM_LMS_Assignments::uploaded_attachments( $unpassed['id'] );
$attempts    = STM_LMS_Assignments::get_attempts( $item_id );
$can_attempt = $attempts['can_attempt'];
$retake_html = array(
	'',
	'</a>',
);

stm_lms_register_script(
	'assignment_edit',
	array(),
	false,
	"stm_lms_editor_id = '{$editor_id}'; 
	stm_lms_draft_id = {$unpassed['id']}; 
	stm_lms_assignment_translations = {'delete' : '" . esc_html__( 'Delete File?', 'masterstudy-lms-learning-management-system-pro' ) . "'}
	stm_lms_assignment_files = " . wp_json_encode( $attachments )
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

			<div class="assignment_status not_passed heading_font">
				<div class="inner">
					<i class="fa fa-times"></i>
					<span>
						<?php
						esc_html_e(
							'You failed assignment.',
							'masterstudy-lms-learning-management-system-pro'
						);

						if ( isset( $attempts['total'] ) && isset( $attempts['attempts'] ) ) :
							printf(
								/* translators: %s: number */
								esc_html__(
									'%1$s/%2$s attempts.',
									'masterstudy-lms-learning-management-system-pro'
								),
								esc_html( $attempts['attempts'] ),
								esc_html( $attempts['total'] )
							);
						endif;

						if ( $can_attempt ) :
							$data = array(
								'start_assignment' => $item_id,
								'course_id'        => $post_id,
							);
							?>
							<a href="<?php echo esc_url( add_query_arg( $data, $actual_link ) ); ?>">
								<?php esc_html_e( 'Retake', 'masterstudy-lms-learning-management-system-pro' ); ?>
							</a>
						<?php endif; ?>
					</span>
				</div>
			</div>

			<?php
			if ( ! empty( $unpassed['meta'] ) && ! empty( $unpassed['meta']['editor_comment'] ) && ! empty( $unpassed['meta']['editor_comment'][0] ) ) {
				STM_LMS_Templates::show_lms_template(
					'course/parts/assignment_parts/comment',
					array(
						'comment'   => $unpassed['meta']['editor_comment'][0],
						'editor_id' => $unpassed['editor_id'],
					)
				);
			}
			?>

			<div class="stm_lms_assignment__edit">

				<div class="assignment_approved_content">
					<?php echo wp_kses_post( $unpassed['content'] ); ?>
				</div>

				<?php
				STM_LMS_Templates::show_lms_template(
					'course/parts/assignment_parts/file_loader',
					array( 'readonly' => true )
				);
				?>

			</div>

		<?php endwhile; ?>
	</div>

	<?php
endif;
