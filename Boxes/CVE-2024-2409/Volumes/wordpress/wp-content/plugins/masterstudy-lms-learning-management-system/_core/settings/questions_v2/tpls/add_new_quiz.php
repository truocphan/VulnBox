<div class="add_item" v-if="tab === 'new_quiz'">
<?php
/** @var  $allow_new_question_categories from settings
 * PS - bad practice
 */
$allow_new_question_categories = STM_LMS_Options::get_option( 'course_allow_new_question_categories', false );
?>
	<div class="stm_lms_add_new_question">

		<div class="stm_lms_row">
			<div class="question_types_input">
				<div class="question_types_input_label">
					<span v-html="choices[question.type]"></span>
					<i class="fa fa-chevron-down"></i>

					<div class="question_types_wrapper">
						<div class="question_types">
							<div class="question_type"
								v-for="(choice_label, choice_key) in choices"
								v-html="choice_label"
								v-bind:class="{'active' : question.type === choice_key}"
								@click="question.type = choice_key">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="question_category_input">

				<div class="question_choosen_category_list">
					<span class="question_choosen_category_item" v-for="q_category in question.categories" :key="q_category.id">
						{{ q_category.name }}
						<i @click="removeQuestionCategory(q_category.id)" class="stm-ms-remove-icon"></i>
					</span>
					<span class="question_new_category">
						<input @keyup.enter="createQuestionCategory()" v-model="new_question_category" placeholder="<?php esc_html_e( 'Question category', 'masterstudy-lms-learning-management-system' ); ?>" type="text" />
					</span>
				</div>

				<span v-if="isAddQuestionCategory && allowNewQuestionCategories" @click="createQuestionCategory()" title='<?php esc_html_e( 'Create question category', 'masterstudy-lms-learning-management-system' ); ?>' class="add_question_category">
					<?php esc_html_e( 'Add', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				<div class="question_category_list_wrapper" v-if="categories.length > 0">
					<ul class="question_category_list">
						<li v-if="!question.categories.map(q_category => q_category.id ).includes(category.id)" @click="addQuestionCategory( category )" ref="bar"  v-for="category in categories" :key="category.id"
							class='question_category_list_item' >
							{{ category.name }}
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stm_lms_row">
			<input type="text"
					v-model="question.title"
					@keydown.enter.prevent="createQuestion()"
					v-bind:placeholder="'<?php esc_html_e( 'Enter question title', 'masterstudy-lms-learning-management-system' ); ?>'"/>
			<a href="#"
				class="add_question_button"
				@click.prevent="createQuestion()">
			<span>
				<?php esc_html_e( 'Add question', 'masterstudy-lms-learning-management-system' ); ?>
			</span>
			</a>
		</div>

	</div>

</div>
