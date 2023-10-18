<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * WPR_Subscribe_Mailchimp setup
 *
 * @since 3.4.6
 */

 class WPR_Subscribe_Mailchimp {

    public function __construct() {
        // For form builder
        add_action( 'wp_ajax_wpr_form_builder_mailchimp', [$this, 'wpr_form_builder_mailchimp'] );
        add_action( 'wp_ajax_nopriv_wpr_form_builder_mailchimp', [$this, 'wpr_form_builder_mailchimp'] );
    }

	/**
	** Mailchimp AJAX Subscribe
	*/
	public static function wpr_form_builder_mailchimp() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }
		
		// API Key
        $api_key = get_option('wpr_mailchimp_api_key') ? get_option('wpr_mailchimp_api_key') : '';

        $api_key_sufix = explode( '-', $api_key )[1];

        // // List ID
        $list_id = isset($_POST['listId']) ? sanitize_text_field(wp_unslash($_POST['listId'])) : '';

        // // Get Available Fileds (PHPCS - fields are sanitized later on input)
		$fields = isset($_POST['form_data']) ? $_POST['form_data'] : []; // phpcs:ignore

		$group_ids = isset($fields['group_id']) ? array_map('sanitize_text_field', array_map('trim', explode(',', wp_unslash($fields['group_id'])))) : [];

        // Merge Additional Fields
        $merge_fields = [
            'FNAME' => !empty( $fields['first_name_field'] ) ? sanitize_text_field($fields['first_name_field']) : '',
            'LNAME' => !empty( $fields['last_name_field'] ) ? sanitize_text_field($fields['last_name_field']) : '',
			'PHONE' => !empty ( $fields['phone_field'] ) ? sanitize_text_field($fields['phone_field']) : '',
			'ADDRESS' => !empty ( $fields['address_field'] ) ? sanitize_text_field($fields['address_field']) : '',
			'BIRTHDAY' => !empty ( $fields['birthday_field'] ) ? sanitize_text_field($fields['birthday_field']) : '',
		];

        // API URL
        $api_url = 'https://'. $api_key_sufix .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower(sanitize_text_field($fields['email_field'])));
		
		$api_body = [
			'email_address' => sanitize_text_field($fields[ 'email_field' ]),
			'status' => 'subscribed',
			'merge_fields' => $merge_fields
		];
			
		if ( !empty($group_ids) ) {
			$api_body['interests'] = self::group_ids_to_interests_array($group_ids);
		}

        // API Args
        $api_args = [
			'method' => 'PUT',
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'apikey '. $api_key,
			],
			'body' => json_encode($api_body),
        ];

        // Send Request
        $request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error($request) ) {
			$request = json_decode( wp_remote_retrieve_body($request) );

			// Set Status
			if ( ! empty($request) ) {
				if ($request->status == 'subscribed') {

					wp_send_json_success(array(
						'action' => 'wpr_form_builder_mailchimp',
						'status' => 'success',
						'message' => 'Mailchimp subscription was successful',
						'request' => $request
					));

				} else {
					wp_send_json_error([ 
						'action' => 'wpr_form_builder_mailchimp',
						'status' => 'error',
						'message' => 'Mailchimp subscription failed',
						'request' => $request
					]);
				}
			}
		} else {

			wp_send_json_error([ 
				'action' => 'wpr_form_builder_mailchimp',
				'status' => 'error',
				'message' => 'Mailchimp subscription failed',
				'request' => $request
			]);
		}
	}

	public static function group_ids_to_interests_array($group_ids) {
		$interests_array = [];
		
		foreach ($group_ids as $group_id) {
			$interests_array[$group_id] = true;
		}
	
		return $interests_array;
	}
	
 }

 new WPR_Subscribe_Mailchimp();