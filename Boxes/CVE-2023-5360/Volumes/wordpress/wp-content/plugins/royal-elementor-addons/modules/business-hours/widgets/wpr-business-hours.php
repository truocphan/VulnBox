<?php
namespace WprAddons\Modules\BusinessHours\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
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

class Wpr_Business_Hours extends Widget_Base {
		
	public function get_name() {
		return 'wpr-business-hours';
	}

	public function get_title() {
		return esc_html__( 'Business Hours', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-clock-o';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'business hours', 'opening Hours', 'opening times', 'currently Open' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-business-hours-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_repeater_args_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => [
				'value' => '',
				'library' => '',
			],
		];
	}

	public function add_repeater_args_highlight() {
		return [
			'label' => sprintf( __( 'Highlight this Item %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'classes' => 'wpr-pro-control no-distance'
		];
	}

	public function add_repeater_args_highlight_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_highlight_bg_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_general_even_bg() {
		$this->add_control(
			'general_even_bg',
			[
				'label' => sprintf( __( 'Enable Even Color %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control'
			]
		);	
	}

	public function add_control_general_even_bg_color() {}

	public function add_control_general_icon_color() {}

	public function add_control_general_hover_icon_color() {}

	public function add_control_general_icon_size() {}

	protected function register_controls() {
		
		// Section: Business Hours ---
		$this->start_controls_section(
			'wpr__section_business_hours_items',
			[
				'label' => esc_html__( 'Business Hours', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_business_hours_item' );

		$repeater->add_control(
			'day',
			[
				'label' => esc_html__( 'Day', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Monday',
			]
		);

		$repeater->add_control( 'icon', $this->add_repeater_args_icon() );

		$repeater->add_control(
			'time',
			[
				'label' => esc_html__( 'Time', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '08:00 AM - 05:00 PM',
				'separator' => 'before'
			]
		);

		$repeater->add_control( 'highlight', $this->add_repeater_args_highlight() );

		$repeater->add_control( 'highlight_color', $this->add_repeater_args_highlight_color() );

		$repeater->add_control( 'highlight_bg_color', $this->add_repeater_args_highlight_bg_color() );

		$repeater->add_control(
			'closed',
			[
				'label' => esc_html__( 'Closed', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'closed_text',
			[
				'label' => esc_html__( 'Closed Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Closed',
				'condition' => [
					'closed' => 'yes',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
				'business_hours_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Custom Icon and Even/Odd Item Background Color</span> options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-business-hours-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Custom Icon and Even/Odd Item Background Color</span> options are available in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'hours_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
					[
						'day' => 'Thursday',
					],
					[
						'day' => 'Friday',
					],
					[
						'day' => 'Saturday',
						'time' => '08:00 AM - 01:00 PM',
					],
					[
						'day' => 'Sunday',
						'closed' => 'yes',
					],
				],
				'title_field' => '{{{ day }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'business-hours', [
			'List Item Custom Icon options',
			'List Item Custom Text & Background Color options',
			'List Item Even/Odd Background Color option',
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'wpr__section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_general_colors' );

		$this->start_controls_tab(
			'tab_general_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'general_day_color',
			[
				'label' => esc_html__( 'Day Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_icon_color();

		$this->add_control(
			'general_time_color',
			[
				'label' => esc_html__( 'Time Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-time' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-business-hours' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_general_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'general_hover_day_color',
			[
				'label' => esc_html__( 'Day Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours .wpr-business-hours-item:not(.wpr-business-hours-item-closed):hover .wpr-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control_general_hover_icon_color();

		$this->add_control(
			'general_hover_time_color',
			[
				'label' => esc_html__( 'Time Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours .wpr-business-hours-item:not(.wpr-business-hours-item-closed):hover .wpr-business-time' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours .wpr-business-hours-item:not(.wpr-business-hours-item-closed):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control_general_even_bg();

		$this->add_control_general_even_bg_color();

		$this->add_control(
			'general_closed_section',
			[
				'label' => esc_html__( 'Closed', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_closed_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item-closed' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_day_color',
			[
				'label' => esc_html__( 'Day Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item-closed .wpr-business-day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_closed_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item-closed .wpr-business-closed' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'general_day_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Day Typography', 'wpr-addons' ),
				'name' => 'general_day_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-business-day',
			]
		);

		$this->add_control_general_icon_size();

		$this->add_control(
			'general_time_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Time Typography', 'wpr-addons' ),
				'name' => 'general_time_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-business-time,{{WRAPPER}} .wpr-business-closed',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_type',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',		
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_divider_weight',
			[
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'general_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-business-hours',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		$item_count = 0;

		?>

		<div class="wpr-business-hours">

			<?php

			foreach ( $settings['hours_items'] as $item ) : 

				if (   '' !== $item['day'] || '' !== $item['time'] ) : 

				$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'wpr-business-hours-item elementor-repeater-item-'.esc_attr( $item['_id'] ) );

				if ( 'yes' === $item['closed'] ) {
					$this->add_render_attribute( 'hours_item_attribute'. $item_count, 'class', 'wpr-business-hours-item-closed' );
				}

				?>
				
				<div <?php echo $this->get_render_attribute_string( 'hours_item_attribute'. $item_count ); ?>>

					<?php if ( '' !== $item['day'] ) : ?>	
					<span class="wpr-business-day">
						<?php echo '' !== $item['icon']['value'] ? '<i class="'. esc_attr($item['icon']['value']) .'"></i>' : ''; ?>
						<?php echo esc_html($item['day']); ?>
					</span>
					<?php endif; ?>

					<?php if ( 'yes' === $item['closed'] ) : ?>	
					<span class="wpr-business-closed"><?php echo esc_html($item['closed_text']); ?></span>
					<?php elseif ( '' !== $item['time'] ) : ?>	
					<span class="wpr-business-time"><?php echo esc_html($item['time']); ?></span>
					<?php endif; ?>

				</div>

				<?php

				endif;

				$item_count++;

			endforeach;

			?>

		</div>
		<?php
	}
}