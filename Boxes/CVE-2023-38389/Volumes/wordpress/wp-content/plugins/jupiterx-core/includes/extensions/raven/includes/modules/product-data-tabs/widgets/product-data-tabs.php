<?php

namespace JupiterX_Core\Raven\Modules\Product_Data_Tabs\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Product_Data_Tabs extends Base_Widget {

	public function get_title() {
		return esc_html__( 'Product Data Tabs', 'jupiterx-core' );
	}

	public function get_name() {
		return 'raven-product-data-tabs';
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-data-tabs';
	}

	public function get_script_depends() {
		return [ 'wc-single-product' ];
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	/**
	 * @suppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function register_controls() {
		$this->start_controls_section(
			'section_product_data_tabs_settings',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'tab' => 'content',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'standard' => esc_html__( 'Standard', 'jupiterx-core' ),
					'modern' => esc_html__( 'Modern', 'jupiterx-core' ),
				],
				'default' => 'standard',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'product_data_tabs_style',
			[
				'label' => esc_html__( 'Tab', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'stretch_to_fit',
			[
				'label' => esc_html__( 'Stretch to Fit', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'before_tab_divider',
			[
				'type' => 'divider',
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'normal_tab_style',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'tab_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs ul.tabs li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#fafafa',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs ul.tabs li' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#cccccc',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li' => 'border-color: {{VALUE}}  !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs .panel' => 'border-color: transparent {{VALUE}} {{VALUE}} {{VALUE}} !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'view' => 'standard',
				],
			]
		);

		$this->add_control(
			'modern_tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.modern-tab-style .woocommerce-tabs ul.tabs li:not(.active)' => 'border-color: transparent transparent {{VALUE}} transparent !important;border-style: solid !important',
					'{{WRAPPER}}' => '--data-tab-modern-border: {{VALUE}};',
				],
				'condition' => [
					'view' => 'modern',
				],
			]
		);

		$this->add_responsive_control(
			'modern_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 4,
					],
				],
				'default' => [
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.modern-tab-style .woocommerce-tabs ul.tabs li:not(.active)' => 'border-width: 0 0 {{SIZE}}px 0 !important;border-style: solid !important',
					'{{WRAPPER}} .raven-product-data-tabs.modern-tab-style .woocommerce-tabs ul.tabs::before' => 'border-width: 0 0 {{SIZE}}px 0 !important;border-style: solid !important;',
				],
				'condition' => [
					'view' => 'modern',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active_tab_style',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'tab_active_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .panel' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs ul.tabs li.active' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'standard_tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs .panel' => 'border-color: transparent {{VALUE}} {{VALUE}} {{VALUE}} !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li.active' => 'border-color: {{VALUE}} {{VALUE}} transparent {{VALUE}}  !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li.active:not(:first-child)' => 'border-left-color: {{VALUE}} !important;border-left-style: solid !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li:not(.active)' => 'border-bottom-color: {{VALUE}} !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'view' => 'standard',
				],
			]
		);

		$this->add_control(
			'modern_tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.modern-tab-style .woocommerce-tabs ul.tabs li.active' => 'border-color: transparent transparent {{VALUE}} transparent !important;border-style: solid !important',
				],
				'condition' => [
					'view' => 'modern',
				],
			]
		);

		$this->add_responsive_control(
			'modern_border_width_active',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 4,
					],
				],
				'default' => [
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.modern-tab-style .woocommerce-tabs ul.tabs li.active' => 'border-width: 0 0 {{SIZE}}px 0 !important;border-style: solid !important',
				],
				'condition' => [
					'view' => 'modern',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'standard_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 4,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li' => 'border-width: {{SIZE}}px {{SIZE}}px 0 {{SIZE}}px !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li.active:not(:first-child)' => 'border-left-width: {{SIZE}}px !important;;border-left-style: solid !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs li:not(.active)' => 'border-width: {{SIZE}}px !important;',
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs ul.tabs:after' => 'border-bottom-width: {{SIZE}}px;',
				],
				'condition' => [
					'view' => 'standard',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'description',
			[
				'label' => esc_html__( 'Panel', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-Tabs-panel p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-Tabs-panel pre' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-Tabs-panel td' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-Tabs-panel th' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'panel_content_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'font_size' => [
						'default' => [
							'size' => 16,
							'unit' => 'px',
						],
					],
					'font_weight' => [
						'default' => '400',
					],
					'line_height' => [
						'default' => [
							'size' => 24,
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel p, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel td, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel th, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel td, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel pre',
			]
		);

		$this->add_control(
			'heading_panel_heading_style',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h1' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h2' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h3' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h5' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'panel_heading_typography',
				'selector' => '{{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h1, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h2, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h3, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h4, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h5, {{WRAPPER}} .raven-product-data-tabs .woocommerce-tabs .woocommerce-Tabs-panel h6',
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'panel_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'dimensions',
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-data-tabs.standard-tab-style .woocommerce-tabs .panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
			return;
		}

		$stretch_to_fit = 'yes' === $settings['stretch_to_fit'] ? 'stretch_to_fit_yes' : 'stretch_to_fit_no';

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'raven-product-data-tabs',
				$settings['view'] . '-tab-style',
				$stretch_to_fit,
			]
		);
		remove_all_filters( 'woocommerce_product_description_heading' );
		remove_all_filters( 'woocommerce_product_additional_information_heading' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			echo $this->get_wc_product_tabs_template( $product );
			?>
		</div>
		<?php
	}

	/**
	 * Get tabs template
	 *
	 * @param $product
	 *
	 * @return false|string
	 */
	public function get_wc_product_tabs_template( $product ) {
		ob_start();

		setup_postdata( $product->get_id() );

		$product_tabs = $this->wc_product_tabs( $product );

		if ( ! empty( $product_tabs ) ) : ?>

			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs" role="tablist">
					<?php
					foreach ( $product_tabs as $key => $product_tab ) :
						$active = array_key_first( $product_tabs ) === $key ? ' active ' : '';
						?>
						<li class="<?php echo esc_attr( $active ) . esc_attr( $key ); ?>_tab"
							id="tab-title-<?php echo esc_attr( $key ); ?>"
							role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
							<a href="#tab-<?php echo esc_attr( $key ); ?>">
								<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php
						if ( ! isset( $product_tab['callback'] ) ) {
							continue;
						}

						$method = $product_tab['callback'];

						if ( method_exists( $this, $method ) ) {
							$this->$method( $key, $product_tab );
						} else {
							call_user_func( $method, $key, $product_tab );
						}
						?>
					</div>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_product_after_tabs' ); ?>
			</div>

		<?php endif; ?>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	public function wc_product_tabs( $product ) {
		global $post; // phpcs:ignore
		$post = get_post( $product->get_id() ); // phpcs:ignore

		// Description tab - shows product content.
		if ( $post->post_content ) {
			$tabs['description'] = [
				'title'    => esc_html__( 'Description', 'jupiterx-core' ),
				'priority' => 10,
				'callback' => 'woocommerce_product_description_tab',
			];
		}

		// Additional information tab - shows attributes.
		if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
			$tabs['additional_information'] = [
				'title'    => esc_html__( 'Additional information', 'jupiterx-core' ),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			];
		}

		// Reviews tab - shows comments.
		if ( comments_open( $post->ID ) ) {
			$tabs['reviews'] = [
				/* translators: %s: reviews count */
				'title'    => sprintf( esc_html__( 'Reviews (%d)', 'jupiterx-core' ), $product->get_review_count() ),
				'priority' => 30,
				'callback' => 'comments_template',
			];
		}

		$tabs = apply_filters( 'woocommerce_product_tabs', $tabs );

		return $tabs;
	}

	public function woocommerce_product_description_tab() {
		if ( ! function_exists( 'woocommerce_product_description_tab' ) ) {
			return;
		}

		woocommerce_product_description_tab();
	}

	public function woocommerce_product_additional_information_tab() {
		global $product;

		$heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional information', 'jupiterx-core' ) );

		?>

		<?php if ( $heading ) : ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php wc_display_product_attributes( $product );
	}

	public function comments_template() {
		set_query_var( 'jx_data_tabs_widget', true );
		comments_template();
	}
}
