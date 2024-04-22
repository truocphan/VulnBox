<div class="section_items" v-if="section.opened">

	<section_items inline-template :materials="section.materials" :current_course_id="<?php echo esc_attr( get_the_ID() ); ?>">

		<draggable :list="materials"
				v-bind:class="'count_' + materials.length"
				class="dragArea items"
				@start="startDrag"
				@end="endDrag"
				@change="itemReordered($event, section.id)"
				handle=".item_move"
				:options="{ group: 'member', dragoverBubble: true }">

			<?php stm_lms_curriculum_v2_load_template( 'item' ); ?>

		</draggable>

	</section_items>

</div>
