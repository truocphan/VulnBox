<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Attachments {

	public function __construct() {
		add_action( 'add_attachment', array( $this, 'hooks_add_attachment' ) );
		add_action( 'edit_attachment', array( $this, 'hooks_edit_attachment' ) );
		add_action( 'delete_attachment', array( $this, 'hooks_delete_attachment' ) );
	}

	public function hooks_delete_attachment( $attachment_id ) {
		$this->add_log_attachment( 'attachment_deleted', $attachment_id );
	}

	public function hooks_edit_attachment( $attachment_id ) {
		$this->add_log_attachment( 'attachment_updated', $attachment_id );
	}

	public function hooks_add_attachment( $attachment_id ) {
		$this->add_log_attachment( 'attachment_uploaded', $attachment_id );
	}

	private function add_log_attachment( $action, $attachment_id ) {
		$post = get_post( $attachment_id );

		InstaWP_Activity_Log::insert_log( array(
			'action'         => $action,
			'object_type'    => 'Attachments',
			'object_subtype' => $post->post_type,
			'object_id'      => $attachment_id,
			'object_name'    => esc_html( get_the_title( $post->ID ) ),
		) );
	}
}

new InstaWP_Activity_Log_Attachments();