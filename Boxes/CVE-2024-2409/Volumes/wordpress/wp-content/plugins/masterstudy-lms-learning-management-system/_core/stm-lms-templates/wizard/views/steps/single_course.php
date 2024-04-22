<div class="stm_lms_splash_wizard__content_tab"
	v-if="active_step === 'single_course'">
	<h4>
		<?php esc_html_e( 'Single Course', 'masterstudy-lms-learning-management-system' ); ?>
	</h4>
	<hr/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch"
		v-bind:class="{'inactive' : !wizard.redirect_after_purchase}">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Redirect to Checkout', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/switcher',
				array(
					'model' => 'wizard.redirect_after_purchase',
					'desc'  => esc_html__( 'Enable redirect to the checkout page after the course was added to cart.', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>
		</div>
	</div>
	<hr/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_checkboxes stm_lms_splash_wizard__field_splitted">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Course tabs', 'masterstudy-lms-learning-management-system' ),
				'desc'  => esc_html__( 'Turn on the courses’ tabs display to improve the navigation for users and to show additional information. The tabs include Description, Curriculum, FAQ, Announcement, Reviews.', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<div class="stm_lms_splash_wizard__field_checks">
				<?php
				STM_LMS_Templates::show_lms_template(
					'wizard/fields/checkbox',
					array(
						'model' => 'wizard.course_tab_description',
						'label' => esc_html__( 'Description', 'masterstudy-lms-learning-management-system' ),
					)
				);
				STM_LMS_Templates::show_lms_template(
					'wizard/fields/checkbox',
					array(
						'model' => 'wizard.course_tab_curriculum',
						'label' => esc_html__( 'Curriculum', 'masterstudy-lms-learning-management-system' ),
					)
				);
				STM_LMS_Templates::show_lms_template(
					'wizard/fields/checkbox',
					array(
						'model' => 'wizard.course_tab_faq',
						'label' => esc_html__( 'FAQ', 'masterstudy-lms-learning-management-system' ),
					)
				);
				STM_LMS_Templates::show_lms_template(
					'wizard/fields/checkbox',
					array(
						'model' => 'wizard.course_tab_announcement',
						'label' => esc_html__( 'Announcement', 'masterstudy-lms-learning-management-system' ),
					)
				);
				STM_LMS_Templates::show_lms_template(
					'wizard/fields/checkbox',
					array(
						'model' => 'wizard.course_tab_reviews',
						'label' => esc_html__( 'Reviews', 'masterstudy-lms-learning-management-system' ),
					)
				);
				?>
			</div>
		</div>
	</div>
	<hr v-if="isPro()"/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_image_radio stm_lms_splash_wizard__field_splitted"
		v-if="isPro()">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Course style', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/radio_image',
				array(
					'model' => 'wizard.course_style',
					'value' => 'default',
					'image' => 'assets/img/wizard/courses/default.png?v=1',
					'label' => esc_html__( 'Default', 'masterstudy-lms-learning-management-system' ),
				)
			);
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/radio_image',
				array(
					'model' => 'wizard.course_style',
					'value' => 'classic',
					'image' => 'assets/img/wizard/courses/classic.png?v=1',
					'label' => esc_html__( 'Classic', 'masterstudy-lms-learning-management-system' ),
				)
			);
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/radio_image',
				array(
					'model' => 'wizard.course_style',
					'value' => 'udemy',
					'image' => 'assets/img/wizard/courses/udemy@2x_2.png',
					'label' => esc_html__( 'Modern', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>
		</div>
	</div>
</div>
