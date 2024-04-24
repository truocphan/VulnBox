<?php
/**
 * Masteriyo Pro class.
 *
 * @since 1.6.11
 * @package Masteriyo\Pro
 */

namespace Masteriyo\Pro;

/**
 * Masteriyo pro class.
 *
 * @since 1.6.11
*/
class Pro {

	/**
	 *
	 * Instance of addons class.
	 *
	 * @since 1.6.11
	 *
	 * @var Masteriyo\Addons
	 *
	 */
	public $addons;

	/**
	 * Constructor.
	 *
	 * @since 1.6.11
	 *
	 * @param \Masteriyo\Pro\Addons $addons
	 */
	public function __construct( Addons $addons ) {
		$this->addons = $addons;
	}

	/**
	 * Initialize.
	 *
	 * @since 1.6.11
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.11
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'localize_addons_data' ) );
		add_filter( 'masteriyo_localized_public_scripts', array( $this, 'localize_public_scripts' ) );
		add_filter( 'masteriyo_admin_submenus', array( $this, 'register_submenus' ) );
	}

	/**
	 * Localize addons data.
	 *
	 * @since 1.6.11
	 *
	 * @param array $data Localized data.
	 */
	public function localize_addons_data( $data ) {
		$addons_data = array_map(
			function( $slug ) {
				return $this->addons->get_data( $slug, true );
			},
			array_keys( $this->addons->get_all_addons() )
		);

		$addons_data = array_map(
			function( $addon ) {
				$addon_plan      = masteriyo_array_get( $addon, 'plan', '' );
				$locked          = empty( $addon ) ? false : ! $this->addons->is_allowed( masteriyo_array_get( $addon, 'slug' ) );
				$addon['locked'] = $locked;

				return $addon;
			},
			$addons_data
		);

		$addons_keys = wp_list_pluck( $addons_data, 'slug' );
		$addons_data = array_combine( $addons_keys, $addons_data );

		// Move content drip addon to the end of the array.
		// TODO Remove after number of addons are introduced.
		$addons_data += array_splice( $addons_data, array_search( 'content-drip', array_keys( $addons_data ), true ), 1 );
		$addons_data  = array_values( $addons_data );

		$active_integration_addons = array_filter(
			$addons_data,
			function( $addon ) {
				return $addon['active'] && 'integration' === $addon['addon_type'];
			}
		);

		if ( isset( $data['backend'] ) ) {
			$data['backend']['data']['addons']       = $addons_data;
			$data['backend']['data']['integrations'] = masteriyo_bool_to_string( ! empty( $active_integration_addons ) );
		}

		return $data;
	}

	/**
	 * Localize public scripts
	 *
	 * @since 1.6.11
	 *
	 * @param array $data Localized data.
	 */
	public function localize_public_scripts( $data ) {
		$addons_data = array_map(
			function( $slug ) {
				return $this->addons->get_data( $slug, true );
			},
			array_keys( $this->addons->get_all_addons() )
		);

		$active_integration_addons = array_filter(
			$addons_data,
			function( $addon ) {
				return $addon['active'] && 'integration' === $addon['addon_type'];
			}
		);

		$data = masteriyo_parse_args(
			$data,
			array(
				'learn'   => array(
					'data' => array(
						'addons'       => $addons_data,
						'integrations' => masteriyo_bool_to_string( ! empty( $active_integration_addons ) ),
					),
				),
				'account' => array(
					'data' => array(
						'addons'       => $addons_data,
						'integrations' => masteriyo_bool_to_string( ! empty( $active_integration_addons ) ),
					),
				),
			)
		);

		return $data;
	}

	/**
	 * Register sub menus.
	 *
	 * @since 1.6.11
	 *
	 * @param array $submenus
	 * @return array
	 */
	public function register_submenus( $submenus ) {
		$submenus['add-ons'] = array(
			'page_title' => esc_html__( 'Addons', 'masteriyo' ),
			'menu_title' => esc_html__( 'Addons', 'masteriyo' ),
			'position'   => 100,
		);

		return $submenus;
	}
}
