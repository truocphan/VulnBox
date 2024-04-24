<?php

/**
 * Handle meta table functionality.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Classes
 */

namespace Masteriyo\Repository;

use Masteriyo\MetaData;
use Masteriyo\Helper\Utils;
use Masteriyo\DateTime;

defined( 'ABSPATH' ) || exit;

abstract class AbstractRepository {


	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'post';

	/**
	 * This only needs set if you are using a custom metadata type (for example payment tokens.
	 * This should be the name of the field your table uses for associating meta with objects.
	 * For example, in payment_tokenmeta, this would be payment_token_id.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_id_field_for_meta = '';

	/**
	 * Data stored in meta keys, but not considered "meta" for an object.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Meta data which should exist in the DB, even if empty.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $must_exist_meta_keys = array();

	/**
	 * Data stored in separate lookup table.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $internal_lookup_keys = array();

	/**
	 * If we have already saved our extra data, don't do automatic / default handling.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $extra_data_saved = false;

	/**
	 * Stores updated props.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $updated_props = array();

	/**
	 * Deletes a meta based on meta ID.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model      Model object.
	 * @param MetaData $meta    MetaData object.
	 *
	 * @return void
	 */
	public function delete_meta( &$model, $meta ) {
		// TODO Abstract the delete_metadata_by_mid().
		delete_metadata_by_mid( $this->meta_type, $meta->id );
	}

	/**
	 * Add new piece of meta.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model      Model object.
	 * @param MetaData $meta    MetaData object.
	 *
	 * @return int meta ID.
	 */
	public function add_meta( &$model, $meta ) {
		return add_metadata( $this->meta_type, $model->get_id(), $meta->key, $meta->value, false );
	}

	/**
	 * Update meta.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $mode       Model object.
	 * @param MetaData $meta    MetaData object.
	 *
	 * @return void
	 */
	public function update_meta( &$model, $meta ) {
		update_metadata_by_mid( $this->meta_type, $meta->id, $meta->value, $meta->key );
	}

	/**
	 * Read meta.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model Model object.
	 *
	 * @return MetaData[]
	 */
	public function read_meta( &$model ) {
		// TODO Abstract global $wpdb;
		global $wpdb;

		$meta_table_info = $this->get_meta_table_info();

		$cache         = masteriyo_cache();
		$cache_key     = array(
			'metadata',
			$meta_table_info['table'],
			$meta_table_info['object_id_field'],
			$model->get_id(),
		);
		$raw_meta_data = $cache->get( $cache_key );

		if ( false === $raw_meta_data ) {
			// phpcs:disable
			$sql = $wpdb->prepare(
				"SELECT {$meta_table_info['meta_id_field']} as  meta_id, meta_key, meta_value
					FROM {$meta_table_info['table']}
					WHERE {$meta_table_info['object_id_field']} = %d
					ORDER BY {$meta_table_info['meta_id_field']}",
				$model->get_id()
			);
			$raw_meta_data = $wpdb->get_results($sql);
			// phpcs:enable

			$cache->set( $cache_key, $raw_meta_data );
		}

		$meta_data = array_map(
			function ( $meta_data ) {
				return new MetaData(
					array(
						'id'    => $meta_data->meta_id,
						'key'   => $meta_data->meta_key,
						'value' => maybe_unserialize( $meta_data->meta_value ),
					)
				);
			},
			$raw_meta_data
		);

		return $this->filter_raw_meta_data( $model, $meta_data );
	}

	/**
	 * Helper method to filter internal meta keys from all meta data rows for the object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Database\Model $model        Model object.
	 * @param array   $raw_meta_data Array of std object of meta data to be filtered.
	 *
	 * @return array
	 */
	public function filter_raw_meta_data( &$model, $raw_meta_data ) {
		$this->internal_meta_keys = array_merge( array_map( array( $this, 'prefix_key' ), $model->get_data_keys() ), $this->internal_meta_keys );
		$meta_data                = array_filter( $raw_meta_data, array( $this, 'exclude_internal_meta_keys' ) );

		/**
		 * Filters raw meta data of a model.
		 *
		 * @since 1.0.0
		 *
		 * @param array $meta_data The raw meta data.
		 * @param Masteriyo\Database\Model $model Model object.
		 * @param Masteriyo\Repository\AbstractRepository $repository Repository object.
		 */
		return apply_filters( "masteriyo_repository_{$this->meta_type}_read_meta", $meta_data, $model, $this );
	}

