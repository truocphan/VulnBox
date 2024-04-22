<div id="stm_lms_splash_wizard"
	class="stm_lms_splash_wizard"
	v-bind:class="{'deactivated' : !business_type}"
	v-cloak="">
	<?php STM_LMS_Templates::show_lms_template( 'wizard/views/header' ); ?>
	<?php STM_LMS_Templates::show_lms_template( 'wizard/views/content' ); ?>
	<?php STM_LMS_Templates::show_lms_template( 'wizard/views/footer' ); ?>
</div>
