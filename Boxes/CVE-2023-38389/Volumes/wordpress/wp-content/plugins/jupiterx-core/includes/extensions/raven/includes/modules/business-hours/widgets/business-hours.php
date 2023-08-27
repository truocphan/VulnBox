<?php
namespace JupiterX_Core\Raven\Modules\Business_Hours\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || die();

class Business_Hours extends Base_Widget {

	public function get_name() {
		return 'raven-business-hours';
	}

	public function get_title() {
		return esc_html__( 'Business Hours', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-business-hours';
	}

	protected function register_controls() {
		$this->register_controls_content_settings();
		$this->register_controls_style_container();
		$this->register_controls_style_rows_settings();
		$this->register_controls_style_day_and_time();
	}

	/**
	 * Register controls of the content tab -> Settings.
	 *
	 * @return void
	 */
	private function register_controls_content_settings() {
		$this->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
				'tab' => 'content',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'day',
			[
				'label' => esc_html__( 'Enter Day', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'time',
			[
				'label' => esc_html__( 'Enter Time', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'repeater_item_styling',
			[
				'label' => esc_html__( 'Styling', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'is_style_day_enabled',
			[
				'label' => esc_html__( 'Style This Day', 'jupiterx-core' ),
				'type' => 'switcher',
				'yes' => esc_html__( 'Yes', 'jupiterx-core' ),
				'no' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'repeater_day_color',
			[
				'label' => esc_html__( 'Day Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .day' => 'color: {{VALUE}} !important',
				],
				'default' => '#FF0000',
				'condition' => [
					'is_style_day_enabled' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'repeater_time_color',
			[
				'label' => esc_html__( 'Time Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .time' => 'color: {{VALUE}} !important',
				],
				'default' => '#FF0000',
				'condition' => [
					'is_style_day_enabled' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'repeater_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important',
				],
				'condition' => [
					'is_style_day_enabled' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'business_hours_list',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'day' => esc_html__( 'Monday', 'jupiterx-core' ),
						'time' => esc_html__( '8:00 AM - 7:00 PM', 'jupiterx-core' ),
					],
					[
						'day' => esc_html__( 'Tuesday', 'jupiterx-core' ),
						'time' => esc_html__( '8:00 AM - 7:00 PM', 'jupiterx-core' ),
					],
					[
						'day' => esc_html__( 'Wednesday', 'jupiterx-core' ),
						'time' => esc_html__( '8:00 AM - 7:00 PM', 'jupiterx-core' ),
					],
					[
						'day' => esc_html__( 'Thursday', 'jupiterx-core' ),
						'time' => esc_html__( '8:00 AM - 7:00 PM', 'jupiterx-core' ),
					],
					[
						'day' => esc_html__( 'Friday', 'jupiterx-core' ),
						'time' => esc_html__( '8:00 AM - 7:00 PM', 'jupiterx-core' ),
					],
					[
						'day' => esc_html__( 'Saturday', 'jupiterx-core' ),
						'time' => esc_html__( 'Closed', 'jupiterx-core' ),
						'is_style_day_enabled' => 'yes',
					],
					[
						'day' => esc_html__( 'Sunday', 'jupiterx-core' ),
						'time' => esc_html__( 'Closed', 'jupiterx-core' ),
						'is_style_day_enabled' => 'yes',
					],
				],
				'title_field' => '{{{ day }}}',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls of the Style tab -> Container.
	 *
	 * @return void
	 */
	private function register_controls_style_container() {
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'wrapper_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .business-hours-list-wrapper' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'wrapper_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .business-hours-list-wrapper',
			]
		);

		$this->add_responsive_control(
			'wrapper_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .business-hours-list-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'wrapper_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .business-hours-list-wrapper',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls of the Style tab -> Rows.
	 *
	 * @return void
	 */
	private function register_controls_style_rows_settings() {
		$this->start_controls_section(
			'section_style_rows',
			[
				'label' => esc_html__( 'Rows', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'rows_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'is_style_striped_effect_enabled',
			[
				'label' => esc_html__( 'Striped Effect', 'jupiterx-core' ),
				'type' => 'switcher',
				'yes' => esc_html__( 'Yes', 'jupiterx-core' ),
				'no' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'striped_effect_odd_rows_color',
			[
				'label' => esc_html__( 'Striped Odd Rows Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .list-item:nth-of-type(odd)' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'is_style_striped_effect_enabled' => 'yes',
				],
				'default' => '#E8EBED',
			]
		);

		$this->add_control(
			'striped_effect_even_rows_color',
			[
				'label' => esc_html__( 'Striped Even Even Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .list-item:nth-of-type(even)' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'is_style_striped_effect_enabled' => 'yes',
				],
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'is_style_divider_enabled',
			[
				'label' => esc_html__( 'Divider', 'jupiterx-core' ),
				'type' => 'switcher',
				'yes' => esc_html__( 'Yes', 'jupiterx-core' ),
				'no' => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => esc_html__( 'Border Style', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'solid',
				'options' => [
					'solid'  => esc_html__( 'Solid', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
				],
				'condition' => [
					'is_style_divider_enabled' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .business-hours-list-item-divider' => 'border-style: {{VALUE}}; display: block;',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'is_style_divider_enabled' => 'yes',
				],
				'default' => '#D4D4D4',
				'selectors' => [
					'{{WRAPPER}} .business-hours-list-item-divider' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'weight',
			[
				'label' => esc_html__( 'Weight', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .business-hours-list-item-divider' => 'border-top-width: {{SIZE}}px;',
				],
				'condition' => [
					'is_style_divider_enabled' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls of the Style tab -> Day and Time.
	 *
	 * @return void
	 */
	private function register_controls_style_day_and_time() {
		$this->start_controls_section(
			'section_style_day_and_time',
			[
				'label' => esc_html__( 'Day and Time', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'style_day_heading',
			[
				'label' => esc_html__( 'Day', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'style_day_text_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .list-item .day' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'style_day_typography',
				'selector' => '{{WRAPPER}} .list-item .day',
			]
		);

		$this->add_control(
			'style_day_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .list-item .day' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_time_heading',
			[
				'label' => esc_html__( 'Time', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'style_time_text_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .list-item .time' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'style_time_typography',
				'selector' => '{{WRAPPER}} .list-item .time',
			]
		);

		$this->add_control(
			'style_time_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .list-item .time' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_controls_settings();
		$list     = $settings['business_hours_list'];
		?>
		<div class="business-hours-list-wrapper">
			<?php
			$last_item = array_key_last( $list );

			foreach ( $list as $key => $item ) :
				?>
				<div class="list-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
					<div class="day">
						<span><?php $this->print_unescaped_setting( 'day', 'business_hours_list', $key ); ?></span>
					</div>
					<div class="time">
						<span><?php $this->print_unescaped_setting( 'time', 'business_hours_list', $key ); ?></span>
					</div>
				</div>
				<?php
				if ( $last_item !== $key ) {
					echo '<hr class="business-hours-list-item-divider">';
				}

			endforeach;
			?>
		</div>
		<?php
	}
}
