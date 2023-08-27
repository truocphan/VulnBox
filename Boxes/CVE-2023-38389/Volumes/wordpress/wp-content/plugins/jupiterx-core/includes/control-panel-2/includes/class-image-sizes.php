<?php
/**
 * This class provides the methods to Store and retrieve Image sizes from database.
 *
 * @package JupiterX_Core\Control_Panel\Image_Sizes
 *
 * @since 1.18.0
 */

if ( ! class_exists( 'JupiterX_Core_Control_Panel_Image_Sizes' ) ) {
	/**
	 * Store and retrieve Image sizes.
	 *
	 * @since 1.18.0
	 */
	class JupiterX_Core_Control_Panel_Image_Sizes {
		/**
		 * Array of custom image sizes.
		 *
		 * @since 1.18.0
		 * @access public
		 *
		 * @var array
		 */
		protected static $default_options = [
			[
				'size_w'  => 500,
				'size_h'  => 500,
				'size_n'  => 'Image Size 500x500',
				'size_c'  => 'on',
				'default' => true,
				'id'      => 1,
			],
		];

		/**
		 * Class constructor.
		 *
		 * @since 1.18.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_jupiterx_core_cp_get_image_sizes', [ $this, 'get_image_sizes' ] );
			add_action( 'wp_ajax_jupiterx_core_cp_save_image_sizes', [ $this, 'save_image_sizes' ] );
		}

		/**
		 * Return list of the stored image sizes.
		 *
		 * If empty, it will return default sample size.
		 *
		 * @since 1.18.0
		 *
		 * @return array
		 */
		public static function get_available_image_sizes() {
			$options = get_option( JUPITERX_IMAGE_SIZE_OPTION );

			if ( empty( $options ) ) {
				$options = [];
			}

			$existing_default_options = [];

			foreach ( $options as $option ) {
				if ( ! empty( $option['default'] ) ) {
					$existing_default_options[] = intval( $option['id'] );
				}
			}

			$deleted_default_options = get_option( 'jupiterx_image_sizes_deleted' );

			if ( false === $deleted_default_options ) {
				$deleted_default_options = [];
			}

			foreach ( self::$default_options as $default_option ) {
				if (
					in_array( $default_option['id'], $deleted_default_options, true ) ||
					in_array( $default_option['id'], $existing_default_options, true )
				) {
					continue;
				}

				array_unshift( $options, $default_option );
			}

			return $options;
		}

		/**
		 * Get image sizes..
		 *
		 * @since 1.18.0
		 */
		public function get_image_sizes() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
			}

			check_ajax_referer( 'jupiterx_control_panel' );

			wp_send_json_success( self::get_available_image_sizes() );
		}

		/**
		 * Save image sizes..
		 *
		 * @since 1.18.0
		 */
		public function save_image_sizes() {
			check_ajax_referer( 'jupiterx_control_panel' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( 'You do not have access to this section.', 'jupiterx-core' );
			}

			$options         = jupiterx_post( 'options', [] );
			$default_options = [];

			foreach ( $options as $option ) {
				if ( ! empty( $option['default'] ) ) {
					$default_options[] = intval( $option['id'] );
				}
			}

			$deleted_default_options = [];

			foreach ( self::$default_options as $default_option ) {
				if ( in_array( $default_option['id'], $default_options, true ) ) {
					continue;
				}

				$deleted_default_options[] = $default_option['id'];
			}

			update_option( 'jupiterx_image_sizes_deleted', $deleted_default_options );
			update_option( JUPITERX_IMAGE_SIZE_OPTION, $options );

			wp_send_json_success( self::get_available_image_sizes() );
		}
	}
}

new JupiterX_Core_Control_Panel_Image_Sizes();
