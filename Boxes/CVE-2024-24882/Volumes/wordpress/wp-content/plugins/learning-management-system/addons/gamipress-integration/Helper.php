<?php
/**
 * GamiPress Integration helper class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration;

use Masteriyo\Taxonomy\Taxonomy;

/**
 * GamiPress Integration helper class.
 *
 * @since 1.6.15
 */
class Helper {

	/**
	 * Check if the GamiPress plugin is active.
	 *
	 * @since 1.6.15
	 *
	 * @return boolean
	 */
	public static function is_gamipress_active() {
		return in_array( 'gamipress/gamipress.php', get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Get post term ids for a taxonomy.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $post_id
	 *
	 * @return array
	 */
	public static function get_category_ids_of_course( $course_id ) {
		$terms = get_the_terms( $course_id, Taxonomy::COURSE_CATEGORY );

		if ( ! is_array( $terms ) ) {
			return array();
		}

		return wp_list_pluck( $terms, 'term_id' );
	}

	/**
	 * Get image of a point type.
	 *
	 * @since 1.6.15
	 *
	 * @param mixed $point_type
	 *
	 * @return string|false
	 */
	public static function get_point_type_image_url( $point_type ) {
		if ( gettype( $point_type ) === 'integer' ) {
			$post_id = $point_type;
		} elseif ( absint( $point_type ) !== 0 ) {
			$post_id = $point_type;
		} else {
			$point_types = gamipress_get_points_types();

			if ( ! isset( $point_types[ $point_type ] ) ) {
				return '';
			}

			$post_id = $point_types[ $point_type ]['ID'];
		}

		return get_the_post_thumbnail_url( $post_id );
	}

	/**
	 * Get point types.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function get_point_types() {
		$point_types = gamipress_get_points_types();

		foreach ( $point_types as $point_type => $data ) {
			$point_types[ $point_type ]['points']    = gamipress_get_user_points( get_current_user_id(), $point_type );
			$point_types[ $point_type ]['image_url'] = self::get_point_type_image_url( $point_type );
		}

		return $point_types;
	}

	/**
	 * Get rank types.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function get_rank_types() {
		$rank_types = gamipress_get_rank_types();

		foreach ( $rank_types as $rank_type => $data ) {
			$rank                                  = gamipress_get_user_rank( get_current_user_id(), $rank_type );
			$rank_types[ $rank_type ]['rank']      = '';
			$rank_types[ $rank_type ]['image_url'] = '';

			if ( $rank instanceof \WP_Post && ! in_array( $rank->post_type, array( 'post', 'page' ), true ) ) {
				$rank_types[ $rank_type ]['rank']      = $rank->post_title;
				$rank_types[ $rank_type ]['image_url'] = get_the_post_thumbnail_url( $rank->ID );
			}

			if ( empty( $rank_types[ $rank_type ]['image_url'] ) ) {
				$parent_rank                           = get_page_by_path( $rank_type, OBJECT, 'rank-type' );
				$rank_types[ $rank_type ]['image_url'] = get_the_post_thumbnail_url( $parent_rank->ID );
			}
		}

		return $rank_types;
	}

	/**
	 * Get achievement types.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function get_achievement_types() {
		$achievement_types = gamipress_get_achievement_types();

		foreach ( $achievement_types as $type => $data ) {
			$ids                                        = gamipress_get_user_earned_achievement_ids( get_current_user_id(), $type );
			$achievement_types[ $type ]['achievements'] = array();

			foreach ( $ids as $id ) {
				$post = get_post( $id );

				if ( $post && ! is_wp_error( $post ) ) {
					$achievement              = array(
						'label'     => $post->post_title,
						'image_url' => '',
					);
					$achievement['image_url'] = get_the_post_thumbnail_url( $post->ID );

					if ( empty( $achievement['image_url'] ) ) {
						$parent_achievement       = get_page_by_path( $type, OBJECT, 'achievement-type' );
						$achievement['image_url'] = get_the_post_thumbnail_url( $parent_achievement->ID );
					}

					$achievement_types[ $type ]['achievements'][] = $achievement;
				}
			}
		}

		return $achievement_types;
	}
}
