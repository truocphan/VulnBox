<?php
/**
 * Welcart item base class
 *
 * @package  Welcart
 */

namespace Welcart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Item class
 *
 * The Welcart item class handles individual product data.
 *
 * @since 2.2.2
 */
class ItemData {

	/**
	 * ID for this object.
	 *
	 * @since 2.2.2
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Item data format.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $item_format = array(
		'post_id'                => 0,
		'itemCode'               => null,
		'itemName'               => null,
		'itemRestriction'        => null,
		'itemPointrate'          => 0,
		'itemGpNum1'             => null,
		'itemGpNum2'             => null,
		'itemGpNum3'             => null,
		'itemGpDis1'             => null,
		'itemGpDis2'             => null,

		'itemGpDis3'             => null,
		'itemOrderAcceptable'    => 0,
		'itemShipping'           => 0,
		'itemDeliveryMethod'     => array(),
		'itemShippingCharge'     => 0,
		'itemIndividualSCharge'  => null,
		'item_charging_type'     => 0,
		'item_division'          => 'shipped',
		'dlseller_date'          => null,
		'dlseller_file'          => null,

		'dlseller_interval'      => null,
		'dlseller_validity'      => null,
		'dlseller_version'       => null,
		'dlseller_author'        => null,
		'dlseller_purchases'     => 0,
		'dlseller_downloads'     => 0,
		'item_chargingday'       => null,
		'item_frequency'         => null,
		'wcad_regular_unit'      => null,
		'wcad_regular_interval'  => null,

		'wcad_regular_frequency' => null,
		'select_sku_switch'      => 0,
		'select_sku_display'     => 0,
		'select_sku'             => array(),
		'atobarai_propriety'     => 0,
		'atodene_propriety'      => 0,
		'structuredDataSku'      => null,
		'lower_limit'            => null,
		'popularity'             => null,
		'main_price'             => null,

		'itemPicts'              => null,
		'itemAdvanced'           => array(),
	);

	/**
	 * Item data placeholders.
	 *
	 * @since 2.7
	 * @var array
	 */
	protected $placeholders = array(
		'%d',
		'%s',
		'%s',
		'%s',
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',

		'%s',
		'%d',
		'%d',
		'%s',
		'%d',
		'%s',
		'%d',
		'%s',
		'%s',
		'%s',

		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',

		'%s',
		'%d',
		'%d',
		'%s',
		'%d',
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',

		'%s',
		'%s',
	);

	/**
	 * Old Item data key.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $item_old_key = array(
		'itemCode'               => '_itemCode',
		'itemName'               => '_itemName',
		'itemRestriction'        => '_itemRestriction',
		'itemPointrate'          => '_itemPointrate',
		'itemGpNum1'             => '_itemGpNum1',
		'itemGpNum2'             => '_itemGpNum2',
		'itemGpNum3'             => '_itemGpNum3',
		'itemGpDis1'             => '_itemGpDis1',
		'itemGpDis2'             => '_itemGpDis2',
		'itemGpDis3'             => '_itemGpDis3',
		'itemOrderAcceptable'    => '_itemOrderAcceptable',
		'itemShipping'           => '_itemShipping',
		'itemDeliveryMethod'     => '_itemDeliveryMethod',
		'itemShippingCharge'     => '_itemShippingCharge',
		'itemIndividualSCharge'  => '_itemIndividualSCharge',
		'item_charging_type'     => '_item_charging_type',
		'item_division'          => '_item_division',
		'dlseller_date'          => '_dlseller_date',
		'dlseller_file'          => '_dlseller_file',
		'dlseller_interval'      => '_dlseller_interval',
		'dlseller_validity'      => '_dlseller_validity',
		'dlseller_version'       => '_dlseller_version',
		'dlseller_author'        => '_dlseller_author',
		'dlseller_purchases'     => '_dlseller_purchases',
		'dlseller_downloads'     => '_dlseller_downloads',
		'item_chargingday'       => '_item_chargingday',
		'item_frequency'         => '_item_frequency',
		'wcad_regular_unit'      => '_wcad_regular_unit',
		'wcad_regular_interval'  => '_wcad_regular_interval',
		'wcad_regular_frequency' => '_wcad_regular_frequency',
		'select_sku_switch'      => '_select_sku_switch',
		'select_sku_display'     => '_select_sku_display',
		'select_sku'             => '_select_sku',
		'atobarai_propriety'     => 'atobarai_propriety',
		'atodene_propriety'      => 'atodene_propriety',
		'structuredDataSku'      => '_structuredDataSku',
		'lower_limit'            => 'lower_limit',
		'popularity'             => 'popularity',
		'main_price'             => 'main_price',
		'itemPicts'              => '_itemPicts',
		'itemAdvanced'           => '_itemAdvanced',
	);

	/**
	 * SKU data format.
	 *
	 * @since 3.0
	 * @var array
	 */
	protected $sku_format = array(
		'meta_id'   => null,
		'post_id'   => null,
		'code'      => null,
		'name'      => null,
		'cprice'    => null,
		'price'     => null,
		'stocknum'  => null,
		'stock'     => null,
		'unit'      => null,
		'gp'        => null,
		'taxrate'   => null,
		'size'      => null,
		'weight'    => null,
		'pict_id'   => null,
		'advance'   => array(),
		'paternkey' => null,
		'sort'      => 0,
	);

