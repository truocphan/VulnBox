<transition name="slide">

    <div class="stm-lms-dashboard-navigation">
        <div class="stm-lms-dashboard-navigation--inner">
            <a href="<?php echo esc_url(get_dashboard_url()); ?>" class="back_to_site">
                <i class="fa fa-arrow-left"></i>
                <?php esc_html_e('Back to Site', 'masterstudy-lms-learning-management-system'); ?>
            </a>

            <div class="stm-lms-dashboard-navigation--links">

                <router-link to="/courses">
                    <i class="fa fa-book-open"></i>
                    <?php esc_html_e('Courses', 'masterstudy-lms-learning-management-system'); ?>
                </router-link>

                <a href="">
                    <i class="fa fa-users"></i>
                    <?php esc_html_e('Students', 'masterstudy-lms-learning-management-system'); ?>
                    <span><?php esc_html_e('Soon', 'masterstudy-lms-learning-management-system'); ?></span>
                </a>

            </div>

        </div>
    </div>

</transition>