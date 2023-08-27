<?php

/**
 * Handle the JupiterX custom icons functionality.
 *
 * @since 2.5.0
 */
class JupiterX_Custom_Icons {

	private static $instance = null;

	const POST_TYPE = 'jupiterx-icons';

	const OPTION_NAME = 'jupiterx_custom_icon_sets_config';

	const JX_CUSTOM_ICONS_PANEL_URL = 'admin.php?page=jupiterx#/custom-icons';

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_filter( 'elementor/editor/localize_settings', [ $this, 'add_custom_icons_url' ], 15 );
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'register_icon_libraries_control' ] );
	}

	/**
	 * Change the link of custom icon button in elementor editor -> icon library.
	 *
	 * @param $config
	 *
	 * @return mixed
	 * @since 2.5.0
	 */
	public function add_custom_icons_url( $config ) {
		$config['customIconsURL'] = admin_url( self::JX_CUSTOM_ICONS_PANEL_URL );

		return $config;
	}

	/**
	 * Register JX custom icons to elementor icon library
	 *
	 * @param $additional_sets
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public function register_icon_libraries_control( $additional_sets ) {
		return array_merge( $additional_sets, self::get_custom_icons_config() );
	}

	/**
	 * Creates custom icons configurations for icon library
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public static function get_custom_icons_config() {
		$icons = new \WP_Query( [
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		] );

		$config = [];
		foreach ( $icons->posts as $icon_set ) {
			$set_config                        = json_decode( $icon_set->post_content, true );
			$set_config['custom_icon_post_id'] = $icon_set->ID;
			$set_config['label']               = $icon_set->post_title;

			$config[ $set_config['name'] ] = $set_config;
		}

		update_option( self::OPTION_NAME, $config );

		return $config;
	}
}

JupiterX_Custom_Icons::get_instance();
