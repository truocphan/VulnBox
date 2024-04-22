<div v-if="!course.is_expired && !course.membership_expired && !course.membership_inactive" class="stm_lms_expired_notice__wrapper" v-html="course.expiration"></div>

<div v-else class="stm_lms_expired_notice__wrapper">
    <div class="stm_lms_expired_notice warning_expired">
        <i class="far fa-clock"></i>
        <template v-if="course.is_expired && !course.membership_expired && !course.membership_inactive">
            <?php esc_html_e('Course has expired', 'masterstudy-lms-learning-management-system'); ?>
        </template>
        <template v-else-if="course.membership_expired">
            <?php esc_html_e('Membership has expired', 'masterstudy-lms-learning-management-system'); ?>
        </template>
        <template v-else-if="course.membership_inactive">
            <?php esc_html_e('Membership is not active', 'masterstudy-lms-learning-management-system'); ?>
        </template>
    </div>
</div>