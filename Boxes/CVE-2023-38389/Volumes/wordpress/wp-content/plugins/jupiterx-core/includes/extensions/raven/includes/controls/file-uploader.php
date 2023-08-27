<?php
/**
 * File Uploader Control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.2.0
 */

namespace JupiterX_Core\Raven\Controls;

use Elementor\Control_Base_Multiple;

defined( 'ABSPATH' ) || die();

/**
 * Raven File Uploader.
 *
 * A Raven File Uploader Control will let you upload any type of file in a customizable location.
 *
 *
 * $this->add_control(
 *  'attachment',
 *   [
 *    'label' => __( 'Choose File', 'plugin-domain' ),
 *    'type' => 'raven_file_uploader'
 *   ]
 * );
 *
 * @since 1.2.0
 *
 * @param string $label       Optional. The label that appears above of the
 *                            field. Default is empty.
 * @param string $title       Optional. The field title that appears on mouse
 *                            hover. Default is empty.
 * @param string $description Optional. The description that appears below the
 *                            field. Default is empty.
 * @param string $separator   Optional. Set the position of the control separator.
 *                            Available values are 'default', 'before', 'after'
 *                            and 'none'. 'default' will position the separator
 *                            depending on the control type. 'before' / 'after'
 *                            will position the separator before/after the
 *                            control. 'none' will hide the separator. Default
 *                            is 'default'.
 * @param bool   $show_label  Optional. Whether to display the label. Default is
 *                            true.
 * @param bool   $label_block Optional. Whether to display the label in a
 *                            separate line. Default is true.
 *
 * @return array {
 *     An array containing the files with properties Name and Path : `[ 'name' => '', 'path' => '']`.
 *
 *     @type string $name  File name.
 *     @type string $path  File complete path with filename.
 * }
 */
class File_Uploader extends Control_Base_Multiple {

	/**
	 * Retrieve file uploader control type.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raven_file_uploader';
	}

	/**
	 * Retrieve Raven File Uploader Control default values.
	 *
	 * Get the default value of the file uploader control. Used to return the default
	 * values while initializing the file uploader control.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'files' => [],
		];
	}

	/**
	 * Render Raven File Uploader Control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field raven-control-file-uploader">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-button-wrapper">
					<button class="elementor-button elementor-button-default elementor-repeater-add raven-control-file-uploader-button" type="button">
						<input
							type="file"
							data-max-upload-limit="<?php echo wp_max_upload_size(); ?>"
							data-ajax-url="<?php echo admin_url( 'admin-ajax.php' ); ?>"
							class="raven-control-file-uploader-input"/>
						<i class="fa fa-upload" aria-hidden="true"></i>
						<?php echo esc_html__( 'Upload File', 'jupiterx-core' ); ?>
					</button>
					<div class="raven-control-file-uploader-progress">
						Uploading <span class="fa fa-spinner fa-spin"></span>
					</div>
					<div class="raven-control-file-uploader-value">
						<span></span> <span class="fa fa-trash"></span>
					</div>
				</div>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<div class="raven-control-file-uploader-warning">
			<div class="elementor-panel-alert elementor-panel-alert-danger">
				<ul>
					<li class="raven-control-file-uploader-warning-size"> <?php esc_html_e( 'Maximum allowed file size is', 'jupiterx-core' ); ?> <strong><?php echo round( wp_max_upload_size() / ( 1024 * 1024 ), 2 ); ?> MB</strong></li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Retrieve Raven File Uploader Control default settings.
	 *
	 * Get the default settings of the Raven File Uploader Control. Used to return the default
	 * settings while initializing the Raven File Uploader Control.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => false,
		];
	}

	/**
	 * Retrieve Raven File Uploader Control default settings.
	 *
	 * Get the default settings of the Raven File Uploader Control. Used to return the default
	 * settings while initializing the Raven File Uploader Control.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return void
	 */
	public static function handle_file_upload() {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$uploadedfile = $_FILES['file'];

		$upload_overrides = array(
			'test_form' => false,
			'mimes' => get_allowed_mime_types(),
			'unique_filename_callback' => [ self::class, 'rename_uploading_file' ],
		);

		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if ( $movefile && ! isset( $movefile['error'] ) ) {
			wp_send_json_success( [
				'path' => $movefile['file'],
				'name' => pathinfo( $movefile['file'], PATHINFO_BASENAME ),
			] );
		}

		wp_send_json_error( $movefile['error'] );
	}

	/**
	 * Rename file.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @param string $dir File directory.
	 * @param string $name File name.
	 * @param string $ext File extension.
	 *
	 * @return string File name.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function rename_uploading_file( $dir, $name, $ext ) {
		return str_replace( $ext, '', $name ) . '__' . uniqid() . $ext;
	}
}
