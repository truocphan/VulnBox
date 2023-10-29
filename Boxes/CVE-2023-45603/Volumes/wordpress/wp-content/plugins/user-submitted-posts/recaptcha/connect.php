<?php // Google reCAPTCHA for PHP >= 5.3.0

// PHP Global space backslash class requires >= 5.3.0

// Google reCAPTCHA 1.1.3 @ https://github.com/google/recaptcha

// Supports allow_url_fopen/file_get_contents and cURL

if (!defined('ABSPATH')) die();

require_once('autoload.php');

if (ini_get('allow_url_fopen')) {
	
	// file_get_contents: allow_url_fopen = on
	$recaptcha = new \ReCaptcha\ReCaptcha($private);
	
} elseif (extension_loaded('curl')) {
	
	// cURL: allow_url_fopen = off
	$recaptcha = new \ReCaptcha\ReCaptcha($private, new \ReCaptcha\RequestMethod\CurlPost());
	
} else {
	
	$recaptcha = null;
	
	error_log('WP Plugin USP: Google reCAPTCHA: allow_url_fopen and curl both disabled!', 0);
	
}

if (isset($recaptcha)) {
	
	$response = $recaptcha->verify($_POST['g-recaptcha-response'], usp_get_ip_address());
	
} else {
	
	$response = null;
	
	error_log('WP Plugin USP: Google reCAPTCHA: $recaptcha variable not set!', 0);
	
}

if ($response->isSuccess()) {
	
	return true;
	
} else {
	
	$errors = $response->getErrorCodes();
	
	if (!empty($errors) && is_array($errors)) {
		
		foreach ($errors as  $error) {
			
			// error_log('WP Plugin USP: Google reCAPTCHA: '. $error, 0);
			
		}
		
	} else {
		
		// error_log('WP Plugin USP: Google reCAPTCHA: '. $errors, 0);
		
	}
	
}

return false;