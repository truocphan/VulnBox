<?php
namespace WprAddons\Modules\Button\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Button extends Widget_Base {
		
	public function get_name() {
		return 'wpr-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-button';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'button' ];
	}
	
	public function get_style_depends() {
		return [ 'wpr-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-button-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_icon_style() {
		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Select Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'pro-bk' => esc_html__( 'Block (Pro)', 'wpr-addons' ),
					'pro-ibk' => esc_html__( 'Inline Block (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-button-icon-style-',
				'separator' => 'before',
			]
		);
	}

	public function add_control_icon_width() {}

	public function add_section_style_icon() {}

	public function add_section_tooltip() {}

	public function add_section_style_tooltip() {}

	public function render_pro_element_tooltip( $settings ) {}
	
	protected function register_controls() {

		// Section: Button ----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
			]
		);

		$this->add_control(
			'button_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '#link',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'button', 'button_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button .wpr-button-icon' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button .wpr-button-icon svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button .wpr-button-text' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button .wpr-button-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="wpr-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="wpr-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_hover_animation' => ['wpr-button-underline-from-left','wpr-button-underline-from-center','wpr-button-underline-from-right','wpr-button-underline-reveal','wpr-button-overline-reveal','wpr-button-overline-from-left','wpr-button-overline-from-center','wpr-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_hover_animation' => ['wpr-button-winona','wpr-button-rayen-left','wpr-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 160,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);


		$this->add_responsive_control(
			'button_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_content_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-content' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-text' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_id',
			[
				'label' => esc_html__( 'Button ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'wpr-addons' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'wpr-addons' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon -------------
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'select_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_icon_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'button', 'icon_style', ['pro-bk', 'pro-ibk'] );

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-button-icon-position-',
				'separator' => 'before',
			]
		);

		$this->add_control_icon_width();

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-button-icon-position-left .wpr-button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-icon-position-right .wpr-button-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_style' => ['inline', 'pro-bk', 'pro-ibk'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip ---------
		$this->add_section_tooltip();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'button', [
			'Advanced Tooltip options',
			'Advanced Button Styles',
			'Advanced Hover Animations - Change Text on Hover',
		] );

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button'
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-button-icon-style-inline .wpr-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-button-icon-style-inline .wpr-button-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-button-text,{{WRAPPER}} .wpr-button::after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} [class*="elementor-animation"]:hover,
								{{WRAPPER}} .wpr-button::before,
								{{WRAPPER}} .wpr-button::after',
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button:hover .wpr-button-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button::after' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-button-icon-style-inline .wpr-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-icon-style-block .wpr-button-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-icon-style-inline-block .wpr-button-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .wpr-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Icon ---------------
		$this->add_section_style_icon();

		// Styles
		// Section: Tooltip ---------------
		$this->add_section_style_tooltip();
	
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		
		$btn_element = 'div';
		$btn_url =  $settings['button_url']['url'];

	?>
	
	<?php if ( '' !== $settings['button_text'] || '' !== $settings['select_icon']['value'] ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_attribute', 'class', 'wpr-button wpr-button-effect '. $settings['button_hover_animation'] );
			
		if ( '' !== $settings['button_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_attribute', 'data-text', $settings['button_hover_animation_text'] );
		}	

		if ( '' !== $btn_url ) {

			$btn_element = 'a';

			$this->add_render_attribute( 'button_attribute', 'href', $settings['button_url']['url'] );

			if ( $settings['button_url']['is_external'] ) {
				$this->add_render_attribute( 'button_attribute', 'target', '_blank' );
			}

			if ( $settings['button_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_id'] ) {
			$this->add_render_attribute( 'button_attribute', 'id', $settings['button_id']  );
		}

		?>

		<div class="wpr-button-wrap elementor-clearfix">
		<<?php echo esc_html($btn_element); ?> <?php echo $this->get_render_attribute_string( 'button_attribute' ); ?>>
			
			<span class="wpr-button-content">
				<?php if ( '' !== $settings['button_text'] ) : ?>
					<span class="wpr-button-text"><?php echo esc_html__( $settings['button_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon']['value'] ) : ?>
					<span class="wpr-button-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_element); ?>>

		<?php $this->render_pro_element_tooltip( $settings ); ?>
		</div>
	
	<?php endif; ?>

	<?php

	}
}