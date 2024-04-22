<?php
/**
 * @var $course_id
 */

use MasterStudy\Lms\Repositories\CurriculumSectionRepository;
use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;

$section_ids      = ( new CurriculumSectionRepository() )->get_course_section_ids( $course_id );
$lessons_count    = ( new CurriculumMaterialRepository() )->count_by_type( $section_ids, 'stm-lessons' );
$video            = get_post_meta( $course_id, 'udemy_content_length_video', true );
$video_duration   = get_post_meta( $course_id, 'video_duration', true );
$assets           = get_post_meta( $course_id, 'udemy_num_additional_assets', true );
$articles         = get_post_meta( $course_id, 'udemy_num_article_assets', true );
$access_duration  = get_post_meta( $course_id, 'access_duration', true );
$access_devices   = get_post_meta( $course_id, 'access_devices', true );
$certificate      = get_post_meta( $course_id, 'udemy_has_certificate', true );
$certificate_info = get_post_meta( $course_id, 'certificate_info', true );

if ( empty( $video ) && empty( $video_duration ) && empty( $assets ) && empty( $articles ) && empty( $access_duration )
	&& empty( $access_devices ) && empty( $certificate ) && empty( $certificate_info ) ) {
	return;
}
?>

<div class="stm_lms_udemy_includes">

	<h4><?php esc_html_e( 'Includes', 'masterstudy-lms-learning-management-system-pro' ); ?></h4>

	<?php if ( ! empty( $video ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-play primary_color"></i>
			<?php
			printf(
				/* translators: %s Hours */
				esc_html__( '%s hours on-demand video', 'masterstudy-lms-learning-management-system-pro' ),
				round( $video / 3600, 0 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</div>
	<?php elseif ( ! empty( $video_duration ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-play primary_color"></i>
			<?php echo esc_html( $video_duration ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $articles ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-text-format primary_color"></i>
			<?php
			printf(
				/* translators: %s Articles */
				_n( '%s article', '%s articles', $articles, 'masterstudy-lms-learning-management-system-pro' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$articles // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $lessons_count ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-book2 primary_color"></i>
			<?php echo esc_html( $lessons_count ); ?>
			<?php echo esc_html__( 'lectures', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $access_duration ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-clock3 primary_color"></i>
			<?php echo esc_html( $access_duration ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $access_devices ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-laptop-phone primary_color"></i>
			<?php echo esc_html( $access_devices ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $certificate ) || ! empty( $certificate_info ) ) : ?>
		<div class="stm_lms_udemy_include heading_font">
			<i class="lnricons-license2 primary_color"></i>
			<?php
			if ( ! empty( $certificate_info ) ) :
				echo esc_html( $certificate_info );
			else :
				esc_html_e( 'Certificate of Completion', 'masterstudy-lms-learning-management-system-pro' );
			endif;
			?>
		</div>
	<?php endif; ?>

</div>
