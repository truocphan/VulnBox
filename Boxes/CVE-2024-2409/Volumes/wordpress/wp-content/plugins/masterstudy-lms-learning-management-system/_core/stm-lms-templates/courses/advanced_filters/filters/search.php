<?php
$value          = ( ! empty( $_GET['search'] ) ) ? sanitize_text_field( $_GET['search'] ) : '';
$action         = '';
$filter_enabled = STM_LMS_Courses::filter_enabled();
/** get action for search if Filter disabled */
if ( false == $filter_enabled ) {
	$action = get_permalink( STM_LMS_Options::courses_page() );
}
?>
<div class="stm_lms_courses__search">
	<input data-action="<?php echo esc_attr( $action ); ?>" id='lms-search-input' type="text" name="search" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Search Courses', 'masterstudy-lms-learning-management-system' ); ?>"/>
	<button class="lms-search-btn" id="lms-search-btn">
		<i class="fa fa-search"></i>
	</button>
</div>
