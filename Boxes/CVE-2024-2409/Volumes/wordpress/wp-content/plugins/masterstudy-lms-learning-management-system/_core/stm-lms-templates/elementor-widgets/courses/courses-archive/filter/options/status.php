<?php $metas['status'] = isset( $metas['status'] ) ? $metas['status'] : array(); ?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['statuses'] as $status => $status_label ) {
			?>
			<div class="ms_lms_courses_archive__filter_options_item_category">
				<label class="ms_lms_courses_archive__filter_options_item_checkbox">
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
						<input type="checkbox" value="<?php echo esc_html( $status ); ?>" <?php checked( in_array( $status, $metas['status'] ) ); ?> name="status[]"/>
						<span><i class="fa fa-check"></i></span>
					</span>
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $status_label ); ?></span>
				</label>
			</div>
		<?php } ?>
	</div>
</div>
