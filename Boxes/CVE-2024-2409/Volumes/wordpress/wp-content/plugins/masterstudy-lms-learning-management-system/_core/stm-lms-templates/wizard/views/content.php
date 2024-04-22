<div class="stm_lms_splash_wizard__content">
	<div class="stm_lms_splash_wizard__content_inner">
		<transition name="fade">
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/business' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/general' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/courses' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/single_course' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/curriculum' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/profiles' ); ?>
			<?php STM_LMS_Templates::show_lms_template( 'wizard/views/steps/finish' ); ?>
		</transition>
	</div>
</div>
