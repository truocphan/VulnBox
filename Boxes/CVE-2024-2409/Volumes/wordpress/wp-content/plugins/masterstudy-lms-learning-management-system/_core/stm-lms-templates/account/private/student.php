<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */

stm_lms_register_style('user_info_top');

?>


<?php STM_LMS_Templates::show_lms_template('account/private/parts/enrolled-courses'); ?>