<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */
stm_lms_register_style('instructor/account');
?>

<div class="row">

	<div class="col-md-3 col-sm-12">

		<?php STM_LMS_Templates::show_lms_template('buddypress/account/v1/public/parts/info', compact('current_user')); ?>

	</div>

	<div class="col-md-9 col-sm-12">

		<?php STM_LMS_Templates::show_lms_template('account/private/student', compact('current_user')); ?>

		<?php STM_LMS_Templates::show_lms_template('account/private/instructor_parts/bio', compact('current_user')); ?>

	</div>

</div>