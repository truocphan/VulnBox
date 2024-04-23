<?php

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_Term {

    public function __construct() {
	    // Term actions
	    add_action( 'created_term', array( $this, 'create_term' ), 10, 3 );
	    add_action( 'edited_term', array( $this, 'edit_term' ), 10, 3 );
	    add_action( 'pre_delete_term', array( $this, 'delete_term' ), 10, 2 );

	    // Process event
	    add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
    }

	/**
	 * Function for `created_(taxonomy)` action-hook.
	 *
	 * @param int    $term_id   Term ID.
	 * @param int    $tt_id     Term taxonomy ID.
	 * @param string $taxonomy  Taxonomy name.
	 *
	 * @return void
	 */
	public function create_term( $term_id, $tt_id, $taxonomy ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'term' ) ) {
			return;
		}

		$term_details = ( array ) get_term( $term_id, $taxonomy );
		$event_name   = sprintf( __('%s created', 'instawp-connect'), $this->taxonomy_name( $taxonomy ) );
		$source_id    = InstaWP_Sync_Helpers::get_term_reference_id( $term_id );
		$term_details = $this->term_details( $term_details, $term_id, $taxonomy );

		InstaWP_Sync_DB::insert_update_event( $event_name, 'create_term', $taxonomy, $source_id, $term_details['name'], $term_details );
	}

	/**
	 * Function for `created_(taxonomy)` action-hook.
	 *
	 * @param int   $term_id Term ID.
	 * @param int   $tt_id   Term taxonomy ID.
	 * @param array $args    Arguments passed to wp_insert_term().
	 *
	 * @return void
	 */
	public function edit_term( $term_id, $tt_id, $taxonomy ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'term' ) ) {
			return;
		}

		$term_details = ( array ) get_term( $term_id, $taxonomy );
		$event_name   = sprintf( __('%s modified', 'instawp-connect'), $this->taxonomy_name( $taxonomy ) );
		$source_id    = InstaWP_Sync_Helpers::get_term_reference_id( $term_id );
		$term_details = $this->term_details( $term_details, $term_id, $taxonomy );

		InstaWP_Sync_DB::insert_update_event( $event_name, 'edit_term', $taxonomy, $source_id, $term_details['name'], $term_details );
	}

	/**
	 * Function for `delete_(taxonomy)` action-hook.
	 *
	 * @param int     $term_id         Term ID.
	 * @param int     $taxonomy        Term taxonomy ID.
	 *
	 * @return void
	 */
	public function delete_term( $term_id, $taxonomy ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'term' ) ) {
			return;
		}

		$term_details = ( array ) get_term( $term_id, $taxonomy );
		$source_id    = get_term_meta( $term_id, 'instawp_source_id', true );
		$event_name   = sprintf( __('%s deleted', 'instawp-connect' ), $this->taxonomy_name( $taxonomy ) );

		InstaWP_Sync_DB::insert_update_event( $event_name, 'delete_term', $taxonomy, $source_id, $term_details['name'], $term_details );
	}

	public function parse_event( $response, $v ) {
		$source_id = $v->source_id;
		$term      = InstaWP_Sync_Helpers::object_to_array( $v->details );
		$logs      = array();

		// create and update term
		if ( in_array( $v->event_slug, array( 'create_term', 'edit_term' ), true ) ) {
			$term_meta = $term['term_meta'];
			unset( $term['term_meta'] );

			$parent_term_id = $term['parent'];
			if ( $parent_term_id ) {
				$parent_details = $term['parent_details'];
				$parent_term_id = $this->get_term( $parent_details['source_id'], $parent_details['data'] );
			}

			$term_id = $this->get_term( $source_id, $term, array(
				'parent' => $parent_term_id,
			) );

			if ( is_wp_error( $term_id ) ) {
				$logs[ $v->id ] = $term_id->get_error_message();

				return InstaWP_Sync_Helpers::sync_response( $v, $logs, array(
					'status'  => 'pending',
					'message' => $term_id->get_error_message(),
				) );
			}

			if ( $v->event_slug === 'edit_term' && $term_id && ! is_wp_error( $term_id ) ) {
				$result = wp_update_term( $term_id, $term['taxonomy'], array(
					'description' => $term['description'],
					'name'        => $term['name'],
					'slug'        => $term['slug'],
					'parent'      => $parent_term_id,
				) );

				if ( is_wp_error( $result ) ) {
					$logs[ $v->id ] = $result->get_error_message();

					return InstaWP_Sync_Helpers::sync_response( $v, $logs, array(
						'status'  => 'pending',
						'message' => $result->get_error_message(),
					) );
				}
			}

			foreach ( $term_meta['data'] as $key => $value ) {
				update_term_meta( $term_id, $key, maybe_unserialize( reset( $value ) ) );
			}

			foreach ( $term_meta['media'] as $key => $media ) {
				$attachment_id = InstaWP_Sync_Helpers::string_to_attachment( $media );
				if ( ! empty( $attachment_id ) ) {
					update_term_meta( $term_id, $key, $attachment_id );
				}
			}

			return InstaWP_Sync_Helpers::sync_response( $v, $logs );
		}

		// delete term
		if ( $v->event_slug === 'delete_term' ) {
			$term_id = $this->get_term( $source_id, $term, array(), false );
			$status  = 'pending';
			
			$deleted = wp_delete_term( $term_id, $term['taxonomy'] );

			if ( is_wp_error( $deleted ) ) {
				$message = $deleted->get_error_message();
			} elseif ( $deleted === false ) {
				$message = 'Term not found for delete operation.';
			} elseif ( $deleted === 0 ) {
				$message = 'Default Term can not be deleted.';
			} elseif ( $deleted ) {
				$status  = 'completed';
				$message = 'Sync successfully.';
			}

			return InstaWP_Sync_Helpers::sync_response( $v, array(), compact( 'status', 'message' ) );
		}

		return $response;
	}

	private function get_term( $source_id, $term, $args = array(), $insert = true ) {
		$term_id = 0;
		if ( ! taxonomy_exists( $term['taxonomy'] ) ) {
			return $term_id;
		}

		$terms = get_terms( array(
			'hide_empty' => false,
			'meta_key'   => 'instawp_event_term_sync_reference_id',
			'meta_value' => $source_id,
			'fields'     => 'ids',
			'taxonomy'   => $term['taxonomy'],
		) );

		if ( empty( $terms ) ) {
			$get_term_by = ( array ) get_term_by( 'slug', $term['slug'], $term['taxonomy'] );

			if ( ! empty( $get_term_by['term_id'] ) ) {
				$term_id = $get_term_by['term_id'];
			} elseif ( $insert === true ) {
				$inserted_term = wp_insert_term( $term['name'], $term['taxonomy'], wp_parse_args( $args, array(
					'description' => $term['description'],
					'slug'        => $term['slug'],
					'parent'      => 0,
				) ) );

				$term_id = is_wp_error( $inserted_term ) ? $inserted_term : $inserted_term['term_id'];
			}

			if ( $term_id && ! is_wp_error( $term_id ) ) {
				update_term_meta( $term_id, 'instawp_event_term_sync_reference_id', $source_id );
			}
		} else {
			$term_id = reset( $terms );
		}

		return $term_id;
	}

	private function taxonomy_name( $taxonomy ) {
		$taxonomy = str_replace( 'pa_', '', $taxonomy );

		return ucwords( str_replace( array( '-', '_' ), ' ', $taxonomy ) );
	}

	private function handle_meta_attachments( $metadata ) {
		$attachment_keys = array( 'thumbnail_id' );
		$attachments     = array();

		foreach ( ( array ) $metadata as $key => $meta ) {
			if ( ! in_array( $key, $attachment_keys ) ) {
				continue;
			}
			$attachments[ $key ] = InstaWP_Sync_Helpers::attachment_to_string( $meta[0] );
		}

		return $attachments;
	}

	private function term_details( $term_details, $term_id, $taxonomy ) {
		$term_meta = get_term_meta( $term_id );

		$term_details['term_meta'] = array(
			'data'  => $term_meta,
			'media' => $this->handle_meta_attachments( $term_meta ),
		);

		if ( ! empty( $term_details['parent'] ) ) {
			$term_details['parent_details'] = array(
				'data'      => ( array ) get_term( $term_details['parent'], $taxonomy ),
				'source_id' => InstaWP_Sync_Helpers::get_term_reference_id( $term_details['parent'] ),
			);
		}

		return $term_details;
	}
}

new InstaWP_Sync_Term();