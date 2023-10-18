<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductExcerpt\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Excerpt extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Product Excerpt', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-post-excerpt';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-excerpt', 'product', 'excerpt' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_excerpt',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-excerpt p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-excerpt li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-excerpt a' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'excerpt_typography',
				'label'          => esc_html__('Typography', 'wpr-addons'),
				'selector'       => '{{WRAPPER}} .wpr-product-excerpt p, {{WRAPPER}} .wpr-product-excerpt li, {{WRAPPER}} .wpr-product-excerpt a',
				'exclude'        => ['text_decoration'],
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '14',
							'unit' => 'px'
						],
						'label'      => 'Font size (px)',
						'size_units' => ['px'],
					],
				],
			)
		);

		$this->add_control(
			'excerpt_align',
			[
				'label'     => esc_html__('Alignment', 'wpr-addons'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options'   => [
					'left'   => [
						'title' => esc_html__('Left', ''),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', ''),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', ''),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-excerpt p' => 'text-align: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
		
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        $post = get_post( $product->get_id() );
        setup_postdata( $product->get_id() );

		$product_excerpt = apply_filters('woocommerce_short_description', $post->post_excerpt);

        echo '<div class="wpr-product-excerpt">';
            echo $product_excerpt;
        echo '</div>';
    }
}