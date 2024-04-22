<?php
add_filter('wpcfto_field_stm_lms_shortcodes', function () {
    return STM_LMS_PATH . '/settings/stm_lms_shortcodes/stm_lms_shortcodes.php';
});