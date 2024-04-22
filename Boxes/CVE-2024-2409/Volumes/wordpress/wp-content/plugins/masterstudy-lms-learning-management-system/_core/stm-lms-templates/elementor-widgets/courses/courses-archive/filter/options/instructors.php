<?php
$metas['instructor'] = isset( $metas['instructor'] ) ? $metas['instructor'] : array();
$limit = 2;
?>
<div class="ms_lms_courses_archive__filter_options_item">
	<div class="ms_lms_courses_archive__filter_options_item_title">
		<h3><?php echo esc_html( $option['label'] ); ?></h3>
		<div class="ms_lms_courses_archive__filter_options_item_title_toggler"></div>
	</div>
	<div class="ms_lms_courses_archive__filter_options_item_content">
		<?php
		foreach ( $option['instructors'] as $index => $instructor ) {
			?>
			<div class="ms_lms_courses_archive__filter_options_item_category <?php echo esc_attr( ( $index > $limit ) ? 'hide_instructor' : '' ); ?>">
				<label class="ms_lms_courses_archive__filter_options_item_checkbox">
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_inner">
						<input type="checkbox" value="<?php echo intval( $instructor->ID ); ?>" <?php checked( in_array( $instructor->ID, $metas['instructor'] ) ); ?> name="instructor[]"/>
						<span><i class="fa fa-check"></i></span>
					</span>
					<span class="ms_lms_courses_archive__filter_options_item_checkbox_label"><?php echo esc_html( $instructor->display_name ); ?></span>
				</label>
			</div>
		<?php } ?>
		<?php if ( count( $option['instructors'] ) - 1 > $limit ) { ?>
			<div class="ms_lms_courses_archive__filter_options_item_show-instructors">
				<i class="lnricons-plus-circle"></i>
				<span><?php esc_html_e( 'Show more', 'masterstudy-lms-learning-management-system' ); ?></span>
			</div>
		<?php } ?>
	</div>
</div>
<?php
