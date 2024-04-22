<?php

$paypal_files[] = STM_LMS_LIBRARY . '/paypal/includes/classes/vender/autoload.php';
$paypal_files[] = STM_LMS_LIBRARY . '/paypal/route.php';

foreach ( scandir( STM_LMS_LIBRARY . '/paypal/includes/classes/' ) as $key => $value ) {
	if ( strpos( $value, '.php' ) ) {
		$paypal_files[] = STM_LMS_LIBRARY . '/paypal/includes/classes/' . $value;
	}
}

$paypal_files[] = STM_LMS_LIBRARY . '/paypal/init.php';

foreach ( $paypal_files as $file ) {
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}
