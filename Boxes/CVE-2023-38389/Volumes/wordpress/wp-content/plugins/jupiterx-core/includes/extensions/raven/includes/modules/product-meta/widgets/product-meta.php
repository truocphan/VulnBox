<?php

namespace JupiterX_Core\Raven\Modules\Product_Meta\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

class Product_Meta extends Base_Widget {

	public function get_name() {
		return 'raven-product-meta';
	}

	public function get_title() {
		return esc_html__( 'Product Meta', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-meta';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {
		$this->get_section_product_meta_styles_controls();

		$this->get_section_product_meta_captions_controls();
	}

	private function get_section_product_meta_styles_controls() {
		$this->start_controls_section(
			'section_product_meta_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'view',
			[
				'label'        => esc_html__( 'View', 'jupiterx-core' ),
				'type'         => 'select',
				'default'      => 'inline',
				'options'      => [
					'table'   => esc_html__( 'Table', 'jupiterx-core' ),
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'inline'  => esc_html__( 'Inline', 'jupiterx-core' ),
				],
				'prefix_class' => 'elementor-woo-meta--view-',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label'     => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type'      => 'slider',
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:not(:first-child)' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:first-child' => 'margin-right: calc({{SIZE}}{{UNIT}}/2) !important',
					'body:not(.rtl) {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
					'body:not.rtl {{WRAPPER}}.elementor-woo-meta--view-inline .detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label'        => esc_html__( 'Divider', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_off'    => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on'     => esc_html__( 'On', 'jupiterx-core' ),
				'selectors'    => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'content: ""',
				],
				'return_value' => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'     => esc_html__( 'Style', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
				],
				'default'   => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after'       => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'     => esc_html__( 'Weight', 'jupiterx-core' ),
				'type'      => 'slider',
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after'       => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label'      => esc_html__( 'Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'default'    => [
					'unit' => '%',
				],
				'condition'  => [
					'divider' => 'yes',
					'view!'   => 'inline',
				],
				'selectors'  => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label'      => esc_html__( 'Height', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ '%', 'px' ],
				'default'    => [
					'unit' => '%',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition'  => [
					'divider' => 'yes',
					'view'    => 'inline',
				],
				'selectors'  => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#dddddd',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label'     => esc_html__( 'Title', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'font_weight' => [
						'default' => '700',
					],
					'font_size'   => [
						'default' => [
							'size' => 16,
							'unit' => 'px',
						],
					],
					'line_height'   => [
						'default' => [
							'size' => 19,
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .product_meta .detail-container .detail-label',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#111111',
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container .detail-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_description_style',
			[
				'label'     => esc_html__( 'Description', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'size' => 16,
							'unit' => 'px',
						],
					],
					'line_height'   => [
						'default' => [
							'size' => 19,
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .product_meta .detail-container .detail-content, {{WRAPPER}} .product_meta .detail-container .detail-content a',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#555555',
				'selectors' => [
					'{{WRAPPER}} .product_meta .detail-container .detail-content' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .product_meta .detail-container .detail-content a' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_section_product_meta_captions_controls() {
		$this->start_controls_section(
			'section_product_meta_captions',
			[
				'label' => esc_html__( 'Captions', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'heading_category_caption',
			[
				'label' => esc_html__( 'Category', 'jupiterx-core' ),
				'type'  => 'heading',
			]
		);

		$this->add_control(
			'category_caption_single',
			[
				'label'       => esc_html__( 'Singular', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Category', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'category_caption_plural',
			[
				'label'       => esc_html__( 'Plural', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Categories', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'heading_tag_caption',
			[
				'label'     => esc_html__( 'Tag', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tag_caption_single',
			[
				'label'       => esc_html__( 'Singular', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Tag', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'tag_caption_plural',
			[
				'label'       => esc_html__( 'Plural', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Tags', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'heading_sku_caption',
			[
				'label'     => esc_html__( 'SKU', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sku_caption',
			[
				'label'       => esc_html__( 'SKU', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'SKU', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'sku_missing_caption',
			[
				'label'       => esc_html__( 'Missing', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'N/A', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
	}

	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	protected function render() {
		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		?>

		<div class="product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php $this->sku_to_display( $settings, $product ); ?>

			<?php $this->categories_to_display( $settings, $product ); ?>

			<?php $this->tags_to_display( $settings, $product ); ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>

		<?php
	}

	private function sku_to_display( $settings, $product ) {
		$sku         = $product->get_sku();
		$sku_caption = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : esc_html__( 'SKU', 'jupiterx-core' );
		$sku_missing = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : esc_html__( 'N/A', 'jupiterx-core' );

		if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
			<span class="sku_wrapper detail-container">
				<span class="detail-label"><?php echo esc_html( $sku_caption ); ?></span>
				<span class="sku detail-content"><?php echo esc_html( $sku ) ?: esc_html( $sku_missing ); ?></span>
			</span>
		<?php endif;
	}

	private function categories_to_display( $settings, $product ) {
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : esc_html__( 'Category', 'jupiterx-core' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : esc_html__( 'Categories', 'jupiterx-core' );

		if ( count( $product->get_category_ids() ) ) : ?>
			<span class="posted_in categories detail-container">
				<span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span>
				<span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', '<span class="product-meta-separator">,&nbsp;</span>' ); ?></span>
			</span>
		<?php endif;
	}

	private function tags_to_display( $settings, $product ) {
		$tag_caption_single = ! empty( $settings['tag_caption_single'] ) ? esc_html( $settings['tag_caption_single'] ) : esc_html__( 'Tag', 'jupiterx-core' );
		$tag_caption_plural = ! empty( $settings['tag_caption_plural'] ) ? esc_html( $settings['tag_caption_plural'] ) : esc_html__( 'Tags', 'jupiterx-core' );

		if ( count( $product->get_tag_ids() ) ) : ?>
			<span class="tagged_as tags detail-container">
				<span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span>
				<span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', '<span class="product-meta-separator">,&nbsp;</span>' ); ?></span>
			</span>
		<?php endif;
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
