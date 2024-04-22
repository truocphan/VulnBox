<?php

require_once STM_LMS_PATH . '/settings/curriculum/api/get.php';

add_filter('wpcfto_field_curriculum', function () {
    return STM_LMS_PATH . '/settings/curriculum/field.php';
});