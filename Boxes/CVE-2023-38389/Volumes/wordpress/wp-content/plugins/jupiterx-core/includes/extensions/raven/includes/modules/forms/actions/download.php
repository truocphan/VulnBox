<?php
/**
 * Add form Download action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.2.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Download Action.
 *
 * Initializing the Download action by extending action base.
 *
 * @since 1.2.0
 */
class Download extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_name() {
		return 'download';
	}

	/**
	 * Get title.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Download', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return false;
	}

	/**
	 * Update controls.
	 *
	 * Add Redirect section.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {
		$widget->start_controls_section(
			'section_download',
			[
				'label' => __( 'Download', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'download',
				],
			]
		);

		$widget->add_control(
			'download_resource',
			[
				'label' => __( 'Download Resource', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'file' => __( 'File', 'jupiterx-core' ),
					'url' => __( 'URL', 'jupiterx-core' ),
				],
			]
		);

		$widget->add_control(
			'download_url',
			[
				'label' => __( 'Download URL', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'jupiterx-core' ),
				'show_external' => false,
				'default' => [
					'url' => '',
				],
				'condition' => [
					'download_resource' => 'url',
				],
			]
		);

		$widget->add_control(
			'download_file',
			[
				'label' => __( 'Download File', 'jupiterx-core' ),
				'type' => 'raven_file_uploader',
				'condition' => [
					'download_resource' => 'file',
				],
			]
		);

		$widget->end_controls_section();
	}

	/**
	 * Run action.
	 *
	 * Download File/URL.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {
		if (
			! empty( $ajax_handler->response['errors'] ) ||
			! empty( $ajax_handler->response['admin_errors'] )
		) {
			return;
		}

		$download_resource = $ajax_handler->form['settings']['download_resource'];

		if ( 'file' === $download_resource ) {

			self::download_file( $ajax_handler );

		} elseif ( 'url' === $download_resource ) {

			self::download_url( $ajax_handler );

		}
	}

	/**
	 * Download URL.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	private static function download_url( $ajax_handler ) {
		$value = $ajax_handler->form['settings']['download_url'];

		if ( empty( $value ) || empty( $value['url'] ) ) {
			return;
		}

		if ( ! filter_var( $value['url'], FILTER_VALIDATE_URL ) ) {
			$admin_error = __( 'Download Action: The "Download URL" value is not a valid URL.', 'jupiterx-core' );
		}

		if ( empty( $admin_error ) ) {
			return $ajax_handler->add_response( 'download_url', $value['url'] );
		}

		$ajax_handler->add_response( 'admin_errors', $admin_error );
	}

	/**
	 * Download File.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	private static function download_file( $ajax_handler ) {
		$value = $ajax_handler->form['settings']['download_file'];

		if ( empty( $value['files'] ) || count( $value['files'] ) === 0 ) {
			return;
		}

		$value = $value['files'][0];

		if ( ! file_exists( $value['path'] ) ) {
			$admin_error = __( 'Download Action: The "Download File" doesn\'t exist anymore.', 'jupiterx-core' );
		}

		if ( empty( $admin_error ) ) {
			$url = admin_url( 'admin-post.php?action=raven_download_file&file=' . base64_encode( $value['path'] ) . '&_wpnonce=' . wp_create_nonce() ); // phpcs:ignore
			return $ajax_handler->add_response( 'download_url', $url );
		}

		$ajax_handler->add_response( 'admin_errors', $admin_error );
	}
}
