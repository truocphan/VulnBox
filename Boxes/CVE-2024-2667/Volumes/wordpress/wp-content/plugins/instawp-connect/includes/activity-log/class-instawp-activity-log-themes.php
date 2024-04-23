<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Themes {

	public function __construct() {
		add_filter( 'wp_redirect', array( &$this, 'hooks_theme_modify' ), 10, 2 );
		add_action( 'switch_theme', array( &$this, 'hooks_switch_theme' ), 10, 2 );
		add_action( 'delete_site_transient_update_themes', array( &$this, 'hooks_theme_deleted' ) );
		add_action( 'upgrader_process_complete', array( &$this, 'hooks_theme_install_or_update' ), 10, 2 );

		// Theme customizer
		add_action( 'customize_save', array( &$this, 'hooks_theme_customizer_modified' ) );
		//add_action( 'customize_preview_init', array( &$this, 'hooks_theme_customizer_modified' ) );
	}

	public function hooks_theme_modify( $location, $status ) {
		if ( false !== strpos( $location, 'theme-editor.php?file=' ) ) {
			if ( ! empty( $_POST ) && 'update' === $_POST['action'] ) {
				$event_args = array(
					'action'         => 'theme_file_updated',
					'object_type'    => 'Themes',
					'object_subtype' => 'theme_unknown',
					'object_id'      => 0,
					'object_name'    => 'file_unknown',
				);

				if ( ! empty( $_POST['file'] ) )
					$event_args['object_name'] = $_POST['file'];

				if ( ! empty( $_POST['theme'] ) )
					$event_args['object_subtype'] = $_POST['theme'];

				InstaWP_Activity_Log::insert_log( $event_args );
			}
		}

		// We are need return the instance, for complete the filter.
		return $location;
	}

	public function hooks_switch_theme( $new_name, WP_Theme $new_theme ) {
		InstaWP_Activity_Log::insert_log(
				array(
					'action'         => 'theme_activated',
					'object_type'    => 'Themes',
					'object_subtype' => $new_theme->get_stylesheet(),
					'object_id'      => 0,
					'object_name'    => $new_name,
				)
		);
	}

	public function hooks_theme_customizer_modified( WP_Customize_Manager $obj ) {
		$event_args = array(
			'action'         => 'theme_updated',
			'object_type'    => 'Themes',
			'object_subtype' => $obj->theme()->display( 'Name' ),
			'object_id'      => 0,
			'object_name'    => 'Theme Customizer',
		);

		if ( 'customize_preview_init' === current_filter() )
			$event_args['action'] = 'theme_accessed';

		InstaWP_Activity_Log::insert_log( $event_args );
	}

	public function hooks_theme_deleted() {
		$backtrace_history = debug_backtrace();

		$delete_theme_call = null;
		foreach ( $backtrace_history as $call ) {
			if ( isset( $call['function'] ) && 'delete_theme' === $call['function'] ) {
				$delete_theme_call = $call;
				break;
			}
		}

		if ( empty( $delete_theme_call ) )
			return;

		$name = $delete_theme_call['args'][0];
		
		InstaWP_Activity_Log::insert_log(
			array(
				'action'      => 'theme_deleted',
				'object_type' => 'Themes',
				'object_name' => $name,
			)
		);
	}

	/**
	 * @param Theme_Upgrader $upgrader
	 * @param array $extra
	 */
	public function hooks_theme_install_or_update( $upgrader, $extra ) {
		if ( ! isset( $extra['type'] ) || 'theme' !== $extra['type'] )
			return;
		
		if ( 'install' === $extra['action'] ) {
			$slug = $upgrader->theme_info();
			if ( ! $slug )
				return;

			wp_clean_themes_cache();
			$theme   = wp_get_theme( $slug );
			$name    = $theme->name;
			$version = $theme->version;

			InstaWP_Activity_Log::insert_log(
				array(
					'action'         => 'theme_installed',
					'object_type'    => 'Themes',
					'object_name'    => $name,
					'object_subtype' => $version,
				)
			);
		}
		
		if ( 'update' === $extra['action'] ) {
			if ( isset( $extra['bulk'] ) && true == $extra['bulk'] )
				$slugs = $extra['themes'];
			else
				$slugs = array( $upgrader->skin->theme );

			foreach ( $slugs as $slug ) {
				$theme      = wp_get_theme( $slug );
				$stylesheet = $theme['Stylesheet Dir'] . '/style.css';
				$theme_data = get_file_data( $stylesheet, array( 'Version' => 'Version' ) );
				
				$name    = $theme['Name'];
				$version = $theme_data['Version'];

				InstaWP_Activity_Log::insert_log(
					array(
						'action'         => 'theme_updated',
						'object_type'    => 'Themes',
						'object_name'    => $name,
						'object_subtype' => $version,
					)
				);
			}
		}
	}
}

new InstaWP_Activity_Log_Themes();