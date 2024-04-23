<?php
/**
 *
 * This file is used for change event traking
 *
 * @link       https://instawp.com/
 * @since      1.0
 * @package    instaWP
 * @subpackage instaWP/admin
 */
/**
 * This file is used for change event traking
 *
 * @since      1.0
 * @package    instawp
 * @subpackage instawp/admin
 * @author     instawp team
 */

defined( 'ABSPATH' ) || die;

class InstaWP_Sync_Helpers {

	/**
	 * get post type singular name
	 *
	 * @param $post_type
	 *
	 * @return string
	 */
	public static function get_post_type_name( $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		if ( ! empty( $post_type_object ) ) {
			return $post_type_object->labels->singular_name;
		}

		return '';
	}

	/*
     * Update post metas
     */
	public static function get_post_reference_id( $post_id ) {
		$reference_id = get_post_meta( $post_id, 'instawp_event_sync_reference_id', true );

		return ! empty( $reference_id ) ? $reference_id : self::set_post_reference_id( $post_id );
	}

	/*
	 * Update post metas
	 */
	public static function set_post_reference_id( $post_id ) {
		$reference_id = InstaWP_Tools::get_random_string();
		update_post_meta( $post_id, 'instawp_event_sync_reference_id', $reference_id );

		return $reference_id;
	}

	/*
	 * Get user metas
	 */
	public static function get_term_reference_id( $term_id ) {
		$reference_id = get_term_meta( $term_id, 'instawp_event_term_sync_reference_id', true );

		return ! empty( $reference_id ) ? $reference_id : self::set_term_reference_id( $term_id );
	}

	/*
	 * Update user metas
	 */
	public static function set_term_reference_id( $term_id ) {
		$reference_id = InstaWP_Tools::get_random_string();
		update_term_meta( $term_id, 'instawp_event_term_sync_reference_id', $reference_id );

		return $reference_id;
	}

	/*
     * Get user metas
     */
	public static function get_user_reference_id( $user_id ) {
		$reference_id = get_user_meta( $user_id, 'instawp_event_user_sync_reference_id', true );

		return ! empty( $reference_id ) ? $reference_id : self::set_user_reference_id( $user_id );
	}

	/*
	 * Update user metas
	 */
	public static function set_user_reference_id( $user_id ) {
		$reference_id = InstaWP_Tools::get_random_string();
		update_user_meta( $user_id, 'instawp_event_user_sync_reference_id', $reference_id );

		return $reference_id;
	}

	public static function can_sync( $module ) {
		$syncing_status = get_option( 'instawp_is_event_syncing', 0 );
		$can_sync       = ( intval( $syncing_status ) === 1 ) && self::is_enabled( $module );

		return (bool) apply_filters( 'INSTAWP_CONNECT/Filters/can_two_way_sync', $can_sync, $module );
	}

	/**
	 * Get media from content
	 */
	public static function get_media_from_content( $content ) {
		preg_match_all( '!(https?:)?//\S+\.(?:jpe?g|jpg|png|gif|mp4|pdf|doc|docx|xls|xlsx|csv|txt|rtf|html|zip|mp3|wma|mpg|flv|avi)!Ui', $content, $match );

		$media = array();
		if ( isset( $match[0] ) ) {
			$attachment_urls = array_unique( $match[0] );

			foreach ( $attachment_urls as $attachment_url ) {
				if ( strpos( $attachment_url, $_SERVER['HTTP_HOST'] ) !== false ) {
					$full_attachment_url = preg_replace( '~-[0-9]+x[0-9]+.~', '.', $attachment_url );
					$attachment_data     = self::url_to_attachment( $full_attachment_url );

					$media[] = array_merge( $attachment_data, array(
						'attachment_url' => $attachment_url,
					) );
				}
			}
		}

		return $media;
	}

	public static function url_to_attachment( $attachment_url ) {
		global $wpdb;

		$attachment_id = attachment_url_to_postid( $attachment_url );

		if ( $attachment_id === 0 ) {
			$post_name = sanitize_title( pathinfo( $attachment_url, PATHINFO_FILENAME ) );

			$sql     = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_name = '%s'", $post_name );
			$results = $wpdb->get_results( $sql );

			if ( $results ) {
				$attachment_id = reset( $results )->ID;
			}
		}

		return self::attachment_to_string( $attachment_id );
	}

