<?php
/**
 * @var $bundle
 * @var $courses
 */

if ( ! empty( $bundle['courses'] ) ) : ?>

	<div class="stm_lms_single_bundle_card__courses">

		<?php
		foreach ( $bundle['courses'] as $course_id ) :
			if ( empty( $courses[ $course_id ] ) ) {
				continue;
			}
			$course_data = $courses[ $course_id ];
			?>

			<a class="stm_lms_single_bundle_card__course" href="<?php echo esc_url( $course_data['link'] ); ?>">

				<div class="stm_lms_single_bundle_card__course_image">
					<?php echo stm_lms_filtered_output( $course_data['image'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<div class="stm_lms_single_bundle_card__course_data heading_font">
					<div class="stm_lms_single_bundle_card__course_title">
						<?php echo esc_html( $course_data['title'] ); ?>
					</div>

					<?php if ( $course_data['simple_price'] ) : ?>
						<div class="stm_lms_single_bundle_card__course_price">
							<?php echo sanitize_text_field( $course_data['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>

				</div>

			</a>

		<?php endforeach; ?>

	</div>

<?php endif; ?>
