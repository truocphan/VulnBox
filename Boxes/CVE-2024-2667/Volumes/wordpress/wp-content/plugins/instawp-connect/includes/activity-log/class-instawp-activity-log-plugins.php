<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstaWP_Activity_Log_Plugins {
	
	public function __construct() {
		add_action( 'activated_plugin', array( $this, 'hooks_activated_plugin' ) );
		add_action( 'deactivated_plugin', array( $this, 'hooks_deactivated_plugin' ) );
		add_action( 'delete_plugin', array( $this, 'hooks_delete_plugin' ) );
		add_filter( 'wp_redirect', array( $this, 'hooks_plugin_modify' ), 10, 2 );
		add_action( 'upgrader_process_complete', array( $this, 'hooks_plugin_install_or_update' ), 10, 2 );
	}

	public function hooks_deactivated_plugin( $plugin_name ) {
		$this->add_plugin_log( 'plugin_deactivated', $plugin_name );
	}

	public function hooks_activated_plugin( $plugin_name ) {
		$this->add_plugin_log( 'plugin_activated', $plugin_name );
	}
	
	public function hooks_delete_plugin( $plugin_file ) {
		$this->add_plugin_log( 'plugin_deleted', $plugin_file );
	}

	public function hooks_plugin_modify( $location, $status ) {
		if ( false !== strpos( $location, 'plugin-editor.php' ) ) {
			if ( ( ! empty( $_POST ) && 'update' === $_REQUEST['action'] ) ) {
				$event_args = array(
					'action'         => 'plugin_file_updated',
					'object_type'    => 'Plugins',
					'object_subtype' => 'plugin_unknown',
					'object_id'      => 0,
					'object_name'    => 'file_unknown',
				);

				if ( ! empty( $_REQUEST['file'] ) ) {
					$event_args['object_name'] = $_REQUEST['file'];
					// Get plugin name
					$plugin_dir  = explode( '/', $_REQUEST['file'] );
					$plugin_data = array_values( get_plugins( '/' . $plugin_dir[0] ) );
					$plugin_data = array_shift( $plugin_data );

					$event_args['object_subtype'] = $plugin_data['Name'];
				}
				InstaWP_Activity_Log::insert_log( $event_args );
			}
		}

		// We are need return the instance, for complete the filter.
		return $location;
	}

	/**
	 * @param Plugin_Upgrader $upgrader
	 * @param array $extra
	 */
	public function hooks_plugin_install_or_update( $upgrader, $extra ) {
		if ( ! isset( $extra['type'] ) || 'plugin' !== $extra['type'] )
			return;

		if ( 'install' === $extra['action'] ) {
			$path = $upgrader->plugin_info();
			if ( ! $path )
				return;
			
			$data = get_plugin_data( $upgrader->skin->result['local_destination'] . '/' . $path, true, false );
			
			InstaWP_Activity_Log::insert_log(
				array(
					'action'         => 'plugin_installed',
					'object_type'    => 'Plugins',
					'object_name'    => $data['Name'],
					'object_subtype' => $data['Version'],
				)
			);
		}

		if ( 'update' === $extra['action'] ) {
			if ( isset( $extra['bulk'] ) && true == $extra['bulk'] ) {
				$slugs = $extra['plugins'];
			} else {
				$plugin_slug = isset( $upgrader->skin->plugin ) ? $upgrader->skin->plugin : $extra['plugin'];

				if ( empty( $plugin_slug ) ) {
					return;
				}

				$slugs = array( $plugin_slug );
			}
			
			foreach ( $slugs as $slug ) {
				$data = get_plugin_data( WP_PLUGIN_DIR . '/' . $slug, true, false );
				
				InstaWP_Activity_Log::insert_log(
					array(
						'action'         => 'plugin_updated',
						'object_type'    => 'Plugins',
						'object_name'    => $data['Name'],
						'object_subtype' => $data['Version'],
					)
				);
			}
		}
	}

	private function add_plugin_log( $action, $plugin_name ) {
		$plugin_version = '';

		// Get plugin name if is a path
		if ( false !== strpos( $plugin_name, '/' ) ) {
			$plugin_dir  = explode( '/', $plugin_name );
			$plugin_data = array_values( get_plugins( '/' . $plugin_dir[0] ) );
			$plugin_data = array_shift( $plugin_data );
			$plugin_name = $plugin_data['Name'];

			if ( ! empty( $plugin_data['Version'] ) ) {
				$plugin_version = $plugin_data['Version'];
			}
		}

		InstaWP_Activity_Log::insert_log(
			array(
				'action'         => $action,
				'object_type'    => 'Plugins',
				'object_id'      => 0,
				'object_name'    => $plugin_name,
				'object_subtype' => $plugin_version,
			)
		);
	}
}

new InstaWP_Activity_Log_Plugins();