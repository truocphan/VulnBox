<div class="add_items" v-if="section.opened">

	<div class="add_items_nav">

		<?php if ( apply_filters( 'stm_lms_allow_add_lesson', true ) ) : ?>
			<div class="add_item_nav_title lessons" @click="$set(section, 'activeTab', 'stm-lessons')" v-bind:class="{'active' : section.activeTab === 'stm-lessons'}">
				<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/text.svg' ); ?><?php esc_html_e( 'Lesson', 'masterstudy-lms-learning-management-system' ); ?>
			</div>
		<?php endif; ?>

		<div class="add_item_nav_title quizzes" @click="$set(section, 'activeTab', 'stm-quizzes')" v-bind:class="{'active' : section.activeTab === 'stm-quizzes'}">
			<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/quiz.svg' ); ?><?php esc_html_e( 'Quiz', 'masterstudy-lms-learning-management-system' ); ?>
		</div>

		<?php if ( class_exists( 'STM_LMS_Assignments' ) ) : ?>

			<div class="add_item_nav_title assignments" @click="$set(section, 'activeTab', 'stm-assignments')" v-bind:class="{'active' : section.activeTab === 'stm-assignments'}">
				<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/assignment.svg' ); ?><?php esc_html_e( 'Assignment', 'masterstudy-lms-learning-management-system' ); ?>
			</div>

		<?php endif; ?>

		<div class="add_item_nav_title search" @click="$set(section, 'search', true)" v-bind:class="{'active' : section.activeTab === 'search'}">
			<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/search.svg' ); ?><?php esc_html_e( 'Search', 'masterstudy-lms-learning-management-system' ); ?>
		</div>

	</div>

	<curriculum_add_item inline-template :type.sync="section.activeTab" v-if="section.activeTab !== 'search'" v-on:curriculum_item_added="itemAdded(section, $event)">
		<?php stm_lms_curriculum_v2_load_template( 'add_item' ); ?>
	</curriculum_add_item>

	<curriculum_search inline-template v-if="section.search" :sections="sections" :section="section" :current_course_id="<?php echo esc_attr( get_the_ID() ); ?>" v-on:close_popup="$set(section, 'search', false)">
		<?php stm_lms_curriculum_v2_load_template( 'search' ); ?>
	</curriculum_search>

</div>
