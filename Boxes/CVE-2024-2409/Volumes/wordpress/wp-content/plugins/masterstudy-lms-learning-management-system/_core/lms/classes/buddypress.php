<?php

STM_LMS_BuddyPress::init();

class STM_LMS_BuddyPress
{

    public static function init()
    {
        add_action('stm_lms_before_user_header', 'STM_LMS_BuddyPress::before_user_header');
        add_action('wp_enqueue_scripts', 'STM_LMS_BuddyPress::scripts');

        add_filter('bp_get_displayed_user_nav_front', 'STM_LMS_BuddyPress::user_nav_front', 10, 2);
        add_filter('bp_get_displayed_user_nav_activity', 'STM_LMS_BuddyPress::user_nav_activity');
        add_filter('bp_get_displayed_user_nav_xprofile', 'STM_LMS_BuddyPress::user_nav_profile');
        add_filter('bp_before_group_home_content', 'STM_LMS_BuddyPress::before_group_content');

        add_filter('bp_pre_user_query_construct', 'STM_LMS_BuddyPress::instructor_members');

        add_action('ms_remove_mce_buttons', 'STM_LMS_BuddyPress::remove_mce_buttons');

        add_filter('bp_core_fetch_avatar', 'STM_LMS_BuddyPress::change_lms_avatar', 10, 2);


    }

    public static function change_lms_avatar($data, $params){
        if(!empty($params['object']) and $params['object'] === 'user') {
            $user_data = STM_LMS_User::get_current_user($params['item_id']);
            return $user_data['avatar'];
        }

        return $data;
    }

    public static function bp_user_profile_url()
    {
        if (bp_displayed_user_domain()) {
            $user_domain = bp_displayed_user_domain();
        } elseif (bp_loggedin_user_domain()) {
            $user_domain = bp_loggedin_user_domain();
        } else {
            return '';
        }

        return $user_domain;
    }

    public static function is_student_public_profile()
    {
        /*Is Private*/

        if (self::is_bp_current_user()) return false;

        return !STM_LMS_Instructor::is_instructor(bp_displayed_user_id());
    }

    public static function is_instructor_public_profile()
    {
        /*Is Private*/

        if (self::is_bp_current_user()) return false;

        return STM_LMS_Instructor::is_instructor(bp_displayed_user_id());
    }

    public static function is_bp_current_user()
    {
        $current_user = STM_LMS_User::get_current_user('', false, true);
        $currentUserID = bp_displayed_user_id();

        return $currentUserID == $current_user['id'];
    }

    public static function before_user_header()
    {
        STM_LMS_User::js_redirect(self::bp_user_profile_url());
    }

    public static function scripts()
    {
        stm_lms_register_style('buddypress');
        stm_lms_register_script('buddypress', array(), true);
    }

    public static function user_nav_front($item, $user_nav_item)
    {
        if (self::is_student_public_profile()) return '';

        if (self::is_instructor_public_profile()) {
            $name = $user_nav_item->name;
            return str_replace($name, esc_html__('Teacher Courses', 'masterstudy-lms-learning-management-system'), $item);
        }

        return $item;
    }

    public static function user_nav_activity($item)
    {
        if (self::is_student_public_profile()) return '';
        return $item;
    }

    public static function user_nav_profile($item)
    {
        return '';
    }

    public static function before_group_content()
    { ?>
        <h2 class="stm_lms_group_title"><?php the_title(); ?></h2>
    <?php }

    public static function instructor_members($args)
    {

        $scope = (!empty($_POST['scope'])) ? sanitize_text_field($_POST['scope']) : '';

        $users = get_users(array(
            'role' => STM_LMS_Instructor::role(),
        ));

        if ($scope === 'instructors') {
            $args->query_vars['include'] = implode(',', wp_list_pluck($users, 'ID'));
        }

        return $args;
    }

    public static function remove_mce_buttons()
    {
        // Remove the temporary filter on editor buttons
        remove_filter('mce_buttons', 'bp_nouveau_messages_mce_buttons', 10, 1);
    }

}