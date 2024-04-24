<?php
/**
 * Account page location enums.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Account page location enums.
 *
 * @since 1.6.15
 */
class AccountPageLocation {

	/**
	 * Below username.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const BELOW_USERNAME = 'below-username';

	/**
	 * Below author section.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const BELOW_AUTHOR_SECTION = 'below-author-section';

	/**
	 * Dashboard new section.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const DASHBOARD_NEW_SECTION = 'dashboard-new-section';

	/**
	 * New tab.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const NEW_TAB = 'new-tab';

	/**
	 * Dashboard card.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	const DASHBOARD_CARD = 'dashboard-card';

	/**
	 * Return all the Account page locations.
	 *
	 * @since 1.6.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters the account page locations.
			 *
			 * @since 1.6.15
			 *
			 * @param string[] $locations
			 */
			apply_filters(
				'masteriyo_gamipress_account_page_locations',
				array(
					self::BELOW_USERNAME,
					self::BELOW_AUTHOR_SECTION,
					self::DASHBOARD_NEW_SECTION,
					self::NEW_TAB,
					self::DASHBOARD_CARD,
				)
			)
		);
	}
}