	/**
	 * Callback to remove unwanted meta data.
	 *
	 * @since 1.0.0
	 *
	 * @param object $meta Meta object to check if it should be excluded or not.
	 * @return bool
	 */
	protected function exclude_internal_meta_keys( $meta ) {
		if ( ! $meta->meta_key ) {
			return true;
		}

		return ! in_array( $meta->meta_key, $this->internal_meta_keys, true ) && 0 !== stripos( $meta->meta_key, 'wp_' );
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 1.0.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The WP_Data object.
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_user_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_user_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_user_meta( $object->get_id(), $meta_key, $meta_value );
		}

		if ( $updated ) {
			$this->invalidate_metadata_cache( $object->get_id() );
		}

		return (bool) $updated;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 1.0.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The Model object.
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_post_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_post_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_post_meta( $object->get_id(), $meta_key, $meta_value );
		}

		if ( $updated ) {
			$this->invalidate_metadata_cache( $object->get_id() );
		}

		return (bool) $updated;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 1.0.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The Model object.
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_comment_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_comment_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_comment_meta( $object->get_id(), $meta_key, $meta_value );
		}

		if ( $updated ) {
			$this->invalidate_metadata_cache( $object->get_id() );
		}

		return (bool) $updated;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 1.0.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The WP_Data object.
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_custom_table_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_metadata( $this->meta_type, $object->get_id(), $meta_key );
		} else {
			$updated = update_metadata( $this->meta_type, $object->get_id(), $meta_key, $meta_value );
		}

		if ( $updated ) {
			$this->invalidate_metadata_cache( $object->get_id() );
		}

		return (bool) $updated;
	}

	/**
	 * Update meta data in, or delete it from, the database.
	 *
	 * Avoids storing meta when it's either an empty string or empty array.
	 * Other empty values such as numeric 0 and null should still be stored.
	 * Data-stores can force meta to exist using `must_exist_meta_keys`.
	 *
	 * Note: WordPress `get_metadata` function returns an empty string when meta data does not exist.
	 *
	 * @since 1.0.0 Added to prevent empty meta being stored unless required.
	 *
	 * @param Model $object The Model object
	 * @param Model $object The Model object.
	 * @param string  $meta_key Meta key to update.
	 * @param mixed   $meta_value Value to save.
	 *
	 *
	 * @return bool True if updated/deleted.
	 */
	protected function update_or_delete_term_meta( $object, $meta_key, $meta_value ) {
		if ( in_array( $meta_value, array( array(), '' ), true ) && ! in_array( $meta_key, $this->get_must_exist_meta_keys(), true ) ) {
			$updated = delete_term_meta( $object->get_id(), $meta_key );
		} else {
			$updated = update_term_meta( $object->get_id(), $meta_key, $meta_value );
		}

		if ( $updated ) {
			$this->invalidate_metadata_cache( $object->get_id() );
		}

		return (bool) $updated;
	}

	/**
	 * Invalidate/destroy the metadata cache related to a specific object.
	 *
	 * @since 1.6.16
	 *
	 * @param integer $object_id The Model object ID.
	 */
	protected function invalidate_metadata_cache( $object_id ) {
		$meta_table_info = $this->get_meta_table_info();
		$cache_key       = array(
			'metadata',
			$meta_table_info['table'],
			$meta_table_info['object_id_field'],
			$object_id,
		);

		masteriyo_cache()->delete( $cache_key );
	}

	/**
	 * Returns meta table info.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_meta_table_info() {
		// TODO Abstract wpdb class.
		global $wpdb;

		$meta_id_field = 'meta_id';
		$table         = $wpdb->prefix;

		// If we are dealing with a type of metadata that is not a core type, the table should be prefixed.
		if ( ! in_array( $this->meta_type, array( 'post', 'user', 'comment', 'term' ), true ) ) {
			$table .= 'masteriyo_';
		}

		$table          .= $this->meta_type . 'meta';
		$object_id_field = $this->meta_type . '_id';

		// Figure out our field names.
		if ( 'user' === $this->meta_type ) {
			$meta_id_field = 'umeta_id';
			$table         = $wpdb->usermeta;
		}

		if ( ! empty( $this->object_id_field_for_meta ) ) {
			$object_id_field = $this->object_id_field_for_meta;
		}

		return array(
			'table'           => $table,
			'object_id_field' => $object_id_field,
			'meta_id_field'   => $meta_id_field,
		);
	}

	/**
	 * Retrieve stopwords used when parsing search terms.
	 *
	 * @since 1.0.0
	 *
	 * @return array Stopwords.
	 */
	protected function get_search_stopwords() {
		 // Translators: This is a comma-separated list of very common words that should be excluded from a search, like a, an, and the. These are usually called "stopwords". You should not simply translate these individual words into your language. Instead, look for and provide commonly accepted stopwords in your language.
		$stopwords = array_map(
			array( Utils::class, 'strtolower' ),
			array_map(
				'trim',
				explode(
					',',
					_x(
						'about,an,are,as,at,be,by,com,for,from,how,in,is,it,of,on,or,that,the,this,to,was,what,when,where,who,will,with,www',
						'Comma-separated list of search stopwords in your language',
						'masteriyo'
					)
				)
			)
		);

		return apply_filters( 'wp_search_stopwords', $stopwords );
	}

	/**
	 * Get and store terms from a taxonomy.
	 *
	 * @since  1.0.0
	 * @param  Model|integer $model Model model or model ID.
	 * @param  string          $taxonomy Taxonomy name e.g. model_cat.
	 * @return array of terms
	 */
	protected function get_term_ids( $model, $taxonomy ) {
		$id = is_numeric( $model ) ? absint( $model ) : $model->get_id();

		$terms = get_the_terms( $id, $taxonomy );

		if ( false === $terms || is_wp_error( $terms ) ) {
			return array();
		}

		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Check if the terms are suitable for searching.
	 *
	 * Uses an array of stopwords (terms) that are excluded from the separate
	 * term matching when searching for posts. The list of English stopwords is
	 * the approximate search engines list, and is translatable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $terms Terms to check.
	 *
	 * @return array Terms that are not stopwords.
	 */
	protected function get_valid_search_terms( $terms ) {
		$valid_terms = array();
		$stopwords   = $this->get_search_stopwords();

		foreach ( $terms as $term ) {
			// keep before/after spaces when term is for exact match, otherwise trim quotes and spaces.
			if ( preg_match( '/^".+"$/', $term ) ) {
				$term = trim( $term, "\"'" );
			} else {
				$term = trim( $term, "\"' " );
			}

			// Avoid single A-Z and single dashes.
			if ( empty( $term ) || ( 1 === strlen( $term ) && preg_match( '/^[a-z\-]$/i', $term ) ) ) {
				continue;
			}

			if ( in_array( Utils::strtolower( $term ), $stopwords, true ) ) {
				continue;
			}

			$valid_terms[] = $term;
		}

		return $valid_terms;
	}

	/**
	 * Internal meta keys we don't want exposed as part of meta_data. This is in
	 * addition to all data props with _ prefix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Prefix to be added to meta keys.
	 *
	 * @return string
	 */
	protected function prefix_key( $key ) {
		return '_' === substr( $key, 0, 1 ) ? $key : '_' . $key;
	}

	/**
	 * Get data to save to a lookup table.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $id ID of object to update.
	 * @param string $table Lookup table name.
	 *
	 * @return array
	 */
	protected function get_data_for_lookup_table( $id, $table ) {
		return array();
	}

	/**
	 * Get primary key name for lookup table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $table Lookup table name.
	 *
	 * @return string
	 */
	protected function get_primary_key_for_lookup_table( $table ) {
		return '';
	}

	/**
	 * Gets a list of props and meta keys that need updated based on change state
	 * or if they are present in the database or not.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model   $model               The Model model.
	 * @param  array   $meta_key_to_props   A mapping of meta keys => prop names.
	 * @param  string  $meta_type           The internal WP meta type (post, user, etc).
	 * @return array                        A mapping of meta keys => prop names, filtered by ones that should be updated.
	 */
	protected function get_props_to_update( $model, $meta_key_to_props, $meta_type = 'post' ) {
		$props_to_update = array();
		$changed_props   = $model->get_changes();

		// Props should be updated if they are a part of the $changed array or don't exist yet.
		foreach ( $meta_key_to_props as $meta_key => $prop ) {
			if (
				array_key_exists( $prop, $changed_props )
				|| ! metadata_exists( $meta_type, $model->get_id(), $meta_key )
			) {
				$props_to_update[ $meta_key ] = $prop;
			}
		}

		return $props_to_update;
	}

	/**
	 * Update a lookup table for an object.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $id ID of object to update.
	 * @param string $table Lookup table name.
	 *
	 * @return NULL
	 */
	protected function update_lookup_table( $id, $table ) {
		global $wpdb;

		$id    = absint( $id );
		$table = sanitize_key( $table );

		if ( empty( $id ) || empty( $table ) ) {
			return false;
		}

		$existing_data = wp_cache_get( 'lookup_table', 'model_' . $id );
		$update_data   = $this->get_data_for_lookup_table( $id, $table );

		if ( ! empty( $update_data ) && $update_data !== $existing_data ) {
			$wpdb->replace(
				$wpdb->$table,
				$update_data
			);
			wp_cache_set( 'lookup_table', $update_data, 'model_' . $id );
		}
	}

	/**
	 * Delete lookup table data for an ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $id ID of model to update.
	 * @param string $table Lookup table name.
	 */
	public function delete_from_lookup_table( $id, $table ) {
		global $wpdb;

		$id    = absint( $id );
		$table = sanitize_key( $table );

		if ( empty( $id ) || empty( $table ) ) {
			return false;
		}

		$pk = $this->get_primary_key_for_lookup_table( $table );

		$wpdb->delete(
			$wpdb->$table,
			array(
				$pk => $id,
			)
		);

		wp_cache_delete( 'lookup_table', 'model_' . $id );
	}

	/**
	 * Helper method that updates all the post meta for a model based on it's settings in the Model class.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_post_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ $key ] = '_' . $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update = $this->get_props_to_update( $model, $this->get_internal_meta_keys() );
		}

		foreach ( $props_to_update as $prop => $meta_key ) {
			if ( ! is_callable( array( $model, "get_{$prop}" ) ) ) {
				continue;
			}

			$value = $model->{"get_$prop"}( 'edit' );
			$value = is_string( $value ) ? wp_slash( $value ) : $value;
			switch ( $prop ) {
				case 'featured':
					$value = masteriyo_bool_to_string( $value );
					break;
			}

			$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Helper method that updates all the comments meta for a model based on it's settings in the Model class.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_comment_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ '_' . $key ] = $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update = $this->get_props_to_update( $model, $this->get_internal_meta_keys(), 'comment' );
		}

		foreach ( $props_to_update as $prop => $meta_key ) {
			if ( ! is_callable( array( $model, "get_{$prop}" ) ) ) {
				continue;
			}

			$value   = $model->{"get_$prop"}( 'edit' );
			$value   = is_string( $value ) ? wp_slash( $value ) : $value;
			$updated = $this->update_or_delete_comment_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_comment_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Helper method that updates all the custom table meta for a model based on it's settings in the Model class.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_custom_table_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ '_' . $key ] = $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update = $this->get_props_to_update( $model, $this->get_internal_meta_keys(), $this->meta_type );
		}

		foreach ( $props_to_update as $prop => $meta_key ) {
			if ( ! is_callable( array( $model, "get_{$prop}" ) ) ) {
				continue;
			}

			$value   = $model->{"get_$prop"}( 'edit' );
			$value   = is_string( $value ) ? wp_slash( $value ) : $value;
			$updated = $this->update_or_delete_custom_table_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_custom_table_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Helper method that updates all the post meta for a model based on it's settings in the Model class.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $model model object.
	 * @param bool  $force Force update. Used during create.
	 */
	protected function update_term_meta( &$model, $force = false ) {
		// Make sure to take extra data into account.
		$extra_data_keys = $model->get_extra_data_keys();

		foreach ( $extra_data_keys as $key ) {
			$meta_key_to_props[ '_' . $key ] = $key;
		}

		if ( $force ) {
			$props_to_update = $this->get_internal_meta_keys();
		} else {
			$props_to_update = $this->get_props_to_update( $model, $this->get_internal_meta_keys() );
		}

		foreach ( $props_to_update as $prop => $meta_key ) {
			if ( ! is_callable( array( $model, "get_{$prop}" ) ) ) {
				continue;
			}

			$value = $model->{"get_$prop"}( 'edit' );
			$value = is_string( $value ) ? wp_slash( $value ) : $value;
			switch ( $prop ) {
				case 'featured':
					$value = Utils::bool_to_string( $value );
					break;
			}

			$updated = $this->update_or_delete_term_meta( $model, $meta_key, $value );

			if ( $updated ) {
				$this->updated_props[] = $prop;
			}
		}

		// Update extra data associated with the model like button text or model URL for external models.
		if ( ! $this->extra_data_saved ) {
			foreach ( $extra_data_keys as $key ) {
				$meta_key = '_' . $key;
				$function = 'get_' . $key;
				if ( ! array_key_exists( $meta_key, $props_to_update ) ) {
					continue;
				}
				if ( is_callable( array( $model, $function ) ) ) {
					$value   = $model->{$function}( 'edit' );
					$value   = is_string( $value ) ? wp_slash( $value ) : $value;
					$updated = $this->update_or_delete_post_meta( $model, $meta_key, $value );

					if ( $updated ) {
						$this->updated_props[] = $key;
					}
				}
			}
		}
	}

	/**
	 * Get internal meta keys.
	 *
	 * @since 1.0.0
	 * @since 1.4.8 Changed access modifier to `public`.
	 *
	 * @return array
	 */
	public function get_internal_meta_keys() {
		return $this->internal_meta_keys;
	}

	/**
	 * Get must exist meta keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_must_exist_meta_keys() {
		 return $this->must_exist_meta_keys;
	}

	/**
	 * Get lookup data keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_internal_lookup_keys() {
		 return $this->internal_lookup_keys;
	}

	/**
	 * Get valid WP_Query args from a ObjectQuery's query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars query vars from a ObjectQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		$skipped_values = array( '', array(), null );
		$wp_query_args  = array(
			'errors'     => array(),
			'meta_query' => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		);

		foreach ( $query_vars as $key => $value ) {
			if ( in_array( $value, $skipped_values, true ) || 'meta_query' === $key ) {
				continue;
			}

			// Build meta queries out of vars that are stored in internal meta keys.
			if ( in_array( '_' . $key, $this->internal_meta_keys, true ) ) {
				// Check for existing values if wildcard is used.
				if ( '*' === $value ) {
					$wp_query_args['meta_query'][] = array(
						array(
							'key'     => '_' . $key,
							'compare' => 'EXISTS',
						),
						array(
							'key'     => '_' . $key,
							'value'   => '',
							'compare' => '!=',
						),
					);
				} else {
					$wp_query_args['meta_query'][] = array(
						'key'     => '_' . $key,
						'value'   => $value,
						'compare' => is_array( $value ) ? 'IN' : '=',
					);
				}
			} else { // Other vars get mapped to wp_query args or just left alone.
				$key_mapping = array(
					'status'         => 'post_status',
					'page'           => 'paged',
					'include'        => 'post__in',
					'exclude'        => 'post__not_in',
					'parent'         => 'post_parent',
					'parent_exclude' => 'post_parent__not_in',
					'limit'          => 'posts_per_page',
					'type'           => 'post_type',
					'return'         => 'fields',
				);

				if ( isset( $key_mapping[ $key ] ) ) {
					$wp_query_args[ $key_mapping[ $key ] ] = $value;
				} else {
					$wp_query_args[ $key ] = $value;
				}
			}
		}

		/**
		 * Filter WP query vars.
		 *
		 * @since 1.0.0
		 * @since 1.4.9 Added third parameter $repository.
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars query vars from a ObjectQuery.
		 * @param Masteriyo\Repository\AbstractRepository $repository AbstractRepository object.
		 *
		 * @return array WP Query args.
		 */
		return apply_filters( 'masteriyo_get_wp_query_args', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Map a valid date query var to WP_Query arguments.
	 * Valid date formats: YYYY-MM-DD or timestamp, possibly combined with an operator from $valid_operators.
	 * Also accepts a DateTime object.
	 *
	 * @since 1.0.0
	 * @param mixed  $query_var A valid date format.
	 * @param string $key meta or db column key.
	 * @param array  $wp_query_args WP_Query args.
	 * @return array Modified $wp_query_args
	 */
	public function parse_date_for_wp_query( $query_var, $key, $wp_query_args = array() ) {
		$query_parse_regex = '/([^.<>]*)(>=|<=|>|<|\.\.\.)([^.<>]+)/';
		$valid_operators   = array( '>', '>=', '=', '<=', '<', '...' );

		// YYYY-MM-DD queries have 'day' precision. Timestamp/DateTime queries have 'second' precision.
		$precision = 'second';

		$dates    = array();
		$operator = '=';

		try {
			// Specific time query with a DateTime.
			if ( is_a( $query_var, 'Masteriyo\DateTime' ) ) {
				$dates[] = $query_var;
			} elseif ( is_numeric( $query_var ) ) { // Specific time query with a timestamp.
				$dates[] = new DateTime( "@{$query_var}", new DateTimeZone( 'UTC' ) );
			} elseif ( preg_match( $query_parse_regex, $query_var, $sections ) ) { // Query with operators and possible range of dates.
				if ( ! empty( $sections[1] ) ) {
					$dates[] = is_numeric( $sections[1] ) ? new DateTime( "@{$sections[1]}", new DateTimeZone( 'UTC' ) ) : masteriyo_string_to_datetime( $sections[1] );
				}

				$operator = in_array( $sections[2], $valid_operators, true ) ? $sections[2] : '';
				$dates[]  = is_numeric( $sections[3] ) ? new DateTime( "@{$sections[3]}", new DateTimeZone( 'UTC' ) ) : masteriyo_string_to_datetime( $sections[3] );

				if ( ! is_numeric( $sections[1] ) && ! is_numeric( $sections[3] ) ) {
					$precision = 'day';
				}
			} else { // Specific time query with a string.
				$dates[]   = masteriyo_string_to_datetime( $query_var );
				$precision = 'day';
			}
		} catch ( Exception $e ) {
			return $wp_query_args;
		}

		// Check for valid inputs.
		if ( ! $operator || empty( $dates ) || ( '...' === $operator && count( $dates ) < 2 ) ) {
			return $wp_query_args;
		}

		// Build date query for 'post_date' or 'post_modified' keys.
		if ( 'post_date' === $key || 'post_modified' === $key ) {
			if ( ! isset( $wp_query_args['date_query'] ) ) {
				$wp_query_args['date_query'] = array();
			}

			$query_arg = array(
				'column'    => 'day' === $precision ? $key : $key . '_gmt',
				'inclusive' => '>' !== $operator && '<' !== $operator,
			);

			// Add 'before'/'after' query args.
			$comparisons = array();
			if ( '>' === $operator || '>=' === $operator || '...' === $operator ) {
				$comparisons[] = 'after';
			}
			if ( '<' === $operator || '<=' === $operator || '...' === $operator ) {
				$comparisons[] = 'before';
			}

			foreach ( $comparisons as $index => $comparison ) {
				if ( 'day' === $precision ) {
					/**
					 * WordPress doesn't generate the correct SQL for inclusive day queries with both a 'before' and
					 * 'after' string query, so we have to use the array format in 'day' precision.
					 *
					 * @see https://core.trac.wordpress.org/ticket/29908
					 */
					$query_arg[ $comparison ]['year']  = $dates[ $index ]->date( 'Y' );
					$query_arg[ $comparison ]['month'] = $dates[ $index ]->date( 'n' );
					$query_arg[ $comparison ]['day']   = $dates[ $index ]->date( 'j' );
				} else {
					/**
					 * WordPress doesn't support 'hour'/'second'/'minute' in array format 'before'/'after' queries,
					 * so we have to use a string query.
					 */
					$query_arg[ $comparison ] = gmdate( 'm/d/Y H:i:s', $dates[ $index ]->getTimestamp() );
				}
			}

			if ( empty( $comparisons ) ) {
				$query_arg['year']  = $dates[0]->date( 'Y' );
				$query_arg['month'] = $dates[0]->date( 'n' );
				$query_arg['day']   = $dates[0]->date( 'j' );
				if ( 'second' === $precision ) {
					$query_arg['hour']   = $dates[0]->date( 'H' );
					$query_arg['minute'] = $dates[0]->date( 'i' );
					$query_arg['second'] = $dates[0]->date( 's' );
				}
			}
			$wp_query_args['date_query'][] = $query_arg;
			return $wp_query_args;
		}

		// Build meta query for unrecognized keys.
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Meta dates are stored as timestamps in the db.
		// Check against beginning/end-of-day timestamps when using 'day' precision.
		if ( 'day' === $precision ) {
			$start_timestamp = strtotime( gmdate( 'm/d/Y 00:00:00', $dates[0]->getTimestamp() ) );
			$end_timestamp   = '...' !== $operator ? ( $start_timestamp + DAY_IN_SECONDS ) : strtotime( gmdate( 'm/d/Y 00:00:00', $dates[1]->getTimestamp() ) );
			switch ( $operator ) {
				case '>':
				case '<=':
					$wp_query_args['meta_query'][] = array(
						'key'     => $key,
						'value'   => $end_timestamp,
						'compare' => $operator,
					);
					break;
				case '<':
				case '>=':
					$wp_query_args['meta_query'][] = array(
						'key'     => $key,
						'value'   => $start_timestamp,
						'compare' => $operator,
					);
					break;
				default:
					$wp_query_args['meta_query'][] = array(
						'key'     => $key,
						'value'   => $start_timestamp,
						'compare' => '>=',
					);
					$wp_query_args['meta_query'][] = array(
						'key'     => $key,
						'value'   => $end_timestamp,
						'compare' => '<=',
					);
			}
		} else {
			if ( '...' !== $operator ) {
				$wp_query_args['meta_query'][] = array(
					'key'     => $key,
					'value'   => $dates[0]->getTimestamp(),
					'compare' => $operator,
				);
			} else {
				$wp_query_args['meta_query'][] = array(
					'key'     => $key,
					'value'   => $dates[0]->getTimestamp(),
					'compare' => '>=',
				);
				$wp_query_args['meta_query'][] = array(
					'key'     => $key,
					'value'   => $dates[1]->getTimestamp(),
					'compare' => '<=',
				);
			}
		}

		return $wp_query_args;
	}

	/**
	 * Converts a WP post date string into a timestamp.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $time_string The WP post date string.
	 * @return int|null The date string converted to a timestamp or null.
	 */
	protected function string_to_timestamp( $time_string ) {
		return '0000-00-00 00:00:00' !== $time_string ? masteriyo_string_to_timestamp( $time_string ) : null;
	}

	/**
	 * Create sql where part for the array items.
	 *
	 * @since 1.3.9
	 *
	 * @param string $column Table column name.
	 * @param array $items Number of items in IN query.
	 * @return string
	 */
	protected function create_sql_in_query( $column, $fields ) {
		global $wpdb;

		if ( is_array( $fields ) && count( $fields ) > 1 ) {
			$fields_count = count( $fields );
			$placeholders = array_fill( 0, $fields_count, '%s' );
			$placeholders = implode( ', ', $placeholders );
			return $wpdb->prepare( "{$column} IN ({$placeholders})", $fields ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		} elseif ( is_array( $fields ) && 1 === count( $fields ) ) {
			$fields = current( $fields );
			return $wpdb->prepare( "$column = %s", $fields ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		} else {
			return $wpdb->prepare( "$column = %s", $fields ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}
}
