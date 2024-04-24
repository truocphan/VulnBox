<?php
/**
 * Courses exporter class.
 *
 * @since 1.6.0
 * @package Masteriyo\Exporter
 */

namespace Masteriyo\Exporter;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\CourseChildrenPostType;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;
use ZipArchive;

/**
 * Export class.
 *
 * @since 1.6.0
 */
class CourseExporter {

	/**
	 * Get exportable post types related to courses.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	protected function get_post_types() {
		return array_merge(
			array(
				PostType::COURSE,
			),
			CourseChildrenPostType::all(),
			array(
				PostType::QUESTION,
			)
		);
	}

	/**
	 * Return post type label.
	 *
	 * @since 1.6.0
	 *
	 * @param string $post_type Post type slug.
	 * @return string
	 */
	protected function get_post_type_label( $post_type ) {
		$post_object = get_post_type_object( $post_type );
		return strtolower( $post_object->labels->name ?? '' );
	}

	/**
	 * Export data.
	 *
	 * Includes courses, sections, lessons, quizzes, questions etc.
	 *
	 * @since 1.6.0
	 * @return \WP_Error|array Array of data (filename, download_url) on success else WP_Error on failure.
	 */
	public function export( bool $compress = false ) {
		wp_raise_memory_limit( 'admin' );

		$export_file = $this->create_export_file();

		if ( false === $export_file ) {
			return new \WP_Error(
				'export_error',
				__( 'Unable to create export file.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$terms       = $this->get_terms( Taxonomy::all() );
		$posts       = $this->get_posts( $this->get_post_types() );
		$meta_data   = $this->get_export_meta_data();
		$attachments = array_merge( $terms['attachments'], $posts['attachments'] );

		$data = array_merge(
			array(
				'manifest'    => $meta_data,
				'terms'       => $terms['terms'],
				'attachments' => $attachments,
			),
			$posts['posts']
		);

		$this->write( $export_file['filepath'], $data );

		if ( $compress ) {
			return $this->compress( $export_file['filepath'] );
		}

		return $export_file;
	}

	/**
	 * Compress.
	 *
	 * @since 1.6.0
	 * @param string $filepath Path to file for compress.
	 * @return \WP_Error|array Array of data on success or WP_Error on failure.
	 */
	protected function compress( string $filepath ) {
		if ( ! class_exists( 'ZipArchive' ) ) {
			return new \WP_Error(
				'missing_zip_package',
				__( 'Zip Export not supported.', 'masteriyo' )
			);
		}

		$upload_dir   = wp_upload_dir();
		$archiver     = new ZipArchive();
		$filename     = pathinfo( $filepath, PATHINFO_FILENAME );
		$zip_filename = $filename . '.zip';
		$zip_filepath = $upload_dir['basedir'] . '/masteriyo/' . $zip_filename;

		if ( true !== $archiver->open( $zip_filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
			return new \WP_Error(
				'unable_to_create_zip',
				__( 'Unable to open export file (archive) for writing.', 'masteriyo' )
			);
		}

		$archiver->addFile( $filepath, $filename . '.json' );
		$archiver->close();

		// Delete json file after compress.
		$filesystem = masteriyo_get_filesystem();
		if ( $filesystem ) {
			$filesystem->delete( $filepath );
		}

		return array(
			'filepath'     => $zip_filepath,
			'filename'     => $zip_filename,
			'download_url' => $upload_dir['baseurl'] . '/masteriyo/' . $zip_filename,
		);
	}

	/**
	 * Write.
	 *
	 * @since 1.6.0
	 *
	 * @param string $filepath
	 * @param array $contents
	 */
	protected function write( $filepath, $contents ) {
		$filesystem = masteriyo_get_filesystem();

		if ( $filesystem ) {
			$filesystem->put_contents( $filepath, wp_json_encode( $contents ) );
		}
	}

	/**
	 * Create and return export file path.
	 *
	 * @since 1.6.0
	 * @return bool|array Array of data on success or false on failure.
	 */
	protected function create_export_file() {
		$upload_dir = wp_upload_dir();
		$filesystem = masteriyo_get_filesystem();

		if ( ! $filesystem ) {
			return false;
		}

		if ( ! $filesystem->is_dir( $upload_dir['basedir'] . '/masteriyo' ) ) {
			$filesystem->mkdir( $upload_dir['basedir'] . '/masteriyo' );
		}

		$export_files = $filesystem->dirlist( $upload_dir['basedir'] . '/masteriyo' );

		// Remove old export file.
		foreach ( $export_files as $file ) {
			$prefix = sprintf( 'masteriyo-export-%s-', get_current_user_id() );
			if ( masteriyo_starts_with( $file['name'], $prefix ) ) {
				$filesystem->delete( $upload_dir['basedir'] . '/masteriyo/' . $file['name'] );
			}
		}

		$filename = sprintf( 'masteriyo-export-%s-%s.json', get_current_user_id(), gmdate( 'Y-m-d-H-i-s' ) );
		$filepath = $upload_dir['basedir'] . '/masteriyo/' . $filename;

		if ( ! $filesystem->touch( $filepath ) ) {
			return false;
		}

		return array(
			'filepath'     => $filepath,
			'filename'     => $filename,
			'download_url' => $upload_dir['baseurl'] . '/masteriyo/' . $filename,
		);
	}

	/**
	 * Return all the terms associated with taxonomies.
	 *
	 * @since 1.6.0
	 *
	 * @param array $taxonomies
	 * @return array
	 */
	public function get_terms( $taxonomies ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomies,
				'hide_empty' => false,
			)
		);

		$terms = array_map(
			function( $term ) {
				return (array) $term;
			},
			$terms
		);

		$data = array(
			'terms'       => array(),
			'attachments' => array(),
		);

		if ( is_wp_error( $terms ) ) {
			return $data;
		}

		foreach ( $terms as $term ) {
			$term_meta         = get_term_meta( $term['term_id'] );
			$term['termmeta']  = $term_meta ?? array();
			$featured_image_id = get_term_meta( $term['term_id'], '_featured_image', true );

			if ( $featured_image_id ) {
				$attachment = get_post( $featured_image_id, ARRAY_A );
				if ( $attachment ) {
					$data['attachments'][] = $attachment + array(
						'postmeta' => get_post_meta( $featured_image_id ),
					);
				}
			}

			$data['terms'][] = $term;
		}

		return $data;
	}

	/**
	 * Return terms related to posts.
	 *
	 * @since 1.6.0
	 *
	 * @param array $post Post array.
	 *
	 * @return array
	 */
	protected function get_post_terms( $post ) {
		$taxonomies = get_object_taxonomies( $post['post_type'] );

		if ( empty( $taxonomies ) ) {
			return array();
		}

		$terms = wp_get_object_terms( $post['ID'], $taxonomies );

		if ( empty( $terms ) ) {
			return array();
		}

		return array_map(
			function( $term ) {
				return array(
					'slug'     => $term->slug,
					'name'     => $term->name,
					'taxonomy' => $term->taxonomy,
				);
			},
			$terms
		);
	}

	/**
	 * Prepare data for export.
	 *
	 * @since 1.6.0
	 *
	 * @param string[] Post types to be exported.
	 * @return array
	 */
	protected function get_posts( $post_types ) {
		$posts = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => $post_types,
				'post_status'    => PostStatus::ANY,
				'author'         => current_user_can( 'manage_masteriyo_settings' ) ? null : get_current_user_id(),
			)
		);

		$posts = array_map(
			function( $post ) {
				return (array) $post;
			},
			$posts
		);
		$data  = array(
			'posts'       => array(),
			'attachments' => array(),
		);

		if ( empty( $posts ) ) {
			return $data;
		}

		$posts = array_filter(
			$posts,
			function( $post ) {
				$course_id = get_post_meta( $post['ID'], '_course_id', true );
				if ( $course_id ) {
					$status = get_post_status( $course_id );
					if ( 'trash' === $status ) {
						return false;
					}
				}
				return true;
			}
		);

		foreach ( $posts as $post ) {
			$post['postmeta']          = get_post_meta( $post['ID'] );
			$post['terms']             = $this->get_post_terms( $post );
			$label                     = $this->get_post_type_label( $post['post_type'] );
			$data['posts'][ $label ][] = $post;
			$data['attachments']       = array_merge( $data['attachments'], $this->get_post_featured_attachments( $post['ID'] ) );
		}

		return $data;
	}

	/**
	 * Get featured attachments.
	 *
	 * @since 1.6.0
	 * @param int $post_id Post ID.
	 * @return array
	 */
	protected function get_post_featured_attachments( $post_id ) {
		$meta_keys = array(
			'_thumbnail_id',
		);

		if ( 'self-hosted' === get_post_meta( $post_id, '_video_source', true ) ) {
			$meta_keys[] = '_video_source_url';
		}

		$attachments = array();

		foreach ( $meta_keys as $meta_key ) {
			$id = get_post_meta( $post_id, $meta_key, true );
			if ( ! $id ) {
				continue;
			}
			$attachment = get_post( $id, ARRAY_A );
			if ( ! $attachment ) {
				continue;
			}
			$attachments[] = $attachment + array(
				'postmeta' => get_post_meta( $id ),
			);
		}

		return $attachments;
	}

	/**
	 * Return export meta data.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	protected function get_export_meta_data() {
		return array(
			'version'    => masteriyo_get_version(),
			'created_at' => gmdate( 'D, d M Y H:i:s +0000' ),
			'base_url'   => home_url(),
		);
	}
}
