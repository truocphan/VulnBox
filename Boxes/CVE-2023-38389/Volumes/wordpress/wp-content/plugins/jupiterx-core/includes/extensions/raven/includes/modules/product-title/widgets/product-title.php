<?php

namespace JupiterX_Core\Raven\Modules\Product_Title\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils as JXUtils;
use Elementor\Utils;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
* @SuppressWarnings(PHPMD.NPathComplexity)
*/
class Product_Title extends Base_Widget {

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_name() {
		return 'raven-product-title';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-title';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'textarea',
				'dynamic' => [
					'active' => true,
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text( null, 'woocommerce-product-title-tag' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'small' => esc_html__( 'Small', 'jupiterx-core' ),
					'medium' => esc_html__( 'Medium', 'jupiterx-core' ),
					'large' => esc_html__( 'Large', 'jupiterx-core' ),
					'xl' => esc_html__( 'XL', 'jupiterx-core' ),
					'xxl' => esc_html__( 'XXL', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'small' => '15px',
					'medium' => '19px',
					'large' => '29px',
					'xl' => '39px',
					'xxl' => '59px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-title' => 'font-size: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'jupiterx-core' ),
				'type' => 'hidden',
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-product-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .raven-product-title, {{WRAPPER}} .raven-product-title a',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .raven-product-title, {{WRAPPER}} .raven-product-title a',
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .raven-product-title, {{WRAPPER}} .raven-product-title a',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'multiply' => esc_html__( 'Multiply', 'jupiterx-core' ),
					'screen' => esc_html__( 'Screen', 'jupiterx-core' ),
					'overlay' => esc_html__( 'Overlay', 'jupiterx-core' ),
					'darken' => esc_html__( 'Darken', 'jupiterx-core' ),
					'lighten' => esc_html__( 'Lighten', 'jupiterx-core' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'jupiterx-core' ),
					'saturation' => esc_html__( 'Saturation', 'jupiterx-core' ),
					'color' => esc_html__( 'Color', 'jupiterx-core' ),
					'difference' => esc_html__( 'Difference', 'jupiterx-core' ),
					'exclusion' => esc_html__( 'Exclusion', 'jupiterx-core' ),
					'hue' => esc_html__( 'Hue', 'jupiterx-core' ),
					'luminosity' => esc_html__( 'Luminosity', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);
	}

	protected function render() {
		JXUtils::get_product();

		global $product;
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'title', 'class', 'raven-product-title' );

		$default_title = $product->get_name();
		$title         = $settings['title'];

		if ( empty( $title ) ) {
			$title = $default_title;
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( 'url' ),
				wp_kses_post( $title )
			);
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>',
			Utils::validate_html_tag( $settings['header_size'] ),
			$this->get_render_attribute_string( 'title' ),
			$title
		);

		echo $title_html;
	}
}
