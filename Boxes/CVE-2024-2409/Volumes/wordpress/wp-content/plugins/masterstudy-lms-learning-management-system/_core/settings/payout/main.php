<?php
add_filter('wpcfto_field_payout', function () {
	return STM_LMS_PATH . '/settings/payout/fields/payout.php';
});