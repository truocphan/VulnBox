<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use \MasterStudy\Lms\Repositories\CurriculumRepository;

stm_lms_register_style( 'curriculum' );
stm_lms_register_script( 'curriculum' );

$post_id      = ( ! empty( $post_id ) ) ? $post_id : get_the_ID();
$curriculum   = ( new CurriculumRepository() )->get_curriculum( $post_id, true );
$not_salebale = get_post_meta( $post_id, 'not_single_sale', true );
$price        = STM_LMS_Course::get_course_price( $post_id );

if ( ! empty( $curriculum ) ) :
	$has_access = STM_LMS_User::has_course_access( $post_id, '', false );
	?>

	<div class="stm-curriculum">
		<?php foreach ( $curriculum as $section ) : ?>
			<div class="stm-curriculum-section">
				<h3><?php echo wp_kses_post( $section['title'] ); ?></h3>
			</div>
			<?php
			if ( empty( $section['materials'] ) ) {
				continue;
			}

			foreach ( $section['materials'] as $index => $material ) {
				$meta    = '';
				$icon    = 'text';
				$hint    = esc_html__( 'Text Lesson', 'masterstudy-lms-learning-management-system' );
				$excerpt = get_post_meta( $material['post_id'], 'lesson_excerpt', true );
				$preview = '';
				$url     = STM_LMS_Lesson::get_lesson_url( get_the_ID(), $material['post_id'] );

				if ( in_array( $material['post_type'], array( 'stm-google-meets', 'stm-assignments', 'stm-quizzes' ) ) ) {
					$post_type_labels    = array(
						'stm-quizzes'      => array( 'quiz', esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system' ) ),
						'stm-google-meets' => array( 'google-meet', esc_html__( 'Google meeting', 'masterstudy-lms-learning-management-system' ) ),
						'stm-assignments'  => array( 'assignments', esc_html__( 'Assignment', 'masterstudy-lms-learning-management-system' ) ),
					);
					list( $icon, $hint ) = isset( $post_type_labels[ $material['post_type'] ] ) ? $post_type_labels[ $material['post_type'] ] : array( $icon, $hint );

					if ( 'stm-quizzes' === $material['post_type'] ) {
						$questions = get_post_meta( $material['post_id'], 'questions', true );
						if ( ! empty( $questions ) ) {
							$meta = sprintf(
							/* translators: %s: number */
								_n(
									'%s question',
									'%s questions',
									count( explode( ',', $questions ) ),
									'masterstudy-lms-learning-management-system'
								),
								count( explode( ',', $questions ) )
							);
						}
					}
				} else {
					$preview = get_post_meta( $material['post_id'], 'preview', true );
					$meta    = get_post_meta( $material['post_id'], 'duration', true );
					$type    = get_post_meta( $material['post_id'], 'type', true );

					$post_type_labels = array(
						'video'           => array( 'video', esc_html__( 'Video', 'masterstudy-lms-learning-management-system' ) ),
						'stream'          => array( 'stream', esc_html__( 'Live Stream', 'masterstudy-lms-learning-management-system' ) ),
						'zoom_conference' => array( 'zoom_conference', esc_html__( 'Zoom meeting', 'masterstudy-lms-learning-management-system' ) ),
					);
					if ( array_key_exists( $type, $post_type_labels ) ) {
						list($icon, $hint) = $post_type_labels[ $type ];
					}

					if ( ! empty( $meta ) ) {
						$meta = '<i class="far fa-clock"></i>' . $meta;
					}
				}

				$curriculum_atts = apply_filters( 'stm_lms_curriculum_item_atts', array(), $post_id, $material['post_id'] );
				?>

				<div class="stm-curriculum-item <?php echo ! empty( $excerpt ) ? 'has-excerpt' : ''; ?>">
					<div class="stm-curriculum-item__info">
						<div class="stm-curriculum-item__num">
							<?php echo esc_html( ++$index ); ?>
						</div>

						<div class="stm-curriculum-item__icon" data-toggle="tooltip" data-placement="bottom" title="<?php echo wp_kses_post( $hint ); ?>">
							<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/lessons/{$icon}.svg" ); ?>">
						</div>

						<div class="stm-curriculum-item__title">
							<div class="heading_font">
								<?php echo esc_html( $material['title'] ); ?>
							</div>
						</div>
						<div class="stm-curriculum-item__wrapper">
							<?php if ( ! empty( $excerpt ) ) : ?>
								<div class="stm-curriculum-item__toggle-container">
									<span class="stm-curriculum-item__toggle"></span>
								</div>
							<?php endif; ?>

							<?php if ( ( ! empty( $preview ) && ! $has_access && 0 !== $price ) || ( ! empty( $preview ) && ! $has_access && $not_salebale ) ) : ?>
								<div class="stm-curriculum-item__preview">
									<a href="<?php echo esc_url( $url ); ?>">
										<?php esc_html_e( 'Preview', 'masterstudy-lms-learning-management-system' ); ?>
									</a>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $meta ) ) : ?>
								<div class="stm-curriculum-item__meta">
									<?php echo wp_kses_post( $meta ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( ! empty( $excerpt ) ) : ?>
						<div class="stm-curriculum-item__excerpt">
							<?php echo wp_kses_post( $excerpt ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php
			}
		endforeach;
		?>
	</div>

<?php endif; ?>
