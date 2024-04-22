<?php
/**
 *
 * @var $udemy_meta
 */

if ( ! empty( $udemy_meta['udemy_objectives'] ) ) :
	$objectives = $udemy_meta['udemy_objectives']; ?>
	<div class="stm_lms_course_objectives">
		<div class="stm_lms_course_objectives__inner">
			<?php foreach ( $objectives as $objective ) : ?>
				<div class="stm_lms_course_objectives__single">
					<div class="stm_lms_course_objectives__single_text">
						<i class="far fa-check-circle primary_color"></i>
						<?php echo wp_kses_post( $objective ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
endif;
