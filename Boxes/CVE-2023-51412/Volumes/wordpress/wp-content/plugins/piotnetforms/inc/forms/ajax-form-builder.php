<?php
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	add_action( 'wp_ajax_piotnetforms_ajax_form_builder', 'piotnetforms_ajax_form_builder' );
	add_action( 'wp_ajax_nopriv_piotnetforms_ajax_form_builder', 'piotnetforms_ajax_form_builder' );

	function find_element_recursive_piotnetforms( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = find_element_recursive_piotnetforms( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	function set_val_piotnetforms(&$array,$path,$val) {
		for($i=&$array; $key=array_shift($path); $i=&$i[$key]) {
			if(!isset($i[$key])) $i[$key] = array();
		}
		$i = $val;
	}

	function piotnetforms_merge_string(&$string,$string_add) {
		$string = $string . $string_add;
	}

	function piotnetforms_unset_string(&$string) {
		$string = '';
	}

	function piotnetforms_set_string(&$string,$string_set) {
		$string = $string_set;
	}

	function replace_email_piotnetforms($content, $fields, $payment_status = 'succeeded', $payment_id = '', $succeeded = 'succeeded', $pending = 'pending', $failed = 'failed', $submit_id = 0 ) {
		$message = $content;

		$message_all_fields = '';

		if (!empty($fields)) {

			// all fields
			foreach ($fields as $field) {
				$field_value = $field['value'];
				$field_label = isset($field['label']) ? $field['label'] : '';
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = '<span data-id="' . esc_attr( $repeater_id_string ) . '"><strong>' . esc_html( $field['repeater_label'] . ' ' . $repeater_index_1 ) . ': </strong></span><br>';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($message_all_fields, $repeater_label) !== false) {
						$message_all_fields .= $field_label . ': ' . $field_value . '<br />';
					} else {
						$message_all_fields .= $repeater_label;
						if (strpos($field['name'], 'piotnetforms-end-repeater') === false) {
							$message_all_fields .= $field_label . ': ' . $field_value . '<br />';
						}
					}
				} else {
					if (strpos($field['name'], 'piotnetforms-end-repeater') === false) {
						$message_all_fields .= $field_label . ': ' . $field_value . '<br />';
					}
				}

			}

			$message = str_replace( '[all-fields]', $message_all_fields, $message );

			// each field

			$repeater_content = '';
			$repeater_id_one = '';
			foreach ($fields as $field) {
				$field_value = $field['value'];
				$field_label = isset($field['label']) ? $field['label'] : '';
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}

				$search_remove_line_if_field_empty = '[field id="' . $field['name'] . '"]' . '[remove_line_if_field_empty]';

				if (empty($field_value)) {
					$lines = explode("\n", $message);
					$lines_found = array();

					foreach($lines as $num => $line){
					    $pos = strpos($line, $search_remove_line_if_field_empty);
					    if($pos !== false) {
					    	$lines_found[] = $line;
					    }
					}

					if (!empty($lines_found)) {
						foreach ($lines_found as $line) {
							$message = str_replace( [ $line . "\n", "\n" . $line ], '', $message );
						}
					}
				}

				$search = '[field id="' . $field['name'] . '"]';
				$message = str_replace($search, $field_value, $message);

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = '<span data-id="' . esc_attr( $repeater_id_string ) . '"><strong>' . esc_html( $field['repeater_label'] . ' ' . $repeater_index_1 ) . ': </strong></span><br>';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($repeater_content, $repeater_label) !== false) {
						if (strpos($field['name'], 'piotnetforms-end-repeater') === false) {
							$string_add = $field_label . ': ' . $field_value . '<br />';
						}
						piotnetforms_merge_string($repeater_content,$string_add);
					} else {
						$string_add = $repeater_label . $field['label'] . ': ' . $field_value . '<br />';
						piotnetforms_merge_string($repeater_content,$string_add);
					}
					if (substr_count($field['repeater_id'],'|') == 2) {
						piotnetforms_set_string($repeater_id_one,$field['repeater_id_one']);
					}
				}

				if (empty($repeater_id)) {
					if (!empty($repeater_id_one) && !empty($repeater_content)) {
						$search_repeater = '[repeater id="' . $repeater_id_one . '"]';
						$message = str_replace($search_repeater, $repeater_content, $message);
						piotnetforms_unset_string($repeater_content);
						piotnetforms_unset_string($repeater_id_one);
					}
				}
				
			}
		}

		$search_remove_line_if_field_empty = '"]' . '[remove_line_if_field_empty]'; // fix alert [

		$lines = explode("\n", $message);
		$lines_found = array();

		foreach($lines as $num => $line){
		    $pos = strpos($line, $search_remove_line_if_field_empty);
		    if($pos !== false) {
		    	$lines_found[] = $line;
		    }
		}

		if (!empty($lines_found)) {
			foreach ($lines_found as $line) {
				$message = str_replace( [ $line . "\n", "\n" . $line ], '', $message );
			}
		}

		$message = str_replace( [ "[remove_line_if_field_empty]" ], '', $message );

		$message = str_replace( [ "\r\n", "\n", "\r", "[remove_line_if_field_empty]" ], '<br />', $message );

		if ($payment_status == 'succeeded') {
			$message = str_replace( '[payment_status]', $succeeded, $message );
		}

		if ($payment_status == 'pending') {
			$message = str_replace( '[payment_status]', $pending, $message );
		}

		if ($payment_status == 'failed') {
			$message = str_replace( '[payment_status]', $failed, $message );
		}

		if (!empty($payment_id)) {
			$message = str_replace( '[payment_id]', $payment_id, $message );
		}

		if (!empty($submit_id)) {
			$message = str_replace( '[submit_id]', $submit_id, $message );
		}

		return $message;
	}

	function get_field_name_shortcode_piotnetforms($content) {
		$field_name = str_replace('[field id="', '', $content);
		$field_name = str_replace('[repeater id="', '', $field_name); // fix alert ]
		$field_name = str_replace('"]', '', $field_name);
		return trim($field_name);
	}

	function piotnetforms_get_field_value($field_name,$fields, $payment_status = 'succeeded', $payment_id = '', $succeeded = 'succeeded', $pending = 'pending', $failed = 'failed', $multiple = false ) {

		$field_name_first = $field_name;

		if (strpos($field_name, '[repeater id') !== false) { // ] [ [ fix alert
			$field_name = str_replace('id="', "id='", $field_name);
			$field_name = str_replace('"]', "']", $field_name);
			$field_label = isset($field['label']) ? $field['label'] : '';
			$message = $field_name;
			$repeater_content = '';
			$repeater_id_one = '';
			foreach ($fields as $field) {
				$field_value = $field['value'];
				if (isset($field['value_label'])) {
					$field_value = $field['value_label'];
				}
				
				$search = '[field id="' . $field['name'] . '"]';
				$message = str_replace($search, $field_value, $message);

				$repeater_id = $field['repeater_id'];
				$repeater_id_string = '';
				$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
				foreach ($repeater_id_array as $repeater) {
					$repeater_array = explode('|', $repeater);
					array_pop($repeater_array);
					$repeater_id_string .= join(",",$repeater_array);
				}
				$repeater_index = $field['repeater_index']; 
				$repeater_index_1 = $repeater_index + 1;
				$repeater_label = $field['repeater_label'] . ' ' . $repeater_index_1 . '\n';

				$repeater_remove_this_field = false;
				if (isset($field['repeater_remove_this_field'])) {
					$repeater_remove_this_field = true;
				}
				
				if (!empty($repeater_id) && !empty($repeater_label) && $repeater_remove_this_field == false) {
					if (strpos($repeater_content, $repeater_label) !== false) {
						$string_add = $field_label . ': ' . $field_value . '\n';
						piotnetforms_merge_string($repeater_content,$string_add);
					} else {
						$string_add = $repeater_label . $field['label'] . ': ' . $field_value . '\n';
						piotnetforms_merge_string($repeater_content,$string_add);
					}
					if (substr_count($field['repeater_id'],'|') == 2) {
						piotnetforms_set_string($repeater_id_one,$field['repeater_id_one']);
					}
				}

				if (empty($repeater_id)) {
					if (!empty($repeater_id_one) && !empty($repeater_content)) {
						$search_repeater = "[repeater id='" . $repeater_id_one . "']";
						$message = str_replace($search_repeater, $repeater_content, $message);

						piotnetforms_unset_string($repeater_content);
						piotnetforms_unset_string($repeater_id_one);
					}
				}
			}

			$field_value = $message;
		} else {
			$field_name = get_field_name_shortcode_piotnetforms($field_name);
			$field_value = '';
			foreach ($fields as $key_field=>$field) {
				if ($fields[$key_field]['name'] == $field_name) {

					if (isset($fields[$key_field]['calculation_results'])) {
						$field_value = $fields[$key_field]['calculation_results'];
					} else {
						$field_value = $fields[$key_field]['value'];
						if (isset($fields[$key_field]['value_label'])) {
                            $field_value = $fields[$key_field]['value_label'];
                        }
						if ($multiple && !empty($fields[$key_field]['value_multiple'])) {
							$field_value = $fields[$key_field]['value_multiple'];
						}
					}
				}
			}
		}

		if (!is_array($field_value)) {
			if (strpos($field_name_first, '[payment_status]') !== false || strpos($field_name_first, '[payment_id]') !== false) {
				if ($payment_status == 'succeeded') {
					$field_value = str_replace( '[payment_status]', $succeeded, $field_name_first );
				}

				if ($payment_status == 'pending') {
					$field_value = str_replace( '[payment_status]', $pending, $field_name_first );
				}

				if ($payment_status == 'failed') {
					$field_value = str_replace( '[payment_status]', $failed, $field_name_first );
				}

				if (!empty($payment_id) && strpos($field_name_first, '[payment_id]') !== false) {
					$field_value = str_replace( '[payment_id]', $payment_id, $field_name_first );
				}
			}

			return trim($field_value);
		} else {
			return $field_value;
		}
		
	}

	function hexToRgb_piotnetforms($hex, $alpha = false) {
		$hex      = str_replace('#', '', $hex);
		$length   = strlen($hex);
		$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
		$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
		$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
		if ( $alpha ) {
		   $rgb['a'] = $alpha;
		}
		return $rgb;
	 }

	function getIndexColumn_piotnetforms($column) {
		$columnArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

		$columnFirstWord = strtoupper( substr($column, 0, 1) );
		$columnSecondWord = strtoupper( substr($column, 1, 2) );
		$index = 0;
		  
		if($columnSecondWord == '') {
		  $index = array_search($columnFirstWord, $columnArray);
		} else {
		  $index = (array_search($columnFirstWord, $columnArray) + 1)*26 + array_search($columnSecondWord, $columnArray);
		}

		return $index;
	}

	function acf_get_field_key_piotnetforms( $field_name, $post_id ) {
		global $wpdb;
		$acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $field_name , 'acf-field' ) );
		// get all fields with that name.
		switch ( count( $acf_fields ) ) {
			case 0: // no such field
				return false;
			case 1: // just one result. 
				return $acf_fields[0]->post_name;
		}
		// result is ambiguous
		// get IDs of all field groups for this post
		$field_groups_ids = array();
		$field_groups = acf_get_field_groups( array(
			'post_id' => $post_id,
		) );
		foreach ( $field_groups as $field_group )
			$field_groups_ids[] = $field_group['ID'];
		
		// Check if field is part of one of the field groups
		// Return the first one.
		foreach ( $acf_fields as $acf_field ) {
			if ( in_array($acf_field->post_parent,$field_groups_ids) )
				return $acf_field->post_name;
		}
		return false;
	}

	/**
	 * Save the image on the server.
	 */
	function save_image_piotnetforms( $base64_img, $title ) {

		// Upload dir.
		$upload_dir  = wp_upload_dir();
		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

		// $img             = str_replace( 'data:image/png;base64,', '', $base64_img );
		// $img             = str_replace( ' ', '+', $img );
		// $decoded         = base64_decode( $img );
		// $filename        = $title;
		// $file_type       = 'image/png';
		// $hashed_filename = $title . '_' . md5( $filename . microtime() ) .'.png';
		$file_type       = 'image/png';
		$data_uri = $base64_img;
		$encoded_image = explode(",", $data_uri)[1];
		$decoded = base64_decode($encoded_image);
		$hashed_filename = $title . '_' . md5( $title . microtime() ) .'.png';

		// Save the image in the uploads directory.
		$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

		$attachment = array(
			'post_mime_type' => $file_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
		);

		$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

		$attach_data = wp_generate_attachment_metadata( $attach_id, $hashed_filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return wp_get_attachment_image_src($attach_id,'full')[0];
	}

	function piotnetforms_ajax_form_builder() {

		global $wpdb;
			if ( !empty($_POST['post_id']) && !empty($_POST['form_id']) && !empty($_POST['fields']) ) {
				$post_id = sanitize_text_field($_POST['post_id']);

				// Validate post_id has post type is piotnetforms
				if ( get_post_type( $post_id ) != 'piotnetforms' ) {
					wp_die();
				}

				$form_id = sanitize_text_field($_POST['form_id']);

				// Validate form_id is valid
				$piotnetforms_data = json_decode( get_post_meta( $post_id, '_piotnetforms_data', true ), true );
				if (!array_key_exists('widgets', $piotnetforms_data) || !array_key_exists($form_id, $piotnetforms_data['widgets'])) {
					wp_die();
				}

				$fields = stripslashes(sanitize_text_field($_POST['fields']));
				$fields = json_decode($fields, true);
				$fields = array_unique($fields, SORT_REGULAR);

				$failed = false;

				$post_url = '';

				$message = '';
				$meta_content = '';

				$upload = wp_upload_dir();
				$upload_dir = $upload['basedir'];
				$upload_dir = $upload_dir . '/piotnetforms/files';

				$attachment = array();

				$not_allowed_extensions = array('php', 'phpt', 'php5', 'php7', 'exe');

				if( !empty($_FILES) ) {
					foreach ($_FILES as $key=>$file) {
						
						for ($i=0; $i < count($file['name']); $i++) { 
							$file_extension = pathinfo( $file['name'][$i], PATHINFO_EXTENSION );

							if(in_array(strtolower($file_extension), $not_allowed_extensions)){
								wp_die();
							}

							$filename_goc = str_replace( '.' . $file_extension, '', $file['name'][$i]);
							$filename = $filename_goc . '-' . uniqid() . '.' . $file_extension;
							$filename = wp_unique_filename( $upload_dir, $filename );
							$new_file = trailingslashit( $upload_dir ) . $filename;

							if ( is_dir( $upload_dir ) && is_writable( $upload_dir ) ) {
								$move_new_file = @ move_uploaded_file( $file['tmp_name'][$i], $new_file );
								if ( false !== $move_new_file ) {
									// Set correct file permissions.
									$perms = 0644;
									@ chmod( $new_file, $perms );

									$file_url = $upload['baseurl'] . '/piotnetforms/files/' . $filename;

									foreach ($fields as $key_field=>$field) {
										if ($key == $field['name']) {
											if ($fields[$key_field]['attach-files'] == 1) {
												$attachment[] = WP_CONTENT_DIR . '/uploads/piotnetforms/files/' . $filename;
											} else {
												$fields[$key_field]['value'] = $fields[$key_field]['value'] . $file_url;
												if ( $i != (count($file['name']) - 1) ) {
													$fields[$key_field]['value'] = $fields[$key_field]['value'] . ' , ';
												}
											}
										}
									}
								}
							}
						}						
					} 
				}

				foreach ($fields as $key_field=>$field) {
					$field_value = $fields[$key_field]['value'];

					if (isset($fields[$key_field]['value_label'])) {
                        $field_value = $fields[$key_field]['value_label'];
                    }
                        
					if (strpos($field_value, 'data:image/png;base64') !== false) {
						$image_url = save_image_piotnetforms( $field_value, $fields[$key_field]['name'] );
						$fields[$key_field]['value'] = $image_url;
					}

					if (isset($fields[$key_field]['attach-files'])) {
						if ($fields[$key_field]['attach-files'] == 1) {
							if (isset($fields[$key_field])) {
								unset($fields[$key_field]);
							}
						}
					}
				}

				$form = array();
				$form['settings'] = $piotnetforms_data['widgets'][ $form_id ]['settings'];

				$body = array(); // Webhook

				$meta_data = array(); // Webhook

				$fields_data = array(); // Webhook

				if ( ! empty( $form['settings']['form_metadata'] ) ) {
					$form_metadata = $form['settings']['form_metadata'];
					$meta_content .= '<br>---<br><br>';
					foreach ($form_metadata as $meta) {
						if ($meta == 'date') {
							$meta_content .= __('Date','piotnetforms') . ': ' . date_i18n( get_option( 'date_format' ) ) . '<br>';
						}
						if ($meta == 'time') {
							$meta_content .= __('Time','piotnetforms') . ': ' . date_i18n( get_option( 'time_format' ) ) . '<br>';
						}
						if ($meta == 'page_url') {
							$meta_content .= __('Page URL','piotnetforms') . ': ' . sanitize_text_field($_POST['referrer']) . '<br>';
						}
						if ($meta == 'user_agent') {
							$meta_content .= __('User Agent','piotnetforms') . ': ' . sanitize_text_field($_SERVER['HTTP_USER_AGENT']) . '<br>';
						}
						if ($meta == 'remote_ip') {
							$meta_content .= __('Remote IP','piotnetforms') . ': ' . sanitize_text_field($_POST['remote_ip']) . '<br>';
						}
					}
				}

				$meta_data['date']['title'] = __('Date','piotnetforms');
				$meta_data['date']['value'] = date_i18n( get_option( 'date_format' ) );
				$meta_data['time']['title'] = __('Time','piotnetforms');
				$meta_data['time']['value'] = date_i18n( get_option( 'time_format' ) );
				$meta_data['page_url']['title'] = __('Page URL','piotnetforms');
				$meta_data['page_url']['value'] = sanitize_text_field($_POST['referrer']);
				$meta_data['user_agent']['title'] = __('User Agent','piotnetforms');
				$meta_data['user_agent']['value'] = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
				$meta_data['remote_ip']['title'] = __('Remote IP','piotnetforms');
				$meta_data['remote_ip']['value'] = sanitize_text_field($_POST['remote_ip']);

				if( in_array('webhook', $form['settings']['submit_actions']) && !empty($form['settings']['webhooks_advanced_data']) ) {
					if ($form['settings']['webhooks_advanced_data'] == 'yes') {
						$body['meta'] = $meta_data;
					}
				}

				$status = '';

				$payment_status = 'succeeded';
				$payment_id = '';

				// Recaptcha

				$recaptcha_check = 1;

				if (!empty($form['settings']['remove_empty_form_input_fields'])) {
					$fields_new = array();
				    foreach ($fields as $field) {
				    	if (!isset($field['calculation_results'])) {
				    		if (!empty($field['value'])) {
					    		$fields_new[] = $field;
					    	}
				    	} else {
				    		if (!empty($field['calculation_results'])) {
					    		$fields_new[] = $field;
					    	}
				    	}
				    }
				    $fields = $fields_new;
				}

				// Filter Hook
					
				$fields = apply_filters( 'piotnetforms/form_builder/fields', $fields );

				// repeater

				$fields_array = array();

				foreach ($fields as $field) {
					$repeater_id = $field['repeater_id'];
					$repeater_index = $field['repeater_index'];
					$repeater_label = $field['repeater_label'];

					if (!empty($repeater_id)) {
						$repeater_id_array = array_reverse( explode(',', rtrim($repeater_id, ',')) );
						$repeater_id_array_new = array();

						if (strpos(rtrim($repeater_id, ','), ',') !== false) {
							for ($i=0; $i < count($repeater_id_array); $i++) { 
								if ($i != count($repeater_id_array) - 1) {
									$repeater_id_array_new[] = str_replace('|' . $field['name'], '', $repeater_id_array[$i]);
								} else {
									$repeater_id_array_new[] = $repeater_id_array[$i];
								}
							}
						} else {
							$repeater_id_array_new = $repeater_id_array;
						}

						$path = join(",",$repeater_id_array_new);
						$path = str_replace('|', ',', $path);
						$path = explode(',',$path);

						set_val_piotnetforms($fields_array,$path,$field['value']);
					} else {
						$field['repeater'] = false;
						$fields_array[$field['name']] = $field;
					}
				}

				array_walk($fields_array, function (& $item) {
					foreach ($item as $key => $value) {
						if (strpos($key, 'index') === 0) {
							$key_new = str_replace('index', '', $key);
							$item[$key_new] = $item[$key];
							unset($item[$key]);
						}
					}
				});

				$form_database_post_id = 0;

				if ($recaptcha_check == 1) {

					// Add to Form Database

					$form_database_post_id = 1;

					// Webhook

					if( in_array('webhook', $form['settings']['submit_actions']) && !empty($form['settings']['webhooks'])) {
						$repeater = array();

						foreach ($fields as $field) {
							$field_name = $field['name'];

							if (strpos($field['name'], 'piotnetforms-end-repeater') === false && empty($field['repeater_id'])) {
								$fields_data[$field_name]['id'] = $field['name'];
								$fields_data[$field_name]['title'] = $field['label'];
								$fields_data[$field_name]['value'] = $field['value'];
							}

							if (!empty($field['repeater_id'])) {
								if (substr_count($field['repeater_id'],',') == 1) {
									$repeater_id = explode('|', $field['repeater_id']);

									if (!in_array($repeater_id[0], $repeater)) {
										$repeater[$repeater_id[0]] = array(
											'repeater_id' => $repeater_id[0],
											'repeater_label' => $field['repeater_label'],
										);
									}
								}

							}
							
						}

						foreach ($repeater as $repeater_item) {
							$fields_data[$repeater_item['repeater_id']]['id'] = $repeater_item['repeater_id'];
							$fields_data[$repeater_item['repeater_id']]['title'] = $repeater_item['repeater_label'];
							$fields_data[$repeater_item['repeater_id']]['value'] = $fields_array[$repeater_item['repeater_id']];
						}

						$body['fields'] = $fields_data;

						$body['form']['id'] = $form['settings']['form_id'];

						$args = [
							'body' => $body,
						];

						$response = wp_remote_post( replace_email_piotnetforms($form['settings']['webhooks'], $fields), $args );
					}

					// Replace redirect

					$redirect = '';

					if (in_array("redirect", $form['settings']['submit_actions'])) {
						$redirect = replace_email_piotnetforms($form['settings']['redirect_to'], $fields);
					}

					// Action Hook

					do_action('piotnetforms/form_builder/new_record',$fields);

					// Email

					if (in_array("email", $form['settings']['submit_actions']) && $failed == false) {

						$to = replace_email_piotnetforms($form['settings']['email_to'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$to = replace_email_piotnetforms( $form['settings']['email_to'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$subject = replace_email_piotnetforms($form['settings']['email_subject'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$subject = replace_email_piotnetforms($form['settings']['email_subject'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$message = replace_email_piotnetforms($form['settings']['email_content'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$message = replace_email_piotnetforms($form['settings']['email_content'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$reply_to = ( ! empty( $form['settings']['email_reply_to'] )) ? $form['settings']['email_reply_to'] : '';
						if (empty($reply_to)) {
							$reply_to = ( ! empty( $form['settings']['email_from'] )) ? $form['settings']['email_from'] : '';
						}
						$reply_to = replace_email_piotnetforms($reply_to, $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['email_from'] ) ) {
							$headers[] = 'From: ' . replace_email_piotnetforms($form['settings']['email_from_name'], $fields, '', '', '', '', '', $form_database_post_id ) . ' <' . replace_email_piotnetforms($form['settings']['email_from'], $fields, '', '', '', '', '', $form_database_post_id ) . '>';
							$headers[] = 'Reply-To: ' . $reply_to;
						}

						if ( ! empty( $form['settings']['email_to_cc'] ) ) {
							$headers[] = 'Cc: ' . replace_email_piotnetforms($form['settings']['email_to_cc'], $fields, '', '', '', '', '', $form_database_post_id );
						}

						if ( ! empty( $form['settings']['email_to_bcc'] ) ) {
							$headers[] = 'Bcc: ' . replace_email_piotnetforms($form['settings']['email_to_bcc'], $fields, '', '', '', '', '', $form_database_post_id );
						}

						$headers[] = 'Content-Type: text/html; charset=UTF-8';

						if (!empty($post_url)) {
							$subject = str_replace( '[post_url]', $post_url, $subject );
							$message = str_replace( '[post_url]', '<a href="' . $post_url . '">' . $post_url . '</a>', $message );
						}

						$status = wp_mail( $to, $subject, $message . $meta_content, $headers, $attachment );

						// if ( ! empty( $form['settings']['email_to_bcc'] ) ) {
						// 	$bcc_emails = explode( ',', replace_email_piotnetforms($form['settings']['email_to_bcc'], $fields, '', '', '', '', '', $form_database_post_id ) );
						// 	foreach ( $bcc_emails as $bcc_email ) {
						// 		wp_mail( trim( $bcc_email ), $subject, $message . $meta_content, $headers, $attachment );
						// 	}
						// }

					}

					if (in_array("email2", $form['settings']['submit_actions']) && $failed == false) {

						// $to = replace_email_piotnetforms($form['settings']['email_to_2'], $fields);

						// $subject = replace_email_piotnetforms($form['settings']['email_subject_2'], $fields);

						// $message = replace_email_piotnetforms($form['settings']['email_content_2'], $fields);

						$to = replace_email_piotnetforms($form['settings']['email_to_2'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$to = replace_email_piotnetforms( $form['settings']['email_to_2'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$subject = replace_email_piotnetforms($form['settings']['email_subject_2'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$subject = replace_email_piotnetforms($form['settings']['email_subject_2'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$message = replace_email_piotnetforms($form['settings']['email_content_2'], $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['piotnetforms_stripe_status_succeeded'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_pending'] ) && ! empty( $form['settings']['piotnetforms_stripe_status_failed'] ) ) {
							$message = replace_email_piotnetforms($form['settings']['email_content_2'], $fields, $payment_status, $payment_id, $form['settings']['piotnetforms_stripe_status_succeeded'], $form['settings']['piotnetforms_stripe_status_pending'], $form['settings']['piotnetforms_stripe_status_failed'], $form_database_post_id );
						}

						$reply_to = ( ! empty( $form['settings']['email_reply_to_2'] )) ? $form['settings']['email_reply_to_2'] : '';
						if (empty($reply_to)) {
							$reply_to = ( ! empty( $form['settings']['email_from_2'] )) ? $form['settings']['email_from_2'] : '';
						}
						$reply_to = replace_email_piotnetforms($reply_to, $fields, '', '', '', '', '', $form_database_post_id );

						if ( ! empty( $form['settings']['email_from_2'] ) ) {
							$headers[] = 'From: ' . replace_email_piotnetforms($form['settings']['email_from_name_2'], $fields, '', '', '', '', '', $form_database_post_id ) . ' <' . replace_email_piotnetforms($form['settings']['email_from_2'], $fields, '', '', '', '', '', $form_database_post_id ) . '>';
							$headers[] = 'Reply-To: ' . $reply_to;
						}

						if ( ! empty( $form['settings']['email_to_cc_2'] ) ) {
							$headers[] = 'Cc: ' . replace_email_piotnetforms($form['settings']['email_to_cc_2'], $fields, '', '', '', '', '', $form_database_post_id );
						}

						if ( ! empty( $form['settings']['email_to_bcc_2'] ) ) {
							$headers[] = 'Bcc: ' . replace_email_piotnetforms($form['settings']['email_to_bcc_2'], $fields, '', '', '', '', '', $form_database_post_id );
						}

						$headers[] = 'Content-Type: text/html; charset=UTF-8';

						if (!empty($post_url)) {
							$subject = str_replace( '[post_url]', $post_url, $subject );
							$message = str_replace( '[post_url]', '<a href="' . $post_url . '">' . $post_url . '</a>', $message );
						}

						$status = wp_mail( $to, $subject, $message, $headers, $attachment );

					}

					foreach ($attachment as $attachment_item) {
						unlink($attachment_item);
					}

					$failed_status = 0;

					if ($failed) {
						$redirect = '';
						$failed_status = 1;
					}

					if ($failed == false && empty($status)) {
						$status = 1;
					}

					$register_message = '';

					echo esc_attr($payment_status) . ',' . esc_attr($status) . ',' . esc_attr($payment_id) . ',' . esc_url($post_url) . ',' . esc_url($redirect) . ',' . esc_attr($register_message) . ',' . esc_attr($failed_status);
				} // End $recaptcha_check = 1;
			}
		wp_die();
	}
?>
