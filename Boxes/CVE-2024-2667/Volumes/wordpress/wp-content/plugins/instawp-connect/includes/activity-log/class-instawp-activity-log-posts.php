<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Posts {

	public function __construct() {
		add_action( 'transition_post_status', array( $this, 'hooks_transition_post_status' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'hooks_delete_post' ) );
	}

	public function hooks_transition_post_status( $new_status, $old_status, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'auto-draft' === $old_status && ( 'auto-draft' !== $new_status && 'inherit' !== $new_status ) ) {
			$action = 'post_created';
		} elseif ( 'auto-draft' === $new_status || ( 'new' === $old_status && 'inherit' === $new_status ) ) {
			return;
		} elseif ( 'trash' === $new_status ) {
			$action = 'post_trashed';
		} elseif ( 'trash' === $old_status ) {
			$action = 'post_restored';
		} else {
			$action = 'post_updated';
		}

		// Check auto save or revision.
		if ( wp_is_post_autosave( $post->ID ) || wp_is_post_revision( $post->ID ) ) {
			return;
		}

		// Skip for menu items.
		if ( 'nav_menu_item' === get_post_type( $post->ID ) ) {
			return;
		}

		InstaWP_Activity_Log::insert_log( array(
			'action'         => $action,
			'object_type'    => 'Posts',
			'object_subtype' => $post->post_type,
			'object_id'      => $post->ID,
			'object_name'    => $this->_draft_or_post_title( $post->ID ),
		) );
	}

	public function hooks_delete_post( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return;
		}

		if ( in_array( $post->post_status, array( 'auto-draft', 'inherit' ) ) ) {
			return;
		}

		// Skip for menu items.
		if ( 'nav_menu_item' === get_post_type( $post->ID ) ) {
			return;
		}

		InstaWP_Activity_Log::insert_log( array(
			'action'         => 'post_deleted',
			'object_type'    => 'Posts',
			'object_subtype' => $post->post_type,
			'object_id'      => $post->ID,
			'object_name'    => $this->_draft_or_post_title( $post->ID ),
		) );
	}

	protected function _draft_or_post_title( $post = 0 ) {
		$title = esc_html( get_the_title( $post ) );

		if ( empty( $title ) ) {
			$title = __( '(no title)', 'instawp-connect' );
		}

		return $title;
	}
}

new InstaWP_Activity_Log_Posts();