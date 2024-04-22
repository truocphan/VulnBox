<?php

/**
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $section_name
 *
 */

// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
wp_enqueue_script( 'stm-lms-curriculum', STM_LMS_URL . '/settings/curriculum/js/curriculum.js', null, stm_lms_custom_styles_v() );
// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
wp_enqueue_script( 'stm-lms-curriculum-add-item', STM_LMS_URL . '/settings/curriculum/js/add_item.js', null, stm_lms_custom_styles_v() );
// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
wp_enqueue_script( 'stm-lms-curriculum-search', STM_LMS_URL . '/settings/curriculum/js/search.js', null, stm_lms_custom_styles_v() );
stm_lms_register_style( 'admin/curriculum_v2' );

?>

	<curriculum inline-template
				v-on:curriculum_changed="<?php echo esc_attr( $field_value ); ?> = $event"
				v-bind:current_course_id="<?php echo esc_attr( get_the_ID() ); ?>">

		<div class="stm_lms_curriculum_v2_wrapper" v-bind:class="{'loaded' : loaded, 'dragging' : onDrag}">

			<div v-if="loading">
				<?php esc_html_e( 'Loading curriculum...', 'masterstudy-lms-learning-management-system' ); ?>
			</div>

			<div class="stm_lms_curriculum_v2" v-else>

				<draggable :list="sections"
					class="sections dragArea"
					:options="{ group: 'section'}"
					@start="startDrag"
					@end="endDrag"
					@change="reorderSection($event)"
					handle=".section_move">

					<div class="section" v-for="(section, section_key) in sections" v-bind:class="{'hovered' : section.hovered}">

						<?php stm_lms_curriculum_v2_load_template( 'section_data' ); ?>

						<?php stm_lms_curriculum_v2_load_template( 'section_items' ); ?>

						<?php stm_lms_curriculum_v2_load_template( 'add_items' ); ?>

					</div>

				</draggable>

				<?php stm_lms_curriculum_v2_load_template( 'add_section' ); ?>

			</div>

		</div>

	</curriculum>

<?php if ( ! empty( $field_name ) ) : ?>
	<!-- Here We store actual value in hidden input -->
	<!-- Mostly it needed for metabox area, where WordPress saves field automatically after post update -->
	<input type="hidden"
		name="<?php echo esc_attr( $field_name ); ?>"
		v-bind:placeholder="<?php echo esc_attr( $field_label ); ?>"
		v-bind:id="'<?php echo esc_attr( $field_id ); // phpcs:ignore Squiz.PHP.EmbeddedPhp.ContentAfterOpen ?>'"
		v-model="<?php echo esc_attr( $field_value ); ?>"/>
	<?php
endif;
