<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Send_Email setup
 *
 * @since 3.4.6
 */

 class WPR_Send_Email {

    public function __construct() {
        add_action('wp_ajax_wpr_form_builder_email' , [$this, 'send_email']);
        add_action('wp_ajax_nopriv_wpr_form_builder_email',[$this, 'send_email']);
    }

    public function send_email() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }
        
        $message_body = [];

		foreach ($_POST['form_content'] as $field) {
			if ($field[0] === 'email') {
				if (!is_email($field[1])) {
					// The field is an email, but it is not a valid email address
					// Take action or abort function execution here
					wp_send_json_error(array(
						'action' => 'wpr_form_builder_email',
						'message' => esc_html__('Email provided is invalid', 'wpr-addons'),
						'status' => 'error'
					));
				}
			}
		}
		
		// Rest of your function code here (if needed)
		
		
    
		$email_fields = trim(get_option('wpr_email_fields_' . $_POST['wpr_form_id']));
		
		if ($email_fields === '[all-fields]') {
			foreach ( $_POST['form_content'] as $key => $value ) {
				if ( is_array($value[1]) ) {
					$message_body[] = trim($value[2]) . ': ' . implode("\n", $value[1]);
				} else {
					$message_body[] = trim($value[2]) . ': ' . $value[1];
				}
			}
		} else {
			preg_match_all('/id="([^"]+)"/', $email_fields, $matches);
			$field_ids = $matches[1];

			foreach ( $_POST['form_content'] as $key => $value ) {
				$key_parts = explode('-', $key);
				$last_part = end($key_parts);

				if (in_array($last_part, $field_ids)) {
					if ( is_array($value[1]) ) {
						$message_body[] = trim($value[2]) . ': ' . implode("\n", $value[1]);
					} else {
						$message_body[] = trim($value[2]) . ': ' . $value[1];
					}
				}
			}
		}

		// public function replace_setting_shortcodes( $setting, $urlencode = false ) {
		// 	// Shortcode can be `[id="fds21fd"]` or `[field title="Email" id="fds21fd"]`, multiple shortcodes are allowed
		// 	return preg_replace_callback( '/(\[field[^]]*id="(\w+)"[^]]*\])/', function( $matches ) use ( $urlencode ) {
		// 		$value = '';
	   
		// 		if ( isset( $this->fields[ $matches[2] ] ) ) {
		// 			$value = $this->fields[ $matches[2] ]['value'];
		// 		}
	   
		// 		if ( $urlencode ) {
		// 			$value = urlencode( $value );
		// 		}
		// 		return $value;
		// 	}, $setting );
		// }

        $meta_keys = get_option('wpr_meta_keys_'. $_POST['wpr_form_id']);
        $meta_fields = [];

		foreach ( $meta_keys as $metadata_type ) {
			switch ( $metadata_type ) {
				case 'date':
					$meta_fields['date'] = [
						'title' => esc_html__( 'Date', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'date_format' ) ),
					];
					break;

				case 'time':
					$meta_fields['time'] = [
						'title' => esc_html__( 'Time', 'wpr-addons' ),
						'value' => date_i18n( get_option( 'time_format' ) ),
					];
					break;

				case 'page_url':
					$meta_fields['page_url'] = [
						'title' => esc_html__( 'Page URL', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? esc_url_raw( wp_unslash( get_option('wpr_referrer_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'page_title':
					$meta_fields['page_title'] = [
						'title' => esc_html__( 'Page Title', 'wpr-addons' ),
						// 'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? sanitize_text_field( wp_unslash( get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
						'value' => get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) ? get_option('wpr_referrer_title_'. $_POST['wpr_form_id']) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing
					];
					break;

				case 'user_agent':
					$meta_fields['user_agent'] = [
						'title' => esc_html__( 'User Agent', 'wpr-addons' ),
						'value' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
					];
					break;

				case 'remote_ip':
					$meta_fields['remote_ip'] = [
						'title' => esc_html__( 'Remote IP', 'wpr-addons' ),
						'value' => Utilities::get_client_ip(),
					];
					break;
                    
				case 'credit':
					$meta_fields['credit'] = [
						'title' => esc_html__( 'Powered by', 'wpr-addons' ),
						'value' => esc_html__( 'Royal Addons', 'wpr-addons' ), // is it necessary ?
					];
					break;
			}
		}

		$content_type = get_option('wpr_email_content_type_'. $_POST['wpr_form_id']);

		$line_break = 'html' === $content_type ? '<br>' : "\n";

        $email_meta = [];

        foreach( $meta_fields as $key => $value ) {
            $email_meta[] = $value['title'] . ': ' . $value['value'];
        }

        $to = get_option('wpr_email_to_'. $_POST['wpr_form_id']);
        $subject = get_option('wpr_email_subject_'. $_POST['wpr_form_id']);
		
		$subject = preg_replace_callback(
			'/\[id="(\w+)"\]/',
			function ($matches) {
				return $this->get_field_value($matches[1]);
			},
			$subject
		);

        $body = implode($line_break, $message_body) . $line_break . '-----' . $line_break . implode($line_break, $email_meta);

		$cc_header = '';
		if ( !empty( get_option('wpr_cc_header_'. $_POST['wpr_form_id']) ) ) {
			$cc_header = 'Cc: ' . get_option('wpr_cc_header_'. $_POST['wpr_form_id']);
		}

		$bcc_header = '';
		if ( !empty( get_option('wpr_bcc_header_'. $_POST['wpr_form_id']) ) ) {
			$bcc_header = 'Bcc: ' . get_option('wpr_bcc_header_'. $_POST['wpr_form_id']);
		}
		
		if ( !empty( get_option('wpr_reply_to_'. $_POST['wpr_form_id']) ) && !empty(get_option('wpr_email_from_name_'. $_POST['wpr_form_id'])) && !empty(get_option('wpr_email_from_'. $_POST['wpr_form_id'])) ) {
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_reply_to_'. $_POST['wpr_form_id']), $matche);
			$reply_to_field_id = $matche[1];
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_email_from_name_'. $_POST['wpr_form_id']), $matche);
			$email_from_name_field_id = $matche[1];
			
			preg_match_all('/id="([^"]+)"/', get_option('wpr_email_from_'. $_POST['wpr_form_id']), $matche);
			$email_from_field_id = $matche[1];

			foreach ( $_POST['form_content'] as $key => $value ) {
				$key_parts = explode('-', $key);
				$last_part = end($key_parts);

				if (in_array($last_part, $reply_to_field_id)) {
					$reply_to_address = $value[1];
				}

				if (in_array($last_part, $email_from_name_field_id)) {
					$email_from_name = $value[1];
				}

				if (in_array($last_part, $email_from_field_id)) {
					$email_from_mail = $value[1];
				}
			}

			if ( !$reply_to_address ) {
				$reply_to_address = get_option('wpr_reply_to_'. $_POST['wpr_form_id']);
			}

			if ( !$email_from_name ) {
				$email_from_name = get_option('wpr_email_from_name_'. $_POST['wpr_form_id']);
			}

			if ( !$email_from_mail ) {
				$email_from_mail = get_option('wpr_email_from_'. $_POST['wpr_form_id']);
			}
			
			$reply_to = 'Reply-To: ' . $reply_to_address;
		}
		
		$email_from = sprintf( 'From: %s <%s>' . "\r\n", $email_from_name, $email_from_mail );
		
		$headers = array('Content-Type: text/'. $content_type .'; charset=UTF-8', $email_from, $cc_header, $bcc_header, $reply_to);
      
        // Send email using wp_mail() function
        $sent = wp_mail( $to, $subject, $body, $headers);

        if ( $sent ) {
			wp_send_json_success(array(
				'action' => 'wpr_form_builder_email',
				'message' => esc_html__('Message sent successfully', 'wpr-addons'),
				'status' => 'success',
				'details' => json_encode($message_body)
			));
        } else {
			wp_send_json_error(array(
				'action' => 'wpr_form_builder_email',
				'message' => esc_html__('Message could not be sent', 'wpr-addons'),
				'status' => 'error',
				'details' => json_encode($message_body)
			));
        }
    }
	
	public function get_field_value($field_id) {
		foreach ($_POST['form_content'] as $key => $field) {
			$key_parts = explode('-', $key);
			$last_part = end($key_parts);
	
			if ($last_part === $field_id) {
				return $field[1];
			}
		}
		return ''; // Return an empty string if the field id is not found
	}
 }

 new WPR_Send_Email();