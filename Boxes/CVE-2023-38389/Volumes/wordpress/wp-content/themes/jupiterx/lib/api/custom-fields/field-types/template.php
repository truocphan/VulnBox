<?php
/**
 * Class for extending acf_fields and adding new control as 'template'.
 *
 * @link https://www.advancedcustomfields.com/resources/creating-a-new-field-type/
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since 1.2.0
 */

/**
 * Template selector field.
 *
 * @since 1.2.0
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 */
class JupiterX_Field_Template extends acf_field {

	/**
	 * Class constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->name     = 'jupiterx_template';
		$this->label    = __( 'Template', 'jupiterx' );
		$this->category = 'choice';

		parent::__construct();
	}

	/**
	 * Create the HTML interface for your field.
	 *
	 * @since 1.2.0
	 *
	 * @param array $field The field settings being rendered.
	 */
	public function render_field( $field ) {
		$template_type = isset( $field['template_type'] ) ? $field['template_type'] : 'post';

		$settings = [
			'templateType' => $template_type,
		];

		if ( isset( $field['choices']['global'] ) ) {
			$settings['global'] = $field['choices']['global'];
		}
		?>
		<select data-settings="<?php echo esc_attr( wp_json_encode( $settings ) ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>">
			<?php foreach ( $field['choices'] as $value => $label ) { ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $field['value'], $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php } ?>
		</select>
		<a class="edit-button button"><?php esc_html_e( 'Edit', 'jupiterx' ); ?></a>
		<a class="new-button button"><?php esc_html_e( 'New', 'jupiterx' ); ?></a>
		<?php
	}
}

new JupiterX_Field_Template();
