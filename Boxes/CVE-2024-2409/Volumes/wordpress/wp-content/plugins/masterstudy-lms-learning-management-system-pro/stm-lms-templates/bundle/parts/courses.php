<?php

stm_lms_register_style( 'expiration/main' );

$courses = get_post_meta( get_the_ID(), STM_LMS_My_Bundle::bundle_courses_key(), true );
$price   = get_post_meta( get_the_ID(), STM_LMS_My_Bundle::bundle_price_key(), true );


if ( ! empty( $courses ) ) : ?>

	<div class="stm_lms_single_bundle__courses_wrapper">

		<h3><?php esc_html_e( 'Courses in this bundle:', 'masterstudy-lms-learning-management-system-pro' ); ?></h3>

		<div class="stm_lms_single_bundle__courses">

			<?php foreach ( $courses as $course_id ) : ?>

				<a href="<?php echo esc_url( get_the_permalink( $course_id ) ); ?>" class="stm_lms_single_bundle__courses_course">

					<div class="stm_lms_single_bundle__courses_course__inner">

						<div class="stm_lms_single_bundle__courses_course__image">
							<?php
							$img_size = '85x50';
							if ( function_exists( 'stm_get_VC_img' ) ) {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo stm_lms_lazyload_image( stm_get_VC_img( get_post_thumbnail_id( $course_id ), $img_size ) );
							} else {
								echo get_the_post_thumbnail( $course_id, $img_size );
							}
							?>
						</div>

						<?php $course_expiration_days = STM_LMS_Course::get_course_expiration_days( $course_id ); ?>

						<div class="stm_lms_single_bundle__courses_course__data heading_font">

							<?php
							if ( $course_expiration_days ) {
								STM_LMS_Templates::show_lms_template( 'expiration/info', compact( 'course_expiration_days' ) );
							}
							?>

							<div class="stm_lms_single_bundle__courses_course__title">
								<?php echo esc_html( get_the_title( $course_id ) ); ?>
							</div>

							<?php if ( ! empty( $price ) ) : ?>
								<div class="stm_lms_single_bundle__courses_course__price">
									<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo STM_LMS_Helpers::display_price( STM_LMS_Course::get_course_price( $course_id ) );
									?>
								</div>
							<?php endif; ?>

						</div>

					</div>

				</a>

			<?php endforeach; ?>

		</div>

	</div>

	<?php
endif;
