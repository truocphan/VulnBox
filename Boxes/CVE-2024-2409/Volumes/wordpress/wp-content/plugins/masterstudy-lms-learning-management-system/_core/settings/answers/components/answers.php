<?php
stm_lms_register_style( 'admin/questions' );

$quiz_media_type = STM_LMS_Options::get_option( 'quiz_media_type', 'upload' );
?>

<div>

	<!--Wrapper-->
	<div class="stm_lms_questions_v2_wrapper">
		<div class="stm_lms_curriculum_v2 stm_lms_questions_v2">
			<div class="stm-lms-questions">
				<?php
				if ( class_exists( 'STM_LMS_Media_library' ) ) {
					require STM_LMS_PATH . '/settings/media_library/main.php';
				}
				?>

				<!--Simple Question-->
				<transition name="slide-fade">
					<div :class="['stm-lms-questions-single stm-lms-questions-single_choice', view_type]" v-if="choice == 'single_choice' && questions.length">

						<div class="stm-lms-questions-single_answer" v-for="(value, key) in questions">

							<div class="stm-lms-questions-single_input">
								<template v-if="view_type == 'image'">
									<div class="actions">
										<label class="wpcfto_radio" v-bind:class="{'active' : value.isTrue}">
											<span><?php esc_html_e( 'Correct', 'masterstudy-lms-learning-management-system' ); ?></span>
											<input type="radio"
												   v-bind:name="choice + '_' + origin"
												   v-model="correctAnswer"
												   v-bind:value="value.text"
												   @change="isAnswer()"/>
											<i></i>
										</label>
										<div class="extra-actions">
											<div class="actions_single actions_single_info" v-bind:class="infoClass(questions[key]['explain'])">
												<span><i class="fa fa-info"></i> + </span>
												<div class="actions_single_info_popup">
													<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
													<textarea v-model="questions[key]['explain']"
															  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
												</textarea>
												</div>
											</div>
											<div class="actions_single actions_single_delete">
												<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
											</div>
										</div>
									</div>

									<?php
									stm_lms_answers_load_template(
										'image',
										array(
											'quiz_media_type' => $quiz_media_type,
											'image_name'  => 'text_image',
											'description' => 'text',
										)
									);
									?>
								</template>
								<template v-else>
									<input type="text"
										   v-model="questions[key]['text']"
										   placeholder="<?php esc_attr_e( 'Enter answer', 'masterstudy-lms-learning-management-system' ); ?>"
										   v-bind:size="inputSize(questions[key]['text'].length)"/>
								</template>
							</div>

							<div class="actions" v-if="view_type != 'image'">
								<div class="actions_single actions_single_info" v-bind:class="infoClass(questions[key]['explain'])">
									<span><i class="fa fa-info"></i> + </span>
									<div class="actions_single_info_popup">
										<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
										<textarea v-model="questions[key]['explain']"
												  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
										</textarea>
									</div>
								</div>

								<div class="actions_single actions_single_delete">
									<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
								</div>

								<div class="actions_single actions_single_answer">
									<label class="wpcfto_radio" v-bind:class="{'active' : value.isTrue}">
										<span><?php esc_html_e( 'Correct', 'masterstudy-lms-learning-management-system' ); ?></span>
										<input type="radio"
											   v-bind:name="choice + '_' + origin"
											   v-model="correctAnswer"
											   v-bind:value="value.text"
											   @change="isAnswer()"/>
										<i></i>
									</label>
								</div>
							</div>
						</div>

					</div>
				</transition>

				<!--Multi Answer Question-->
				<transition name="slide-fade">
					<div :class="['stm-lms-questions-single stm-lms-questions-multi_choice', view_type]" v-if="choice == 'multi_choice' && questions.length">

						<div class="stm-lms-questions-single_answer" v-for="(value, key) in questions">

							<div class="stm-lms-questions-single_input">
								<template v-if="view_type == 'image'">
									<div class="actions">
										<label class="wpcfto_checkbox" v-bind:class="{'active' : value.isTrue}">
											<span><?php esc_html_e( 'Correct', 'masterstudy-lms-learning-management-system' ); ?></span>
											<input type="checkbox" v-bind:name="choice" v-model="correctAnswers[value.text]"
												   v-bind:value="value.text" @change="isAnswers()"/>
											<i class="fa fa-check"></i>
										</label>
										<div class="extra-actions">
											<div class="actions_single actions_single_info" v-bind:class="infoClass(questions[key]['explain'])">
												<span><i class="fa fa-info"></i> +</span>
												<div class="actions_single_info_popup">
													<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
													<textarea v-model="questions[key]['explain']"
															  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
													</textarea>
												</div>
											</div>
											<div class="actions_single actions_single_delete">
												<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
											</div>
										</div>
									</div>

									<?php
									stm_lms_answers_load_template(
										'image',
										array(
											'quiz_media_type' => $quiz_media_type,
											'image_name'  => 'text_image',
											'description' => 'text',
										)
									);
									?>
								</template>
								<template v-else>
									<input type="text"
										   v-model="questions[key]['text']"
										   placeholder="<?php esc_attr_e( 'Enter answer', 'masterstudy-lms-learning-management-system' ); ?>"
										   v-bind:size="inputSize(questions[key]['text'].length)"/>
								</template>
							</div>

							<div class="actions" v-if="view_type != 'image'">
								<div class="actions_single actions_single_info"
									 v-bind:class="infoClass(questions[key]['explain'])">
									<span><i class="fa fa-info"></i> +</span>
									<div class="actions_single_info_popup">
										<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
										<textarea v-model="questions[key]['explain']"
												  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
										</textarea>
									</div>
								</div>

								<div class="actions_single actions_single_delete">
									<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
								</div>

								<div class="actions_single actions_single_answer">
									<label class="wpcfto_checkbox" v-bind:class="{'active' : value.isTrue}">
										<span><?php esc_html_e( 'Correct', 'masterstudy-lms-learning-management-system' ); ?></span>
										<input type="checkbox" v-bind:name="choice" v-model="correctAnswers[value.text]"
											   v-bind:value="value.text" @change="isAnswers()"/>
										<i class="fa fa-check"></i>
									</label>
								</div>
							</div>

						</div>

					</div>
				</transition>

				<!--True False Question-->
				<transition name="slide-fade">

					<div class="stm-lms-questions-single stm-lms-questions-true_false"
						 v-if="choice == 'true_false' && questions.length">

						<div class="stm-lms-questions-single_answer" v-for="(value, key) in questions">

							<label class="wpcfto_radio" v-bind:class="{'active' : value.isTrue}">
								<input type="radio"
									   v-model="correctAnswer"
									   v-bind:value="value.text"
									   @change="isAnswer()"/>
								<i></i>
								<span>{{ value.text }}</span>
							</label>
						</div>

					</div>
				</transition>

				<!--Item Match Question-->
				<transition name="slide-fade">

					<div class="stm-lms-questions-single stm-lms-questions-item_match"
						 v-if="choice == 'item_match' && questions.length">

						<div class="stm-lms-questions-single_answer" v-for="(value, key) in questions">


							<div class="stm-lms-questions-single_input">

								<div class="row"
									 v-bind:class="{'has-question' : typeof questions[key]['question'] !== 'undefined' && questions[key]['question'].length, 'has-answer' : typeof questions[key]['text'] !== 'undefined' && questions[key]['text'].length}">
									<div class="column column-match">
										<div class="border">
											<h6><?php esc_html_e( 'Question', 'masterstudy-lms-learning-management-system' ); ?></h6>
											<input type="text"
												   placeholder="<?php esc_attr_e( 'Add question...', 'masterstudy-lms-learning-management-system' ); ?>"
												   v-model="questions[key]['question']"/>
										</div>
									</div>
									<div class="column column-answer">
										<div class="border">
											<h6><?php esc_html_e( 'Match', 'masterstudy-lms-learning-management-system' ); ?></h6>
											<input type="text"
												   placeholder="<?php esc_attr_e( 'Add answer...', 'masterstudy-lms-learning-management-system' ); ?>"
												   v-model="questions[key]['text']"/>
										</div>
									</div>
								</div>

							</div>

							<div class="actions">

								<div class="actions_single actions_single_info"
									 v-bind:class="infoClass(questions[key]['explain'])">
							<span>
								<i class="fa fa-info"></i> +
							</span>
									<div class="actions_single_info_popup">
										<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
										<textarea v-model="questions[key]['explain']"
												  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
							</textarea>
									</div>
								</div>

								<div class="actions_single actions_single_delete">
									<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
								</div>

							</div>


						</div>

					</div>
				</transition>

				<!--Image Match Question-->
				<transition name="slide-fade">

					<div :class="['stm-lms-questions-single stm-lms-questions-image_match', view_type]" v-if="choice == 'image_match'">

						<div class="stm-lms-questions-single_answer" v-for="(value, key) in questions" v-if="questions.length">

							<div class="stm-lms-questions-single_input">
								<div class="row"
									 v-bind:class="{'has-question' : typeof questions[key]['question'] !== 'undefined' && questions[key]['question'].length, 'has-answer' : typeof questions[key]['text'] !== 'undefined' && questions[key]['text'].length}">
									<div class="column column-match">
										<div class="border">
											<h6><?php esc_html_e( 'Question', 'masterstudy-lms-learning-management-system' ); ?></h6>
											<?php
											stm_lms_answers_load_template(
												'image',
												array(
													'quiz_media_type' => $quiz_media_type,
													'image_name' => 'question_image',
													'description' => 'question',
												)
											);
											?>
										</div>
									</div>
									<div class="column column-answer">
										<div class="border">
											<h6><?php esc_html_e( 'Match', 'masterstudy-lms-learning-management-system' ); ?></h6>
											<?php
											stm_lms_answers_load_template(
												'image',
												array(
													'quiz_media_type' => $quiz_media_type,
													'image_name' => 'text_image',
													'description' => 'text',
												)
											);
											?>
										</div>
									</div>
								</div>
							</div>

							<div class="actions">
								<div class="actions_single actions_single_info" v-bind:class="infoClass(questions[key]['explain'])">
									<span>
										<i class="fa fa-info"></i> +
									</span>
									<div class="actions_single_info_popup">
										<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
										<textarea v-model="questions[key]['explain']"
												  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
										</textarea>
									</div>
								</div>
								<div class="actions_single actions_single_delete">
									<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
								</div>
							</div>

						</div>

						<div class="stm_lms_answers_container">
							<a class="add_match_button" @click="addMatch()"><i class="fas fa-plus-circle"></i> <?php esc_html_e( 'Add new match', 'masterstudy-lms-learning-management-system' ); ?></a>
						</div>

					</div>

				</transition>

				<!--Keywords Question-->
				<transition name="slide-fade">
					<div class="stm-lms-questions-single stm-lms-questions-keywords"
						 v-if="choice == 'keywords' && questions.length">

						<div class="stm-lms-questions-single_answer stm-lms-questions-single_keyword"
							 v-for="(value, key) in questions">

							<div class="stm-lms-questions-single_input">
								<input type="text"
									   v-model="questions[key]['text']"
									   placeholder="<?php esc_attr_e( 'Enter answer', 'masterstudy-lms-learning-management-system' ); ?>"
									   v-bind:size="inputSize(questions[key]['text'].length)"/>
							</div>

							<div class="actions">

								<div class="actions_single actions_single_info"
									 v-bind:class="infoClass(questions[key]['explain'])">
							<span>
								<i class="fa fa-info"></i> +
							</span>
									<div class="actions_single_info_popup">
										<span><?php esc_html_e( 'Question Explanation', 'masterstudy-lms-learning-management-system' ); ?></span>
										<textarea v-model="questions[key]['explain']"
												  placeholder="<?php esc_attr_e( 'Answer explanation (Will be shown in "Show Answers" section)', 'masterstudy-lms-learning-management-system' ); ?>">
							</textarea>
									</div>
								</div>

								<div class="actions_single actions_single_delete">
									<i class="lnr lnr-trash" @click="deleteAnswer(key)"></i>
								</div>

							</div>

						</div>

					</div>
				</transition>

				<!--Fill the Gap Question-->
				<transition name="slide-fade">
					<div class="stm-lms-questions-single stm-lms-questions-fill_the_gap"
						 v-if="choice == 'fill_the_gap' && questions.length">

						<div class="stm-lms-questions-single_fill_the_gap">
					<textarea v-model="questions[0]['text']"
							  placeholder="<?php esc_attr_e( 'Enter text, separate answers with "|" symbol', 'masterstudy-lms-learning-management-system' ); ?>">

					</textarea>
							<h4>
								<i class="fa fa-info"></i>
								<strong><?php esc_html_e( 'Example:', 'masterstudy-lms-learning-management-system' ); ?></strong>
								<?php esc_html_e( "Deborah was angry at her son. Her son didn't", 'masterstudy-lms-learning-management-system' ); ?>
								<strong>|<?php esc_html_e( 'listen', 'masterstudy-lms-learning-management-system' ); ?>|</strong>
								<?php esc_html_e( 'to her. Her son was 16 years old. Her son', 'masterstudy-lms-learning-management-system' ); ?>
								<strong>|<?php esc_html_e( 'thought', 'masterstudy-lms-learning-management-system' ); ?>|</strong>
								<?php esc_html_e( 'he knew everything. Her son', 'masterstudy-lms-learning-management-system' ); ?>
								<strong>|<?php esc_html_e( 'yelled', 'masterstudy-lms-learning-management-system' ); ?>|</strong>
								<?php esc_html_e( 'at Deborah.', 'masterstudy-lms-learning-management-system' ); ?>
							</h4>
						</div>

					</div>
				</transition>

				<?php if ( get_post_type() !== 'stm-questions' ) : ?>
					<!--Question Bank-->
					<transition name="slide-fade">
						<div class="stm-lms-questions-single stm-lms-questions-question_bank"
							 v-if="choice == 'question_bank'">

							<div class="bank_data" v-if="typeof questions[0] !== 'undefined'">

								<div class="bank_data__single">
									<span><?php esc_html_e( 'Questions:', 'masterstudy-lms-learning-management-system' ); ?></span>
									<strong class="bank_category__single">
										{{questions[0]['number']}}
									</strong>
								</div>

								<div class="bank_data__single">
									<span><?php esc_html_e( 'Categories:', 'masterstudy-lms-learning-management-system' ); ?></span>
									<strong class="bank_category__single"
											v-for="category in questions[0]['categories']">
										{{category.name}}
									</strong>
								</div>

							</div>

						</div>
					</transition>

				<?php endif; ?>

				<div class="stm_lms_answers_container"
					 v-if="choice === 'single_choice' || choice === 'multi_choice' || choice === 'item_match' || choice === 'keywords' || (choice === 'fill_the_gap' && questions.length < 1)">
					<div class="stm_lms_answers_container__input">
						<input type="text"
							   v-model="new_answer"
							   v-bind:class="{'shake-it' : isEmpty}"
							   @keydown.enter.prevent="addAnswer()"
							   placeholder="<?php esc_attr_e( 'Enter new Answer', 'masterstudy-lms-learning-management-system' ); ?>"/>

						<div class="stm_lms_answers_container__submit">
							<a class="button" @click="addAnswer()">
								<?php STM_LMS_Helpers::print_svg( 'assets/svg/enter.svg' ); ?>
							</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
