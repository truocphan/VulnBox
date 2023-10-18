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

 class WPR_File_Upload {
    public function __construct() {
        add_action('wp_ajax_wpr_addons_upload_file', [$this, 'handle_file_upload']);
        add_action('wp_ajax_nopriv_wpr_addons_upload_file', [$this, 'handle_file_upload']);
    }
    
    public function handle_file_upload() {
		// $email_fields = get_option('wpr_email_fields_' . $_POST['wpr_form_id']); remove if necessary
		
		if (!isset($_POST['wpr_addons_nonce']) || !wp_verify_nonce($_POST['wpr_addons_nonce'], 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

        // Get the max_file_size value from the file_size control.
        $max_file_size = isset($_POST['max_file_size']) ? floatval(sanitize_text_field($_POST['max_file_size'])) : 0; // Replace this with the value from the file_size control.
        if ($max_file_size <= 0) {
            $max_file_size = wp_max_upload_size() / pow(1024, 2); //MB
        }

        // Check if a file is uploaded.
        if (isset($_FILES['uploaded_file'])) {
            $file = $_FILES['uploaded_file'];  
			
			// Check if the uploaded file size exceeds the allowed limit.
            if ($file['size'] > $max_file_size * 1024 * 1024) {
                wp_send_json_error(array(
					'cause' => 'filesize',
					'sizes' => [
						$max_file_size * 1024 * 1024,
						$file['size']
					],
                    'message' => 'File size exceeds the allowed limit.'
                ));
            }

			// valid file type?
			if ( !$this->file_validity(  $file ) ) {
                wp_send_json_error(array(
					'cause' => 'filetype',
                    'message' => esc_html__('File type is not valid.', 'wpr-addons')
                ));
			}
			
			if ( 'click' == $_POST['triggering_event'] ) {
				// Set the upload directory.
				$upload_dir = wp_upload_dir();
				$upload_path = $upload_dir['basedir'] . '/wpr-addons/forms';

				wp_mkdir_p( $upload_path );
		
				// Generate a unique file name.
				$filename = wp_unique_filename($upload_path, $file['name']);
		
				// Move the uploaded file to the uploads directory.
				if (move_uploaded_file($file['tmp_name'], $upload_path . '/' . $filename)) {
					// Return the uploaded file's URL.
					wp_send_json_success(array(
						'url' => $upload_dir['baseurl'] . '/wpr-addons/forms/' . $filename
					));
				} else {
					wp_send_json_error(array(
						'message' => esc_html__('Failed to upload the file.', 'wpr-addons')
					));
				}
			} else {
				wp_send_json_success(array(
					'message' => esc_html__('File validation passed', 'wpr-addons')
				));
			}
        }
    
		if ( 'click' == $_POST['triggering_event'] ) {
			// Folder not being created during interaction was causing issues
			$upload_dir = wp_upload_dir();
			$upload_path = $upload_dir['basedir'] . '/wpr-addons/forms';

			wp_mkdir_p( $upload_path );

			wp_send_json_error(array(
				'message' => esc_html__('No file was uploaded.', 'wpr-addons'),
				'files' => $_FILES['uploaded_file']
			));
		}
    }

	// private function file_validity( $field, $file ) {
	private function file_validity( $file ) {
		// File type validation
		if ( empty( $_POST['allowed_file_types'] ) ) {
			$allowed_file_types = 'jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,odt,avi,ogg,m4a,mov,mp3,mp4,mpg,wav,wmv,txt';
		} else {
			$allowed_file_types = $_POST['allowed_file_types'];
		}

		$f_extension = pathinfo( $file['name'], PATHINFO_EXTENSION );
		$allowed_file_types = explode( ',', $allowed_file_types );
		$allowed_file_types = array_map( 'trim', $allowed_file_types );
		$allowed_file_types = array_map( 'strtolower', $allowed_file_types );

		$f_extension = strtolower( $f_extension );

		return ( in_array( $f_extension, $allowed_file_types ) && !in_array( $f_extension, $this->get_exclusion_list() ) );
	}

    private function get_exclusion_list() {
		static $exclusionlist = false;
		if ( ! $exclusionlist ) {
			$exclusionlist = [
				'php',
				'php3',
				'php4',
				'php5',
				'php6',
				'phps',
				'php7',
				'phtml',
				'shtml',
				'pht',
				'swf',
				'html',
				'asp',
				'aspx',
				'cmd',
				'csh',
				'bat',
				'htm',
				'hta',
				'jar',
				'exe',
				'com',
				'js',
				'lnk',
				'htaccess',
				'htpasswd',
				'phtml',
				'ps1',
				'ps2',
				'py',
				'rb',
				'tmp',
				'cgi',
				'svg',
			];
		}

		return $exclusionlist;
	}
 }

 new WPR_File_Upload();