<?php
/**
 * @var $courses
 */

stm_lms_register_script( 'prerequisites' );
stm_lms_register_style( 'prerequisites' );
?>

<div class="stm_lms_prerequisite_courses">
	<a href="#" class="btn btn-default btn_big heading_font">
		<span><?php esc_html_e( 'Prerequisites', 'masterstudy-lms-learning-management-system-pro' ); ?></span>

		<label></label>
	</a>

	<ul>
		<?php
		foreach ( $courses as $course ) :
			$course_id = $course['course_id'];
			$progress  = $course['progress_percent'];
			?>
			<li>
				<a href="<?php the_permalink( $course_id ); ?>">
					<h5><?php echo esc_html( get_the_title( $course_id ) ); ?></h5>
				</a>

				<?php
				if ( empty( $progress ) ) :
					$price      = get_post_meta( $course_id, 'price', true );
					$sale_price = STM_LMS_Course::get_sale_price( $course_id );

					if ( empty( $price ) && ! empty( $sale_price ) ) {
						$price      = $sale_price;
						$sale_price = '';
					}

					if ( ! empty( $price ) && ! empty( $sale_price ) ) {
						$tmp_price  = $sale_price;
						$sale_price = $price;
						$price      = $tmp_price;
					}

					?>

					<?php if ( ! empty( $price ) || ! empty( $sale_price ) ) : ?>
						<div class="btn-prices heading_font">

							<?php if ( ! empty( $sale_price ) ) : ?>
								<label class="sale_price"><?php echo STM_LMS_Helpers::display_price( $sale_price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
							<?php endif; ?>

							<?php if ( ! empty( $price ) ) : ?>
								<label class="price"><?php echo STM_LMS_Helpers::display_price( $price ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
							<?php endif; ?>

						</div>
					<?php else : ?>
						<div class="btn-prices heading_font">
							<label class="price"><?php esc_html_e( 'Free', 'masterstudy-lms-learning-management-system-pro' ); ?></label>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="progress">
						<div class="progress-bar progress-bar-striped active"
							role="progressbar"
							aria-valuenow="45"
							aria-valuemin="0"
							aria-valuemax="100"
							style="width: <?php echo intval( $progress ); ?>%"></div>
					</div>
					<span class="progress-started"><?php esc_html_e( 'Enrolled', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				<?php endif; ?>

			</li>
		<?php endforeach; ?>
		<li class="explanation" data-text="<?php esc_html_e( 'Login', 'masterstudy-lms-learning-management-system-pro' ); ?>"
			data-target=".stm-lms-modal-prerequisites"
			data-lms-modal="prerequisite">
			<i class="fa fa-question-circle"></i>
			<?php esc_html_e( 'What is Prerequisite courses', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</li>
	</ul>
</div>
