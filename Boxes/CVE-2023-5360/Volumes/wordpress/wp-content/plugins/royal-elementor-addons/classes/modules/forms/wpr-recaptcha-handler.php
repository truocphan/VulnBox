<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Actions_Status setup
 *
 * @since 3.4.6
 */

 class WPR_Recaptcha_Handler {
    public function __construct() {
        add_action('wp_ajax_wpr_verify_recaptcha', [$this, 'wpr_verify_recaptcha']);
        add_action('wp_ajax_nopriv_wpr_verify_recaptcha', [$this, 'wpr_verify_recaptcha']);
    }
    
    // In your PHP file
    public function wpr_verify_recaptcha() {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $is_valid_recaptcha = $this->check_recaptcha($recaptcha_response);
        
        if ($is_valid_recaptcha[0] && $is_valid_recaptcha[1] >= get_option('wpr_recaptcha_v3_score')) {
            // Proceed with form processing
			wp_send_json_success(array(
				'message' => 'Recaptcha Success',
                'score' => $is_valid_recaptcha[1]
			));
        } else {
            // Handle the invalid reCAPTCHA case
			wp_send_json_error(array(
				'message' => 'Recaptcha Error',
                'score' => $is_valid_recaptcha[1],
                'results' => [
                    $is_valid_recaptcha[0],
                    $is_valid_recaptcha[1] >= get_option('wpr_recaptcha_v3_score')
                ]
			));
        }
    }
    
    public function check_recaptcha($recaptcha_response) {
        $secret_key = get_option('wpr_recaptcha_v3_secret_key');
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        
        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $secret_key,
                'response' => $recaptcha_response,
                'remoteip' => $remote_ip
            )
        ));
        
        if (is_wp_error($response)) {
            // Handle the error accordingly
            return false;
        }
        
        $decoded_response = json_decode(wp_remote_retrieve_body($response), true);

        $score = $decoded_response['score'];
        
        if ($decoded_response['success'] === true) {
            // reCAPTCHA verification passed
            return [true, $score];
        } else {
            // reCAPTCHA verification failed
            return [false, $score];
        }
    }
 }

 new WPR_Recaptcha_Handler();