	/**
	 * SKU placeholders.
	 *
	 * @since 3.0
	 * @var array
	 */
	protected $sku_placeholders = array(
		'%d',
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
	);

	/**
	 * Option data format.
	 *
	 * @since 3.0
	 * @var array
	 */
	protected $opt_format = array(
		'meta_id'   => null,
		'post_id'   => null,
		'code'      => null,
		'name'      => null,
		'means'     => 0,
		'essential' => 0,
		'value'     => null,
		'sort'      => 0,
	);

	/**
	 * Option placeholders.
	 *
	 * @since 3.0
	 * @var array
	 */
	protected $opt_placeholders = array(
		'%d',
		'%d',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
	);

	/**
	 * Exclusion meta key.
	 *
	 * @since 3.0
	 * @var array
	 */
	protected $exclusion_key = array(
		'_edit_lock' => null,
		'_edit_last' => null,
	);

	/**
	 * Perfect object.
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $product_data = array();

	/**
	 * Post datas for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $post_data = array();

	/**
	 * Item datas for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $item_data = array();

	/**
	 * All SKU datas for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $sku_data = array();

	/**
	 * All option datas for this object. Name value pairs (name + default value).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $opt_data = array();

	/**
	 * Extra datas for this object. Mainly custom fields(post_meta).
	 *
	 * @since 2.2.2
	 * @var array
	 */
	protected $ext_data = array();

