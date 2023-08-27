<?php
/**
 * This class handles customizer function.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 *
 * @todo Enhance the Customizer autoloading code.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extends WordPress customizer capability.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
final class JupiterX_Core_Customizer_Autoloader {

	/**
	 * Directory maps.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $dir_map = [
		'base'                => 'includes/base',
		'control'             => 'includes/control',
		'group-control'       => 'includes/control/group',
		'output'              => 'includes/output',
		'section'             => 'includes/section',
		'kirki-extend-base'   => 'modules/kirki-extend/base',
		'kirki-extend-output' => 'modules/kirki-extend/output',
		'kirki-extend'        => 'modules/kirki-extend',
		'get-variables'       => 'modules/compiler',
		'compiler'            => 'modules/compiler',
		'post-message'        => 'modules/post-message',
		'elementor'           => 'modules/elementor',
	];

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Customizer autoload function.
	 *
	 * @since 1.0.0
	 *
	 * @param string $class_name The class name from spl_autoload_register.
	 */
	public function autoload( $class_name ) {
		if ( class_exists( $class_name ) || 0 !== stripos( $class_name, 'JupiterX_Customizer_' ) ) {
			return;
		}

		// Assumed file name.
		$file_name = strtolower( str_replace( [ 'JupiterX_Customizer_', '_' ], [ '', '-' ], $class_name ) );

		// Get path attributes.
		$path = $this->get_path( $file_name );

		// Create full path.
		$full_path = sprintf( '%1$s/%2$s', $path['dir'], $path['name'] );

		if ( file_exists( $full_path ) ) {
			include_once $full_path;
		}
	}

	/**
	 * Get the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name Assumed file name.
	 *
	 * @return array Path attributes.
	 */
	public function get_path( $file_name ) {
		$path = [
			'dir'  => 'includes',
			'name' => $file_name,
		];

		// Change dir and file name if found in directory map.
		foreach ( $this->dir_map as $key => $dir ) {
			if ( 0 === stripos( $file_name, $key ) ) {
				$path = [
					'dir'  => $dir,
					'name' => str_replace( $key . '-', '', $file_name ),
				];
				break;
			}
		}

		return [
			'dir'  => wp_normalize_path( JUPITERX_CORE_CUSTOMIZER_PATH . $path['dir'] ),
			'name' => sprintf( 'class-%1$s.php', $path['name'] ),
		];
	}
}

// Initialize autoloader.
new JupiterX_Core_Customizer_Autoloader();
