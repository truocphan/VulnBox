<?php stm_lms_register_script( 'bundles/card' ); ?>

<div class="stm_lms_my_course_bundles__list" v-bind:class="{'loading' : loading}">
	<?php STM_LMS_Templates::show_lms_template( 'bundles/card/vue/main' ); ?>
</div>
