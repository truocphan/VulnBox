<?php
$gdpr_warning = STM_LMS_Options::get_option('gdpr_warning');
$gdpr_page = STM_LMS_Options::get_option('gdpr_page');

if (!empty($gdpr_page) and !empty($gdpr_warning)): ?>

    <label class="stm_lms_styled_checkbox" style="margin-bottom: 30px">
                    <span class="stm_lms_styled_checkbox__inner">
                        <input type="checkbox"
                               name="privacy_policy"
                               v-init="hasPrivacyPolicy()"
                               v-model="privacy_policy"/>
                        <span><i class="fa fa-check"></i> </span>
                    </span>

        <a href="<?php echo esc_url(get_the_permalink($gdpr_page)); ?>" target="_blank">
            <?php echo wp_kses_post($gdpr_warning); ?>
        </a>
    </label>

<?php endif; ?>