	public static function sync_response( $data, $log_data = array(), $args = array() ) {
		$data = array(
			'data' => wp_parse_args( $args, array(
				'id'      => $data->id,
				'hash'    => $data->event_hash,
				'status'  => 'completed',
				'message' => 'Sync successfully.',
			) ),
		);

		if ( ! empty( $log_data ) ) {
			$data['log_data'] = $log_data;
		}

		return $data;
	}

	/**
	 * Verify the sync feature is enabled or not.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function is_enabled( $key ) {
		$default = ( 'option' === $key ) ? 'off' : 'on';
		$value   = InstaWP_Setting::get_option( 'instawp_sync_' . $key, $default );
		$value   = empty( $value ) ? $default : $value;

		return 'on' === $value;
	}

	public static function object_to_array( $object ) {
		if ( is_object( $object ) || is_array( $object ) ) {
			$result = array();
			foreach ( $object as $key => $value ) {
				$result[ $key ] = self::object_to_array( $value );
			}

			return $result;
		}

		return $object;
	}

	public static function attachment_to_string( $attachment_id, $size = 'thumbnail', $parent_check = false ) {
		if ( ! $attachment_id ) {
			return array();
		}

		$image_path = wp_get_attachment_image_url( $attachment_id, $size, false );
		if ( ! $image_path ) {
			return array();
		}

		$image_info   = pathinfo( $image_path );
		$file_type    = wp_check_filetype( $image_info['basename'], null );
		$image_string = base64_encode( file_get_contents( $image_path ) );
		$attachment   = get_post( $attachment_id );

		$image_info['reference_id'] = self::get_post_reference_id( $attachment_id );
		$image_info['post_name']    = $attachment->post_name;
		$image_info['post_meta']    = get_post_meta( $attachment_id );
		$image_info['file_type']    = $file_type['type'];

		if ( $attachment->post_parent && $parent_check ) {
			$image_info['post_parent'] = self::parse_post_data( $attachment->post_parent );
		}

		$image_info['base_data'] = $image_string;

		return $image_info;
	}

	public static function string_to_attachment( $data ) {
		$attachment_id = 0;

		if ( empty( $data ) ) {
			return $attachment_id;
		}

		$attachment = self::get_post_by_reference( 'attachment', $data['reference_id'], $data['post_name'] );
		if ( ! $attachment ) {
			$image_data = base64_decode( $data['base_data'] );
			$upload_dir = wp_upload_dir();
			$file_name  = wp_unique_filename( $upload_dir['path'], $data['basename'] );
			$save_path  = $upload_dir['path'] . DIRECTORY_SEPARATOR . $file_name;

			file_put_contents( $save_path, $image_data );

			$attachment = array(
				'post_mime_type' => $data['file_type'],
				'post_title'     => sanitize_file_name( $file_name ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $save_path,
			);

			$parent_post_id = 0;

			if ( ! empty( $data['post_parent'] ) ) {
				$parent_post_id            = self::parse_post_events( $data['post_parent'] );
				$attachment['post_parent'] = $parent_post_id;
			}

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			$attachment_id = wp_insert_attachment( $attachment, $save_path, $parent_post_id );

			if ( ! is_wp_error( $attachment_id ) ) {
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $save_path );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
				update_post_meta( $attachment_id, 'instawp_event_sync_reference_id', $data['reference_id'] );
			}
		} else {
			$attachment_id = $attachment->ID;
		}

		self::process_post_meta( $data['post_meta'], $attachment_id );

		return $attachment_id;
	}

	/**
	 * get post type singular name
	 *
	 * @param $post_name
	 * @param $post_type
	 */
	public static function get_post_by_name( $post_name, $post_type ) {
		global $wpdb;

		$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s ", $post_name, $post_type ) );
		if ( $post ) {
			return get_post( $post );
		}

		return null;
	}

	public static function get_post_by_reference( $post_type, $reference_id, $post_name ) {
		$post = get_posts( array(
			'post_type'   => $post_type,
			'meta_key'    => 'instawp_event_sync_reference_id',
			'meta_value'  => $reference_id,
			'post_status' => 'any',
			'nopaging'    => true,
		) );

		return ! empty( $post ) ? reset( $post ) : self::get_post_by_name( $post_name, $post_type );
	}

