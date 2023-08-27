<?php
/**
 * Add form textarea field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * Textarea Field.
 *
 * Initializing the textarea field by extending field base abstract class.
 *
 * @since 1.0.0
 */
class Textarea extends Field_Base {

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_content() {
		?>
		<textarea
			oninput="onInvalidRavenFormField(event)"
			oninvalid="onInvalidRavenFormField(event)"
			<?php echo $this->widget->get_render_attribute_string( 'field-' . $this->get_id() ); ?>
			rows="<?php echo $this->field['rows']; ?>"><?php echo $this->get_value(); ?></textarea>
		<?php
	}
}
