<?php
$dbhandler      = new PM_DBhandler();
$pmrequests     = new PM_request();
$current_user   = wp_get_current_user();
$pmexportimport = new PM_Export_Import();
$pm_sanitize = new PM_sanitizer;
$post           = $pm_sanitize->sanitize($_POST);
$filefield      = isset($_FILES['uploadcsv']) ? $_FILES['uploadcsv'] : array();
$allowed_ext    ='csv';
switch ( $post['pm_import_step'] ) {
    case 1:
        $attachment_id = $pmrequests->make_upload_and_get_attached_id( $filefield, $allowed_ext );

        if ( is_numeric( $attachment_id ) ) {
            echo "<input type='hidden' name='attachment_id' id='attachment_id' value='" . esc_attr( $attachment_id ) . "' />";
        } else {
            echo '<p class="pm-popup-error" style="display:block;">' . esc_html( $attachment_id ) . '</p>';
        }
        break;

    case 2:
        $pmexportimport->pm_generate_mapping_table( $post );
        break;

    case 3:
         $pmexportimport->pm_import_users_from_csv( $post );

        break;

    default:
        break;


}
