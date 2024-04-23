<?php

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_Post {

	public $restricted_cpts = array(
		'shop_order',
		'customize_changeset',
		'revision',
		'nav_menu_item',
		'custom_css',
		'oembed_cache',
		'user_request',
//      'wp_template',
//      'wp_template_part',
//      'wp_global_styles'
	);

	public function __construct() {

		$this->restricted_cpts = (array) apply_filters( 'INSTAWP_CONNECT/Filters/two_way_sync_restricted_post_types', $this->restricted_cpts );

		// Post Actions.
		add_action( 'wp_after_insert_post', array( $this, 'handle_post' ), 999, 4 );
		add_action( 'elementor/document/after_save', array( $this, 'handle_elementor' ), 999 ); // elementor
		add_action( 'before_delete_post', array( $this, 'delete_post' ), 10, 2 );
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );

		// Media Actions.
		add_action( 'add_attachment', array( $this, 'add_attachment' ) );
		add_action( 'attachment_updated', array( $this, 'attachment_updated' ), 10, 3 );

		// Duplicate post
		add_filter( 'duplicate_post_excludelist_filter', array( $this, 'custom_fields_filter' ) );
		add_filter( 'duplicate_post_post_copy', array( $this, 'generate_reference' ), 10, 2 );

		// Process event
		add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
	}

	/**
	 * Function for `wp_insert_post` action-hook.
	 *
	 * @param int $post Post ID.
	 *
	 * @return void
	 */
	public function handle_post( $post_id, $post, $update, $post_before ) {
		$post = get_post( $post_id );

		if ( ! $post || ! $update || ! InstaWP_Sync_Helpers::can_sync( 'post' ) || in_array( $post->post_type, $this->restricted_cpts ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check auto save or revision.
		if ( wp_is_post_autosave( $post->ID ) || wp_is_post_revision( $post->ID ) ) {
			return;
		}

		// Check post status auto draft.
		if ( in_array( $post->post_status, array( 'auto-draft', 'trash' ) ) ) {
			return;
		}

		// acf field group check
		if ( $post->post_type == 'acf-field-group' && $post->post_content == '' ) {
			InstaWP_Sync_Helpers::set_post_reference_id( $post->ID );
			return;
		}

		// acf check for acf post type
		if ( in_array( $post->post_type, array( 'acf-post-type', 'acf-taxonomy' ) ) && $post->post_title == 'Auto Draft' ) {
			return;
		}

		$singular_name = InstaWP_Sync_Helpers::get_post_type_name( $post->post_type );
		$this->handle_post_events( sprintf( __( '%s modified', 'instawp-connect' ), $singular_name ), 'post_change', $post, $post_before );
	}

	/**
	 * After document save.
	 *
	 * @param \Elementor\Core\Base\Document $this The current document.
	 * @param $data .
	 */
	public function handle_elementor( $document ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'post' ) ) {
			return;
		}

		$post = $document->get_post();
		$this->handle_post( $post->ID, $post, true, null );
	}

	public function custom_fields_filter( $meta_excludelist ) {
		$meta_excludelist[] = 'instawp_event_sync_reference_id';

		return $meta_excludelist;
	}

	public function generate_reference( $new_post_id, $post ) {
		$reference_id = InstaWP_Tools::get_random_string();
		update_post_meta( $new_post_id, 'instawp_event_sync_reference_id', $reference_id );
	}

	/**
	 * Function for `after_delete_post` action-hook.
	 *
	 * @param int $post_id Post ID.
	 * @param WP_Post $post Post object.
	 *
	 * @return void
	 */
	public function delete_post( $post_id, $post ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'post' ) || in_array( $post->post_type, $this->restricted_cpts ) ) {
			return;
		}

		if ( get_post_type( $post_id ) !== 'revision' ) {
			$event_name = sprintf( __( '%s deleted', 'instawp-connect' ), InstaWP_Sync_Helpers::get_post_type_name( $post->post_type ) );
			$this->handle_post_events( $event_name, 'post_delete', $post );
		}
	}

	/**
	 * Fire a callback only when my-custom-post-type posts are transitioned to 'publish'.
	 *
	 * @param string $new_status New post status.
	 * @param string $old_status Old post status.
	 * @param WP_Post $post Post object.
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'post' ) || in_array( $post->post_type, $this->restricted_cpts ) ) {
			return;
		}

		if ( $new_status === 'trash' && $new_status !== $old_status && $post->post_type !== 'customize_changeset' ) {
			$event_name = sprintf( __( '%s trashed', 'instawp-connect' ), InstaWP_Sync_Helpers::get_post_type_name( $post->post_type ) );
			$this->handle_post_events( $event_name, 'post_trash', $post );
		}

		if ( $new_status === 'draft' && $old_status === 'trash' ) {
			$event_name = sprintf( __( '%s restored', 'instawp-connect' ), InstaWP_Sync_Helpers::get_post_type_name( $post->post_type ) );
			$this->handle_post_events( $event_name, 'untrashed_post', $post );
		}

		if ( $old_status === 'auto-draft' && $new_status !== $old_status ) {
			$event_name = sprintf( __( '%s created', 'instawp-connect' ), InstaWP_Sync_Helpers::get_post_type_name( $post->post_type ) );
			$this->handle_post_events( $event_name, 'post_new', $post );
		}
	}

	/**
	 * Function for `add_attachment` action-hook
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	public function add_attachment( $post_id ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'post' ) ) {
			return;
		}

		$event_name = esc_html__( 'Media created', 'instawp-connect' );
		$this->handle_post_events( $event_name, 'post_new', $post_id );
	}

	/**
	 * Function for `attachment_updated` action-hook
	 *
	 * @param $post_id
	 * @param $post_after
	 * @param $post_before
	 *
	 * @return void
	 */
	public function attachment_updated( $post_id, $post_after, $post_before ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'post' ) ) {
			return;
		}

		$event_name = esc_html__( 'Media updated', 'instawp-connect' );
		$this->handle_post_events( $event_name, 'post_change', $post_after );
	}

	public function parse_event( $response, $v ) {
		$reference_id = $v->source_id;
		$details      = InstaWP_Sync_Helpers::object_to_array( $v->details );

		// create and update
		if ( in_array( $v->event_slug, array( 'post_change', 'post_new' ), true ) ) {
			InstaWP_Sync_Helpers::parse_post_events( $details );

			return InstaWP_Sync_Helpers::sync_response( $v );
		}

		// trash, untrash and delete
		if ( in_array( $v->event_slug, array( 'post_trash', 'post_delete', 'untrashed_post' ), true ) ) {
			$wp_post   = isset( $details['post'] ) ? $details['post'] : array();
			$post_name = $wp_post['post_name'];
			$function  = 'wp_delete_post';
			$data      = array();
			$logs      = array();

			if ( $v->event_slug !== 'post_delete' ) {
				$post_name = ( $v->event_slug === 'untrashed_post' ) ? $wp_post['post_name'] . '__trashed' : str_replace( '__trashed', '', $wp_post['post_name'] );
				$function  = ( $v->event_slug === 'untrashed_post' ) ? 'wp_untrash_post' : 'wp_trash_post';
			}
			$post_by_reference_id = InstaWP_Sync_Helpers::get_post_by_reference( $wp_post['post_type'], $reference_id, $post_name );

			if ( ! empty( $post_by_reference_id ) ) {
				$post_id = $post_by_reference_id->ID;
				$post    = call_user_func( $function, $post_id );
				$status  = isset( $post->ID ) ? 'completed' : 'pending';
				$message = isset( $post->ID ) ? 'Sync successfully.' : 'Something went wrong.';

				$data = compact( 'status', 'message' );
			} else {
				$logs[ $v->id ] = sprintf( '%s not found at destination', ucfirst( str_replace( array( '-', '_' ), '', $wp_post['post_type'] ) ) );
			}

			return InstaWP_Sync_Helpers::sync_response( $v, $logs, $data );
		}

		return $response;
	}

	/**
	 * Function for `handle_post_events`
	 *
	 * @param $event_name
	 * @param $event_slug
	 * @param $post
	 * @param $post_before
	 *
	 * @return void
	 */
	private function handle_post_events( $event_name, $event_slug, $post, $post_before = null ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$data         = InstaWP_Sync_Helpers::parse_post_data( $post, $post_before );
		$reference_id = isset( $data['reference_id'] ) ? $data['reference_id'] : '';

		$event_type = $post->post_type;
		$title      = $post->post_title;
		$data       = apply_filters( 'INSTAWP_CONNECT/Filters/two_way_sync_post_data', $data, $event_type, $post );

		if ( is_array( $data ) && ! empty( $reference_id ) ) {
			InstaWP_Sync_DB::insert_update_event( $event_name, $event_slug, $event_type, $reference_id, $title, $data );
		}
	}
}

new InstaWP_Sync_Post();
