<?php
/**
 * Welcart Item Images
 *
 * Functions for product related.
 *
 * @package Welcart
 */

add_action( 'admin_enqueue_scripts', 'usces_item_image_enqueue_scripts' );
add_action( 'wp_ajax_wel_item_image_ajax', 'wel_item_image_ajax' );

/**
 * Hook load js, css use for tab update item image.
 *
 * @param string $hook_suffix name of hook.
 */
function usces_item_image_enqueue_scripts( $hook_suffix ) {

	$action = filter_input( INPUT_GET, 'action' );
	$post   = filter_input( INPUT_GET, 'post', FILTER_DEFAULT, FILTER_REQUIRE_SCALAR );

	if ( ( $action && $post && 'welcart-shop_page_usces_itemedit' === $hook_suffix ) || 'welcart-shop_page_usces_itemnew' === $hook_suffix ) {

		if ( 'welcart-shop_page_usces_itemnew' === $hook_suffix ) {

			$jquery_cookie_url = USCES_FRONT_PLUGIN_URL . '/js/jquery/jquery.cookie.js';
			wp_enqueue_script( 'jquery-cookie', $jquery_cookie_url, array( 'jquery', 'jquery-ui-dialog' ), USCES_VERSION, true );

		}

		if ( 'welcart-shop_page_usces_itemedit' === $hook_suffix || 'welcart-shop_page_usces_itemnew' === $hook_suffix ) {

			$style_item_images = USCES_FRONT_PLUGIN_URL . '/css/admin-item-images.css';
			wp_enqueue_style( 'usces_item_images_css', $style_item_images, array(), '1.0.0', 'all' );

			$js_item_images = USCES_FRONT_PLUGIN_URL . '/js/admin-item-images.js';
			wp_enqueue_script( 'usces_item_images_js', $js_item_images, array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), USCES_VERSION, true );
			wp_localize_script(
				'usces_item_images_js',
				'usces_item_images_js_setting',
				array(
					'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
					'_ajax_nonce'                => wp_create_nonce( 'wel_item_images_nonce' ),
					'title_image_frame'          => __( 'Select Media', 'usces' ),
					'text_close'                 => __( 'close', 'usces' ),
					'msg_choose_item_image'      => __( 'Please choose at least one item image.', 'usces' ),
					'msg_upload_file_size_error' => __( 'File size error.', 'usces' ),
					'msg_nonce_expried'          => __( 'The link you followed has expired. Please refresh your site.', 'usces' ),
				)
			);
		}
	}
}

/**
 * Handle sync item image from old workflow.
 *
 * @param integer $post_id item post id.
 * @param boolean $cache Switch of cache.
 */
function wel_sync_item_images( $post_id, $cache = true ) {
	$meta_key   = '_itemPicts';
	$item_picts = usces_get_post_meta( $post_id, $meta_key, $cache );
	if ( is_array( $item_picts ) && 0 === count( $item_picts ) ) {

		// sync value init.
		$arr_pict_id  = array();
		$main_pict_id = (int) wel_get_main_pict_id( $post_id, $cache );
		if ( false !== $main_pict_id && 0 < $main_pict_id ) {
			$arr_pict_id[] = $main_pict_id;
		}
		$sub_pict_ids = wel_get_sub_pict_ids( $post_id, $cache );
		if ( $sub_pict_ids && is_array( $sub_pict_ids ) ) {
			$arr_pict_id = array_merge( $arr_pict_id, $sub_pict_ids );
		}
		$arr_pict_id_db = array();
		foreach ( $arr_pict_id as $pict_id ) {
			if ( 0 < $pict_id ) {
				$arr_pict_id_db[] = $pict_id;
			}
		}
		if ( 0 < count( $arr_pict_id_db ) ) {
			$arr_pict_id_db = array_unique( $arr_pict_id_db );
			$pict_ids       = implode( ';', $arr_pict_id_db );
		} else {
			$pict_ids = null;
		}
		update_post_meta( $post_id, $meta_key, $pict_ids );
	}
}

/**
 * Build layout box item image.
 *
 * @param object $post object post info.
 */
