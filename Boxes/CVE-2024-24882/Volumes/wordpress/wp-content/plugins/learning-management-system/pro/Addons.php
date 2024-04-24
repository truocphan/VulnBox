<?php
/**
 * Masteriyo addons class.
 *
 * @since 1.6.11
 * @package Masteriyo\Pro
 */

namespace Masteriyo\Pro;

use Masteriyo\Constants;

/**
 * Masteriyo addons class.
 */
class Addons {
	/**
	 * Active addons list.
	 *
	 * @since 1.6.11
	 *
	 * @var string
	 */
	public $active_addons_option = 'masteriyo_active_addons';

	/**
	 * Constructor
	 *
	 * @since 1.6.11
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks
	 *
	 * @since 1.6.11
	 */
	public function init_hooks() {
		add_action( 'masteriyo_activation', array( $this, 'on_activate' ) );
		add_action( 'masteriyo_deactivate', array( $this, 'on_deactivate' ) );
	}

	/**
	 * Run all the setup files of addon.
	 *
	 * @since 1.6.11
	 */
	public function on_activate() {
		foreach ( $this->get_active_addons() as $slug => $addon ) {
			$active_addon_file = Constants::get( 'MASTERIYO_PRO_ADDONS_DIR' ) . "/{$slug}/setup.php";
			if ( file_exists( $active_addon_file ) ) {
				require_once $active_addon_file;
			}
		}
	}

	/**
	 * Run all the destroy files of addon.
	 *
	 * @since 1.6.11
	 */
	public function on_deactivate() {
		foreach ( $this->get_active_addons() as $slug => $addon ) {
			$active_addon_file = Constants::get( 'MASTERIYO_PRO_ADDONS_DIR' ) . "/{$slug}/destory.php";
			if ( file_exists( $active_addon_file ) ) {
				require_once $active_addon_file;
			}
		}
	}

	/**
	 * Return masteriyo pro addons dir.
	 *
	 * @since 1.6.11
	 * @return string
	 */
	public function get_addons_dir() {
		return MASTERIYO_PRO_ADDONS_DIR;
	}

	/**
	 * Return masteriyo pro addons url.
	 *
	 * @since 1.6.11
	 * @return string
	 */
	public function get_addons_url() {
		return plugin_dir_url( MASTERIYO_PLUGIN_FILE ) . 'addons';
	}

	/**
	 * Return default addon header.
	 *
	 * @since 1.6.11
	 * @return string
	 */
	private function get_default_addon_headers() {
		return apply_filters(
			'masteriyo_pro_default_addon_headers',
			array(
				'Addon Name'  => 'Addon Name',
				'Addon URI'   => 'Addon URI',
				'Addon Type'  => 'Addon Type',
				'Description' => 'Description',
				'Author'      => 'Author',
				'Author URI'  => 'Author URI',
				'Requires'    => 'Requires',
				'Plan'        => 'Plan',
			)
		);
	}

	/**
	 * Return main addon file form addon slug.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 *
	 * @return string|null
	 */
	public function get_main_file( $slug ) {
		$file = $this->get_addons_dir() . "/{$slug}/main.php";

		if ( file_exists( $file ) ) {
			return $file;
		}

		return null;
	}

	/**
	 * Return addon thumbnail url.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 *
	 * @return string
	 */
	public function get_thumbnail_url( $slug ) {
		$url  = '';
		$file = $this->get_addons_dir() . "/{$slug}/thumbnail.png";

		if ( file_exists( $file ) ) {
			$url = $this->get_addons_url() . "/{$slug}/thumbnail.png";
		}

		return $url;
	}

	/**
	 * Get addon data from the main file.
	 *
	 * @since 1.6.11
	 *
	 * @param string $addon Main addon file or slug.
	 * @param bool $rest Whether to format the data for rest or not.
	 * @param string $context Edit/View context.
	 *
	 * @return array
	 */
	public function get_data( $addon, $rest = false, $context = 'view' ) {
		if ( ! ( file_exists( $addon ) && is_readable( $addon ) ) ) {
			$file = $this->get_main_file( $addon );
		} else {
			$file = $addon;
		}

		$main_data = get_file_data( $file, $this->get_default_addon_headers() );
		$data      = array_merge( $main_data, $this->get_additional_data( $addon ) );

		if ( $rest ) {
			$keys = array_map(
				function( $addon_key ) {
					return sanitize_key( str_replace( ' ', '_', $addon_key ) );
				},
				array_keys( $data )
			);

			$data = array_combine( $keys, array_values( $data ) );
		}

		if ( 'view' === $context ) {
			/**
			 * Filter addon data.
			 *
			 * @since 1.6.11
			 *
			 * @param array $data Addon data.
			 * @param string $slug Addon slug.
			 * @param bool $rest Whether to format the data for rest or not.
			 */
			$data = apply_filters( 'masteriyo_pro_addon_data', $data, $addon, $rest );
		}

		return $data;
	}

