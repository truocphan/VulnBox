<?php
/**
 * @var $bundle
 */
?>

<div class="stm_lms_single_bundle_card__top heading_font">

	<a href="<?php echo esc_url( $bundle['url'] ); ?>" class="stm_lms_single_bundle_card__title">
		<?php echo esc_html( $bundle['title'] ); ?>
	</a>

	<div class="stm_lms_single_bundle_card__top_subplace">
		<span>
			<?php /* translators: %s Courses Count */ printf( esc_html__( '%s Courses', 'masterstudy-lms-learning-management-system-pro' ), count( $bundle['courses'] ) ); ?>
		</span>
	</div>

</div>