function wel_post_item_pict_box_html( $post ) {
	$post_id = isset( $post->ID ) ? $post->ID : 0;
	// init sync list image to meta post.
	// wel_sync_item_images( $post_id );.
	wp_enqueue_media( array( 'post' => $post->ID ) );
	$item = wel_get_item( $post_id, false );
	?>
	<div id="wel-item-image-loading">
		<img id="wel-loading-image" src="<?php echo esc_attr( USCES_PLUGIN_URL ) . '/images/box_image-loading.gif'; ?>" alt="Loading..." />
	</div>
	<div id="uscestabs_item_images" class="uscestabs usces_item_image" style="display: none">
	<?php if ( empty( $item ) ) : ?>
		<div class="wel-item-image-notes">
			<?php esc_html_e( 'The item details such as the item code have to be confirmed when setting the product image.', 'usces' ); ?><br>
			<?php esc_html_e( 'Before setting the item image, the item registration has to be finalized by clicking either "Draft" or "Save" button.', 'usces' ); ?>
		</div>
	<?php endif; ?>
		<ul>
			<li><a id="usces_tabs_item_img" href="#uscestabs_item_img"><?php esc_attr_e( 'Image', 'usces' ); ?></a></li>
			<li><a id="usces_tabs_item_file" href="#uscestabs_item_file"><?php esc_attr_e( 'File', 'usces' ); ?></a></li>
			<li><a id="usces_tabs_item_upload" href="#uscestabs_item_upload"><?php esc_attr_e( 'Upload', 'usces' ); ?></a></li>
			<li class="wel-tab-item-apply"><input type='button' class="custom-show-modal" value="<?php echo( esc_attr_x( 'Apply', 'image file', 'usces' ) ); ?>" id="wel_item_img_media_manager"/></li>
		</ul>
		<div id="uscestabs_item_img">
			<?php echo wel_sub_item_pict_box_img_html( $post_id ); ?>
		</div>
		<div id="uscestabs_item_file">
			<?php echo wel_sub_item_pict_box_file_html( $post_id ); ?>
		</div>
		<div id="uscestabs_item_upload">
			<?php echo wel_sub_item_pict_box_upload_html( $post_id ); ?>
		</div>
		<input type="hidden" name="wel_image_post_id" id="wel_image_post_id" value="<?php echo esc_attr( $post_id ); ?>" />
		<div class="wel_item_image_dialog_wrap" id="wel_item_image_dialog_wrap" title="<?php esc_attr_e( 'Error', 'usces' ); ?>">
			<div id="wel_item_image_dialog_content"></div>
		</div>
	</div>
	<?php
}

/**
 * Build layout tab Image.
 *
 * @param integer $post_id item post id.
 */
