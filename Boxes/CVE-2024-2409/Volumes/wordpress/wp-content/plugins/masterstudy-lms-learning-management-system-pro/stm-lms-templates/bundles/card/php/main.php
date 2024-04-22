<?php
/**
 * @var $bundle
 * @var $courses
 */

stm_lms_register_style( 'bundles/card' );

$classes = array();

if ( count( $bundle['courses'] ) > 3 ) {
	$classes[] = 'overcoursed';
}
?>


<div class="stm_lms_single_bundle_card <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

	<div class="stm_lms_single_bundle_card__inner">

		<?php STM_LMS_Templates::show_lms_template( 'bundles/card/php/top', compact( 'bundle' ) ); ?>

		<div class="stm_lms_single_bundle_card__center_bottom">

			<div class="stm_lms_single_bundle_card__center_bottom_inner">

				<div class="stm_lms_single_bundle_card__center">
					<?php
					STM_LMS_Templates::show_lms_template(
						'bundles/card/php/courses',
						compact( 'bundle', 'courses' )
					);
					?>
				</div>

				<div class="stm_lms_single_bundle_card__bottom">
					<?php STM_LMS_Templates::show_lms_template( 'bundles/card/php/price_rating', compact( 'bundle' ) ); ?>
				</div>

			</div>

		</div>

	</div>

</div>
