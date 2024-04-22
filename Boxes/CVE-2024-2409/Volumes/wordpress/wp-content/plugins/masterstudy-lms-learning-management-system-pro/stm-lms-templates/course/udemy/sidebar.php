<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_id    = get_the_ID();
$price_info = get_post_meta( $post_id, 'price_info', true );
?>

<div class="stm-lms-course__sidebar-holder">
	<div class="stm-lms-course__sidebar">

		<div class="stm_lms_course__image">
			<?php
			if ( function_exists( 'stm_get_VC_attachment_img_safe' ) ) {
				$allowed_tags = stm_lms_pro_allowed_html();
				echo wp_kses( stm_get_VC_attachment_img_safe( get_post_thumbnail_id( $post_id ), 'full' ), $allowed_tags );
			} else {
				echo get_the_post_thumbnail( $post_id, 'large' );
			}
			?>
		</div>

		<?php STM_LMS_Templates::show_lms_template( 'global/expired_course', array( 'course_id' => $post_id ) ); ?>
		<?php
		STM_LMS_Templates::show_lms_template(
			'global/coming_soon',
			array(
				'course_id' => get_the_ID(),
				'mode'      => 'course',
			),
		);
		?>
		<?php STM_LMS_Templates::show_lms_template( 'global/buy-button', array( 'course_id' => $post_id ) ); ?>

		<?php if ( ! empty( $price_info ) ) : ?>
			<div class="stm_lms_money_back">
				<i class="fa fa-info-circle"></i>
				<?php echo esc_html( $price_info ); ?>
			</div>
		<?php endif; ?>

		<?php STM_LMS_Templates::show_lms_template( 'global/completed_info', array( 'course_id' => $post_id ) ); ?>

		<?php STM_LMS_Templates::show_lms_template( 'course/udemy/parts/includes', array( 'course_id' => $post_id ) ); ?>

		<?php STM_LMS_Templates::show_lms_template( 'course/parts/dynamic_sidebar', array( 'course_id' => $post_id ) ); ?>

	</div>
</div>
