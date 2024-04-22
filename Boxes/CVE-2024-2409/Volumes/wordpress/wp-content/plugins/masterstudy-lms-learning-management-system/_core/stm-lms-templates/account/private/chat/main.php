<?php
/**
 * @var $current_user
 */

wp_enqueue_script('vue-resource.js');
stm_lms_register_style('chat');
stm_lms_register_script('chat');
?>

<div id="stm_lms_chat">


    <div class="row">

        <div class="col-md-4">
			<?php STM_LMS_Templates::show_lms_template('account/private/chat/contacts', array('current_user' => $current_user)); ?>
        </div>

        <div class="col-md-8">
			<?php STM_LMS_Templates::show_lms_template('account/private/chat/chat', array('current_user' => $current_user)); ?>
        </div>

    </div>
</div>