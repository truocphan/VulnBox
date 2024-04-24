<?php
/**
 * Placement page enums.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Placement page enums.
 *
 * @since 1.6.15
 */
class PlacementPage {

	/**
	 * Account page.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const ACCOUNT_PAGE = 'account-page';

	/**
	 * Learn page.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const LEARN_PAGE = 'learn-page';

	/**
	 * Return all the placement pages.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters the placement pages.
			 *
			 * @since 1.6.15
			 *
			 * @param string[] $pages
			 */
			apply_filters(
				'masteriyo_gamipress_placement_pages',
				array(
					self::ACCOUNT_PAGE,
					self::LEARN_PAGE,
				)
			)
		);
	}
}
