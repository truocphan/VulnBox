<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */
stm_lms_register_style('user_info_top');
stm_lms_register_style('edit_account');

?>

<div class="row">

    <div class="col-12">

        <div class="stm_lms_private_information" data-container-open=".stm_lms_private_information">

			<?php STM_LMS_Templates::show_lms_template('account/private/student', compact('current_user')); ?>

			<?php STM_LMS_Templates::show_lms_template('account/private/instructor_parts/bio', compact('current_user')); ?>

        </div>

    </div>

</div>