<?php
$parents = get_transient( 'ms_lms_courses_archive_parent_categories' );
$terms   = STM_LMS_Courses::get_courses_child_terms( $parents );
?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php foreach ( $terms as $term ) { ?>
			<div class="ms_lms_courses_archive__filter_options_item_subcategory">
				<h5><?php echo esc_html( $term['parent_name'] ); ?></h5>
				<?php foreach ( $term['category_terms'] as $item ) { ?>
					<div class="ms_lms_courses_archive__filter_options_item_category">
						<label class="ms_lms_courses_archive__filter_options_item_checkbox">
							<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
								<input type="checkbox" value="<?php echo intval( $item->term_id ); ?>" <?php checked( in_array( $item->term_id, $terms ) ); ?> name="subcategory[]"/>
								<span><i class="fa fa-check"></i></span>
							</span>
							<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $item->name ); ?></span>
						</label>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>
<?php
set_transient( 'ms_lms_courses_archive_parent_categories', $parents );
