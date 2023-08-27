<?php
/**
 * Class for extending acf_fields and adding new control as 'divider'.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since 1.0.0
 */

/**
 * The widget_area Custom-field class
 *
 * @since   1.0.0
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 */
class JupiterX_Field_Divider extends acf_field {

	/**
	 * Class constructor that sets needed properties for class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		// name (string) Single word, no spaces. Underscores allowed.
		$this->name = 'jupiterx-divider';

		// label (string) Multiple words, can include spaces, visible when selecting a field type.
		$this->label = __( 'Divider', 'jupiterx' );

		// category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.
		$this->category = 'layout';

		parent::__construct();

	}
}

new JupiterX_Field_Divider();
