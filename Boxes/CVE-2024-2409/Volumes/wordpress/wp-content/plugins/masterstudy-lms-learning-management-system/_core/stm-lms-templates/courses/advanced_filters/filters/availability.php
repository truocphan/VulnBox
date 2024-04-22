<?php

$availability = sanitize_text_field( $_GET['availability'] ?? '' );
$statuses     = array(
	'all'           => esc_html__( 'All', 'masterstudy-lms-learning-management-system' ),
	'available_now' => esc_html__( 'Available Now', 'masterstudy-lms-learning-management-system' ),
	'coming_soon'   => esc_html__( 'Upcoming', 'masterstudy-lms-learning-management-system' ),
);
?>
<div class="stm_lms_courses__filter stm_lms_courses__search">
	<div class="stm_lms_courses__filter_heading">
		<h3><?php esc_html_e( 'Availability', 'masterstudy-lms-learning-management-system' ); ?></h3>
		<div class="toggler"></div>
	</div>
	<div class="stm_lms_courses__filter_content" style="display: none;">
		<?php foreach ( $statuses as $status => $status_label ) : ?>
			<div class="stm_lms_courses__filter_availability">
				<label class="stm_lms_styled_checkbox">
					<span class="stm_lms_styled_checkbox__inner">
						<input type="radio"
						<?php
						if ( $availability === $status ) {
							echo 'checked="checked"';
						}
						?>
							value="<?php echo esc_html( $status ); ?>"
							name="availability"/>
						<span><i class="fa fa-check"></i> </span>
					</span>
					<span><?php echo esc_html( $status_label ); ?></span>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php
