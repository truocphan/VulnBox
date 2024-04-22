<?php
stm_lms_register_style( 'teacher' );
if ( ! empty( $udemy_meta['udemy_visible_instructors'] ) ) :
	$instructor = $udemy_meta['udemy_visible_instructors'];
	if ( empty( $instructor[0] ) ) {
		return false;
	}
	$instructor = $instructor[0];
	?>


	<a href="<?php echo esc_url( "https://www.udemy.com{$instructor['url']}" ); ?>" target="_blank">
		<div class="meta-unit teacher clearfix">
			<div class="pull-left">
				<img src="<?php echo esc_url( $instructor['image_100x100'] ); ?>">
			</div>
			<div class="meta_values">
				<div class="label h6"><?php esc_html_e( 'Instructor:', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
				<div class="value heading_font h6"><?php echo sanitize_text_field( $instructor['display_name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
		</div>
	</a>

<?php else : ?>
	<?php STM_LMS_Templates::show_lms_template( 'course/parts/panel_info/teacher' ); ?>
<?php endif; ?>
