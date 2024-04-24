<?php
/**
 * Tracking information for the Masteriyo.
 *
 * @package Masteriyo\Tracking
 *
 * @since 1.6.0
 */

namespace Masteriyo\Tracking;

use Masteriyo\Enums\OrderStatus;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * MasteriyoTrackingInfo class.
 */
class MasteriyoTrackingInfo {
	/**
	 * Get product license key.
	 *
	 * @since 1.6.0
	 *
	 * @return string|null
	 */
	public static function get_license_key() {
		return get_option( 'masteriyo_pro_license_key', null );
	}

	/**
	 * Get the base product plugin slug.
	 *
	 * @since 1.6.0
	 *
	 * @return string The base product plugin slug.
	 */
	public static function get_slug() {
		if ( self::is_premium() ) {
			return 'learning-management-system-pro/lms.php';
		} else {
			return 'learning-management-system/lms.php';
		}
	}

	/**
	 * Return base product file.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_file_path() {
		return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::get_slug();
	}

	/**
	 * Check if user is using premium version.
	 *
	 * @since 1.6.0
	 *
	 * @return boolean True if the user is using the premium version, false otherwise.
	 */
	public static function is_premium() {
		if ( is_plugin_active( 'learning-management-system-pro/lms.php' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if usage is allowed.
	 *
	 * @since 1.6.0
	 *
	 * @return boolean
	 */
	public static function is_usage_allowed() {
		return masteriyo_get_setting( 'advance.tracking.allow_usage' );
	}

	/**
	 * Return publish courses by AI count.
	 *
	 * @since 1.6.16
	 *
	 * @return integer
	 */
	public static function get_publish_course_by_ai_count() {

		$meta_query = array(
			'key'     => '_is_ai_created',
			'value'   => '1',
			'compare' => '=',
		);

		$args = array(
			'post_type'      => PostType::COURSE,
			'post_status'    => PostStatus::PUBLISH,
			'posts_per_page' => -1,
			'meta_query'     => array( $meta_query ),
		);

		$query = new \WP_Query( $args );

		return $query->found_posts;
	}

	/**
	 * Return publish courses count.
	 *
	 * @since 1.6.0
	 *
	 * @return integer
	 */
	public static function get_publish_course_count() {
		return masteriyo_array_get( (array) wp_count_posts( PostType::COURSE ), PostStatus::PUBLISH, 0 );
	}

	/**
	 * Return completed orders count.
	 *
	 * @since 1.6.0
	 *
	 * @return integer
	 */
	public static function get_completed_orders_count() {
		return masteriyo_array_get( (array) wp_count_posts( PostType::ORDER ), OrderStatus::COMPLETED, 0 );
	}

	/**
	 * Return base product name.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_name() {
		return self::is_premium() ? 'Masteriyo Pro' : 'Masteriyo';
	}

	/**
	 * Return meta information.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	public static function get_meta_data() {
		return array(
			'license_key'        => self::get_license_key(),
			'course_count'       => self::get_publish_course_count(),
			'course_by_ai_count' => self::get_publish_course_by_ai_count(),
			'order_count'        => self::get_completed_orders_count(),
		);
	}

	/**
	 * Return masteriyo plugin data information.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	public static function all() {
		return array(
			'product_name'    => self::get_name(),
			'product_version' => masteriyo_get_version(),
			'product_meta'    => self::get_meta_data(),
			'product_type'    => 'plugin',
			'product_slug'    => self::get_slug(),
			'is_premium'      => self::is_premium(),
		);
	}
}
