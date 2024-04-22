<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $current_user
 */

$is_instructor = STM_LMS_Instructor::is_instructor();
$tpl = ($is_instructor) ? 'instructor' : 'student';

STM_LMS_Templates::show_lms_template("buddypress/account/v1/private/{$tpl}", compact('current_user'));