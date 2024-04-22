<div class="stm_lms_splash_wizard__content_tab"
	v-if="active_step === 'curriculum'">
	<h4>
		<?php esc_html_e( 'Curriculum', 'masterstudy-lms-learning-management-system' ); ?>
	</h4>
	<hr/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_image_radio quiz_style">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Quiz style', 'masterstudy-lms-learning-management-system' ),
				'desc'  => esc_html__( 'Choose how to display quizzes: divide questions into pages or display the whole list on one page.', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/radio_image',
				array(
					'model' => 'wizard.quiz_style',
					'value' => 'default',
					'image' => 'assets/img/wizard/quiz/one-page@2x.png?v=1',
					'label' => esc_html__( 'One page', 'masterstudy-lms-learning-management-system' ),
				)
			);
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/radio_image',
				array(
					'model' => 'wizard.quiz_style',
					'value' => 'pagination',
					'image' => 'assets/img/wizard/quiz/pagination@2x.png?v=1',
					'label' => esc_html__( 'Pagination', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>
		</div>
	</div>
	<hr v-if="isMarketPlace()"/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch wide"
		v-if="isMarketPlace()"
		v-bind:class="{'inactive' : !wizard.allow_upload_video}">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Allow instructors to upload video files to video lessons', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/switcher',
				array(
					'model' => 'wizard.allow_upload_video',
				)
			);
			?>
		</div>
	</div>
	<hr v-if="isMarketPlace()"/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch wide"
		v-if="isMarketPlace()"
		v-bind:class="{'inactive' : !wizard.course_allow_new_categories}">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Allow instructors to create new categories', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/switcher',
				array(
					'model' => 'wizard.course_allow_new_categories',
				)
			);
			?>
		</div>
	</div>
</div>
