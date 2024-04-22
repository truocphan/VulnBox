<?php

function stm_lms_answers_load_template($tpl, $args = []) {
	extract($args);
    require STM_LMS_PATH . "/settings/answers/tpls/{$tpl}.php";
}

add_action('wp_ajax_stm_lms_answers_upload_image', 'stm_lms_answers_upload_image');

function stm_lms_answers_upload_image() {
    $is_valid_image = Validation::is_valid($_FILES, array(
        'file' => 'required_file|extension,png;jpg;jpeg'
    ));

    if ( $is_valid_image !== true ) {
        wp_send_json(array(
            'error' => true,
            'message' => $is_valid_image[0]
        ));
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('file', 0);
	do_action( 'stm_lms_wizard_answers_upload_image', $attachment_id );
    if ( is_wp_error($attachment_id) ) {
        wp_send_json(array(
            'error' => true,
            'message' => $attachment_id->get_error_message()
        ));
    }

    $image = wp_get_attachment_image_src($attachment_id, 'img-480-380');

    wp_send_json(array(
        'files' => $_FILES,
        'id' => $attachment_id,
        'url' => $image[0],
        'error' => false,
    ));
}