<?php
/**
 * @var $stm_lms_vars
 */

?>

<div class="stm_lms_use_membership_popup__multiply">

    <h2><?php esc_html_e('Enroll course via Membership', 'masterstudy-lms-learning-management-system'); ?></h2>

    <?php $i = 0;
    $active_quota_course_id = 0;
    foreach ($stm_lms_vars as $subscription) :

        extract($subscription);
        /**
         * @var $id
         * @var $name
         * @var $course_number
         * @var $used_quotas
         * @var $course_id
         * @var $quotas_left
         */

        if (!$quotas_left) continue;
        $i++;
        if ($i == 1) {
            $active_quota_course_id = $id;
        }
        ?>

        <div class="stm_lms_use_membership_popup__multiply_one <?php echo ($active_quota_course_id === $id) ? 'active' : ''; ?>"
             data-membership-id="<?php echo intval($id); ?>"
             data-course-id="<?php echo intval($course_id); ?>">
            <div class="stm_lms_use_membership_popup__multiply_one_in">
                <span class="name heading_font"><?php echo sanitize_text_field($name); ?></span>
                <span class="quota"><?php printf(esc_html__('Quotas left: %s', 'masterstudy-lms-learning-management-system'), "<span>{$quotas_left}</span>"); ?></span>
            </div>
            <div class="q_checker"></div>
        </div>

    <?php endforeach; ?>

    <a href="#"
       class="btn btn-default btn-mu-membership"
       data-lms-usemembership=""
       data-membership-id="<?php echo intval($active_quota_course_id) ?>"
       data-lms-course="<?php echo intval($course_id); ?>">
            <span>
                <?php esc_html_e('Enroll Course', 'masterstudy-lms-learning-management-system'); ?>
            </span>
    </a>

</div>

<script>
    (function ($) {

        var $one = $('.stm_lms_use_membership_popup__multiply_one');

        $one.on('click', function (e) {
            e.preventDefault();

            var $this = $(this);

            $one.removeClass('active');

            $this.addClass('active');

            var membership_id = $this.attr('data-membership-id');

            $(".btn-mu-membership").attr('data-membership-id', membership_id);

        });
    })(jQuery)
</script>
