<?php

use WprAddons\Plugin;
use WprAddons\Classes\Utilities;

// Add second featured image
add_action( 'add_meta_boxes', 'secondary_image_add_metabox' );
function secondary_image_add_metabox () {
    $post_types = Utilities::get_custom_types_of( 'post', false );

    foreach ( $post_types as $key => $value ) {
        $meta_option = get_option( 'wpr_meta_secondary_image_' . $key );

        if ( 'page' !== $key && 'e-landing-page' !== $key && $meta_option === 'on' ) {
            add_meta_box( 'wpr-secondary-image', __( 'Secondary Image', 'wpr-addons' ), 'secondary_image_metabox', $key, 'side', 'low');
        }
    }
}

function secondary_image_metabox ( $post ) {
    global $content_width, $_wp_additional_image_sizes;

    $image_id = get_post_meta( $post->ID, 'wpr_secondary_image_id', true );

    $old_content_width = $content_width;
    $content_width = 254;

    if ( $image_id && get_post( $image_id ) ) {

        if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
            $thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
        } else {
            $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
        }

        if ( ! empty( $thumbnail_html ) ) {
            $content = $thumbnail_html;
            $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_secondary_image_button" >' . esc_html__( 'Remove secondary image', 'text-domain' ) . '</a></p>';
            $content .= '<input type="hidden" id="upload_secondary_image" name="wpr_secondary_cover_image" value="' . esc_attr( $image_id ) . '" />';
        }

        $content_width = $old_content_width;
    } else {

        $content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
        $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set secondary image', 'text-domain' ) . '" href="javascript:;" id="upload_secondary_image_button" id="set-secondary-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set secondary image', 'text-domain' ) . '">' . esc_html__( 'Set secondary image', 'text-domain' ) . '</a></p>';
        $content .= '<input type="hidden" id="upload_secondary_image" name="wpr_secondary_cover_image" value="" />';

    }

    echo $content;
}

add_action( 'save_post', 'secondary_image_save', 10, 1 );
function secondary_image_save ( $post_id ) {
    if( isset( $_POST['wpr_secondary_cover_image'] ) ) {
        $image_id = (int) $_POST['wpr_secondary_cover_image'];
        update_post_meta( $post_id, 'wpr_secondary_image_id', $image_id );
    }
}


function wpdocs_selectively_enqueue_admin_script( $hook ) {
    if ( 'post.php' != $hook ) {
        return;
    }
    // Get Plugin Version
    $version = Plugin::instance()->get_version();
    // enqueue JS
    wp_enqueue_script( 'wpr-secondary-image-js', WPR_ADDONS_URL .'assets/js/admin/metabox/secondary-image.js', ['jquery'], $version );
}

add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );