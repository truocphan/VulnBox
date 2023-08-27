<?php

namespace JupiterX_Core\Raven\Modules\Post_Title\Widgets;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Utils;

class Post_Title extends Base_Widget {

	public function get_name() {
		return 'raven-post-title';
	}

	public function get_title() {
		return esc_html__( 'Post Title', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-title';
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
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text( null, 'post-title' ),
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
					'{{WRAPPER}} .raven-post-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .raven-post-title, {{WRAPPER}} .raven-post-title a',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .raven-post-title',
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .raven-post-title',
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
					'{{WRAPPER}} .raven-post-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'title', 'class', 'raven-post-title' );

		$title = $settings['title'];

		$title = apply_filters( 'jupiterx_preview_settings_integration_post_title', $title );

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
