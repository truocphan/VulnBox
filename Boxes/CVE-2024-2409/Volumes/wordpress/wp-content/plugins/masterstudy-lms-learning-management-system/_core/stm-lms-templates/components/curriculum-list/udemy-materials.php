<?php
/**
 * @var int $course_id
 * @var array $curriculum
 * @var boolean $dark_mode
 */

$material_number = 0;
$curriculum      = function_exists( 'masterstudy_formatting_udemy_curriculum' )
	? masterstudy_formatting_udemy_curriculum( $curriculum['results'] )
	: array();

if ( empty( $curriculum ) ) {
	return;
}

foreach ( $curriculum as $section ) {
	?>
	<div class="masterstudy-curriculum-list__wrapper masterstudy-curriculum-list__wrapper_opened">
		<div class="masterstudy-curriculum-list__section">
			<h4 class="masterstudy-curriculum-list__section-title"><?php echo esc_html( $section['title'] ); ?></h4>
			<span class="masterstudy-curriculum-list__toggler"></span>
		</div>
		<ul class="masterstudy-curriculum-list__materials">
			<?php
			foreach ( $section['materials'] as $material ) {
				$material_number++;
				$material_excerpt = $material['description'] ?? '';
				if ( 'quiz' === $material['_class'] ) {
					$icon          = 'quiz';
					$material_type = esc_html__( 'Quiz', 'masterstudy-lms-learning-management-system-pro' );
				} else {
					$icon          = 'text';
					$material_type = esc_html__( 'Text lesson', 'masterstudy-lms-learning-management-system-pro' );
					$asset_type    = ( ! empty( $material['asset'] ) ) ? $material['asset']['asset_type'] : 'Article';
					if ( 'Video' === $asset_type ) {
						$icon          = 'video';
						$material_type = esc_html__( 'Video lesson', 'masterstudy-lms-learning-management-system-pro' );
					}
				}
				?>
				<li class="masterstudy-curriculum-list__item">
					<a href="#"
						class="masterstudy-curriculum-list__link masterstudy-curriculum-list__link_disabled">
						<div class="masterstudy-curriculum-list__order">
							<?php echo esc_html( $material_number ); ?>
						</div>
						<img src="<?php echo esc_url( STM_LMS_URL . "/assets/icons/lessons/{$icon}.svg" ); ?>" class="masterstudy-curriculum-list__image">
						<div class="masterstudy-curriculum-list__container">
							<div class="masterstudy-curriculum-list__container-wrapper">
								<div class="masterstudy-curriculum-list__title">
									<?php echo esc_html( $material['title'] ); ?>
								</div>
								<div class="masterstudy-curriculum-list__meta-wrapper">
									<span class="masterstudy-curriculum-list__meta">
										<?php echo esc_html( $material_type ); ?>
									</span>
									<?php if ( ! empty( $material_excerpt ) ) { ?>
										<span class="masterstudy-curriculum-list__excerpt-toggler"></span>
									<?php } ?>
								</div>
							</div>
							<?php if ( ! empty( $material_excerpt ) ) { ?>
								<div class="masterstudy-curriculum-list__excerpt">
									<div class="masterstudy-curriculum-list__excerpt-wrapper">
										<?php echo wp_kses_post( $material_excerpt ); ?>
									</div>
								</div>
							<?php } ?>
						</div>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php
}
