<?php
if ( ! empty( $categories ) ) {
	$tax_args = array(
		'taxonomy'   => 'stm_lms_course_taxonomy',
		'hide_empty' => false,
		'parent'     => 0,
	);
	$parent_terms = get_terms( $tax_args );
}
if ( ! empty( $popup ) ) { ?>
	<a href="#" class="ms_lms_course_search_box__popup_button">
		<i class="lnricons-magnifier"></i>
	</a>
	<div class="ms_lms_course_search_box__popup <?php echo ( ! empty( $popup_presets ) ) ? esc_attr( $popup_presets ) : ''; ?>">
	<?php
	if ( ! empty( $popup_presets ) && 'with_wrapper' === $popup_presets ) {
		?>
		<div class="ms_lms_course_search_box__popup_wrapper">
		<?php
	}
}
?>
<div class="ms_lms_course_search_box">
	<?php
	if ( ! empty( $presets ) && 'search_button_compact' === $presets ) {
		?>
		<div class="ms_lms_course_search_box_compact <?php echo ( ! empty( $compact_direction ) ) ? esc_attr( $compact_direction ) : ''; ?>">
			<div class="ms_lms_course_search_box_compact_wrapper closed">
		<?php
	}
	if ( ! empty( $parent_terms ) ) {
		?>
		<div class="ms_lms_course_search_box__categories <?php echo ( ! empty( $categories_button_align ) ) ? esc_attr( $categories_button_align ) : ''; ?>">
			<i class="stmlms-hamburger"></i>
			<span><?php esc_html_e( 'Category', 'masterstudy' ); ?></span>
			<div class="ms_lms_course_search_box__categories_dropdown <?php echo ( ! empty( $categories_dropdown_align ) ) ? esc_attr( $categories_dropdown_align ) : ''; ?>">
				<?php
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/courses-searchbox/parts/dropdown-parents',
					array(
						'parent_terms' => $parent_terms,
					)
				);
				STM_LMS_Templates::show_lms_template(
					'elementor-widgets/courses-searchbox/parts/dropdown-childs',
					array(
						'parent_terms' => $parent_terms,
					)
				);
				?>
			</div>
		</div>
		<?php
	}
	STM_LMS_Templates::show_lms_template(
		'elementor-widgets/courses-searchbox/parts/search-input',
		array(
			'parent_terms'       => ( ! empty( $parent_terms ) ) ? $parent_terms : array(),
			'presets'            => $presets,
			'search_placeholder' => $search_placeholder,
		)
	);
	if ( ! empty( $presets ) && 'search_button_compact' === $presets ) {
		?>
			</div>
		</div>
		<a :href="'<?php echo esc_url( STM_LMS_Course::courses_page_url() ); ?>?search=' + url" class="ms_lms_course_search_box__compact_button opening">
			<i class="lnricons-magnifier"></i>
		</a>
		<?php
	}
	?>
</div>
<?php
if ( ! empty( $popup ) ) {
	if ( ! empty( $popup_presets ) && 'with_wrapper' === $popup_presets ) {
		?>
		</div>
	<?php } ?>
	</div>
	<?php
}
