<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once __DIR__ . '/../source/import.php';

function piotnetforms_get_templates() {
    $templates = [];

    $dir   = __DIR__ . '/../../assets/forms/templates/';
    $files = array_diff( scandir( $dir ), [ '.', '..' ] );

    foreach ( $files as $file ) {
        $content     = file_get_contents( $dir . $file );
        $data        = json_decode( $content, true );
        $templates[] = [
            'filename' => $file,
            'title'    => $data['title'],
        ];
    }
    return $templates;
}

function piotnetforms_import( $file_content ) {
    $data = json_decode( $file_content, true );
    if ( json_last_error() > 0 ) {
        $last_error_msg = json_last_error_msg();
        print_r( "Can't parse file: " . $last_error_msg );
    } elseif ( array_key_exists( 'error', $data ) ) {
        print_r( 'Error: ' . $data['error'] );
    } else {
        $post = [
            'post_title'  => $data['title'],
            'post_status' => 'publish',
            'post_type'   => 'piotnetforms',
        ];

        $post_id = wp_insert_post( $post );

        if ( is_wp_error( $post_id ) ) {
            print_r( "Can't insert post: " . $post_id->get_error_message() );
        } else {
            piotnetforms_do_import( $post_id, $data );
            print_r( 'Successfully Imported: ' . $data['title'] );
        }
    }
}

if ( isset( $_POST['action'] ) ) {
    $import_action = $_POST['action'];
    if ( 'import_json_file' === $import_action && isset( $_POST['import_nonce'] ) && wp_verify_nonce( $_POST['import_nonce'], 'import_action' ) ) {
        $file_content = file_get_contents( $_FILES['json_file']['tmp_name'] );
        piotnetforms_import( $file_content );
    } elseif ( 'select_template' === $import_action && isset( $_POST['select_template_nonce'] ) && wp_verify_nonce( $_POST['select_template_nonce'], 'select_template_action' ) && isset( $_POST['templates'] ) ) {
        $template     = $_POST['templates'];
        $file_content = file_get_contents( __DIR__ . '/../../assets/forms/templates/' . $template );
        piotnetforms_import( $file_content );
    }
}

?>
<form method="post" enctype="multipart/form-data" action="">
    <?php
    wp_nonce_field( 'import_action', 'import_nonce' );
    ?>
    <h1 style="margin-bottom: 50px;">Import JSON File</h1>
    <input type="file" id="json_file" name="json_file">
    <input type="hidden" name="action" value="import_json_file">
    <?php submit_button( __( 'Import', 'piotnetforms' ) ); ?>
</form>

<form method="post" action="">
    <?php
    wp_nonce_field( 'select_template_action', 'select_template_nonce' );
    ?>
    <h1 style="margin-bottom: 50px;">Select from templates</h1>
    <select name="templates" id="templates">
        <?php
        $templates = piotnetforms_get_templates();
        foreach ( $templates as $template ) {
            echo '<option value="' . esc_attr( $template['filename'] ) . '">' . esc_html( $template['title'] . ' || ' . $template['filename'] ) . '</option>';
        }
        ?>
    </select>
    <input type="hidden" name="action" value="select_template">
    <?php submit_button( __( 'Select', 'piotnetforms' ) ); ?>
</form>
