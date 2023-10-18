<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Send_Webhook setup
 *
 * @since 3.4.6
 */

 class WPR_Send_Webhook {

    public function __construct() {
        add_action('wp_ajax_wpr_form_builder_webhook' , [$this, 'send_webhook']);
        add_action('wp_ajax_nopriv_wpr_form_builder_webhook',[$this, 'send_webhook']);
    }

    public function send_webhook() {
        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }
        
        $message_body = [];
        
        foreach ( $_POST['form_content'] as $key => $value ) {
            if ( is_array($value[1]) ) {
                $message_body[trim($value[2])] = implode("\n", $value[1]);
            } else {
                $message_body[trim($value[2])] = $value[1];
            }
        }

		$message_body['form_id'] = $_POST['wpr_form_id'];
		$message_body['form_name'] = $_POST['form_name'];

		$args = [
			'body' => $message_body,
		];

		$response = wp_remote_post( trim(get_option('wpr_webhook_url_' . $_POST['wpr_form_id'])), $args );

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json_error(array(
				'action' => 'wpr_form_builder_webhook',
				'message' => esc_html__('Webhook error', 'wpr-addons'),
				'status' => 'error',
				'details' => json_encode($message_body)
			));
			// throw new \Exception( 'Webhook error.' );
		} else {
			wp_send_json_success(array(
				'action' => 'wpr_form_builder_webhook',
				'message' => esc_html__('Webhook success', 'wpr-addons'),
				'status' => 'success',
				'details' => json_encode($message_body)
			));
        }
    }
 }

 new WPR_Send_Webhook();