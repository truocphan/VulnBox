<?php
/**
 * Add Base Widget.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Base;

defined( 'ABSPATH' ) || die();

use Elementor\Element_Base;
use Elementor\Widget_Base;

/**
 * Base Widget.
 *
 * An abstract class to register new Raven widgets.
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Base_Widget extends Widget_Base {

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'jupiterx-core-raven-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'raven', 'jupiter', 'jupiterx' ];
	}

	/**
	 * Retrieve widget active state.
	 *
	 * Use to disable or enable the widget on a certain condition.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return true;
	}


	/**
	 * Render link attributes.
	 *
	 * @since 1.20.0
	 * @access public
	 * @param array $link array link.
	 * @param Element_Base $widget object of element base class.
	 * @param string $element element name.
	 * @param boolean $overwrite Whether to overwrite existing.
	 *
	 * @return void
	 */
	public function render_link_properties( Element_Base $widget, $link, $element, $overwrite = false ) {
		if ( ! empty( $link['is_external'] ) ) {
			$widget->add_render_attribute( $element, 'target', '_blank', $overwrite );
		}

		if ( ! empty( $link['nofollow'] ) ) {
			$widget->add_render_attribute( $element, 'rel', 'nofollow', $overwrite );
		}

		if ( ! empty( $link['custom_attributes'] ) ) {
			$extracted_attributes = $this->get_link_custom_attributes( $link['custom_attributes'] );

			foreach ( $extracted_attributes as $key => $extracted_attribute ) {
				$widget->add_render_attribute( $element, $key, $extracted_attribute );
			}
		}
	}

	/**
	 * Extract custom attributes.
	 *
	 * @since 1.20.0
	 * @access public
	 * @param string $custom_attributes array link.
	 *
	 * @return array|boolean
	 */
	private function get_link_custom_attributes( $custom_attributes ) {
		$extracted_attributes = explode( ',', $custom_attributes );

		if ( ! is_array( $extracted_attributes ) ) {
			return false;
		}

		$extracted_custom_attributes = [];

		foreach ( $extracted_attributes as $item ) {
			$extracted_key_value = explode( '|', $item );

			if ( ! empty( $extracted_key_value[0] ) && ! empty( $extracted_key_value[1] ) ) {
				$extracted_custom_attributes[ $extracted_key_value[0] ] = $extracted_key_value[1];
			}
		}

		if ( count( $extracted_custom_attributes ) === 0 ) {
			return false;
		}

		return $extracted_custom_attributes;
	}

	public function show_in_panel() {
		$categories = $this->get_categories();

		if ( ! in_array( 'jupiterx-core-raven-woo-elements', $categories, true ) ) {
			return true;
		}

		$post_id = filter_var( $_REQUEST['post'], FILTER_SANITIZE_NUMBER_INT ); //phpcs:ignore

		if (
			'product' === get_post_type( absint( $post_id ) ) ||
			'section' === get_post_meta( absint( $post_id ), '_elementor_template_type', true )
		) {
			return true;
		}

		$jx_layout_type = get_post_meta( $post_id, 'jx-layout-type', true );

		if ( 'product' !== $jx_layout_type ) {
			return false;
		}

		return true;
	}
}
