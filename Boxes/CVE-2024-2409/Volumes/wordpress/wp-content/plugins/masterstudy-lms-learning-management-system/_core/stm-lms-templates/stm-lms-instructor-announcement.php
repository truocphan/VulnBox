<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php get_header();

$lms_current_user = STM_LMS_User::get_current_user('', true, true);

do_action('stm_lms_template_main');

$is_instructor = STM_LMS_Instructor::is_instructor();

STM_LMS_Templates::show_lms_template("account/private/instructor_parts/announcement", compact('lms_current_user'));

get_footer(); ?>
