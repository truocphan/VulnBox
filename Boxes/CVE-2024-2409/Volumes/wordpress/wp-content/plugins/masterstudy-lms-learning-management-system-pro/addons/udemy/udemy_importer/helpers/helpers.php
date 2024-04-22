<?php

new STM_LMS_Udemy_Interface();

class STM_LMS_Udemy_Interface {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_change_post_status', array( $this, 'change_status' ) );
	}

	public function change_status() {
		check_ajax_referer( 'stm_lms_change_post_status', 'nonce' );

		if ( ! empty( $_GET['post_id'] ) && ! empty( $_GET['status'] ) ) {
			remove_action( 'save_post', array( $this, 'stm_lms_save' ), 10 );

			$post_id = intval( $_GET['post_id'] );
			$status  = sanitize_text_field( $_GET['status'] );
			$post    = array(
				'post_type'   => 'stm-courses',
				'ID'          => $post_id,
				'post_status' => $status,
			);

			wp_update_post( $post );

			add_action( 'save_post', array( $this, 'stm_lms_save' ), 10 );
			wp_send_json( $status );
		}
	}

}
