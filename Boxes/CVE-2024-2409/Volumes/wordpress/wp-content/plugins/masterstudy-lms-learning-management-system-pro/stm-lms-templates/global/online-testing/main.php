<?php

/**
 * @var $item_id
 */

stm_lms_register_style( 'online-testing' );
$source = STM_LMS_Helpers::current_screen();
$inline = "var stm_lms_lesson_id = {$item_id}; var source = {$source};";
stm_lms_register_script( 'online-testing', array(), false, $inline );
?>
<div class="stm_lms_online-testing">
	<div class="col-md-8 col-md-push-2">
		<h1><?php the_title(); ?></h1>
		<?php STM_LMS_Templates::show_lms_template( 'course/parts/quiz', array( 'item_id' => $item_id ) ); ?>
	</div>
</div>
