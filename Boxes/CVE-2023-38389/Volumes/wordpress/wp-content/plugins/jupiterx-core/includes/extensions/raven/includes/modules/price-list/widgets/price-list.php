<?php
namespace JupiterX_Core\Raven\Modules\Price_List\Widgets;

use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Price_List extends Base_Widget {

	public function get_name() {
		return 'raven-price-list';
	}

	public function get_title() {
		return esc_html__( 'Price List', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-price-list';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_list();
		$this->register_section_image();
		$this->register_section_item();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'List', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		/* Start repeater */
		$repeater->add_control(
			'item_price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'textarea',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'default' => [
					'url' => '#',
				],
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);
		/* End repeater */

		$this->add_control(
			'list_items',
			[
				'label' => esc_html__( 'List Items', 'jupiterx-core' ),
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_price' => esc_html__( '$20', 'jupiterx-core' ),
						'item_title' => esc_html__( 'First item on the list', 'jupiterx-core' ),
						'item_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
					],
					[
						'item_price' => esc_html__( '$9', 'jupiterx-core' ),
						'item_title' => esc_html__( 'Second item on the list', 'jupiterx-core' ),
						'item_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
					],
					[
						'item_price' => esc_html__( '$32', 'jupiterx-core' ),
						'item_title' => esc_html__( 'Third item on the list', 'jupiterx-core' ),
						'item_description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ item_title }}}',
				'textarea_field' => '{{{ item_description }}}',
				'url_field' => '{{{ item_link }}}',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_list() {
		$this->start_controls_section(
			'section_list',
			[
				'label' => esc_html__( 'List', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-header' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-price-list-title',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'title_text_stroke',
				'selector' => '{{WRAPPER}} .raven-price-list-title',
			]
		);

		$this->add_control(
			'heading_price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .raven-price-list-price',
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .raven-price-list-description',
			]
		);

		$this->add_control(
			'heading_separator',
			[
				'label' => esc_html__( 'Separator', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'solid' => esc_html__( 'Solid', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'none' => esc_html__( 'None', 'jupiterx-core' ),
				],
				'default' => 'dashed',
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-separator' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator_weight',
			[
				'label' => esc_html__( 'Weight', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-separator' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-separator' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 40,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-separator' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_image() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image', // Actually its `image_size`.
				'default' => 'thumbnail',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .raven-price-list-image' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
					'body.rtl {{WRAPPER}} .raven-price-list-image + .raven-price-list-text' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .raven-price-list-image' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .raven-price-list-image + .raven-price-list-text' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_item() {
		$this->start_controls_section(
			'section_item',
			[
				'label' => esc_html__( 'Item', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'item_rows_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->add_control(
			'items_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-price-list-item' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'default' => 'top',
			]
		);

		$this->end_controls_section();
	}

	private function render_image( $item, $instance ) {
		$image_id   = $item['item_image']['id'];
		$image_size = $instance['image_size'];

		if ( 'custom' === $image_size ) {
			$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $instance );
		} else {
			$image_src = wp_get_attachment_image_src( $image_id, $image_size );
			$image_src = $image_src[0];
		}

		return sprintf( '<img src="%s" alt="%s" />', $image_src, $item['item_title'] );
	}

	private function render_item_header( $item ) {
		$url     = $item['item_link']['url'];
		$item_id = $item['_id'];

		if ( $url ) {
			$unique_link_id = 'item-link-' . $item_id;

			$this->add_render_attribute( $unique_link_id, 'class', 'raven-price-list-item' );
			$this->add_link_attributes( $unique_link_id, $item['item_link'] );

			return '<li><a ' . $this->get_render_attribute_string( $unique_link_id ) . '>';
		}

		return '<li class="raven-price-list-item">';
	}

	private function render_item_footer( $item ) {
		if ( $item['item_link']['url'] ) {
			return '</a></li>';
		}
		return '</li>';
	}

	protected function render() {
		$settings = $this->get_settings_for_display(); ?>

		<ul class="raven-price-list">

			<?php foreach ( $settings['list_items'] as $index => $item ) : ?>
				<?php if ( ! empty( $item['item_title'] ) || ! empty( $item['item_price'] ) || ! empty( $item['item_description'] ) ) :
					$title_repeater_setting_key       = $this->get_repeater_setting_key( 'item_title', 'list_items', $index );
					$description_repeater_setting_key = $this->get_repeater_setting_key( 'item_description', 'list_items', $index );
					$this->add_inline_editing_attributes( $title_repeater_setting_key );
					$this->add_inline_editing_attributes( $description_repeater_setting_key );
					$this->add_render_attribute( $title_repeater_setting_key, 'class', 'raven-price-list-title' );
					$this->add_render_attribute( $description_repeater_setting_key, 'class', 'raven-price-list-description' );
				?>
					<?php echo $this->render_item_header( $item ); ?>
					<?php if ( ! empty( $item['item_image']['url'] ) ) : ?>
						<div class="raven-price-list-image">
							<?php echo $this->render_image( $item, $settings ); ?>
						</div>
					<?php endif; ?>

					<div class="raven-price-list-text">
						<?php if ( ! empty( $item['item_title'] ) || ! empty( $item['item_price'] ) ) : ?>
							<div class="raven-price-list-header">
								<?php if ( ! empty( $item['item_title'] ) ) : ?>
									<span <?php echo $this->get_render_attribute_string( $title_repeater_setting_key ); ?>><?php echo $item['item_title']; ?></span>
								<?php endif; ?>
								<?php if ( 'none' !== $settings['separator_style'] ) : ?>
									<span class="raven-price-list-separator"></span>
								<?php endif; ?>
								<?php if ( ! empty( $item['item_price'] ) ) : ?>
									<span class="raven-price-list-price"><?php echo $item['item_price']; ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $item['item_description'] ) ) : ?>
							<p <?php echo $this->get_render_attribute_string( $description_repeater_setting_key ); ?>><?php echo $item['item_description']; ?></p>
						<?php endif; ?>
					</div>
					<?php echo $this->render_item_footer( $item ); ?>
				<?php endif; ?>
			<?php endforeach; ?>

		</ul>

		<?php
	}
}
