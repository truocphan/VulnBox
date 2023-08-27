<?php
/**
 * This handles customizer custom pane section.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Pane class.
 *
 * This is a special section for rendering tabs and child popups controls inside the Popup section.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Section_Pane extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'kirki-pane';

	/**
	 * Parent popup where to render this section.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $popup = '';

	/**
	 * Get the pane type and id.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $pane = [];

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 1.0.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'type' ) );
		$array['content']        = $this->get_content();
		$array['instanceNumber'] = $this->instance_number;
		$array['popup']          = $this->popup;
		$array['pane']           = $this->pane;

		return $array;
	}

	/**
	 * An Underscore (JS) template for rendering this section.
	 *
	 * @since 1.0.0
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
			<ul class="customize-jupiterx-pane-section jupiterx-controls control-section control-section-{{ data.type }}"></ul>
		</li>
		<?php
	}
}
