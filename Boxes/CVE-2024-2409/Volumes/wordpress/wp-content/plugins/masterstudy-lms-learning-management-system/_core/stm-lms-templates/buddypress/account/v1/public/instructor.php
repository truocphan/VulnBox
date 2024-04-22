<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */
?>
<div class="row">

	<div class="col-md-3 col-sm-12">

		<?php STM_LMS_Templates::show_lms_template('buddypress/account/v1/public/instructor_parts/info', array('current_user' => $current_user)); ?>

	</div>

	<div class="col-md-9 col-sm-12">

		<?php STM_LMS_Templates::show_lms_template('buddypress/account/v1/private/instructor_parts/name_and_socials', array('current_user' => $current_user)); ?>

		<?php STM_LMS_Templates::show_lms_template('account/private/instructor_parts/bio', array('current_user' => $current_user)); ?>

	</div>

</div>