	/**
	 * Return all addons in the following format.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_all_addons() {
		global $wp_filesystem;

		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		if ( ! $wp_filesystem ) {
			return;
		}

		// List all addons.
		$addons = (array) $wp_filesystem->dirlist( $this->get_addons_dir() );

		// List addon main files which will be same as that of the addon folder.
		$addon_main_files = array();
		foreach ( $addons as $addon => $data ) {
			$addon_main_files[ $addon ] = $this->get_main_file( $addon );
		}

		// Remove addons whose main files doesn't exist.
		$addon_main_files = array_filter( $addon_main_files );

		// Allow third party to include addons.
		$addon_main_files = apply_filters( 'masteriyo_pro_addons', $addon_main_files );

		return $addon_main_files;
	}

	/**
	 * Return all addons with addon data.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_addons_data() {
		$addons = $this->get_all_addons();

		$addons_data = array_map(
			function( $addon ) {
				return $this->get_data( $addon );
			},
			$addons
		);

		return $addons_data;
	}

	/**
	 * Load all the addons.
	 *
	 * @since 1.6.11
	 */
	public function load_all() {
		// Load addons.
		foreach ( $this->get_all_addons() as $slug => $main_file ) {
			require_once $main_file;
		}
	}

	/**
	 * Return true if the addon is exists.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug
	 * @return boolean
	 */
	public function is_addon( $slug ) {
		return null !== $this->get_main_file( $slug );
	}

	/**
	 * Get active addons.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_active_addons() {
		return get_option( $this->active_addons_option, array() );
	}

	/**
	 * Get inactive addons.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public function get_inactive_addons() {
		$all_addons = $this->get_all_addons();

		$inactive_addons = array_filter(
			$all_addons,
			function( $slug ) {
				return ! $this->is_active( $slug );
			},
			ARRAY_FILTER_USE_KEY
		);

		$inactive_addons = array_map(
			function( $inactive_addon ) {
				return $this->get_data( $inactive_addon );
			},
			$inactive_addons
		);

		return $inactive_addons;
	}

	/**
	 * Return true if the addon is active.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 * @return boolean
	 */
	public function is_active( $slug ) {
		$active_addons = $this->get_active_addons();

		return isset( $active_addons[ $slug ] );
	}

	/**
	 * Set an addon inactive.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 *
	 * @return boolean|array
	 */
	public function set_inactive( $slug ) {
		$main_file = $this->get_main_file( $slug );

		if ( null === $main_file ) {
			return false;
		}

		$addon_data = $this->get_data( $main_file );

		if ( ! $this->is_active( $slug ) ) {
			return $addon_data;
		}

		$active_addons = $this->get_active_addons();
		unset( $active_addons[ $slug ] );

		update_option( $this->active_addons_option, $active_addons );

		$active_addon_file = Constants::get( 'MASTERIYO_PRO_ADDONS_DIR' ) . "/{$slug}/destroy.php";
		if ( file_exists( $active_addon_file ) ) {
			require_once $active_addon_file;
		}

		/**
		 * Fires after addon is deactivated.
		 *
		 * @since 1.6.11
		 * @param \Masteriyo\Pro\Addons $addons Addons class.
		 */
		do_action( "masteriyo_pro_addon_{$slug}_deactivate", $this );

		return $addon_data;
	}

	/**
	 * Set an addon active.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 *
	 * @return bool|array
	 */
	public function set_active( $slug ) {
		$main_file = $this->get_main_file( $slug );

		if ( null === $main_file ) {
			return false;
		}

		$addon_data = $this->get_data( $main_file );

		if ( $this->is_active( $slug ) ) {
			return $addon_data;
		}

		$active_addons          = $this->get_active_addons();
		$active_addons[ $slug ] = $addon_data;

		update_option( $this->active_addons_option, $active_addons );

		$active_addon_file = Constants::get( 'MASTERIYO_PRO_ADDONS_DIR' ) . "/{$slug}/setup.php";
		if ( file_exists( $active_addon_file ) ) {
			require_once $active_addon_file;
		}

		$active_addon_file = Constants::get( 'MASTERIYO_PRO_ADDONS_DIR' ) . "/{$slug}/main.php";
		if ( file_exists( $active_addon_file ) ) {
			require_once $active_addon_file;
		}

		/**
		 * Fires after addon is activate.
		 *
		 * @since 1.6.11
		 * @param \Masteriyo\Pro\Addons $addons Addons class.
		 */
		do_action( "masteriyo_pro_addon_{$slug}_activate", $this );

		return $addon_data;
	}

	/**
	 * Get complete addon data along with thumbnail and active status.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 * @return array
	 */
	public function get_additional_data( $slug ) {
		return array(
			'slug'                  => $slug,
			'active'                => $this->is_active( $slug ),
			'thumbnail'             => $this->get_thumbnail_url( $slug ),
			'requirement_fulfilled' => 'yes',
		);
	}

	/**
	 * Return addon plan.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 * @return string
	 */
	public function get_addon_plan( $slug ) {
		return masteriyo_array_get( $this->get_data( $slug ), 'Plan', '' );
	}

	/**
	 * Return if the addon is allowed for the current user plan.
	 *
	 * @since 1.6.11
	 *
	 * @param string $slug Addon slug.
	 * @return boolean
	 */
	public function is_allowed( $slug ) {
		/**
		 * Filters whether addon is allowed or not.
		 *
		 * @since 1.6.11
		 *
		 * @param string $slug Addon slug.
		 * @param \Masteriyo\Pro\Addons $addons Addons object.
		 */
		return apply_filters( 'masteriyo_pro_is_addon_allowed', true, $slug, $this );
	}
}
