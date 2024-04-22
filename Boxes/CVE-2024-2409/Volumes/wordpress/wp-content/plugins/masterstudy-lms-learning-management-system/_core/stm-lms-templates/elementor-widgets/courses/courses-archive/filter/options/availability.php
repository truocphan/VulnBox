<?php
$metas['option'] = $metas['availability'] ?? '';
?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3> <?php echo esc_html( $option['label'] ); ?> </h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>

	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['options'] as $status => $status_label ) {
			?>
			<label class="ms_lms_courses_archive__filter_options_item_category">
				<span class="ms_lms_courses_archive__filter_options_item_radio">
					<input type="radio" name="availability" value="<?php echo esc_html( $status ); ?>"
						<?php
						if ( $metas['option'] === $status ) {
							echo 'checked="checked"';
						}
						?>
					>
					<span class="ms_lms_courses_archive__filter_options_item_radio_fake"></span>
				</span>
				<div class="ms_lms_courses_archive__filter_options_item_rating_quantity">
					<span><?php echo esc_html( $status_label ); ?></span>
				</div>
			</label>
		<?php } ?>
	</div>
</div>
