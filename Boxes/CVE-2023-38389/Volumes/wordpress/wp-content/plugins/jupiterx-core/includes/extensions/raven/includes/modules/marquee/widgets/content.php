<?php
namespace JupiterX_Core\Raven\Modules\Marquee\Widgets;

defined( 'ABSPATH' ) || die();

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use JupiterX_Core\Raven\Modules\Marquee\Classes\Marquee;
use JupiterX_Core\Raven\Controls\Query as Control_Query;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

class Content extends Marquee {
	public function get_name() {
		return 'raven-content-marquee';
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'content-marquee' );
	}

	public function get_title() {
		return esc_html__( 'Content Marquee', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-content-marquee';
	}

	protected function register_controls() {
		$this->register_content_settings();
		$this->register_item_style_settings();

		parent::register_controls();
	}

	protected function register_content_settings() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'General', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label' => esc_html__( 'Content Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'image',
				'label_block' => true,
				'options' => [
					'template' => esc_html__( 'Template', 'jupiterx-core' ),
					'image' => esc_html__( 'Image', 'jupiterx-core' ),
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'media_types' => [ 'image' ],
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'content_type' => 'image',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'large',
				'condition' => [
					'content_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'template',
			[
				'label' => esc_html__( 'Choose a template', 'jupiterx-core' ),
				'type' => 'raven_query',
				'options' => [],
				'label_block' => true,
				'multiple' => false,
				'query' => [
					'source' => Control_Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default' => false,
				'condition' => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_responsive_control(
			'template_width',
			[
				'label' => esc_html__( 'Template Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '500',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '500',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '500',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'https://your-link.com',

			]
		);

		$repeater->add_control(
			'css_id',
			[
				'label' => esc_html__( 'Item CSS ID', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'content_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'content_type' => 'image',
					],
					[
						'content_type' => 'image',
					],
					[
						'content_type' => 'image',
					],
				],
				'title_field' => '{{{ content_type === "image" ? "" : label }}}',
				'separator' => 'after',
			]
		);

		$this->register_marquee_content_controls( 'content' );

		$this->end_controls_section();
	}

	protected function register_item_style_settings() {
		$this->start_controls_section(
			'item_style_section',
			[
				'label' => esc_html__( 'Item Style', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-marquee .raven-marquee-item' => 'width:{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_height',
			[
				'label' => esc_html__( 'Content Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '400',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '400',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '400',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-content-marquee .raven-marquee-item' => 'height:{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'item_tabs' );

		$this->start_controls_tab(
			'item_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'item_normal_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'opacity:{{SIZE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'item_normal_css_filters',
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_normal_border_type',
				'selector' => '{{WRAPPER}} .raven-marquee-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_normal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'item_hover_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:hover' => 'opacity:{{SIZE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'item_hover_css_filters',
				'selector' => '{{WRAPPER}} .raven-marquee-item:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_hover_border_type',
				'selector' => '{{WRAPPER}} .raven-marquee-item:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-marquee-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = $settings['content_list'];

		$this->add_marquee_render_attribute();

		$before_gradient = 'left';
		$after_gradient  = 'right';

		if ( 'vertical' === $settings['orientation'] ) {
			$before_gradient = 'top';
			$after_gradient  = 'bottom';
		}

		$before_gradient_function = "handle_{$before_gradient}_gradient_overlay";
		$after_gradient_function  = "handle_{$after_gradient}_gradient_overlay";

		$content = $this->render_marquee_content( $items );
		?>
		<div <?php echo $this->get_render_attribute_string( 'content-container' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
				<?php $this->$before_gradient_function(); ?>
				<div <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'duplicated-content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<?php $this->$after_gradient_function(); ?>
			</div>
		</div>
		<?php
	}
}
