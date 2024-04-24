<?php
/**
 * Store global options.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration;

use Masteriyo\Addons\GamiPressIntegration\Enums\AccountPageLocation;
use Masteriyo\Addons\GamiPressIntegration\Enums\LearnPageLocation;
use Masteriyo\Addons\GamiPressIntegration\Enums\PlacementPage;
use Masteriyo\Addons\GamiPressIntegration\Enums\RewardType;

/**
 * Store global options.
 *
 * @since 1.6.15
 */
class GlobalSetting {

	/**
	 * Global option name.
	 *
	 * @since 1.6.15
	 */
	const OPTION_NAME = 'masteriyo_gamipress_integration_settings';

	/**
	 * Option data.
	 *
	 * @since 1.6.15
	 *
	 * @var array
	 */
	public $data = array(
		'ui_placements' => array(
			array(
				'id'          => 10,
				'reward_type' => RewardType::POINT,
				'types'       => array(),
				'page'        => PlacementPage::ACCOUNT_PAGE,
				'location'    => AccountPageLocation::DASHBOARD_CARD,
				'title'       => '',
			),
			array(
				'id'          => 20,
				'reward_type' => RewardType::POINT,
				'types'       => array(),
				'page'        => PlacementPage::LEARN_PAGE,
				'location'    => LearnPageLocation::INFO_BOX_POPOVER_TOP,
				'title'       => '',
			),
			array(
				'id'          => 30,
				'reward_type' => RewardType::ACHIEVEMENT,
				'types'       => array(),
				'page'        => PlacementPage::ACCOUNT_PAGE,
				'location'    => AccountPageLocation::NEW_TAB,
				'title'       => 'Achievements',
			),
			array(
				'id'          => 40,
				'reward_type' => RewardType::RANK,
				'types'       => array(),
				'page'        => PlacementPage::LEARN_PAGE,
				'location'    => LearnPageLocation::INFO_BOX_POPOVER_TOP,
				'title'       => '',
			),
		),
	);

	/**
	 * Initialize global setting.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.15
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_rest_response_setting_data', array( $this, 'append_setting_in_response' ), 10, 4 );
		add_action( 'masteriyo_new_setting', array( $this, 'save_settings' ), 10, 1 );
	}

	/**
	 * Append setting to response.
	 *
	 * @since 1.6.15
	 *
	 * @param array $data Setting data.
	 * @param Masteriyo\Models\Setting $setting Setting object.
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @param Masteriyo\RestApi\Controllers\Version1\SettingsController $controller REST settings controller object.
	 *
	 * @return array
	 */
	public function append_setting_in_response( $data, $setting, $context, $controller ) {
		$data['gamipress'] = $this->get();
		return $data;
	}

	/**
	 * Save settings.
	 *
	 * @since 1.6.15
	 *
	 * @param \Masteriyo\Models\Setting $setting Setting object.
	 */
	public function save_settings( $setting ) {
		if ( ! masteriyo_is_rest_api_request() ) {
			return;
		}

		$request = masteriyo_current_http_request();

		if ( ! isset( $request['gamipress'] ) ) {
			return;
		}

		$submitted                      = (array) $request['gamipress'];
		$point_placements_changed       = isset( $submitted['point_type_ui_placements'] );
		$achievement_placements_changed = isset( $submitted['achievement_type_ui_placements'] );
		$rank_placements_changed        = isset( $submitted['rank_type_ui_placements'] );
		$new_ui_placements              = array_merge(
			(array) masteriyo_array_get( $request, 'gamipress.point_type_ui_placements' ),
			(array) masteriyo_array_get( $request, 'gamipress.achievement_type_ui_placements' ),
			(array) masteriyo_array_get( $request, 'gamipress.rank_type_ui_placements' )
		);
		$old_ui_placements              = (array) $this->get( 'ui_placements' );
		$counter                        = time();

		foreach ( $old_ui_placements as $placement ) {
			$placement = wp_parse_args(
				(array) $placement,
				array(
					'id'          => $counter++,
					'reward_type' => '',
					'types'       => array(),
					'page'        => '',
					'location'    => '',
					'title'       => '',
				)
			);

			$placement['id']          = sanitize_text_field( $placement['id'] . '' );
			$placement['reward_type'] = sanitize_text_field( $placement['reward_type'] );
			$placement['types']       = is_array( $placement['types'] ) ? array_map( 'sanitize_text_field', $placement['types'] ) : array();
			$placement['page']        = sanitize_text_field( $placement['page'] );
			$placement['location']    = sanitize_text_field( $placement['location'] );

			if ( RewardType::POINT === $placement['reward_type'] && $point_placements_changed ) {
				continue;
			}
			if ( RewardType::ACHIEVEMENT === $placement['reward_type'] && $achievement_placements_changed ) {
				continue;
			}
			if ( RewardType::RANK === $placement['reward_type'] && $rank_placements_changed ) {
				continue;
			}
			$new_ui_placements[] = $placement;
		}

		$settings = array(
			'ui_placements' => $new_ui_placements,
		);
		$settings = wp_parse_args( $settings, $this->get() );

		update_option( self::OPTION_NAME, $settings );
	}

	/**
	 * Return global white field value.
	 *
	 * @since 1.6.15
	 *
	 * @param string $key
	 * @return string|array
	 */
	public function get( $key = null ) {
		$settings   = get_option( self::OPTION_NAME, $this->data );
		$this->data = wp_parse_args( $settings, $this->data );

		if ( null === $key ) {
			return $this->data;
		}

		if ( isset( $this->data[ $key ] ) ) {
			return $this->data[ $key ];
		}

		return null;
	}

	/**
	 * Set setting field value.
	 *
	 * @since 1.6.15
	 *
	 * @param string $key Setting key.
	 * @param mixed $value Setting value.
	 */
	public function set( $key, $value ) {
		if ( isset( $this->data[ $key ] ) ) {
			$this->data[ $key ] = $value;
		}
	}
}
