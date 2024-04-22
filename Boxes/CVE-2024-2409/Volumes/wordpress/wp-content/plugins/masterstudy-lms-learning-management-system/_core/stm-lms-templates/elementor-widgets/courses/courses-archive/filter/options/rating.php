<?php $metas['rating'] = isset( $metas['rating'] ) ? $metas['rating'] : ''; ?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['ratings'] as $rating ) {
			?>
			<label class="ms_lms_courses_archive__filter_options_item_category">
				<span class="ms_lms_courses_archive__filter_options_item_radio">
					<input type="radio" name="rating" value="<?php echo floatval( $rating['rate'] ); ?>" <?php checked( floatval( $rating['rate'] ) === $metas['rating'] ); ?>>
					<span class="ms_lms_courses_archive__filter_options_item_radio_fake"></span>
				</span>
				<div class="ms_lms_courses_archive__filter_options_item_rating">
					<div class="ms_lms_courses_archive__filter_options_item_rating_stars">
						<div class="ms_lms_courses_archive__filter_options_item_rating_stars_filled" style="width: <?php echo esc_attr( round( $rating['rate'] * 100 / 5, 2 ) ); ?>%;"></div>
					</div>
					<div class="ms_lms_courses_archive__filter_options_item_rating_quantity">
						<span><?php echo esc_html( $rating['label'] ); ?></span>
					</div>
				</div>
			</label>
		<?php } ?>
	</div>
</div>
