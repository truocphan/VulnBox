<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Form_Builder_Submissions setup
 *
 * @since 3.4.6
 */

 class WPR_Form_Builder_Submissions {

    public function __construct() {
        add_action('wp_ajax_wpr_form_builder_submissions' , [$this, 'add_to_submissions']);
        add_action('wp_ajax_nopriv_wpr_form_builder_submissions',[$this, 'add_to_submissions']);
        add_action('save_post', [$this, 'update_submissions_post_meta']);
    }

    public function add_to_submissions() {

        $nonce = $_POST['nonce'];

        if ( !wp_verify_nonce( $nonce, 'wpr-addons-js' ) ) {
            return; // Get out of here, the nonce is rotten!
        }

        $new = [
            'post_status' => 'publish',
            'post_type' => 'wpr_submissions'
        ];
        
        $post_id = wp_insert_post( $new );
        foreach ($_POST['form_content'] as $key => $value ) {
            update_post_meta($post_id, $key, [$value[0], $value[1], $value[2]]);
        }

        $sanitized_form_name = sanitize_text_field($_POST['form_name']);
        $sanitized_form_id = sanitize_text_field($_POST['form_id']);
        $sanitized_form_page = sanitize_text_field($_POST['form_page']);
        $sanitized_form_page_id = sanitize_text_field($_POST['form_page_id']);
    
        update_post_meta($post_id, 'wpr_form_name', $sanitized_form_name);
        update_post_meta($post_id, 'wpr_form_id', $sanitized_form_id);
        update_post_meta($post_id, 'wpr_form_page', $sanitized_form_page);
        update_post_meta($post_id, 'wpr_form_page_id', $sanitized_form_page_id);
        update_post_meta($post_id, 'wpr_user_agent', sanitize_textarea_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ));
        update_post_meta($post_id, 'wpr_user_ip', Utilities::get_client_ip());
        
        if( $post_id ) {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'message' => esc_html__('Submission created successfully', 'wpr-addons'),
				'status' => 'success',
                'content' => $_POST['form_content']
            ));
        } else {
            wp_send_json_success(array(
                'action' => 'wpr_form_builder_submissions',
                'post_id' => $post_id,
                'message' => esc_html__('Submit action failed', 'wpr-addons'),
				'status' => 'error'
            ));
        }
    }
    
    public function update_submissions_post_meta($post_id) {
        if (isset($_POST['wpr_submission_changes']) && !empty($_POST['wpr_submission_changes'])) {
            $changes = json_decode(stripslashes($_POST['wpr_submission_changes']), true);

            foreach ($changes as $key => $value) { // Iterate through all changes
                update_post_meta($post_id, $key, $value);
            }
        }
    }
 }

 new WPR_Form_Builder_Submissions();