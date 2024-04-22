<div class="section_data">

	<span class="section_count" @click="openSection(section)">
		<i class="fa fa-chevron-down" v-if="!section.opened"></i>
		<i class="fa fa-chevron-up" v-else></i>
		<?php esc_html_e( 'Section', 'masterstudy-lms-learning-management-system' ); ?> {{section_key + 1}}
	</span>

	<div class="section_data_add">

		<input type="text"
			:ref="'section_' + section_key"
			@keydown.enter.prevent="addSectionTitle(section, section_key)"
			@focus="section.editingSectionTitle = true"
			@blur="section.editingSectionTitle = false"
			class="section_title"
			v-model="section.title"
			placeholder="<?php esc_html_e( 'Section title (not required)', 'masterstudy-lms-learning-management-system' ); ?>"/>

		<div class="section_data_add_title" v-if="section.editingSectionTitle" @click="addSectionTitle(section, section_key)">
			<?php esc_html_e( 'Add section', 'masterstudy-lms-learning-management-system' ); ?>
		</div>

	</div>

	<div class="section_move"
		v-if="!section.editingSectionTitle"
		@mouseover="$set(section, 'hovered', true)"
		@mouseout="$set(section, 'hovered', false)">
		<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/dots.svg' ); ?>
	</div>

	<div class="section_delete"
		v-if="!section.editingSectionTitle && section.opened"
		@click="deleteSection(section_key, section.id, '<?php esc_attr_e( 'Do you really want to delete section with all content?', 'masterstudy-lms-learning-management-system' ); ?>')">
		<i class="fa fa-trash"></i>
	</div>

</div>
