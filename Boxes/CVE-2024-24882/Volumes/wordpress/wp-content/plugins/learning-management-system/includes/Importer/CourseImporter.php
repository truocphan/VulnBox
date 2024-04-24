<?php
/**
 * Import class.
 *
 * @since 1.6.0
 * @package Masteriyo\ImportExport
 */

namespace Masteriyo\Importer;

defined( 'ABSPATH' ) || exit;

use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Masteriyo\PostType\PostType;

/**
 * Course Importer class.
 *
 * @since 1.6.0
 */
class CourseImporter {

	/**
	 * Keep track of import.
	 *
	 * @since 1.6.0
	 * @var array
	 */
	protected $history = array();

	/**
	 * Imported courses status.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	protected $imported_courses_status;

	/**
	 * Constructor.
	 *
	 * @since 1.6.0
	 *
	 * @param string $imported_courses_status Imported courses status.
	 */
	public function __construct( $imported_courses_status = null ) {
		$this->imported_courses_status = $imported_courses_status;
	}

	/**
	 * Import.
	 *
	 * @since 1.6.0
	 * @throws \JsonMachine\Exception\InvalidArgumentException
	 * @param string $file Import file.
	 */
	public function import( $file ) {
		wp_raise_memory_limit( 'admin' );

		$items = Items::fromFile(
			$file,
			array(
				'decoder' => new ExtJsonDecoder( true ),
			)
		);

		/**
		 * Fires before import.
		 *
		 * @since 1.6.0
		 *
		 * @param Items $items Import data.
		 */
		do_action( 'masteriyo_before_import', $items );

		foreach ( $items as $key => $value ) {
			$key = trim( $key, '"' );
			if ( 'manifest' === $key ) {
				continue;
			}

			if ( 'terms' === $key ) {
				$this->import_terms( $value );
				continue;
			}

			$this->import_posts( $value );
		}

		$this->map_post_parents();
		$this->map_post_course_ids();
		$this->map_post_attachment_ids();
		$this->map_post_content_urls();
		$this->map_term_parents();
		$this->map_term_featured_image_ids();

		/**
		 * Fires after import.
		 *
		 * @since 1.0.0
		 *
		 * @param Items $items Import data.
		 * @param array $history Import history data.
		 */
		do_action( 'masteriyo_after_import', $items, $this->history );
	}

	/**
	 * Import all types of posts.
	 *
	 * @since 1.6.0
	 * @param array $posts Array of posts.
	 *
	 * @return void
	 */
	protected function import_posts( $posts ) {
		foreach ( $posts as $post ) {
			if (
				! post_type_exists( $post['post_type'] ) ||
				isset( $this->history['posts'][ $post['ID'] ] )
			) {
				continue;
			}

			$this->history['post_parents'][ intval( $post['ID'] ) ] = intval( $post['post_parent'] );

			$data_to_insert           = masteriyo_array_except(
				$post,
				array( 'ID', 'terms', 'postmeta', 'post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt', 'post_author' )
			);
			$data_to_insert['author'] = get_current_user_id();

			if ( 'attachment' === $data_to_insert['post_type'] ) {
				$post_id = $this->import_attachment( $post, $data_to_insert );
			} else {
				if ( PostType::COURSE === $data_to_insert['post_type'] && $this->imported_courses_status ) {
					$data_to_insert['post_status'] = $this->imported_courses_status;
				}
				$this->import_post_content_media( $post['post_content'] ?? '' );
				$post_id = wp_insert_post( $data_to_insert, true );
			}

			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			$this->set_post_terms( $post_id, $post['terms'] ?? array() );
			$this->set_post_metas( $post_id, $post['postmeta'] ?? array() );

			$this->history['posts'][ intval( $post['ID'] ) ] = $post_id;
		}
	}

