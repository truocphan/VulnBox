<?php
/**
 * Adds media control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls;

use Elementor\Control_Base_Multiple;

defined( 'ABSPATH' ) || die();

/**
 * Raven media control.
 *
 * A base control for creating a media chooser control. Based on the WordPress
 * media library. Used to select file from the WordPress media library.
 *
 * Creating new control in the editor (inside `Widget_Base::_register_controls()`
 * method):
 *
 * $this->add_control(
 *  'media',
 *   [
 *    'label' => __( 'Choose File', 'plugin-domain' ),
 *    'type' => 'raven_media',
 *    'query' => [
 *     'type' => 'video'
 *    ],
 *   ]
 * );
 *
 * @since 1.0.0
 *
 * @param string $label       Optional. The label that appears above of the
 *                            field. Default is empty.
 * @param string $title       Optional. The field title that appears on mouse
 *                            hover. Default is empty.
 * @param string $description Optional. The description that appears below the
 *                            field. Default is empty.
 * @param array $default      {
 *     Optional. Defautl media values.
 *
 *     @type int    $id  Optional. Media id. Default is empty.
 *     @type string $url Optional. Media url. Default is empty.
 * }
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
 *     An array containing the media ID and URL: `[ 'id' => '', 'url' => '' ]`.
 *
 *     @type int    $id  Media id.
 *     @type string $url Media url.
 * }
 */
class Media extends Control_Base_Multiple {

	/**
	 * Retrieve media control type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'raven_media';
	}

	/**
	 * Retrieve media control default values.
	 *
	 * Get the default value of the media control. Used to return the default
	 * values while initializing the media control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'url' => '',
			'id' => '',
		];
	}

	/**
	 * Render media control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="raven-control-media">
					<input type="text" class="elementor-input" value="{{ data.controlValue.url }}" placeholder="{{ data.placeholder }}" readonly />
					<div class="raven-control-media-upload tooltip-target" data-tooltip="<?php esc_html_e( 'Media Upload', 'jupiterx-core' ); ?>">
						<i class="fa fa-upload" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			<# if ( data.description ) { #>
				<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<input type="hidden" data-setting="{{ data.name }}" />
		</div>
		<?php
	}

	/**
	 * Retrieve media control default settings.
	 *
	 * Get the default settings of the media control. Used to return the default
	 * settings while initializing the media control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'media_types' => [
				'video',
			],
			'query' => [
				'search' => '',
				'type' => '',
			],
		];
	}
}