	public static function process_post_meta( $meta_data, $post_id ) {
		if ( empty( $meta_data ) || ! is_array( $meta_data ) ) {
			return;
		}

		if ( array_key_exists( '_elementor_version', $meta_data ) && ! array_key_exists( '_elementor_css', $meta_data ) ) {
			$elementor_css = array();
			add_post_meta( $post_id, '_elementor_css', $elementor_css );
		}

		foreach ( $meta_data as $meta_key => $values ) {
			$value = $values[0];

			if ( '_elementor_data' === $meta_key ) {
				$value = wp_slash( $value );
			} else {
				$value = maybe_unserialize( $value );
			}

			update_metadata( 'post', $post_id, $meta_key, $value );
		}

		if ( self::is_built_with_elementor( $post_id ) ) {
			$css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );
			$css_file->delete();
		}

		delete_post_meta( $post_id, '_edit_lock' );
	}

	public static function parse_post_events( $details ) {
		$wp_post     = isset( $details['post'] ) ? $details['post'] : array();
		$parent_data = isset( $details['parent'] ) ? $details['parent'] : array();

		if ( ! $wp_post ) {
			return 0;
		}

		kses_remove_filters();

		if ( $wp_post['post_type'] === 'attachment' ) {
			$wp_post['ID'] = self::string_to_attachment( $details['attachment'] );
		} else {
			$post_meta      = isset( $details['post_meta'] ) ? $details['post_meta'] : array();
			$featured_image = isset( $details['featured_image'] ) ? $details['featured_image'] : array();
			$content_media  = isset( $details['media'] ) ? $details['media'] : array();
			$taxonomies     = isset( $details['taxonomies'] ) ? $details['taxonomies'] : array();
			$wp_post['ID']  = self::create_or_update_post( $wp_post, $post_meta, $details['reference_id'] );

			delete_post_thumbnail( $wp_post['ID'] );

			if ( ! empty( $featured_image ) ) {
				$attachment_id = self::string_to_attachment( $featured_image );
				if ( ! empty( $attachment_id ) ) {
					set_post_thumbnail( $wp_post['ID'], $attachment_id );
				}
			}

			do_action( 'INSTAWP_CONNECT/Actions/process_two_way_sync_post', $wp_post, $details );

			self::reset_post_terms( $wp_post['ID'] );

			foreach ( $taxonomies as $taxonomy => $terms ) {
				$term_ids = array();
				foreach ( $terms as $term ) {
					$term = ( array ) $term;
					if ( ! term_exists( $term['slug'], $taxonomy ) ) {
						$inserted_term = wp_insert_term( $term['name'], $taxonomy, array(
							'description' => $term['description'],
							'slug'        => $term['slug'],
							'parent'      => 0,
						) );
						if ( ! is_wp_error( $inserted_term ) ) {
							$term_ids[] = $inserted_term['term_id'];
						}
					} else {
						$get_term_by = ( array ) get_term_by( 'slug', $term['slug'], $taxonomy );
						$term_ids[]  = $get_term_by['term_id'];
					}
				}
				wp_set_post_terms( $wp_post['ID'], $term_ids, $taxonomy );
			}

			self::upload_content_media( $content_media, $wp_post['ID'] );
		}

		if ( ! empty( $parent_data ) ) {
			$parent_post_id = self::parse_post_events( $parent_data );

			wp_update_post( array(
				'ID'          => $wp_post['ID'],
				'post_parent' => $parent_post_id,
			) );
		}

		kses_init_filters();

		return $wp_post['ID'];
	}

	public static function parse_post_data( $post, $post_before = null ) {
		kses_remove_filters();

		$post = get_post( $post );
		if ( ! $post ) {
			return $post;
		}

		$post_content       = isset( $post->post_content ) ? $post->post_content : '';
		$post_parent_id     = $post->post_parent;
		$reference_id       = self::get_post_reference_id( $post->ID );
		$post->post_content = base64_encode( $post_content );

		if ( $post_before ) {
			$post_before->post_content = base64_encode( $post_before->post_content );
			$post_object               = ( object ) instawp_array_recursive_diff( ( array ) $post, ( array ) $post_before );
			$post_object->post_name    = $post->post_name;
			$post_object->post_status  = $post->post_status;
			$post_object->post_type    = $post->post_type;
		} else {
			$post_object = $post;
		}

		$data = array(
			'post'         => $post_object,
			'post_meta'    => get_post_meta( $post->ID ),
			'reference_id' => $reference_id,
		);

		if ( $post->post_type === 'attachment' ) {
			$data['attachment'] = self::attachment_to_string( $post->ID, 'full', true );
		} else {
			$taxonomies = self::get_taxonomies_items( $post->ID );
			if ( ! empty( $taxonomies ) ) {
				$data['taxonomies'] = $taxonomies;
			}

			$featured_image_id = get_post_thumbnail_id( $post->ID );
			if ( $featured_image_id ) {
				$data['featured_image'] = self::attachment_to_string( $featured_image_id );
			}

			if ( ! empty( $post_object->post_content ) && $post_content ) {
				$data['media'] = self::get_media_from_content( $post_content );
			}
		}

		if ( $post_parent_id > 0 ) {
			$post_parent = get_post( $post_parent_id );

			if ( $post_parent->post_status !== 'auto-draft' ) {
				$data['parent'] = self::parse_post_data( $post_parent );
			}
		}

		kses_init_filters();

		return $data;
	}

	/**
	 * Get taxonomies items
	 */
	public static function get_taxonomies_items( $post_id ) {
		$taxonomies = get_post_taxonomies( $post_id );
		$items      = array();

		if ( ! empty ( $taxonomies ) && is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$taxonomy_items = get_the_terms( $post_id, $taxonomy );

				if ( ! empty( $taxonomy_items ) && is_array( $taxonomy_items ) ) {
					foreach ( $taxonomy_items as $k => $item ) {
						$items[ $item->taxonomy ][ $k ] = ( array ) $item;

						if ( $item->parent > 0 ) {
							$items[ $item->taxonomy ][ $k ]['cat_parent'] = ( array ) get_term( $item->parent, $taxonomy );
						}
					}
				}
			}
		}

		return $items;
	}

	public static function create_or_update_post( $post, $post_meta, $reference_id ) {
		$destination_post = self::get_post_by_reference( $post['post_type'], $reference_id, $post['post_name'] );

		if ( ! empty( $destination_post ) ) {
			unset( $post['post_author'] );
			$post_id = wp_update_post( self::prepare_post_data( $post, $destination_post->ID ) );
		} else {
			$default_post_user = InstaWP_Setting::get_option( 'instawp_default_user' );
			if ( ! empty( $default_post_user ) ) {
				$post['post_author'] = $default_post_user;
			}
			$post_id = wp_insert_post( self::prepare_post_data( $post ) );
		}

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			self::process_post_meta( $post_meta, $post_id );

			return $post_id;
		}

		return 0;
	}

	public static function prepare_post_data( $post, $post_id = 0 ) {
		unset( $post['ID'], $post['guid'] );

		if ( isset( $post['post_content'] ) ) {
			$post['post_content'] = base64_decode( $post['post_content'] );
		}

		if ( $post_id ) {
			$post['ID'] = $post_id;
		}

		return $post;
	}

	public static function reset_post_terms( $post_id ) {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}term_relationships WHERE object_id = %d",
				$post_id
			)
		);
	}

	/**
	 * upload_content_media
	 *
	 * @param $media
	 * @param $post_id
	 *
	 * @return void
	 */
	public static function upload_content_media( array $media, $post_id ) {
		$post    = get_post( $post_id );
		$content = $post->post_content;
		$new     = $old = array();

		if ( ! empty( $media ) ) {
			foreach ( $media as $media_item ) {
				if ( ! empty( $media_item['attachment_url'] ) ) {
					$attachment_id = self::string_to_attachment( $media_item );
					$new[]         = wp_get_attachment_url( $attachment_id );
					$old[]         = $media_item['attachment_url'];
				}
			}

			wp_update_post( array(
				'ID'           => $post_id,
				'post_content' => str_replace( $old, $new, $content ),
			) );
		}
	}

	public static function is_built_with_elementor( $post_id ) {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}

		return \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
	}
}