<?php
namespace JupiterX_Core\Raven\Modules\Post_Terms\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Post_Terms\Module;

defined( 'ABSPATH' ) || die();

class Post_Terms extends Base_Widget {
	public function get_name() {
		return 'raven-post-terms';
	}

	public function get_title() {
		return esc_html__( 'Post Terms', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-terms';
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'tax',
			[
				'label' => esc_html__( 'Taxonomy Type', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => 'category',
				'options' => Module::taxonomy_list(),
			]
		);

		$this->add_control(
			'text_before',
			[
				'label' => esc_html__( 'Before Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Text Before', 'jupiterx-core' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'skin',
			[
				'label' => esc_html__( 'Skin', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'minimal',
				'options' => [
					'minimal' => esc_html__( 'Minimal', 'jupiterx-core' ),
					'flat' => esc_html__( 'Flat', 'jupiterx-core' ),
					'boxed' => esc_html__( 'Boxed', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '0px',
				'options' => [
					'0px' => esc_html__( 'Square', 'jupiterx-core' ),
					'0.5em' => esc_html__( 'Rounded', 'jupiterx-core' ),
					'99.9em' => esc_html__( 'Circle', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item' => 'border-radius: {{VALUE}};',
				],
				'condition' => [
					'skin' => [ 'flat', 'boxed' ],
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => true,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Auto', 'jupiterx-core' ),
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-post-terms-widget-wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'unset' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'columns' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-without-title' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .jupiterx-post-term-with-title' => 'justify-content: {{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->register_buttons_style_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'style_before_text',
			[
				'label' => esc_html__( 'Before Text', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->register_before_text_controls();

		$this->end_controls_section();
	}

	private function register_buttons_style_controls() {
		$this->add_responsive_control(
			'col_gap',
			[
				'label' => esc_html__( 'Items Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-post-terms-widget-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'separator' => 'after',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-post-terms-widget-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'txt_padding',
			[
				'label' => esc_html__( 'Text Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '10',
					'right' => '10',
					'left' => '10',
					'bottom' => '10',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'separator' => 'after',
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 30,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => [ 'boxed' ],
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .jupiterx-post-term-item',
			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'background_normal',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .jupiterx-post-term-item',
				'condition' => [
					'skin' => 'flat',
				],
				'types' => [ 'classic', 'gradient' ],
			]
		);

		$this->add_control(
			'text_color_normal',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color_normal',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'skin' => [ 'boxed' ],
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_shadow_normal',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .jupiterx-post-term-item',
				'condition' => [
					'skin!' => 'minimal',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'background_hover',
				'label' => esc_html__( 'Background', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .jupiterx-post-term-item:hover',
				'condition' => [
					'skin' => 'flat',
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-post-term-item:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'skin' => [ 'boxed' ],
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .jupiterx-post-term-item:hover',
				'condition' => [
					'skin!' => 'minimal',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	private function register_before_text_controls() {
		$this->add_group_control(
			'typography',
			[
				'name' => 'before_text_typography',
				'selector' => '{{WRAPPER}} .jupiter-post-term-title',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->add_control(
			'before_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .jupiter-post-term-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'before_text_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'unit' => 'px',
					'right' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .jupiter-post-term-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	public function show_in_panel() {
		$layout_builder = filter_input( INPUT_GET, 'layout-builder', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$page_id        = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		$builder_type   = 'empty';

		if ( ! empty( $layout_builder ) && ! empty( $page_id ) ) {
			$builder_type = get_post_meta( $page_id, 'jx-layout-type', true );
		}

		// For the layout builder.
		if ( 'single' === $builder_type || 'product' === $builder_type ) {
			return true;
		}

		// For rest of single post types.
		$excludes  = [ 'page', 'elementor_library' ];
		$post_type = get_post_type( $page_id );

		if ( ! in_array( $post_type, $excludes, true ) ) {
			return true;
		}

		return false;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$post_id  = get_the_ID();
		$tax      = $settings['tax'];
		$terms    = get_the_terms( $post_id, $tax );

		if ( empty( $terms ) ) {
			return;
		}

		$classes = [
			'jupiterx-post-term-widget-' . $settings['skin'],
			'jupiterx-post-term-widget-column-' . $settings['columns'],
		];

		if ( 'unset' === $settings['alignment'] ) {
			$classes[] = 'jupiterx-post-term-wrapper-justify';
		} else {
			$classes[] = 'jupiterx-post-term-widget-wrapper-default';
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'id'    => 'jupiterx-post-terms-widget-wrapper',
			]
		);

		$shape = ( ! empty( $settings['shape'] ) ) ? $settings['shape'] : 'normal';

		$this->add_render_attribute(
			'item',
			[
				'class' => [ 'jupiterx-post-term-item', 'jupiterx-post-term-shape-' . $shape ],
			]
		);

		if ( ! empty( $settings['text_before'] ) ) {
			?>
				<div class="jupiterx-post-term-with-title">
					<div class="jupiter-post-term-title">
						<?php echo esc_html( $settings['text_before'] ); ?>
					</div>
			<?php
		} else {
			$classes[] = 'jupiterx-post-term-without-title';
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >
			<?php foreach ( $terms as $term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>">
					<div <?php echo $this->get_render_attribute_string( 'item' ); ?> >
						<?php echo esc_html( $term->name ); ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php

		if ( ! empty( $settings['text_before'] ) ) {
			echo '</div>';
		}
	}
}
