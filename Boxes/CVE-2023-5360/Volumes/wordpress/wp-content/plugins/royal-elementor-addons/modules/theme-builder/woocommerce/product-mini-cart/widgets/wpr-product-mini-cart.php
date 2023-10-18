<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMiniCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Mini_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-mini-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Mini Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-images';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-ini-cart', 'product', 'mini', 'cart' ];
	}

	public function get_script_depends() {
		return ['wpr-perfect-scroll-js'];
	}

	public function add_control_mini_cart_style() {
		$this->add_control(
			'mini_cart_style',
			[
				'label' => esc_html__( 'Cart Content', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'pro-dd' => esc_html__( 'Dropdown (Pro)', 'wpr-addons' ),
					'pro-sb' => esc_html__( 'Sidebar (Pro)', 'wpr-addons' )
				],
				'default' => 'none'
			]
		); 
	}

	public function add_controls_group_mini_cart_style() {}

	public function add_section_style_mini_cart() {}

	public function add_section_style_remove_icon() {}

	public function add_section_style_buttons() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mini_cart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'wpr_particles_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$this->add_control(
				'select_icon',
				[
					'label' => esc_html__('Select Icon', 'wpr-addons'),
					'type' => Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'exclude' => ['svg'],
					'default' => [
						'value' => 'fas fa-shopping-cart',
						'library' => 'solid',
					]
				]
			);
		} else {
			$this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' => esc_html__( 'None', 'wpr-addons' ),
						'cart-light' => esc_html__( 'Cart Light', 'wpr-addons' ),
						'cart-medium' => esc_html__( 'Cart Medium', 'wpr-addons' ),
						'cart-solid' => esc_html__( 'Cart Solid', 'wpr-addons' ),
						'basket-light' => esc_html__( 'Basket Light', 'wpr-addons' ),
						'basket-medium' => esc_html__( 'Basket Medium', 'wpr-addons' ),
						'basket-solid' => esc_html__( 'Basket Solid', 'wpr-addons' ),
						'bag-light' => esc_html__( 'Bag Light', 'wpr-addons' ),
						'bag-medium' => esc_html__( 'Bag Medium', 'wpr-addons' ),
						'bag-solid' => esc_html__( 'Bag Solid', 'wpr-addons' )
					],
					'default' => 'cart-medium',
					'prefix_class' => 'wpr-toggle-icon-',
				]
			);
		}

		$this->add_control(
			'toggle_text',
			[
				'label' => esc_html__( 'Toggle Prefix', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'price' => esc_html__( 'Total Price', 'wpr-addons' ),
					'title' => esc_html__( 'Extra Text', 'wpr-addons' )
				],
				'default' => 'price',
			]
		);

		$this->add_control(
			'toggle_title',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Cart', 'wpr-addons' ),
				'default' => esc_html__( 'Cart', 'wpr-addons' ),
				'condition' => [
					'toggle_text' => 'title'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-wrap' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control_mini_cart_style(); 

		$this->add_controls_group_mini_cart_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'product-mini-cart', 'mini_cart_style', ['pro-dd', 'pro-sb'] );

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'product-mini-cart', [
			'Show Mini Cart Content (Products added to cart) on Mini Cart icon click',
			'Display Mini Cart Content as Dropdown or Off-Canvas Layout'
		] );
		
		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_mini_cart_button',
			[
				'label' => esc_html__( 'Cart Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_btn_cart_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'toggle_btn_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-icon' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_cart_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'separator' => 'before',
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'color: {{VALUE}}',
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'wpr-addons' ),
                'scheme' => Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .wpr-mini-cart-toggle-btn, {{WRAPPER}} .wpr-mini-cart-icon-count',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						]
					]
				]
            ]
        );

		$this->add_responsive_control(
			'toggle_text_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-btn-text' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mini-cart-btn-price' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mini-cart-toggle-btn',
			]
		);

		$this->add_responsive_control(
			'mini_cart_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'mini_cart_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_item_count',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Count', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_item_count_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_font_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart-icon-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Styles ==============
		// Section: Mini Cart ---------------
		$this->add_section_style_mini_cart();

		// Tab: Styles ==============
		// Section: Remove Icon ----------
		$this->add_section_style_remove_icon();

		// Tab: Style ==============
		// Section: Buttons --------
		$this->add_section_style_buttons();

    } 

	public function render_mini_cart_toggle($settings) {

		if ( null === WC()->cart ) {
			return;
		}

		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total = WC()->cart->get_cart_subtotal();
		$counter_attr = 'data-counter="' . $product_count . '"';
		if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$icon_class = $settings['select_icon']['value'];
		} else {
			$icon_class = 'eicon';
		}

		if ( !is_plugin_active('wpr-addons-pro/wpr-addons-pro.php') || 'none' == $settings['mini_cart_style'] ) {
			// global $woocommerce;
			$cart_url = wc_get_cart_url();
		} else {
			$cart_url = '#'; 
		}
		?>

		<span class="wpr-mini-cart-toggle-wrap">
			<a href=<?php echo $cart_url ?> class="wpr-mini-cart-toggle-btn" aria-expanded="false">
				<?php if ( 'none' !== $settings['toggle_text']) :
						if ( 'price' == $settings['toggle_text'] ) { ?>
							<span class="wpr-mini-cart-btn-price">
								<?php echo $sub_total;  ?>
							</span>
						<?php } else { ?>
							<span class="wpr-mini-cart-btn-text">
								 <?php esc_html_e( $settings['toggle_title'], 'wpr-addons' ); ?>
							</span>
						<?php } 
				endif; ?>
				<span class="wpr-mini-cart-btn-icon" <?php echo $counter_attr; ?>>
					<i class="<?php echo $icon_class ?>">
                        <span class="wpr-mini-cart-icon-count <?php echo $product_count ? '' : 'wpr-mini-cart-icon-count-hidden'; ?>"><span><?php echo $product_count ?></span></span>
                    </i>
				</span>
			</a>
		</span>
		<?php
	}

	public function render_close_cart_icon () {}

	public static function render_mini_cart($settings) {}
    
    protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'mini_cart_attributes',
			[
				'data-animation' => (wpr_fs()->can_use_premium_code() && isset($settings['mini_cart_entrance_speed'])) ? $settings['mini_cart_entrance_speed'] : ''
			]
		);

        echo '<div class="wpr-mini-cart-wrap woocommerce"' . $this->get_render_attribute_string( 'mini_cart_attributes' ) . '>';
			echo '<span class="wpr-mini-cart-inner">';
				$this->render_mini_cart_toggle($settings);
				if ( 'none' !== $settings['mini_cart_style'] ) {
					$this->render_mini_cart($settings);
				}
			echo '</span>';
        echo '</div>';
    }    
}        
