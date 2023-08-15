<?php
/**
 * Class to Build the Advanced Form Select Block.
 *
 * @package Kadence Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kadence_Blocks_Select_Block extends Kadence_Blocks_Advanced_Form_Input_Block {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Block name within this namespace.
	 *
	 * @var string
	 */
	protected $block_name = 'select';


	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Builds CSS for block.
	 *
	 * @param array  $attributes the blocks attributes.
	 * @param string $css the css class for blocks.
	 * @param string $unique_id the blocks attr ID.
	 * @param string $unique_style_id the blocks alternate ID for queries.
	 */
	public function build_css( $attributes, $css, $unique_id, $unique_style_id ) {
		$css->set_style_id( 'kb-' . $this->block_name . $unique_style_id );
		$css->set_selector( '.wp-block-kadence-advanced-form .kb-field' . $unique_style_id );

		$css->render_responsive_range( $attributes, 'maxWidth', 'max-width', 'maxWidthUnit' );
		$css->render_responsive_range( $attributes, 'minWidth', 'min-width', 'minWidthUnit' );

		return $css->css_output();
	}

	/**
	 * Return dynamically generated HTML for block
	 *
	 * @param $attributes
	 * @param $unique_id
	 * @param $content
	 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
	 *
	 * @return mixed
	 */
	public function build_html( $attributes, $unique_id, $content, $block_instance ) {
		$is_required = $this->is_required( $attributes, 'required', '' );
		$is_multiselect = ( isset( $attributes['multiSelect'] ) && $attributes['multiSelect'] === true ) ? 'multiple' : '';

		$outer_classes = array( 'kb-adv-form-field', 'kb-adv-form-infield-type-input', 'kb-field' . $unique_id );
		if ( ! empty( $attributes['className'] ) ) {
			$outer_classes[] = $attributes['className'];
		}
		$wrapper_args = array(
			'class' => implode( ' ', $outer_classes ),
		);
		$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );
		$default_value = isset( $attributes['defaultValue'] ) ? $attributes['defaultValue'] : '';
		$show_placeholder = true;
		if ( isset( $attributes['options'] ) && is_array( $attributes['options'] ) ) {
			foreach ( $attributes['options'] as $option ) {
				$option_value = $this->get_option_value( $option );
				if ( $default_value === $option_value ) {
					$show_placeholder = false;
				}
			}
		}
		$inner_content  = '';

		$inner_content .= $this->field_label( $attributes );
		$inner_content .= $this->field_aria_label( $attributes );

		$inner_content .= '<select ' . $is_multiselect . ' name="' . $this->field_name( $attributes ) . '" id="' . $this->field_id( $attributes ) . '"' . $this->aria_described_by( $attributes ) . ' ' . $this->a11y_helpers( $attributes ) . '>';
		if ( ! empty( $attributes['placeholder'] ) && $show_placeholder ) {
			$inner_content .= '<option value="" disabled selected>' . $attributes['placeholder'] . '</option>';
		}
		if ( isset( $attributes['options'] ) && is_array( $attributes['options'] ) ) {
			foreach ( $attributes['options'] as $option ) {
				$option_value = $this->get_option_value( $option );
				$inner_content .= '<option value="' . $option_value . '"' . ( $default_value === $option_value ? ' selected' : '' ) . '>' . $option['label'] . '</option>';
			}
		}
		$inner_content .= '</select>';

		$inner_content .= $this->field_help_text( $attributes );

		$content = sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $inner_content );
		return $content;
	}
}

Kadence_Blocks_Select_Block::get_instance();
