<?php
/**
 * @var $level_id
 */

$sub_info = STM_LMS_Subscriptions::user_subscriptions(true);
$new_level_quota = STM_LMS_Subscriptions::get_course_number($level_id);

if (!empty($sub_info->used_quotas) and $sub_info->used_quotas > $new_level_quota):

    stm_lms_register_style('pmpro-checkout-warning');
    stm_lms_register_script('pmpro-checkout-warning');
    $courses = stm_lms_get_user_courses_by_subscription(get_current_user_id(), '*', array('course_id', 'start_time'), 0);
    ?>

    <div class="stm_lms_subscription_warning" data-quota="<?php echo intval($new_level_quota); ?>">
        <h3><?php printf(esc_html__('Please select the previously added courses to delete, because the new chosen plan allows you to have only %s courses', 'masterstudy-lms-learning-management-system'), $new_level_quota); ?></h3>
        <table class="stm_lms_subscription_warning__courses">
            <?php foreach ($courses as $course): ?>
                <tr class="stm_lms_subscription_warning__course heading_font">
                    <td>
                        <div class="title"><?php echo get_the_title($course['course_id']); ?></div>
                        <div class="date"> <?php echo date("F j, Y, g:i a", $course['start_time']); ?></div>
                    </td>
                    <td><span class="delete" data-course="<?php echo intval($course['course_id']); ?>">
                            <?php esc_html_e('Remove Course', 'masterstudy-lms-learning-management-system'); ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

<?php endif;