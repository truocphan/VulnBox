<?php
namespace JupiterX_Core\Raven\Modules\Photo_Album\Skins;

use JupiterX_Core\Raven\Utils;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

abstract class Skin_Base extends Elementor_Skin_Base {

	protected $item;

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-photo-album/section_content/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_settings_controls();
		$this->register_cover_controls();
		$this->register_overlay_controls();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_description_controls();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'jupiterx-core' ),
					'masonry' => __( 'Masonry', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => __( '1', 'jupiterx-core' ),
					'2' => __( '2', 'jupiterx-core' ),
					'3' => __( '3', 'jupiterx-core' ),
					'4' => __( '4', 'jupiterx-core' ),
					'5' => __( '5', 'jupiterx-core' ),
					'6' => __( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_group_control(
			'image-size',
			[
				'name' => 'image',
				'default' => 'large',
			]
		);

		$this->add_skin_hover_effect();

		$this->add_control(
			'show_thumbnail',
			[
				'label' => __( 'Thumbnail', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->parent->update_control(
			$this->get_control_id( 'show_thumbnail' ),
			[
				'condition' => [
					'_skin' => 'cover',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_description',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'random_order',
			[
				'label' => __( 'Random Order', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_cover_controls() {
		$this->start_controls_section(
			'section_cover',
			[
				'label' => __( 'Cover', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'cover_height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-skin-cover .raven-photo-album-item' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-stack-figure img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'layout' ) => 'grid',
				],
			]
		);

		$this->add_responsive_control(
			'cover_column_spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .raven-photo-album .raven-masonry-item, {{WRAPPER}} .raven-photo-album .raven-grid-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'cover_row_spacing',
			[
				'label' => __( 'Row Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album .raven-masonry-item, {{WRAPPER}} .raven-photo-album .raven-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cover_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
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
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item' => 'text-align: {{VALUE}};',
				],
				'label_block' => false,
			]
		);

		$this->register_cover_border_controls();

		$this->end_controls_section();
	}

	protected function register_cover_border_controls() {}

	protected function register_overlay_controls() {}

	protected function register_thumbnail_controls() {}

	protected function register_title_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );

		$this->start_controls_tab(
			'title_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_tab_typography_normal',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-photo-album-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_tab_typography_hover',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-title',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
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
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_description_controls() {
		$this->start_controls_section(
			'section_description',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'description_tabs' );

		$this->start_controls_tab(
			'description_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'description_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_tab_typography_normal',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-photo-album-description',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'description_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'description_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_tab_typography_hover',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-photo-album-item:hover .raven-photo-album-description',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
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
				'selectors' => [
					'{{WRAPPER}} .raven-photo-album-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render_image() {
		if ( empty( $this->item['images'] ) ) {
			return;
		}

		$settings = [
			'image' => [
				'id' => $this->item['images'][0]['id'],
			],
			'image_size' => $this->get_instance_value( 'image_size' ),
		];

		$this->render_skin_image( $settings );
	}

	protected function render_lightbox() {
		$images = $this->item['images'];

		if ( empty( $images ) ) {
			return;
		}

		if ( 1 >= count( $images ) ) {
			return;
		}

		array_shift( $images );
		?>
		<div class="raven-photo-album-lightbox">
			<?php foreach ( $images as $image ) : ?>
				<a href="<?php echo Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'image', [ 'image_size' => 'full' ] ); ?>" data-elementor-lightbox-slideshow="<?php echo $this->parent->get_id() . $this->item['_id']; ?>"></a>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function render_thumbnails() {
		if ( ! $this->get_instance_value( 'show_thumbnail' ) || 'cover' !== $this->parent->get_settings( '_skin' ) ) {
			return;
		}

		$thumbnails = 'thumbnails-' . $this->item['_id'];

		$this->parent->add_render_attribute(
			$thumbnails,
			'class',
			[
				'raven-photo-album-thumbnails',
				'raven-photo-album-' . $this->get_instance_value( 'thumbnail_style' ),
			]
		);

		$images = $this->item['images'];

		if ( 1 >= count( $images ) ) {
			return;
		}

		$images = array_slice( $images, 1, 3 );

		$settings = [
			'image_size' => 'custom',
			'image_custom_dimension' => [
				'width' => 360,
				'height' => 240,
			],
		];

		if ( 'circle' === $this->get_instance_value( 'thumbnail_style' ) ) {
			$settings['image_custom_dimension']['width']  = 300;
			$settings['image_custom_dimension']['height'] = 300;
		}
		?>

		<div <?php echo $this->parent->get_render_attribute_string( $thumbnails ); ?>>
			<?php
			foreach ( $images as $image ) {
				$settings['image'] = [
					'id' => $image['id'],
				];

				echo Group_Control_Image_Size::get_attachment_image_html( $settings );
			}
			?>
		</div>

		<?php
	}

	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		printf( '<h3 class="raven-photo-album-title">%s</h3>', $this->item['title'] );
	}

	protected function render_description() {
		if ( ! $this->get_instance_value( 'show_description' ) || empty( $this->item['description'] ) ) {
			return;
		}

		printf( '<p class="raven-photo-album-description">%s</p>', $this->item['description'] );
	}

	protected function render_item() {
		$hover_effect = $this->get_instance_value( 'hover_effect' );
		$url          = empty( $this->item['images'][0]['url'] ) ? '#' : $this->item['images'][0]['url'];
		$link         = 'link-' . $this->item['_id'];
		$layout       = $this->get_instance_value( 'layout' );

		$this->parent->add_render_attribute( 'item-' . $this->item['_id'], 'class', 'raven-photo-album-item' );

		if ( ! empty( $hover_effect ) && 'cover' === $this->parent->get_settings( '_skin' ) ) {
			$this->parent->add_render_attribute( 'item', 'class', 'elementor-animation-' . $hover_effect );
		}

		$this->parent->add_render_attribute( $link, [
			'href' => $url,
			'class' => 'elementor-clickable',
		] );

		if ( 1 < count( $this->item['images'] ) ) {
			$this->parent->add_render_attribute( $link, [
				'data-elementor-lightbox-slideshow' => $this->parent->get_id() . $this->item['_id'],
			] );
		}
		?>
		<div class="raven-<?php echo $layout; ?>-item">
			<article <?php echo $this->parent->get_render_attribute_string( 'item-' . $this->item['_id'] ); ?>>
				<a <?php echo $this->parent->get_render_attribute_string( $link ); ?>>
					<figure>
						<?php $this->render_image(); ?>
						<figcaption class="raven-photo-album-content">
							<?php $this->render_thumbnails(); ?>
							<div class="raven-photo-album-meta">
							<?php
								$this->render_title();
								$this->render_description();
							?>
							</div>
						</figcaption>
					</figure>
				</a>
				<?php $this->render_lightbox(); ?>
			</article>
		</div>
		<?php
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();
		$list     = $settings['list'];
		$layout   = $this->get_instance_value( 'layout' );

		$this->parent->add_render_attribute(
			'wrapper',
			'class',
			[
				'raven-photo-album',
				'raven-photo-album-skin-' . $settings['_skin'],
			]
		);

		if ( 'stack' === $settings['_skin'] ) {
			$this->parent->add_render_attribute(
				'wrapper',
				'class',
				'raven-stack-effect-' . strtolower( $this->get_instance_value( 'hover_effect' ) )
			);
		}

		$columns = Utils::get_responsive_class(
			'raven-' . $layout . '%s-',
			$this->get_control_id( 'columns' ),
			$this->parent->get_settings_for_display()
		);

		$this->parent->add_render_attribute(
			'wrapper',
			'class',
			$columns
		);

		$this->parent->add_render_attribute(
			'wrapper', 'class', 'raven-' . $layout
		);

		if ( 'yes' === $this->get_instance_value( 'random_order' ) ) {
			shuffle( $list );
		}

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'wrapper' ); ?>>
		<?php
		foreach ( $list as $item ) {
			$this->item = $item;

			$this->render_item();
		}
		?>
		</div>
		<?php
	}
}
