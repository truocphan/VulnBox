<div class="item"
	v-for="(item, item_key) in materials"
	@mouseover="$set(item, 'class', 'hovered')"
	@mouseout="item.class = ''"
	v-bind:class="[item.class, onDrag ? 'isOnDrag' : '', item.post_type]">

	<div class="item_move">
		<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/dots.svg' ); ?>
	</div>

	<div class="item_icon" v-bind:class="item.post_type">

		<span v-if="item.post_type==='stm-assignments'">
			<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/assignment.svg' ); ?>
		</span>

		<span v-if="item.post_type==='stm-quizzes'">
			<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/quiz.svg' ); ?>
		</span>

		<span v-if="item.post_type==='stm-lessons'">
			<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/text.svg' ); ?>
		</span>

	</div>

	<div class="title">
		<input v-model="item.title" @blur="itemChanged(item)" :size="item.title.length"/>
	</div>

	<div class="actions">

		<div class="item_delete" @click="deleteItem(item_key, item.id, '<?php esc_attr_e( 'Do you really want to delete this item from section?', 'masterstudy-lms-learning-management-system' ); ?>')">
		<i class="fa fa-trash"></i>
		</div>

		<div class="item_edit">
			<a class="item_edit_link" :href="item.edit_link" target="_blank">
				<i class="fa fa-pen"></i>
				<?php esc_html_e( 'Edit', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>

	</div>

</div>