function wel_sub_item_pict_box_img_html( $post_id ) {
	$item_picts   = array();
	$arr_pict_ids = wel_get_item_pict_ids( $post_id, false );
	foreach ( $arr_pict_ids as $pict_id ) {
		$alt          = trim( strip_tags( get_post_meta( $pict_id, '_wp_attachment_image_alt', true ) ) );
		$post_img     = get_post( $pict_id, ARRAY_A );
		$caption      = isset( $post_img['post_excerpt'] ) ? $post_img['post_excerpt'] : '';
		$item_picts[] = array(
			'pict_id'   => $pict_id,
			'alt'       => $alt,
			'caption'   => $caption,
			'main_img'  => wp_get_attachment_image( $pict_id, array( 270, 270 ), true ),
			'thumb_img' => wp_get_attachment_image( $pict_id, array( 50, 50 ), true ),
		);
	}
	$main_item_pict = isset( $item_picts[0] ) ? $item_picts[0] : array();
	ob_start();
	?>
	<div class="item-main-pict">
		<div class="wel_wrap_img">
			<div id="item-select-pict">
				<?php echo isset( $main_item_pict['main_img'] ) ? $main_item_pict['main_img'] : ''; ?>
			</div>
			<?php if ( isset( $item_picts[0] ) ) { ?>
			<div class="mask">
				<div class="wel-img-item-edit wel-img-alt-text" data-setting="alt">
					<label for="wel-img-alt-text" class="name"><?php _e( 'Alt Text' ); ?></label>
					<input type="text" onblur="wel_item_images.save_pict_info('alt' )" id="wel-img-alt" value="<?php echo esc_attr( $main_item_pict['alt'] ); ?>" >
				</div>
				<div class="wel-img-item-edit wel-img-caption-text" data-setting="caption">
					<label for="wel-img-caption" class="name"><?php _e( 'Caption' ); ?></label>
					<textarea rows="1" id="wel-img-caption" onblur="wel_item_images.save_pict_info('caption')" ><?php echo esc_attr( $main_item_pict['caption'] ); ?></textarea>
				</div>
				<input type="hidden" id="wel-img-pict-id" value="<?php echo (int) $main_item_pict['pict_id']; ?>"/>
				<input type="hidden" id="wel-img-pict-ids" value="<?php echo esc_attr( implode( ',', $arr_pict_ids ) ); ?>" />
			</div>
			<?php } ?>
		</div>
		<div class="clearfix">
			<?php foreach ( $item_picts as $item_pict ) { ?>
				<div class="subpict">
					<a class="wel-view-main-img" id="wel-pict-<?php echo (int) $item_pict['pict_id']; ?>" data-id="<?php echo (int) $item_pict['pict_id']; ?>" data-img='<?php wel_esc_script_e( $item_pict['main_img'] ); ?>' data-alt="<?php echo esc_attr( $item_pict['alt'] ); ?>" data-caption="<?php echo esc_attr( $item_pict['caption'] ); ?>"><?php wel_esc_script_e( $item_pict['thumb_img'] ); ?></a>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

/**
 * Build layout tab File.
 *
 * @param integer $post_id item post id.
 */
function wel_sub_item_pict_box_file_html( $post_id ) {
	ob_start();
	?>
	<div id="wrapper_tab_file_item_pict">
		<?php echo wel_build_list_detail_tab_file( $post_id ); ?>
	</div>
	<div class="file_action">
		<input class="button-primary" type="button" value="<?php esc_attr_e( 'Exclude', 'usces' ); ?>" name="wel_file_exclude" onclick="wel_item_images.exclude_item_image()" id="wel_file_exclude">
		<input class="button-primary wel-item-pict-del" type="button" value="<?php echo( esc_attr_x( 'Delete', 'image file', 'usces' ) ); ?>" name="wel_file_delete" onclick="wel_item_images.check_before_delete_item_image()" id="wel_file_delete">
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

/**
 * Build layout detail list file name.
 *
 * @param integer $post_id item post id.
 */
function wel_build_list_detail_tab_file( $post_id ) {
	$arr_pict_file_name = wel_get_item_pict_filename( $post_id );
	ob_start();
	foreach ( $arr_pict_file_name as $pict_id => $filename ) {
		?>
		<div class="tab_file_item_pict" id="<?php echo esc_attr( $pict_id ); ?>">
			<input type="checkbox" value="<?php echo esc_attr( $pict_id ); ?>" name="file_item_picts[]">
			<label><?php echo esc_html( $filename ) . '(' . esc_attr( $pict_id ) . ')'; ?></label>
		</div>
		<?php
	}
	?>
	<img id="wel-tab-file-loading" style="display: none" src="<?php echo esc_attr( USCES_PLUGIN_URL ) . '/images/loading.gif'; ?>" alt="Loading..." />
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

/**
 * Build layout tab Upload.
 *
 * @param mixed $post_id item post id.
 */
function wel_sub_item_pict_box_upload_html( $post_id ) {
	$max_upload_size = wp_max_upload_size();
	if ( ! $max_upload_size ) {
		$max_upload_size = 0;
	}
	ob_start();
	?>
	<div id="plupload-upload-ui" class="hide-if-no-js">
		<div id="drag-drop-area">
			<div class="drag-drop-inside">
				<p class="drag-drop-info"><?php esc_attr_e( 'Drop files to upload', 'usces' ); ?></p>
				<p><?php echo( esc_attr_x( 'or', 'Uploader: Drop files here - or - Select Files', 'usces' ) ); ?></p>
				<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e( 'Select Files', 'usces' ); ?>" class="button" /></p>
				<p class="max-upload-size">
					<?php
					// translators: %s: Maximum allowed file size.
					printf( esc_html__( 'Maximum upload file size: %s', 'usces' ), esc_html( size_format( $max_upload_size ) ) );
					?>
				</p>
			</div>
		</div>
	</div>
	<?php
	// we should probably not apply this filter, plugins may expect wp's media uploader...
	$plupload_init = wel_plupload_init_item_images( $post_id );
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
		// create the uploader and pass the config from above
		var uploader = new plupload.Uploader(<?php echo wp_json_encode( $plupload_init ); ?>);
		// checks if browser supports drag and drop upload, makes some css adjustments if necessary
		uploader.bind('Init', function (up) {
			var uploaddiv = $('#plupload-upload-ui');
			if (up.features.dragdrop) {
			uploaddiv.addClass('drag-drop');
			$('#drag-drop-area')
				.bind('dragover.wp-uploader', function () {
					uploaddiv.addClass('drag-over');
				})
				.bind('dragleave.wp-uploader, drop.wp-uploader', function () {
					uploaddiv.removeClass('drag-over');
				});
			} else {
				uploaddiv.removeClass('drag-drop');
				$('#drag-drop-area').unbind('.wp-uploader');
			}
		});
		uploader.init();
		uploader.bind('BeforeUpload', function (up, file) {
			// add effect loading.
			$("#uscestabs_item_upload #plupload-upload-ui").addClass("wel-loading-upload");
			$("#uscestabs_item_upload #plupload-browse-button").prop("disabled", true);
		});
		// a file was added in the queue
		uploader.bind('FilesAdded', function (up, files) {
			var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
			plupload.each(files, function (file) {
			if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5') {
				// file size error.
				$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_upload_file_size_error);
				$("#uscestabs_item_images #wel_item_image_dialog_wrap").dialog("open");
				// remove effect loading.
				$("#uscestabs_item_upload #plupload-upload-ui").removeClass("wel-loading-upload");
				$("#uscestabs_item_upload #plupload-browse-button").removeAttr('disabled');
			}
			});
			up.refresh();
			up.start();
		});
		uploader.bind('UploadProgress', function (up, files) {
			$("#item-main-pict #wel-tab-file-loading").show();
		});
		// a file was uploaded 
		uploader.bind('FileUploaded', function (up, file, res) {
			// this is your ajax response, update the DOM with it or something.
			var obj_res = jQuery.parseJSON(res.response);
			if (obj_res.status) {
				// Load list on the tab file.
				$('#uscestabs_item_images #uscestabs_item_file #wrapper_tab_file_item_pict').html(obj_res.data_tab_file);
				// Load list on the tab image.
				$('#uscestabs_item_images #uscestabs_item_img').html(obj_res.data_tab_image);
				// active tab file.
				$("#uscestabs_item_images #usces_tabs_item_file").click();
				// add more scoll.
				$("#uscestabs_item_file #wrapper_tab_file_item_pict").animate({ scrollTop: $("#uscestabs_item_file #wrapper_tab_file_item_pict")[0].scrollHeight}, 700);
			} else {
				$("#item-main-pict #wel-tab-file-loading").hide();
				$("#wel_item_image_dialog_content").append(obj_res.msg);
				$("#wel_item_image_dialog_wrap").dialog("open");
			}
			// remove effect loading.
			$("#uscestabs_item_upload #plupload-upload-ui").removeClass("wel-loading-upload");
			$("#uscestabs_item_upload #plupload-browse-button").removeAttr('disabled');
		});
		});
	</script>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

/**
 * List all file name item pict by item post id.
 *
 * @param integer $post_id item post id.
 *
 * @return array
 */
function wel_get_item_pict_filename( $post_id ) {
	$arr_result   = array();
	$arr_pict_ids = wel_get_item_pict_ids( $post_id, false );
	if ( 0 < count( $arr_pict_ids ) ) {
		foreach ( $arr_pict_ids as $pict_id ) {
			$arr_result[ $pict_id ] = wel_get_item_image_name( $pict_id );
		}
	}
	return $arr_result;
}

/**
 * Get name image from string file.
 *
 * @param int $pict_id file id.
 *
 * @return string file name.
 */
function wel_get_item_image_name( $pict_id ) {
	$file     = get_attached_file( $pict_id );
	$filename = '';
	if ( $file ) {
		$filename = wp_basename( $file );
	}
	return $filename;
}

/**
 * Handle all ajax request of item image.
 */
function wel_item_image_ajax() {
	$arr_res = array(
		'status' => false,
		'data'   => array(),
		'msg'    => '',
	);
	$mode    = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : '';
	$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
	switch ( $mode ) {
		case 'wel_photo_gallery_upload':
			check_ajax_referer( 'photo-upload' );
			$name_image   = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$img_post_ids = wel_item_image_gallery_upload();
			if ( is_integer( $img_post_ids ) ) {
				// register image for item post.
				$result = wel_add_item_image_ids( $post_id, $img_post_ids );
				if ( false !== $result ) {
					$arr_res['status']         = true;
					$arr_res['data_tab_file']  = wel_build_list_detail_tab_file( $post_id );
					$arr_res['data_tab_image'] = wel_sub_item_pict_box_img_html( $post_id );
				} else {
					$arr_res['status'] = false;
					$arr_res['msg']    = '<b>' . esc_attr( $name_image ) . '</b>';
					$arr_res['msg']   .= __( 'Upload item image fail', 'usces' ) . '<br>';
				}
			} else {
				$arr_res['status'] = false;
				if ( isset( $img_post_ids->errors['upload_error'] ) ) {
					$arr_res['msg']  = '<b>' . esc_attr( $name_image ) . '</b>';
					$arr_res['msg'] .= '<div>';
					foreach ( $img_post_ids->errors['upload_error'] as $msg ) {
						$arr_res['msg'] .= esc_attr( $msg ) . '<br>';
					}
					$arr_res['msg'] .= '</div>';
				}
			}
			break;
		case 'choose_images_from_media':
			check_ajax_referer( 'wel_item_images_nonce' );
			$str_pict_ids = isset( $_POST['str_pict_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['str_pict_ids'] ) ) : '';
			if ( ! empty( $str_pict_ids ) ) {
				// register image for item post.
				$img_post_ids              = explode( ',', $str_pict_ids );
				$img_post_ids              = wel_validate_item_pict_ids( $img_post_ids );
				$result                    = wel_add_item_image_ids( $post_id, $img_post_ids );
				$arr_res['status']         = true;
				$arr_res['data_tab_file']  = wel_build_list_detail_tab_file( $post_id );
				$arr_res['data_tab_image'] = wel_sub_item_pict_box_img_html( $post_id );
			}
			break;
		case 'sort_order_item_image':
			check_ajax_referer( 'wel_item_images_nonce' );
			$item_pict_ids = filter_input( INPUT_POST, 'item_pict_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$item_pict_ids = wel_validate_item_pict_ids( $item_pict_ids );
			if ( is_array( $item_pict_ids ) && 0 < count( $item_pict_ids ) ) {
				// update new item image.
				$update = wel_update_item_pict( $post_id, $item_pict_ids );
				if ( false !== $update ) {
					$arr_res['status']         = true;
					$arr_res['data_tab_file']  = wel_build_list_detail_tab_file( $post_id );
					$arr_res['data_tab_image'] = wel_sub_item_pict_box_img_html( $post_id );
				} else {
					$arr_res['status'] = false;
					$arr_res['msg']    = __( 'Sort item images fail', 'usces' );
				}
			}
			break;
		case 'exclude_item_image':
			check_ajax_referer( 'wel_item_images_nonce' );
			$exclude_pict_ids = filter_input( INPUT_POST, 'item_pict_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$exclude_pict_ids = array_map( 'intval', $exclude_pict_ids );
			if ( is_array( $exclude_pict_ids ) && 0 < count( $exclude_pict_ids ) ) {
				$arr_pict_ids   = array();
				$arr_item_picts = wel_get_item_pict_ids( $post_id, false );
				foreach ( $arr_item_picts as $pict_id ) {
					if ( ! in_array( (int) $pict_id, $exclude_pict_ids, true ) ) {
						$arr_pict_ids[] = (int) $pict_id;
					}
				}
				// update new item image.
				$update = wel_update_item_pict( $post_id, $arr_pict_ids );
				if ( false !== $update ) {
					$arr_res['status']         = true;
					$arr_res['data_tab_file']  = wel_build_list_detail_tab_file( $post_id );
					$arr_res['data_tab_image'] = wel_sub_item_pict_box_img_html( $post_id );
				} else {
					$arr_res['status'] = false;
					$arr_res['msg']    = __( 'Exclude item images fail', 'usces' );
				}
			}
			break;
		case 'validate_item_image_before_delete':
			check_ajax_referer( 'wel_item_images_nonce' );
			$item_pict_ids = filter_input( INPUT_POST, 'item_pict_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$item_pict_ids = array_map( 'intval', $item_pict_ids );
			if ( is_array( $item_pict_ids ) && 0 < count( $item_pict_ids ) ) {
				$check_in_other_product = false;
				foreach ( $item_pict_ids as $item_pict_id ) {
					$check_post_id = wel_check_item_pict_id_other_product( $post_id, $item_pict_id );
					if ( 0 < $check_post_id ) {
						$check_in_other_product = true;
						break;
					}
				}
				$arr_res['status']                 = true;
				$arr_res['check_in_other_product'] = $check_in_other_product;
				$arr_res['msg_show_confirm']       = '';
				if ( $check_in_other_product ) {
					$arr_res['msg_show_confirm'] = __( 'This image also applies to other products. Are you sure you want to delete?', 'usces' );
				}
				$arr_res['none_item_delete'] = wp_create_nonce( 'wel_item_images_delete_none' );
			}
			break;
		case 'delete_item_image':
			check_ajax_referer( 'wel_item_images_delete_none' );
			$delete_pict_ids = filter_input( INPUT_POST, 'item_pict_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$delete_pict_ids = array_map( 'intval', $delete_pict_ids );
			if ( is_array( $delete_pict_ids ) && 0 < count( $delete_pict_ids ) ) {
				// update exclude other item product if has.
				foreach ( $delete_pict_ids as $del_pict_id ) {
					// find all other product has pict id.
					$other_post_ids = wel_get_all_item_pict_id_other_product( $del_pict_id );
					foreach ( $other_post_ids as $item_id ) {
						$arr_pict_ids   = array();
						$arr_item_picts = wel_get_item_pict_ids( $item_id, false );
						foreach ( $arr_item_picts as $pict_id ) {
							if ( (int) $pict_id !== (int) $del_pict_id ) {
								$arr_pict_ids[] = (int) $pict_id;
							}
						}
						wel_update_item_pict( $item_id, $arr_pict_ids );
					}
					// delete form media WP.
					wp_delete_attachment( (int) $del_pict_id, true );
				}
				$arr_res['status']         = true;
				$arr_res['data_tab_file']  = wel_build_list_detail_tab_file( $post_id );
				$arr_res['data_tab_image'] = wel_sub_item_pict_box_img_html( $post_id );
			} else {
				$arr_res['status'] = false;
				$arr_res['msg']    = __( 'Delete item images fail', 'usces' );
			}
			break;
		case 'save_pict_info':
			check_ajax_referer( 'wel_item_images_nonce' );
			wel_save_pict_info( $arr_res );
			break;
	}
	wp_send_json( $arr_res );
}

/**
 * Format all pict item id to integer.
 *
 * @param array $item_pict_ids item pict ids.
 *
 * @return array
 */
function wel_validate_item_pict_ids( $item_pict_ids ) {
	$result_item_pict_ids = array();
	if ( is_array( $item_pict_ids ) && 0 < count( $item_pict_ids ) ) {
		foreach ( $item_pict_ids as $item_pict_id ) {
			$item_pict_id = (int) $item_pict_id;
			if ( 0 < $item_pict_id ) {
				$result_item_pict_ids[] = $item_pict_id;
			}
		}
	}
	return $result_item_pict_ids;
}

/**
 * Update pict id for item post.
 *
 * @param integer $post_id item post id.
 * @param array   $arr_pict_ids array pict id.
 */
function wel_update_item_pict( $post_id, $arr_pict_ids ) {

	$item              = wel_get_item( $post_id, false );
	$item['itemPicts'] = (array) $arr_pict_ids;
	$update            = wel_update_item_data( $item, $post_id );

	return $update;
}

/**
 * Add new IDs to the current product image IDs.
 *
 * @param integer $post_id item Post ID.
 * @param array   $new_ids array New Pict ids.
 */
function wel_add_item_image_ids( $post_id, $new_ids ) {

	if ( is_int( $new_ids ) ) {
		$new_ids = array( $new_ids );
	} elseif ( ! is_array( $new_ids ) ) {
		return false;
	}

	$current_ids = wel_get_item_pict_ids( $post_id, false );

	foreach ( $new_ids as $pict_id ) {
		if ( ! in_array( (int) $pict_id, $current_ids, true ) ) {
			$current_ids[] = (int) $pict_id;
		}
	}

	$item              = wel_get_item( $post_id, false );
	$item['itemPicts'] = $current_ids;
	$update            = wel_update_item_data( $item, $post_id );
	return $update;
}

/**
 * Handle build param for tab upload image.
 *
 * @param integer $post_id item post id.
 *
 * @return array
 */
function wel_plupload_init_item_images( $post_id ) {
	$plupload_init = array(
		'runtimes'            => 'html5,silverlight,flash,html4',
		'browse_button'       => 'plupload-browse-button',
		'container'           => 'plupload-upload-ui',
		'drop_element'        => 'drag-drop-area',
		'file_data_name'      => 'async-upload',
		'multiple_queues'     => true,
		'max_file_size'       => wp_max_upload_size() . 'b',
		'url'                 => admin_url( 'admin-ajax.php' ),
		'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
		'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
		'filters'             => array(
			array(
				'title'      => __( 'Allowed Files' ),
				'extensions' => 'jpg,jpeg,jpe,png,gif,webp,jfif', // 'jpg', 'jpeg', 'jpe', 'png', 'gif', 'webp' or * (for all)
			),
		),
		'multipart'           => true,
		'urlstream_upload'    => true,
		// additional post data to send to our ajax hook.
		'multipart_params'    => array(
			'_ajax_nonce' => wp_create_nonce( 'photo-upload' ),
			'action'      => 'wel_item_image_ajax',
			'mode'        => 'wel_photo_gallery_upload',
			'post_id'     => $post_id,
		),
	);

	// Should probably not apply this filter, plugins may expect wp's media uploader.
	return apply_filters( 'plupload_init', $plupload_init );
}

/**
 * Handle upload image to media WP.
 *
 * @return pict_id OR WP error object.
 */
function wel_item_image_gallery_upload() {
	$img_post_id = media_handle_upload(
		'async-upload',
		0,
		array(),
		array(
			'test_form' => true,
			'action'    => 'wel_item_image_ajax',
		)
	);
	return $img_post_id;
}

/**
 * Check pict id has used other product item.
 *
 * @param integer $post_id item post id.
 * @param array   $item_pict_id array item pict id.
 *
 * @return boolean
 */
function wel_check_item_pict_id_other_product( $post_id, $item_pict_id ) {
	global $wpdb;

	$table        = $wpdb->prefix . 'usces_item';
	$item_pict_id = (int) $item_pict_id;
	$val_case_1   = $item_pict_id;
	$val_case_2   = $wpdb->esc_like( $item_pict_id ) . ';%';
	$val_case_3   = '%;' . $wpdb->esc_like( $item_pict_id ) . ';%';
	$val_case_4   = '%;' . $wpdb->esc_like( $item_pict_id );
	$result       = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT `post_id` FROM {$table} WHERE post_id <> %d AND ( `itemPicts` = %s OR `itemPicts` LIKE %s OR `itemPicts` LIKE %s OR `itemPicts` LIKE %s )",
			(int) $post_id,
			$val_case_1,
			$val_case_2,
			$val_case_3,
			$val_case_4
		)
	);
	return (int) $result;
}

/**
 * Handle get all list item post used by pict id.
 *
 * @param integer $item_pict_id pict id.
 *
 * @return array
 */
function wel_get_all_item_pict_id_other_product( $item_pict_id ) {
	global $wpdb;

	$table        = $wpdb->prefix . 'usces_item';
	$item_pict_id = (int) $item_pict_id;
	$val_case_1   = $item_pict_id;
	$val_case_2   = $wpdb->esc_like( $item_pict_id ) . ';%';
	$val_case_3   = '%;' . $wpdb->esc_like( $item_pict_id ) . ';%';
	$val_case_4   = '%;' . $wpdb->esc_like( $item_pict_id );
	$post_ids     = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT `post_id` FROM {$table} WHERE `itemPicts` = %s OR `itemPicts` LIKE %s OR `itemPicts` LIKE %s OR `itemPicts` LIKE %s",
			$val_case_1,
			$val_case_2,
			$val_case_3,
			$val_case_4
		)
	);
	return $post_ids;
}

/**
 * Ajax handler for updating image attributes.
 *
 * @param array $arr_res array response.
 */
function wel_save_pict_info( &$arr_res ) {
	$field   = filter_input( INPUT_POST, 'field' );
	$pict_id = (int) filter_input( INPUT_POST, 'pict_id' );
	$value   = filter_input( INPUT_POST, 'value' );
	switch ( $field ) {
		case 'alt':
			$alt = wp_unslash( $value );
			if ( get_post_meta( $pict_id, '_wp_attachment_image_alt', true ) !== $alt ) {
				$alt = wp_strip_all_tags( $alt, true );
				update_post_meta( $pict_id, '_wp_attachment_image_alt', wp_slash( $alt ) );
			}
			$arr_res['status'] = true;
			break;
		case 'caption':
			$post = get_post( $pict_id, ARRAY_A );
			if ( $post ) {
				$post['post_excerpt'] = $value;
				wp_update_post( $post );
			}
			$arr_res['status'] = true;
			break;
	}
}
