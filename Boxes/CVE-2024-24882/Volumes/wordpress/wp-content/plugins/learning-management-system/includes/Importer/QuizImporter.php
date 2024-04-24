<?php
/**
 * Quiz Importer class.
 *
 * Provides functionalities to import quizzes and questions from a JSON file.
 *
 * @since 1.6.15
 *
 * @package Masteriyo\Importer
 */

namespace Masteriyo\Importer;

use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Class QuizImporter
 * Responsible for importing quiz data.
 *
 * @since 1.6.15
 */
class QuizImporter {

	/**
	 * The file path of the CSV file to be imported.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	protected $file_path;

	/**
	 * Keeps track of the imported items.
	 *
	 * @since 1.6.15
	 *
	 * @var array
	 */
	protected $history = array();

	/**
	 * Constructor to initialize importer.
	 *
	 * @since 1.6.15
	 *
	 * @param string $file_path The file path of the CSV file to be imported.
	 */
	public function __construct( $file_path ) {
		$this->file_path = $file_path;
	}

	/**
	 * Initiates the import process.
	 *
	 * @since 1.6.15
	 *
	 * @return void|\WP_Error Does not return a value on successful import, returns \WP_Error on failure.
	 */
	public function import() {
		if ( ! file_exists( $this->file_path ) || ! is_readable( $this->file_path ) ) {
			return new \WP_Error( 'invalid_file', 'Invalid or unreadable JSON file.' );
		}

		$wp_filesystem = masteriyo_get_filesystem();

		if ( ! $wp_filesystem || ! $wp_filesystem->exists( $this->file_path ) ) {
			return new \WP_Error( 'invalid_file', 'Invalid or unreadable JSON file.' );
		}

		wp_raise_memory_limit( 'admin' );

		$file_content = $wp_filesystem->get_contents( $this->file_path );

		$items = json_decode( $file_content, true );

		/**
		 * Fires before the quizzes are imported.
		 *
		 * This action can be used to perform pre-import checks, modifications, or logging.
		 *
		 * @since 1.6.15
		 *
		 * @param array $items The array of items to be imported.
		 */
		do_action( 'masteriyo_before_quizzes_import', $items );

		if ( isset( $items['manifest'] ) ) {
			$this->validate_manifest( $items['manifest'] );
		}

		foreach ( $items as $key => $value ) {
			if ( 'manifest' === $key ) {
				continue;
			}
			$this->import_posts( $value );
		}

		$this->map_post_parents();

		/**
		 * Fires after the quizzes have been imported.
		 *
		 * This action can be used for post-import cleanup, notifications, or other actions.
		 *
		 * @since 1.6.15
		 *
		 * @param array $items   The array of items that were imported.
		 * @param array $history The history array mapping original post IDs to new post IDs.
		 */
		do_action( 'masteriyo_after_quizzes_import', $items, $this->history );
	}

	/**
	 * Validates the manifest information.
	 *
	 * @since 1.6.15
	 *
	 * @param array $manifest Manifest data from the import file.
	 * @throws \Exception If the manifest data is not valid.
	 */
	protected function validate_manifest( $manifest ) {
		// Validate manifest version.
		if ( ! isset( $manifest['version'] ) || masteriyo_get_version() !== $manifest['version'] ) {
			throw new \Exception( 'Invalid manifest version' );
		}
	}

	/**
	 * Validates the manifest information.
	 *
	 * @since 1.6.15
	 *
	 * @param array $manifest Manifest data from the import file.
	 *
	 * @throws \Exception If the manifest data is not valid.
	 */
	protected function import_posts( $posts ) {
		foreach ( $posts as $post ) {
			// Skip if post type is not valid.
			if ( ! post_type_exists( $post['post_type'] ) ) {
				continue;
			}

			// Store parent-child relationship in history before modifying $post_data.
			if ( isset( $post['post_parent'] ) ) {
				$this->history['post_parents'][ intval( $post['ID'] ) ] = intval( $post['post_parent'] );
			}

			$post_data = masteriyo_array_except(
				$post,
				array( 'ID', 'meta', 'post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt', 'post_author' )
			);

			$post_data['post_author'] = get_current_user_id();

			$post_id = wp_insert_post( $post_data );

			// Skip if the post couldn't be inserted.
			if ( is_wp_error( $post_id ) ) {
					continue;
			}

			// Insert post meta data.
			if ( isset( $post['meta'] ) && is_array( $post['meta'] ) ) {
				foreach ( $post['meta'] as $meta_key => $meta_value ) {
					$value = maybe_unserialize( $meta_value[0] );
					add_post_meta( $post_id, $meta_key, wp_slash( $value ) );
				}
			}

			// Record the newly created post ID.
			$this->history['posts'][ $post['ID'] ] = $post_id;
		}
	}

	/**
	 * Map imported posts parent relationship.
	 *
	 * @since 1.6.15
	 *
	 * @return void
	 */
	protected function map_post_parents() {
		if ( ! isset( $this->history['post_parents'] ) || empty( $this->history['post_parents'] ) ) {
				return;
		}

		$posts = isset( $this->history['posts'] ) ? $this->history['posts'] : array();

		foreach ( $this->history['post_parents'] as $child_id => $parent_id ) {
				$new_child_id  = $posts[ $child_id ] ?? false;
				$new_parent_id = $posts[ $parent_id ] ?? false;

			if ( ! $new_child_id || ! $new_parent_id ) {
					continue;
			}

			// Retrieve the post type for the child post.
			$child_post_type = get_post_type( $new_child_id );

			// Only update post_parent if the post type is 'mto-question'.
			if ( PostType::QUESTION === $child_post_type ) {
				wp_update_post(
					array(
						'ID'          => $new_child_id,
						'post_parent' => $new_parent_id,
					)
				);
			}
		}
	}

}
