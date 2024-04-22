<?php
/**
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $field_data
 * @var $section_name
 */
wp_enqueue_script( 'stm-lms-page-generator', STM_LMS_URL . '/settings/page_generator/js/generator.js', array(), STM_LMS_VERSION, true );
stm_lms_register_style( 'admin/page_generator' );

$has_pages           = stm_lms_has_generated_pages( $field_data['options'] );
$has_elementor_pages = stm_lms_has_generated_elementor_pages( stm_lms_elementor_page_list() );
if ( ! $has_pages || ! $has_elementor_pages ) { ?>
	<stm_lms_page_generator v-bind:field_data="<?php echo esc_attr( $field ); ?>['options']" inline-template>
		<div class="stm_lms_page_generator">
			<p><?php esc_html_e( 'Create LMS system pages automatically. Dont forget to re-save permalinks after operation.' ); ?></p>
			<a href="#" class="button" @click.prevent="generatePages" v-bind:class="{'loading' : loading}">
				<span><?php esc_html_e( 'Generate pages', 'masterstudy-lms-learning-management-system' ); ?></span>
				<i class="lnr lnr-sync"></i>
			</a>
		</div>
	</stm_lms_page_generator>
	<?php
}
