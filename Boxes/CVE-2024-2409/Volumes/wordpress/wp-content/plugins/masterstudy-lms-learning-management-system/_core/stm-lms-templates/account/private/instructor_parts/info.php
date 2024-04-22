<?php
/**
 * @var $current_user
 */

?>

<div class="stm_lms_instructor_info">

	<div class="stm_lms_instructor_edit_avatar">

		<?php STM_LMS_Templates::show_lms_template('account/private/parts/avatar_edit', compact('current_user')); ?>

		<?php STM_LMS_Templates::show_lms_template('account/private/parts/settings_button', compact('current_user')); ?>

	</div>
    <?php STM_LMS_Templates::show_lms_template('account/private/instructor_parts/bio', compact('current_user')); ?>


</div>
