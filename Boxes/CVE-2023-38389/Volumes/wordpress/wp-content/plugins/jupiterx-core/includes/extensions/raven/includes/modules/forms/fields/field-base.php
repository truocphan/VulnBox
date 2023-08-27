<?php
/**
 * Add Field Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Module;
use Elementor\Settings;

/**
 * Field Base.
 *
 * An abstract class to register new form field.
 *
 * @since 1.0.0
 * @abstract
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexitys)
 */
abstract class Field_Base {

	/**
	 * Form widget.
	 *
	 * Holds the form widget instance.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $widget;

	/**
	 * Form field.
	 *
	 * Holds all the fields attributes.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $field;

	/**
	 * Depended scripts.
	 *
	 * Holds all the element depended scripts to enqueue.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @var array
	 */
	private $depended_scripts = [];

	/**
	 * Depended styles.
	 *
	 * Holds all the element depended styles to enqueue.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @var array
	 */
	private $depended_styles = [];

	/**
	 * Get field ID.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field ID.
	 */
	public function get_id() {
		return $this->field['_id'];
	}

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return $this->field['type'];
	}

	/**
	 * Get field label.
	 *
	 * Retrieve the field label.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field label.
	 */
	public function get_label() {
		return $this->field['label'];
	}

	/**
	 * Get field class.
	 *
	 * Retrieve the field class.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Field class.
	 */
	public function get_class() {
		return 'raven-field';
	}

	/**
	 * Get field placeholder.
	 *
	 * Retrieve the field placeholder.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field placeholder.
	 */
	public function get_placeholder() {
		return $this->field['placeholder'];
	}

	/**
	 * Get field required state.
	 *
	 * Retrieve the field required state.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field required state.
	 */
	public function get_required() {
		return $this->field['required'];
	}

	/**
	 * Get field width.
	 *
	 * Retrieve the field width.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return integer Field width.
	 */
	public function get_width() {
		return $this->field['width'];
	}

	/**
	 * Get field pattern.
	 *
	 * Retrieve the field pattern.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field pattern.
	 */
	public function get_pattern() {
		return '';
	}

	/**
	 * Get field title.
	 *
	 * Retrieve the field title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field title.
	 */
	public function get_title() {
		return '';
	}

	/**
	 * Get field min.
	 *
	 * Retrieve the field min.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return integer Field min.
	 */
	public function get_min() {
		return empty( $this->field['min'] ) ? '' : $this->field['min'];
	}

	/**
	 * Get field max.
	 *
	 * Retrieve the field max.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return integer Field max.
	 */
	public function get_max() {
		return empty( $this->field['max'] ) ? '' : $this->field['max'];
	}

	/**
	 * Get field value.
	 *
	 * Retrieve the field value.
	 *
	 * @since 1.23.0
	 * @access public
	 *
	 * @return integer Field value.
	 */
	public function get_value() {
		return empty( $this->field['field_value'] ) ? '' : $this->field['field_value'];
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the element requires.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Element scripts dependencies.
	 */
	public function get_script_depends() {
		return $this->depended_scripts;
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers all the scripts defined as element dependencies and enqueues
	 * them. Use `get_script_depends()` method to add custom script dependencies.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	final public function enqueue_scripts() {
		foreach ( $this->get_script_depends() as $script ) {
			wp_enqueue_script( $script );
		}

	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return $this->depended_styles;
	}

	/**
	 * Enqueue styles.
	 *
	 * Registers all the styles defined as element dependencies and enqueues
	 * them. Use `get_style_depends()` method to add custom style dependencies.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	final public function enqueue_styles() {
		foreach ( $this->get_style_depends() as $style ) {
			wp_enqueue_style( $style );
		}
	}

	/**
	 * Render field.
	 *
	 * Render field label and content.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 * @param array  $field Field.
	 */
	public function render( $widget, $field ) {
		$this->widget = $widget;
		$this->field  = $field;

		$this->add_field_render_attribute();

		$this->widget->add_render_attribute(
			'field-group-' . $this->get_id(),
			[
				'id' => 'raven-field-group-' . $this->get_id(),
				'class' => 'raven-flex-wrap raven-field-type-' . $this->get_type() . ' raven-field-group elementor-column elementor-col-' . $this->get_width(),
			]
		);

		if ( 'true' === $field['required'] ) {
			$this->widget->add_render_attribute(
				'field-group-' . $this->get_id(),
				'class',
				'raven-field-required'
			);
		}

		if ( ! empty( $field['width_tablet'] ) ) {
			$this->widget->add_render_attribute(
				'field-group-' . $this->get_id(),
				'class',
				'elementor-md-' . $field['width_tablet']
			);
		}

		if ( ! empty( $field['width_mobile'] ) ) {
			$this->widget->add_render_attribute(
				'field-group-' . $this->get_id(),
				'class',
				'elementor-sm-' . $field['width_mobile']
			);
		}
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'field-group-' . $this->get_id() ); ?>>
			<?php
			$this->render_label();
			$this->render_content();
			?>
		</div>
		<?php
	}

	/**
	 * Add render attribute.
	 *
	 * Add render attributes for each field based on the settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_field_render_attribute() {
		$pattern = $this->get_pattern();
		$title   = $this->get_title();
		$min     = $this->get_min();
		$max     = $this->get_max();
		$value   = $this->get_value();

		$attributes = [
			'type' => $this->get_type(),
			'name' => 'fields[' . $this->get_id() . ']',
			'id' => 'form-field-' . $this->get_id(),
			'class' => $this->get_class(),
			'placeholder' => $this->get_placeholder(),
			'data-type' => $this->field['type'],
		];

		if ( 'true' === $this->get_required() ) {
			$attributes['required'] = 'required';
		}

		if ( ! empty( $pattern ) ) {
			$attributes['pattern'] = $pattern;
		}

		if ( ! empty( $title ) ) {
			$attributes['title'] = $title;
		}

		if ( ! empty( $min ) && 'number' === $this->get_type() ) {
			$attributes['min'] = $min;
		}

		if ( ! empty( $max ) && 'number' === $this->get_type() ) {
			$attributes['max'] = $max;
		}

		if (
			! empty( $value ) &&
			! in_array( $this->get_type(), [ 'textarea', 'select' ], true )
		) {
			$attributes['value'] = $value;
		}

		$this->widget->add_render_attribute( 'field-' . $this->get_id(), $attributes );
	}

	/**
	 * Render label.
	 *
	 * Render the label for each field.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_label() {
		$settings = $this->widget->get_settings_for_display();

		if ( empty( $this->get_label() ) ||
		'yes' !== $settings['label'] ||
		'hidden' === $this->field['type']
		) {
			return;
		}
		?>
		<label
			for="form-field-<?php echo $this->get_id(); ?>"
			class="raven-field-label">
			<?php echo wp_kses_post( $this->get_label() ); ?>
			<?php if (
				! empty( $settings['required_mark'] ) &&
				'true' === $this->get_required()
			) { ?>
				<span class="required-mark-label"></span>
			<?php } ?>
			</label>
		<?php
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 *
	 * @return string The field content.
	 */
	abstract public function render_content();

	/**
	 * Update controls.
	 *
	 * Add, remove and sort the controls in the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widgets Widget instance.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function update_controls( $widgets ) {}

	/**
	 * Validate required.
	 *
	 * Check if field is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate_required( $ajax_handler, $field ) {
		$record_field = $ajax_handler->record['fields'][ $field['_id'] ];

		if ( ! empty( $field['required'] ) && empty( $record_field ) ) {
			$error = Module::$messages['required'];
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function validate( $ajax_handler, $field ) {}

	/**
	 * Inject controls after a specific control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $array The source array.
	 * @param array  $new   The array to insert.
	 * @param string $key   The key.
	 */
	public function inject_field_controls( array $array, array $new, $key = 'placeholder' ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys, true );
		$pos   = false === $index ? count( $array ) : $index + 1;

		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

	/**
	 * Register admin fields.
	 *
	 * Register required admin settings for the field.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function register_admin_fields( $settings ) {}

	/**
	 * Generates random string.
	 *
	 * @uses is used for checkbox and radio fields when there is more than one form on a page or duplicating forms.
	 * @param int $length length of string.
	 * @since 2.5.3
	 */
	public function generate_random_string( $length = 4 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyz';
		$character_length = strlen( $characters );
		$random_string    = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ wp_rand( 0, $character_length - 1 ) ];
		}

		return $random_string;
	}

	/**
	 * Field base constructor.
	 *
	 * Initializing the field base class by hooking in widgets controls.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/element/raven-form/section_form_fields/before_section_end', [ $this, 'update_controls' ] );

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		}
	}

}
