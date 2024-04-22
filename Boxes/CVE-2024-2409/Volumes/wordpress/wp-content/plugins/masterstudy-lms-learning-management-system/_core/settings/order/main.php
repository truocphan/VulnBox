<?php
add_filter('wpcfto_field_order', function () {
	return STM_LMS_PATH . '/settings/order/fields/order.php';
});