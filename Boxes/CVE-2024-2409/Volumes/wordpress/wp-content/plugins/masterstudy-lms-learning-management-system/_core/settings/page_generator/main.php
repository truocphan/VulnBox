<?php

require_once STM_LMS_PATH . '/settings/page_generator/api/get.php';

add_filter('wpcfto_field_generate_page', function () {
    return STM_LMS_PATH . '/settings/page_generator/field.php';
});