	/**
	 * Get the item if ID is passed, otherwise the item is empty.
	 * This class should not be instantiated.
	 * The wc_get_product() function should be used instead.
	 *
	 * @param mixed   $the_item Post object or post ID of the item.
	 * @param boolean $cache Switch of cache.
	 */
	public function __construct( $the_item = 0, $cache = true ) {

		$this->item_data = $this->item_format;

		if ( is_numeric( $the_item ) && $the_item > 0 ) {
			$this->set_id( $the_item );
		} elseif ( is_object( $the_item ) && isset( $the_item->ID ) && ! empty( $the_item->ID ) ) {
			$this->set_id( absint( $the_item->ID ) );
			$this->post_data = $the_item;
		} else {
			$this->set_id( 0 );
		}

		$this->set_data( $this->id, $cache );
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Get ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set data.
	 *
	 * @since  2.2.2
	 * @param int     $post_id post ID of the Item.
	 * @param boolean $cache Switch of cache.
	 */
	public function set_data( $post_id, $cache = true ) {
		if ( ! $post_id ) {
			return $this->item_format;
		}

		$cache_key    = 'wel_product_data_' . $post_id;
		$product_data = wp_cache_get( $cache_key );

		if ( $cache && is_array( $product_data ) ) {

			foreach ( $product_data as $key => $value ) {
				if ( array_key_exists( $key, $this->item_format ) ) {
					$this->item_data[ $key ] = $value;
				}
			}
			$this->post_data    = $product_data['_pst'];
			$this->ext_data     = $product_data['_ext'];
			$this->sku_data     = $product_data['_sku'];
			$this->opt_data     = $product_data['_opt'];
			$this->product_data = $product_data;

		} else {

			if ( ! empty( $this->post_data ) ) {
				$post_data = $this->post_data;
			} else {
				$post_data = get_post( $post_id );
			}

			if ( null === $post_data ) {
				return false;
			}

			$_meta = get_post_meta( $post_id );
			$temp  = array();
			$item  = array();

			foreach ( $_meta as $key => $arr ) {

				$value_arr    = array();
				$value        = '';
				$meta_num     = count( $arr );
				$reserved_key = ltrim( $key, '_' );

				foreach ( $arr as $ind => $v ) {
					$value_arr[] = maybe_unserialize( $v );
				}

				if ( '_iopt_' === $key || '_isku_' === $key ) {

					continue;

				} elseif ( array_key_exists( $reserved_key, $this->item_format ) ) {

					continue;

				} elseif ( ! array_key_exists( $key, $this->exclusion_key ) ) {

					if ( '' === $key || '_' === substr( $key, 0, 1 ) || is_array( maybe_unserialize( $key ) ) ) {
						continue;
					}
					$ac = is_array( $arr ) ? count( $arr ) : null;
					if ( is_array( $arr ) && 1 < $ac ) {
						$val = $arr;
					} elseif ( is_array( $arr ) && 1 === $ac ) {
						$val = $arr[0];
					} else {
						$val = $arr;
					}

					$this->ext_data[ $key ] = $val;
				}
			}
			$this->item_data = $this->get_item( $cache );
			$this->opt_data  = $this->get_opts( 'sort', $cache );
			$this->sku_data  = $this->get_skus( 'sort', $cache );

			$product_data         = $this->item_data;
			$product_data['ID']   = $post_data->ID;
			$product_data['_pst'] = $post_data;
			$product_data['_ext'] = $this->ext_data;
			$product_data['_sku'] = $this->sku_data;
			$product_data['_opt'] = $this->opt_data;

			$this->product_data = $product_data;

			wp_cache_set( $cache_key, $product_data );
		}
	}

	/**
	 * Returns perfect object.
	 *
	 * @since  2.2.2
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_product( $cache = true ) {
		if ( ! isset( $this->id ) ) {
			return false;
		}

		if ( ! $cache ) {
			$this->set_data( $this->id, $cache );
		}
		return $this->product_data;
	}

	/**
	 * Returns item data of this object.
	 *
	 * @since  2.2.2
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_item( $cache = true ) {
		global $wpdb, $usces;

		if ( ! isset( $this->id ) ) {
			return false;
		}

		$item = array();

		if ( ! isset( $usces->update_db_2_7 ) ) {

			$cache_key = 'wel_get_item_' . $this->id;
			if ( $cache ) {
				$res = wp_cache_get( $cache_key );
			} else {
				$res = false;
			}

			if ( false === $res ) {

				$item_table = $wpdb->prefix . 'usces_item';

				$res = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT * FROM {$item_table} WHERE post_id = %d",
						$this->id
					),
					ARRAY_A
				);
				if ( null === $res ) {
					$item = $this->item_format;
				}
				wp_cache_set( $cache_key, $res );
			}

			if ( $res ) {

				foreach ( $res as $key => $value ) {

					$json = json_decode( $value, true );

					if ( 'itemPicts' === $key ) {
						if ( 0 === strlen( $value ) ) {
							$item['itemPicts'] = '';
						} else {
							$item['itemPicts'] = explode( ';', $value );
						}
					} elseif ( null !== $json ) {
							$item[ $key ] = $json;
					} else {
						$item[ $key ] = $value;
					}
				}
			}

			$this->item_data = $item;
		}

		return $this->item_data;
	}

	/**
	 * Returns all sku sorted data of this object.
	 *
	 * @since  2.2.2
	 * @param string  $keyflag Regenerate skus with sku value as skus index.
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_skus( $keyflag = 'sort', $cache = true ) {
		global $wpdb, $usces;

		if ( ! isset( $this->id ) ) {
			return false;
		}

		if ( version_compare( USCES_VERSION, '2.7-beta', '>=' ) && ! isset( $usces->update_db_2_7 ) ) {

			$cache_key = 'wel_get_skus_' . $this->id;
			if ( $cache ) {
				$res = wp_cache_get( $cache_key );
			} else {
				$res = false;
			}

			if ( false === $res ) {

				$sku_table = $wpdb->prefix . 'usces_skus';

				$res = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$sku_table} WHERE post_id = %d",
						$this->id
					),
					ARRAY_A
				);
				if ( null === $res ) {
					$res = false;
				}
				wp_cache_set( $cache_key, $res );
			}

			$skus = array();
			if ( false !== $res && is_array( $res ) ) {

				foreach ( $res as $rows ) {
					$key = isset( $rows[ $keyflag ] ) ? $rows[ $keyflag ] : $rows['sort'];
					$sku = array();
					foreach ( $rows as $k => $v ) {
						$json = json_decode( $v, true );
						if ( null !== $json ) {
							$sku[ $k ] = $json;
						} else {
							$sku[ $k ] = $v;
						}
					}
					$skus[ $key ] = $sku;
				}
			}
		} else {

			$metas = $this->get_post_meta( '_isku_', $cache );
			if ( false === $metas ) {
				$metas = array();
			}
			$skus = array();

			foreach ( $metas as $rows ) {
				$values            = unserialize( $rows['meta_value'] );
				$values['meta_id'] = isset( $rows['meta_id'] ) ? (int) $rows['meta_id'] : '';
				$key               = isset( $values[ $keyflag ] ) ? $values[ $keyflag ] : $values['sort'];

				$new_values = $this->sku_format;
				foreach ( $values as $k => $v ) {
					if ( array_key_exists( $k, $this->sku_format ) ) {
						$new_values[ $k ] = $v;
					}
				}
				$skus[ $key ] = $new_values;
			}
		}

		ksort( $skus );
		return $skus;
	}

	/**
	 * Returns all option sorted data of this object.
	 *
	 * @since  2.3.3
	 * @param string  $keyflag Regenerate option datas with option value as datas index.
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_opts( $keyflag = 'sort', $cache = true ) {
		global $wpdb, $usces;

		if ( ! isset( $this->id ) ) {
			return false;
		}

		if ( version_compare( USCES_VERSION, '2.7-beta', '>=' ) && ! isset( $usces->update_db_2_7 ) ) {

			$cache_key = 'wel_get_opts_' . $this->id;

			if ( $cache ) {
				$res = wp_cache_get( $cache_key );
			} else {
				$res = false;
			}

			if ( false === $res ) {

				$opt_table = $wpdb->prefix . 'usces_opts';

				$res = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$opt_table} WHERE post_id = %d",
						$this->id
					),
					ARRAY_A
				);

				if ( null === $res ) {
					$res = false;
				}
				wp_cache_set( $cache_key, $res );
			}

			$opts = array();
			if ( false !== $res && is_array( $res ) ) {

				foreach ( $res as $rows ) {
					$key = isset( $rows[ $keyflag ] ) ? $rows[ $keyflag ] : $rows['sort'];
					$opt = array();
					foreach ( $rows as $k => $v ) {
						$json = json_decode( $v, true );
						if ( null !== $json ) {
							$opt[ $k ] = $json;
						} else {
							$opt[ $k ] = $v;
						}
					}
					$opts[ $key ] = $opt;
				}
			}
		} else {

			$metas = $this->get_post_meta( '_iopt_', $cache );
			if ( false === $metas ) {
				$metas = array();
			}
			$opts = array();
			foreach ( $metas as $rows ) {

				$values            = unserialize( $rows['meta_value'] );
				$key               = isset( $values[ $keyflag ] ) ? $values[ $keyflag ] : $values['sort'];
				$values['meta_id'] = isset( $rows['meta_id'] ) ? (int) $rows['meta_id'] : '';
				$new_values        = $this->opt_format;
				foreach ( $values as $k => $v ) {
					if ( array_key_exists( $k, $this->opt_format ) ) {
						$new_values[ $k ] = $v;
					}
				}
				$opts[ $key ] = $new_values;
			}
		}

		ksort( $opts );
		return $opts;
	}

	/**
	 * Returns extra data of this object.
	 *
	 * @since  2.3.3
	 * @return array
	 */
	public function get_ext() {
		if ( ! isset( $this->id ) ) {
			return false;
		}

		return $this->ext_data;
	}

	/**
	 * Returns meta data by meta key.
	 *
	 * @since  2.2.2
	 * @param string  $key Meta key.
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_post_meta( $key, $cache = true ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$cache_key = 'wel_get_post_meta_' . $this->id . '_' . $key;
		if ( $cache ) {
			$meta_data = wp_cache_get( $cache_key );
		} else {
			$meta_data = false;
		}

		if ( false === $meta_data ) {
			$meta_data = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT pm.meta_id, pm.meta_key, pm.meta_value FROM $wpdb->posts AS `post` 
					LEFT JOIN $wpdb->postmeta AS `pm` ON post.ID = pm.post_id 
					WHERE pm.meta_key = %s AND post.ID = %d AND ( post.post_status IN ( %s, %s, %s ) OR post.post_status LIKE %s ) AND (post.post_type = %s OR post.post_type = %s)",
					$key,
					$this->id,
					'publish',
					'private',
					'future',
					'%draft%',
					'post',
					'page'
				),
				ARRAY_A
			);

			if ( empty( $meta_data ) ) {
				$meta_data = false;
			}
			wp_cache_set( $cache_key, $meta_data );
		}

		if ( false === $meta_data ) {
			return array();
		} else {
			return $meta_data;
		}
	}

	/**
	 * Returns post ID by item code.
	 *
	 * @since  2.3.3
	 * @param string  $item_code Item code to get post ID.
	 * @param boolean $cache Switch of cache.
	 * @return int Post ID.
	 */
	public function get_id_by_item_code( $item_code, $cache = true ) {
		global $wpdb;

		$cache_key = 'wel_post_id_by_item_code_' . $item_code;
		if ( $cache ) {
			$post_id = wp_cache_get( $cache_key );
		} else {
			$post_id = false;
		}

		if ( false === $post_id ) {

			if ( version_compare( USCES_VERSION, '2.7-beta', '>=' ) && ! isset( $usces->update_db_2_7 ) ) {

				$table = $wpdb->prefix . 'usces_item';
				$query = $wpdb->prepare(
					"SELECT post.ID FROM {$wpdb->posts} AS `post`
					LEFT JOIN {$table} AS `item` ON post.ID = item.post_id
					WHERE item.itemCode = %s AND post.post_status IN ( %s, %s, %s, %s ) AND post.post_type = %s 
					LIMIT 1",
					$item_code,
					'publish',
					'private',
					'draft',
					'future',
					'post'
				);

			} else {

				$query = $wpdb->prepare(
					"SELECT post.ID FROM $wpdb->posts AS `post` 
					LEFT JOIN $wpdb->postmeta AS `pm` ON post.ID = pm.post_id AND pm.meta_key = %s 
					WHERE pm.meta_value = %s AND post.post_status IN ( %s, %s, %s, %s ) AND post.post_type = %s 
					LIMIT 1",
					'_itemCode',
					$item_code,
					'publish',
					'private',
					'draft',
					'future',
					'post'
				);
			}

			$query   = apply_filters( 'usces_filter_get_postidbycode_query', $query, $item_code );
			$post_id = $wpdb->get_var( $query );

			if ( null !== $post_id ) {
				wp_cache_set( $cache_key, $post_id );
			}
		}

		if ( null === $post_id ) {
			return false;
		} else {
			return $post_id;
		}
	}

	/**
	 * Updating product meta information.
	 * In the delete mode, the data is deleted only when there are multiple duplicate data.
	 *
	 * @since  3.0.0
	 * @param string  $data Meta data.
	 * @param boolean $delete True if you want to delete before updating.
	 */
	public function update_item_data( $data, $delete = false ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		if ( ! isset( $data['itemCode'] ) || empty( $data['itemCode'] ) ) {
			return false;
		}

		$post_id     = $this->id;
		$item_table  = $wpdb->prefix . 'usces_item';
		$new_item    = array();
		$item_format = $this->item_format;

		foreach ( $item_format as $key => $value ) {

			if ( 'itemPicts' === $key ) {

				if ( isset( $data['itemPicts'] ) ) {
					$new_item[ $key ] = implode( ';', (array) $data[ $key ] );
				} else {
					$new_item[ $key ] = implode( ';', (array) $value );
				}
			} elseif ( isset( $data[ $key ] ) ) {

				if ( is_array( $data[ $key ] ) ) {
					$new_item[ $key ] = wp_json_encode( $data[ $key ] );
				} else {
					$new_item[ $key ] = $data[ $key ];
				}
			} else {

				if ( is_array( $value ) ) {
					$new_item[ $key ] = wp_json_encode( $value );
				} else {
					$new_item[ $key ] = $value;
				}
			}
		}

		$new_item['post_id'] = $post_id;
		$placeholders        = $this->placeholders;

		$res = $wpdb->replace( $item_table, $new_item, $placeholders );

		$this->set_data( $post_id, false );

		return $res;
	}

	/**
	 * Returns sku data by sku code.
	 *
	 * @since  2.2.2
	 * @param string $sku_code Sku code to get.
	 * @param boolean $cache Switch of cache.
	 * @return array
	 */
	public function get_sku_by_code( $sku_code, $cache = true ) {
		$skus = $this->get_skus( 'code', $cache );
		$sku  = isset( $skus[ $sku_code ] ) ? $skus[ $sku_code ] : false;
		return $sku;
	}

	/**
	 * Returns sku data by meta id.
	 *
	 * @since  2.2.2
	 * @param string  $meta_id Sku meta_id.
	 * @param boolean $cache Switch of cache.
	 * @return array $sku SKU data.
	 */
	public function get_sku_by_id( $meta_id, $cache = true ) {
		$skus = $this->get_skus( 'meta_id', $cache );
		$sku  = isset( $skus[ $meta_id ] ) ? $skus[ $meta_id ] : false;
		return $sku;
	}

	/**
	 * Updating SKU data by id.
	 *
	 * @since  3.0.0
	 * @param array $sku SKU data.
	 * @return boolean
	 */
	public function update_sku_data( $sku ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		if ( ! isset( $sku['meta_id'] ) || empty( $sku['meta_id'] ) ) {
			$target  = $this->get_sku_by_code( $sku['code'], false );
			$meta_id = $target['meta_id'];
		} else {
			$meta_id = $sku['meta_id'];
		}

		$new_sku    = array();
		$sku_format = $this->get_sku_format();
		foreach ( $sku_format as $key => $value ) {
			if ( array_key_exists( $key, $sku ) ) {
				if ( is_array( $sku[ $key ] ) ) {
					$new_sku[ $key ] = wp_json_encode( $sku[ $key ] );
				} else {
					$new_sku[ $key ] = $sku[ $key ];
				}
			} else {
				if ( is_array( $value ) ) {
					$new_sku[ $key ] = wp_json_encode( $value );
				} else {
					$new_sku[ $key ] = $value;
				}
			}
		}
		$new_sku['post_id'] = $this->id;

		$table        = $wpdb->prefix . 'usces_skus';
		$placeholders = $this->sku_placeholders;
		$res = $wpdb->replace( $table, $new_sku, $placeholders );

		$this->set_data( $this->id, false );

		return $res;
	}

	/**
	 * Adding SKU data.
	 *
	 * @since  3.0.0
	 * @param array $sku SKU data.
	 * @return int New meta_id.
	 */
	public function add_sku_data( $sku ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$new_sku    = array();
		$sku_format = $this->get_sku_format();
		foreach ( $sku_format as $key => $value ) {
			if ( array_key_exists( $key, $sku ) ) {
				if ( is_array( $sku[ $key ] ) ) {
					$new_sku[ $key ] = wp_json_encode( $sku[ $key ] );
				} else {
					$new_sku[ $key ] = $sku[ $key ];
				}
			} else {
				if ( is_array( $value ) ) {
					$new_sku[ $key ] = wp_json_encode( $value );
				} else {
					$new_sku[ $key ] = $value;
				}
			}
		}
		$new_sku['post_id'] = $this->id;

		$table        = $wpdb->prefix . 'usces_skus';
		$placeholders = $this->sku_placeholders;
		$wpdb->insert( $table, $new_sku, $placeholders );

		$this->set_data( $this->id, false );

		return $wpdb->insert_id;
	}

	/**
	 * Delete Item data.
	 *
	 * @since  3.0.0
	 * @return boolean Result.
	 */
	public function delete_item_data() {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$table  = $wpdb->prefix . 'usces_item';
		$where  = array( 'post_id' => $this->id );
		$format = array( '%d' );
		$res    = $wpdb->delete( $table, $where, $format );

		return $res;
	}

	/**
	 * Delete SKU data.
	 *
	 * @since  3.0.0
	 * @param int $meta_id Meta id.
	 * @return boolean Result.
	 */
	public function delete_sku_data( $meta_id ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$table  = $wpdb->prefix . 'usces_skus';
		$where  = array( 'meta_id' => $meta_id );
		$format = array( '%d' );
		$res    = $wpdb->delete( $table, $where, $format );

		return $res;
	}

	/**
	 * Delete All SKU data.
	 *
	 * @since  3.0.0
	 * @return boolean Result.
	 */
	public function delete_all_sku_data() {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$table  = $wpdb->prefix . 'usces_skus';
		$where  = array( 'post_id' => $this->id );
		$format = array( '%d' );
		$res    = $wpdb->delete( $table, $where, $format );

		return $res;
	}

	/**
	 * Updating Option data by id.
	 *
	 * @since  3.0.0
	 * @param array $opt Option data.
	 * @return boolean
	 */
	public function update_opt_data( $opt ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		if ( ! isset( $opt['meta_id'] ) || empty( $opt['meta_id'] ) ) {
			return false;
		} else {
			$meta_id = $opt['meta_id'];
		}

		$new_opt    = array();
		$opt_format = $this->get_opt_format();
		foreach ( $opt_format as $key => $value ) {
			if ( array_key_exists( $key, $opt ) ) {
				if ( is_array( $opt[ $key ] ) ) {
					$new_opt[ $key ] = wp_json_encode( $opt[ $key ] );
				} else {
					$new_opt[ $key ] = $opt[ $key ];
				}
			} else {
				if ( is_array( $value ) ) {
					$new_opt[ $key ] = wp_json_encode( $value );
				} else {
					$new_opt[ $key ] = $value;
				}
			}
		}

		$new_opt['post_id'] = $this->id;

		$table        = $wpdb->prefix . 'usces_opts';
		$placeholders = $this->opt_placeholders;

		$res = $wpdb->replace( $table, $new_opt, $placeholders );

		$this->set_data( $this->id, false );

		return $res;
	}

	/**
	 * Adding option data.
	 *
	 * @since  3.0.0
	 * @param array $opt Option data.
	 * @return int New meta_id.
	 */
	public function add_opt_data( $opt ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$new_opt = $this->opt_format;
		foreach ( $opt as $key => $value ) {
			if ( array_key_exists( $key, $new_opt ) ) {
				$new_opt[ $key ] = $value;
			}
		}

		$table        = $wpdb->prefix . 'usces_opts';
		$placeholders = $this->opt_placeholders;

		$new_opt['post_id'] = $this->id;

		$res = $wpdb->insert( $table, $new_opt, $placeholders );

		$this->set_data( $this->id, false );

		return $wpdb->insert_id;
	}

	/**
	 * Delete Option data.
	 *
	 * @since  3.0.0
	 * @param int $meta_id Meta id.
	 * @return boolean Result.
	 */
	public function delete_opt_data( $meta_id ) {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$table  = $wpdb->prefix . 'usces_opts';
		$where  = array( 'meta_id' => $meta_id );
		$format = array( '%d' );
		$res    = $wpdb->delete( $table, $where, $format );

		return $res;
	}

	/**
	 * Delete All Option data.
	 *
	 * @since  3.0.0
	 * @return boolean Result.
	 */
	public function delete_all_opt_data() {
		global $wpdb;

		if ( 0 === $this->id ) {
			return false;
		}

		$table  = $wpdb->prefix . 'usces_opts';
		$where  = array( 'post_id' => $this->id );
		$format = array( '%d' );
		$res    = $wpdb->delete( $table, $where, $format );

		$this->set_data( $this->id, false );

		return $res;
	}

	/**
	 * Get Item format.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_item_format() {
		return $this->item_format;
	}

	/**
	 * Get Item placeholders.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_item_placeholders() {
		return $this->placeholders;
	}

	/**
	 * Get Item old key.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_item_old_key() {
		return $this->item_old_key;
	}

	/**
	 * Get SKU format.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_sku_format() {
		return $this->sku_format;
	}

	/**
	 * Get SKU placeholders.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_sku_placeholders() {
		return $this->sku_placeholders;
	}

	/**
	 * Get Option format.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_opt_format() {
		return $this->opt_format;
	}

	/**
	 * Get Exclusion key.
	 *
	 * @since  3.0.0
	 * @return array Format.
	 */
	public function get_exclusion_key() {
		return $this->exclusion_key;
	}

}
