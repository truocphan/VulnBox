<?php
/**
 * Learn page location enums.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Learn page location enums.
 *
 * @since 1.6.15
 */
class LearnPageLocation {

	/**
	 * Below username.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const PROFILE_PIC_POPOVER_TOP = 'profile-pic-popover-top';

	/**
	 * Below author section.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const PROFILE_PIC_POPOVER_BOTTOM = 'profile-pic-popover-bottom';

	/**
	 * Dashboard new section.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const LEFT_TO_PROFILE_PIC = 'left-to-profile-pic';

	/**
	 * New tab.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const INFO_BOX_POPOVER_TOP = 'info-box-popover-top';

	/**
	 * Dashboard card.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const INFO_BOX_POPOVER_BOTTOM = 'info-box-popover-bottom';

	/**
	 * Return all the Learn page locations.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters the learn page locations.
			 *
			 * @since 1.6.15
			 *
			 * @param string[] $locations
			 */
			apply_filters(
				'masteriyo_gamipress_learn_page_locations',
				array(
					self::PROFILE_PIC_POPOVER_TOP,
					self::PROFILE_PIC_POPOVER_BOTTOM,
					self::LEFT_TO_PROFILE_PIC,
					self::INFO_BOX_POPOVER_TOP,
					self::INFO_BOX_POPOVER_BOTTOM,
				)
			)
		);
	}
}
