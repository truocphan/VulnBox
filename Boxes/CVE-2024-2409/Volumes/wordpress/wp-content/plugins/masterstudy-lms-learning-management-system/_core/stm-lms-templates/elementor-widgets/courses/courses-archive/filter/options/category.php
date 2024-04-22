<?php $parents = array(); ?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['terms'] as $term ) {
			$parents[] = $term->term_id;
			?>
			<div class="ms_lms_courses_archive__filter_options_item_category">
				<label class="ms_lms_courses_archive__filter_options_item_checkbox">
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
						<input type="checkbox" value="<?php echo intval( $term->term_id ); ?>" <?php checked( in_array( $term->term_id, $terms ) ); ?> name="category[]"/>
						<span><i class="fa fa-check"></i></span>
					</span>
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $term->name ); ?></span>
				</label>
			</div>
		<?php } ?>
	</div>
</div>
<?php
set_transient( 'ms_lms_courses_archive_parent_categories', $parents );
