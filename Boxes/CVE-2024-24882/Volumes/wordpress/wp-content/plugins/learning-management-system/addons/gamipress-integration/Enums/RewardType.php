<?php
/**
 * Reward type enums.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Reward type enums.
 *
 * @since 1.6.15
 */
class RewardType {
	/**
	 * Points type.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const POINT = 'point';

	/**
	 * Achievement type.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const ACHIEVEMENT = 'achievement';

	/**
	 * Rank type.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const RANK = 'rank';

	/**
	 * Return all the GamiPress reward types.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters GamiPress reward types.
			 *
			 * @since 1.6.15
			 *
			 * @param string[] $reward_types GamiPress reward types.
			 */
			apply_filters(
				'masteriyo_gamipress_reward_types',
				array(
					self::POINT,
					self::ACHIEVEMENT,
					self::RANK,
				)
			)
		);
	}
}
