<?php
add_filter('wpcfto_field_payments', function () {
	return STM_LMS_PATH . '/settings/payments/fields/payments.php';
});