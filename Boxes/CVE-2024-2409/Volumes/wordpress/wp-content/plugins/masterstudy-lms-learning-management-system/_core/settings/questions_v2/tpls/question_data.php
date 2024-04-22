<template v-if="typeof item !== 'undefined'">

	<?php if ( class_exists( 'STM_LMS_Media_Library' ) ) {
		require STM_LMS_PATH . '/settings/media_library/main.php';
	} ?>

	<div class="section_data">
		<div class="section_data__title">
			<i class="fa fa-chevron-down" v-if="item.opened" @click="$set(item, 'opened', false)"></i>
			<i class="fa fa-chevron-up" @click="$set(item, 'opened', true)" v-else></i>

			<input type="text" v-model="items[item_key]['title']"
					v-bind:size="items[item_key]['title'].length + 2"
					@blur="changeTitle(item.id, items[item_key]['title'], item_key)">
		</div>

		<div class="section_data__actions">

			<div v-if="( Object.keys(categories).length === 0 )" class="question_category_input">
				<label class="no-category">
					<?php esc_html_e( 'No Category', 'masterstudy-lms-learning-management-system' ); ?>
				</label>
			</div>
			<div v-else class="question_category_input">
				<label>
					<?php esc_html_e( 'Category', 'masterstudy-lms-learning-management-system' ); ?>:
				</label>
				<div v-if="item.hasOwnProperty('is_edit') && item.is_edit" class="question_choosen_category_list">
					<span class="question_choosen_category_item" v-for="q_category in item.categories" :key="q_category.id">
						{{ q_category.name }}
						<i @click="$set(item, 'categories', getUpdatedCategories( q_category, item.id, 'remove' ))" class="stm-ms-remove-icon"></i>
					</span>
				</div>
				<div v-else class="question_category_input_label">
					<span v-html="getQuestionCategoriesNames(item.categories)"></span>
				</div>
				<i class="fa fa-pen" @click="updateQuestionCategory( item )"></i>
				<div v-if="item.hasOwnProperty('is_edit') && item.is_edit" class="question_category_list_wrapper">
					<ul class="question_category_list">
						<li v-if="!item.categories.map(q_category => q_category.term_id ).includes(category.id)"
							@click="$set(item, 'categories', getUpdatedCategories( category, item.id, 'add' ));"
							v-for="category in categories" :key="category.id"
							class='question_category_list_item' >
							{{ category.name }}
						</li>
					</ul>
				</div>
			</div>

			<div class="question_types_input" v-if="typeof choices[item.type] !== 'undefined'">
				<div class="question_types_input_label">
					<span v-html="choices[item.type]"></span>
					<i class="fa fa-chevron-down"></i>

					<div class="question_types_wrapper">
						<div class="question_types">
							<div class="question_type"
								v-for="(choice_label, choice_key) in choices"
								v-html="choice_label"
								v-bind:class="{'active' : item.type === choice_key}"
								@click="$set(item, 'type', choice_key)">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="question_image" v-bind:class="{'opened' : typeof item.image_opened !== 'undefined' && item.image_opened, 'filled' : typeof item.image !== 'undefined' && item.image.id}">
				<div class="question_image__btn" @click="$set(item, 'image_opened', !item.image_opened)">
					<img :src="item.image.url" v-if="typeof item.image !== 'undefined' && item.image.url">
					+<i class="fa fa-image"></i>
				</div>

				<div class="question_image__popup_wrapper" v-if="item.image_opened">
					<?php stm_lms_questions_v2_load_template( 'image' ); ?>
				</div>
			</div>

			<div class="question_delete"
				@click="deleteQuestion(item_key, '<?php esc_attr_e( 'Do you really want to delete this question?', 'masterstudy-lms-learning-management-system' ); ?>')">
				<i class="fa fa-trash"></i>
			</div>

			<div class="question_move">
				<?php STM_LMS_Helpers::print_svg( 'settings/curriculum/images/dots.svg' ); ?>
			</div>
		</div>
	</div>

	<div class="section_data_view_type"
		v-if="['single_choice', 'multi_choice', 'image_match'].includes(item.type)">
		<div class="section_data_view_type_title">
			<template v-if="item.type == 'image_match'">
				<?php esc_html_e( 'View Type', 'masterstudy-lms-learning-management-system' ); ?>
			</template>
			<template v-else>
				<?php esc_html_e( 'Answers', 'masterstudy-lms-learning-management-system' ); ?>
			</template>
		</div>

		<div>
			<div class="wpcfto_field_hint text">
				<i class="fa fa-info-circle"></i>
				<div class="hint"><?php esc_html_e( 'Uploading images with the same dimensions is highly recommended! ', 'masterstudy-lms-learning-management-system' ); ?></div>
			</div>

			<template v-if="item.type == 'image_match'">
				<span :class="['grid_view', {'active': item.question_view_type == 'grid'}]" @click="$set(item, 'question_view_type', 'grid')">
					<i class="fas fa-grip-horizontal"></i>
				</span>
					<span :class="['list_view', {'active': item.question_view_type != 'grid'}]" @click="$set(item, 'question_view_type', 'list')">
					<i class="fas fa-grip-vertical"></i>
				</span>
			</template>
			<template v-if="['single_choice', 'multi_choice'].includes(item.type)">
				<span :class="['image_view', {'active': item.question_view_type == 'image'}]" @click="$set(item, 'question_view_type', 'image')">
					<i class="fas fa-image"></i>
				</span>
					<span :class="['list_view', {'active': item.question_view_type != 'image'}]" @click="$set(item, 'question_view_type', 'list')">
					<i class="fas fa-list"></i>
				</span>
			</template>
		</div>
	</div>
</template>
