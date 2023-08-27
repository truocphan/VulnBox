<?php

namespace JupiterX_Core\Raven\Modules\WooCommerce_Breadcrumbs\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;

class WooCommerce_Breadcrumbs extends Base_Widget {

	public function get_name() {
		return 'raven-woocommerce-breadcrumb';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Breadcrumbs', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-woocommerce-breadcrumb';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => esc_html__( 'Link Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb .raven-woocommerce-breadcrumbs-separator' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .woocommerce-breadcrumb',
			]
		);

		$this->add_responsive_control(
			'alignment',
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
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		add_filter( 'woocommerce_breadcrumb_defaults', [ $this, 'change_breadcrumbs_separator' ] );

		woocommerce_breadcrumb();

		remove_filter( 'woocommerce_breadcrumb_defaults', [ $this, 'change_breadcrumbs_separator' ] );
	}

	/**
	 * Change the breadcrumb separator
	 *
	 * Change the breadcrumb separator in order to add a wrapper around it to have CSS control.
	 *
	 * @return array
	 */
	public function change_breadcrumbs_separator( $defaults ) {
		$defaults['delimiter'] = '<span class="raven-woocommerce-breadcrumbs-separator"> / </span>';
		return $defaults;
	}

	/**
	 * Render Plain Content.
	 *
	 * Override the default render behavior, don't render widget content.
	 *
	 * @return void
	 */
	public function render_plain_content() {}
}
