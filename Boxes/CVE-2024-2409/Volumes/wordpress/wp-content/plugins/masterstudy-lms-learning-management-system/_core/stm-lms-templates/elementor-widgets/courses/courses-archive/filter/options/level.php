<?php $metas['level'] = isset( $metas['level'] ) ? $metas['level'] : array(); ?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['levels'] as $levels => $levels_label ) {
			?>
			<div class="ms_lms_courses_archive__filter_options_item_category">
				<label class="ms_lms_courses_archive__filter_options_item_checkbox">
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
						<input type="checkbox" value="<?php echo esc_html( $levels ); ?>" <?php checked( in_array( $levels, $metas['level'] ) ); ?> name="level[]"/>
						<span><i class="fa fa-check"></i></span>
					</span>
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $levels_label ); ?></span>
				</label>
			</div>
		<?php } ?>
	</div>
</div>
