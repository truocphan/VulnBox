<?php $metas['price'] = isset( $metas['price'] ) ? $metas['price'] : array(); ?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['prices'] as $price => $price_label ) {
			?>
			<div class="ms_lms_courses_archive__filter_options_item_category">
				<label class="ms_lms_courses_archive__filter_options_item_checkbox">
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
						<input type="checkbox" value="<?php echo esc_html( $price ); ?>" <?php checked( in_array( $price, $metas['price'] ) ); ?> name="price[]"/>
						<span><i class="fa fa-check"></i></span>
					</span>
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $price_label ); ?></span>
				</label>
			</div>
		<?php } ?>
	</div>
</div>