	/**
	 * Import attachment post type.
	 *
	 * @since 1.6.0
	 * @param array $post Old post data.
	 * @param array $new_post_data New post data to import.
	 * @return \WP_Error|int
	 */
	protected function import_attachment( array $post, array $new_post_data ) {
		$new_post_data['upload_date'] = $post['post_date'];
		$remote_url                   = $post['guid'];

		// Set up upload date from post meta if available.
		if ( ! empty( $post['postmeta'] ) ) {
			foreach ( $post['postmeta'] as $key => $value ) {
				if ( '_wp_attached_file' === $key ) {
					if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $value[0], $matches ) ) {
						$new_post_data['upload_date'] = $matches[0];
					}
					break;
				}
			}
		}

		$local_upload = $this->fetch_remote_file( $remote_url, $new_post_data );

		if ( is_wp_error( $local_upload ) ) {
			return $local_upload;
		}

		$filetype = wp_check_filetype( $local_upload['file'] );

		if ( $filetype ) {
			$new_post_data['post_mime_type'] = $filetype['type'];
		}

		$new_post_data['guid'] = $local_upload['url'];
		$post_id               = wp_insert_attachment( $new_post_data, $local_upload['file'] );

		if ( ! is_wp_error( $post_id ) ) {
			$attachment_metadata                  = wp_generate_attachment_metadata( $post_id, $local_upload['file'] );
			$this->history['urls'][ $remote_url ] = $local_upload['url'];

			wp_update_attachment_metadata( $post_id, $attachment_metadata );
		}

		return $post_id;
	}

	/**
	 * Set post metas.
	 *
	 * @since 1.6.0
	 * @param int $post_id Imported post id.
	 * @param array $metas_to_set Array of meta data.
	 */
	protected function set_post_metas( int $post_id, array $metas_to_set ) {
		if ( empty( $metas_to_set ) ) {
			return;
		}

		foreach ( $metas_to_set as $meta_key => $meta_value ) {
			$value = maybe_unserialize( $meta_value[0] );

			// Update old category ids with new ones.
			if ( '_category_ids' === $meta_key && is_array( $value ) ) {
				array_walk(
					$value,
					function( &$v ) {
						if ( isset( $this->history['terms'][ $v ] ) ) {
							$v = $this->history['terms'][ $v ];
						}
					}
				);
			}

			if (
				is_numeric( $value ) &&
				in_array( $meta_key, $this->get_post_featured_meta_keys(), true )
			) {
				$this->history['post_featured_attachments'][ $post_id ] = array(
					'key'   => $meta_key,
					'value' => intval( $value ),
				);
			}

			add_post_meta( $post_id, $meta_key, wp_slash( $value ) );
		}
	}

	/**
	 * Get featured attachment meta keys.
	 *
	 * @since 1.6.0
	 * @return array
	 */
	protected function get_post_featured_meta_keys() {
		return array(
			'_thumbnail_id',
			'_video_source_url',
		);
	}

	/**
	 * Set post terms.
	 *
	 * @since 1.6.0
	 * @param int $post_id Imported post id.
	 * @param array $terms_to_set Array of terms data.
	 */
	protected function set_post_terms( int $post_id, array $terms_to_set ) {
		if ( empty( $terms_to_set ) ) {
			return;
		}

		$new_terms = array();
		foreach ( $terms_to_set as $term ) {
			$taxonomy    = ( 'tag' === $term['taxonomy'] ) ? 'post_tag' : $term['taxonomy'];
			$term_exists = term_exists( $term['slug'], $taxonomy );
			$term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;

			// Add term if it does not exists.
			if ( ! $term_id ) {
				$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );

				if ( ! is_wp_error( $t ) ) {
					$term_id = $t['term_id'];
				}
			}

			$new_terms[ $taxonomy ][] = intval( $term_id );
		}

		foreach ( $new_terms as $taxonomy => $term_ids ) {
			wp_set_post_terms( $post_id, $term_ids, $taxonomy );
		}
	}

	/**
	 * Fetch remote file.
	 *
	 * Locally download a remote file to uploads directory.
	 *
	 * @since 1.6.0
	 * @param string $url Remote URL.
	 * @param array  $post Related post data.
	 *
	 * @return array|\WP_Error
	 */
	protected function fetch_remote_file( $url, $post ) {
		$file_name  = basename( $url );
		$filesystem = masteriyo_get_filesystem();

		if ( ! $filesystem ) {
			return new \WP_Error(
				'filesystem',
				__( 'WordPress Filesystem API is not initialized.', 'masteriyo' )
			);
		}

		$upload = wp_upload_bits( $file_name, null, '', $post['upload_date'] ?? null );
		if ( $upload['error'] ) {
			return new \WP_Error( 'upload_dir_error', $upload['error'] );
		}

		$response = wp_remote_get(
			$url,
			array(
				'stream'   => true,
				'filename' => $upload['file'],
			)
		);

		if ( is_wp_error( $response ) || 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			$filesystem->delete( $upload['file'] );
			return $response;
		}

		$filesize = filesize( $upload['file'] );
		$headers  = wp_remote_retrieve_headers( $response );

		if ( isset( $headers['content-length'] ) && intval( $headers['content-length'] ) !== $filesize ) {
			$filesystem->delete( $upload['file'] );
			return new \WP_Error( 'import_file_error', __( 'Remote file is incorrect size', 'masteriyo' ) );
		}

		if ( 0 === $filesize ) {
			$filesystem->delete( $upload['file'] );
			return new \WP_Error( 'import_file_error', __( 'Zero size file downloaded', 'masteriyo' ) );
		}

		return $upload;
	}

	/**
	 * Import terms.
	 *
	 * @since  1.6.0
	 * @param  array $terms Terms data.
	 * @return void
	 */
	protected function import_terms( $terms ) {
		foreach ( $terms as $term ) {
			$term_id = term_exists( $term['slug'], $term['taxonomy'] );

			// Skip if term already exits.
			if ( is_array( $term_id ) && isset( $term['term_id'] ) ) {
				$this->history['terms'][ intval( $term['term_id'] ) ] = intval( $term_id['term_id'] );
				continue;
			}

			$this->history['term_parents'][ intval( $term['term_id'] ) ] = intval( $term['parent'] );

			if ( empty( $term['parent'] ) ) {
				$parent = 0;
			} else {
				$parent = term_exists( $term['parent'], $term['taxonomy'] );
				if ( is_array( $parent ) ) {
					$parent = $parent['term_id'];
				}
			}

			$insert_term = wp_insert_term(
				$term['name'],
				$term['taxonomy'],
				array(
					'slug'        => $term['slug'],
					'description' => $term['description'] ?? '',
					'parent'      => intval( $parent ),
				)
			);

			if ( is_wp_error( $insert_term ) ) {
				continue;
			}

			// Add to history for later.
			$this->history['terms'][ intval( $term['term_id'] ) ] = intval( $insert_term['term_id'] );

			$this->set_term_metas( $insert_term['term_id'], $term['termmeta'] ?? array() );
		}
	}

	/**
	 * Import content media.
	 *
	 * @since 1.6.0
	 * @param string $content Post content.
	 */
	protected function import_post_content_media( $content ) {
		if ( empty( $content ) ) {
			return;
		}

		$mimes   = implode( '|', array_keys( wp_get_mime_types() ) );
		$pattern = '/(https?:\/\/\S*?wp-content\S*?\.(' . preg_quote( $mimes ) . '))/i';

		preg_match_all( $pattern, $content, $matches );

		if ( empty( $matches[0] ) ) {
			return;
		}

		foreach ( $matches[0] as $url ) {
			$local_upload = $this->fetch_remote_file( $url, array() );

			if ( is_wp_error( $local_upload ) ) {
				continue;
			}

			$args     = array(
				'guid' => $local_upload['url'],
			);
			$filetype = wp_check_filetype( $local_upload['file'] );

			if ( $filetype ) {
				$args['post_mime_type'] = $filetype['type'];
			}

			$post_id = wp_insert_attachment(
				$args,
				$local_upload['file']
			);

			if ( ! is_wp_error( $post_id ) ) {
				$this->history['urls'][ $url ] = $local_upload['url'];
			}
		}
	}

	/**
	 * Set term metas.
	 *
	 * @param int $term_id Imported term id.
	 * @param array $metas_to_set Array of meta data.
	 */
	protected function set_term_metas( int $term_id, $metas_to_set ) {
		foreach ( $metas_to_set as $meta_key => $meta_value ) {
			$value = maybe_unserialize( $meta_value[0] );

			// Add to history for later.
			if ( '_featured_image' === $meta_key ) {
				$this->history['term_featured_images'][ intval( $term_id ) ] = intval( $value );
			}

			add_term_meta( $term_id, $meta_key, wp_slash( $value ) );
		}
	}

	/**
	 * Map imported posts parent relationship.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_post_parents() {
		if ( empty( $this->history['post_parents'] ) ) {
			return;
		}

		$posts = $this->history['posts'] ?? array();

		foreach ( $this->history['post_parents'] as $child_id => $parent_id ) {
			$new_child_id  = $posts[ $child_id ] ?? false;
			$new_parent_id = $posts[ $parent_id ] ?? false;

			if ( ! $new_child_id || ! $new_parent_id ) {
				continue;
			}

			wp_update_post(
				array(
					'ID'          => $new_child_id,
					'post_parent' => $new_parent_id,
				)
			);
		}
	}

	/**
	 * Map term parents.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_term_parents() {
		if ( empty( $this->history['term_parents'] ) ) {
			return;
		}

		$terms = $this->history['terms'] ?? array();

		foreach ( $this->history['term_parents'] as $child_id => $parent_id ) {
			$new_child_id  = $terms[ $child_id ] ?? false;
			$new_parent_id = $terms[ $parent_id ] ?? false;

			if ( ! $new_child_id || ! $new_parent_id ) {
				continue;
			}

			$term = get_term( $new_child_id );

			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$taxonomy = $term->taxonomy;

			wp_update_term(
				$new_child_id,
				$taxonomy,
				array(
					'parent' => $new_parent_id,
				)
			);
		}
	}

	/**
	 * Map post course ids.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_post_course_ids() {
		if ( empty( $this->history['posts'] ) ) {
			return;
		}

		foreach ( $this->history['posts'] as  $post_id ) {
			$old_course_id = get_post_meta( $post_id, '_course_id', true );

			if ( ! $old_course_id ) {
				continue;
			}

			$new_course_id = $this->history['posts'][ $old_course_id ] ?? false;

			if ( $new_course_id ) {
				update_post_meta( $post_id, '_course_id', $new_course_id );
			}
		}
	}

	/**
	 * Map term featured image.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_term_featured_image_ids() {
		if ( empty( $this->history['term_featured_images'] ) ) {
			return;
		}
		$imported_posts = $this->history['posts'] ?? array();
		foreach ( $this->history['term_featured_images'] as $term_id => $featured_image_id ) {
			if ( ! isset( $imported_posts[ $featured_image_id ] ) ) {
				delete_term_meta( $term_id, '_featured_image' );
				continue;
			}
			$new_id = $imported_posts[ $featured_image_id ];
			if ( $new_id !== $featured_image_id ) {
				update_term_meta( $term_id, '_featured_image', $new_id );
			}
		}
	}

	/**
	 * Map post attachment ids.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_post_attachment_ids() {
		if ( empty( $this->history['post_featured_attachments'] ) ) {
			return;
		}
		$imported_posts = $this->history['posts'] ?? array();
		foreach ( $this->history['post_featured_attachments'] as $post_id => $attachment ) {
			if ( ! isset( $imported_posts[ $attachment['value'] ] ) ) {
				delete_post_meta( $post_id, $attachment['key'] );
				continue;
			}
			$new_id = $imported_posts[ $attachment['value'] ];
			if ( $new_id !== $attachment['value'] ) {
				update_post_meta( $post_id, $attachment['key'], $new_id );
			}
		}
	}

	/**
	 * Map post content urls.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	protected function map_post_content_urls() {
		if ( empty( $this->history['urls'] ) ) {
			return;
		}

		global $wpdb;

		foreach ( $this->history['urls'] as $from => $to ) {
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from, $to ) );
		}
	}
}
