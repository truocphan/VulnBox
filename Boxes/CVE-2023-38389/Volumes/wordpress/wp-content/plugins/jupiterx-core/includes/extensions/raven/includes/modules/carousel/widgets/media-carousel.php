<?php
namespace JupiterX_Core\Raven\Modules\Carousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Embed;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Media_Carousel extends Base {

	private $lightbox_slide_index;

	public function get_name() {
		return 'raven-media-carousel';
	}

	public function get_title() {
		return esc_html__( 'Media Carousel', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-media-carousel';
	}

	protected function render() {
		$settings = $this->get_active_settings();

		if ( $settings['overlay'] ) {
			$this->add_render_attribute( 'image-overlay', 'class', [
				'raven-carousel-image-overlay',
				'e-overlay-animation-' . $settings['overlay_animation'],
			] );
		}

		$this->print_slider();

		if ( 'slideshow' !== $settings['skin'] || count( $settings['slides'] ) <= 1 ) {
			return;
		}

		$settings['thumbs_slider']   = true;
		$settings['container_class'] = 'raven-thumbnails-swiper';
		$settings['show_arrows']     = false;

		$this->print_slider( $settings );
	}

	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => esc_html__( 'Lightbox', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label' => esc_html__( 'UI Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_hover_color',
			[
				'label' => esc_html__( 'UI Hover Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_video_width',
			[
				'label' => esc_html__( 'Video Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->add_injections();

		$this->add_navigation_injections();

		$this->add_slideshow_injections();

		$this->update_controls();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'type',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'default' => 'image',
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'jupiterx-core' ),
						'icon' => 'eicon-image-bold',
					],
					'video' => [
						'title' => esc_html__( 'Video', 'jupiterx-core' ),
						'icon' => 'eicon-video-camera',
					],
				],
				'toggle' => false,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'image_link_to_type',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'file' => esc_html__( 'Media File', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom URL', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'image_link_to',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'https://your-link.com',
				'show_external' => 'true',
				'condition' => [
					'type' => 'image',
					'image_link_to_type' => 'custom',
				],
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'video',
			[
				'label' => esc_html__( 'Video Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your video link', 'jupiterx-core' ),
				'description' => esc_html__( 'YouTube or Vimeo link', 'jupiterx-core' ),
				'options' => false,
				'condition' => [
					'type' => 'video',
				],
			]
		);
	}

	protected function get_default_slides_count() {
		return 5;
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array_fill( 0, $this->get_default_slides_count(), [
			'image' => [
				'url' => $placeholder_image_src,
			],
		] );
	}

	protected function get_image_caption( $slide ) {
		$caption_type = $this->get_settings( 'caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $slide['image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	protected function get_image_link( $slide ) {
		if ( ! empty( $slide['video']['url'] ) ) {
			return $slide['image']['url'] ? $slide['image']['url'] : '#';
		}

		if ( ! $slide['image_link_to_type'] ) {
			return '';
		}

		if ( 'custom' === $slide['image_link_to_type'] ) {
			return $slide['image_link_to']['url'];
		}

		return $slide['image']['url'];
	}

	protected function print_slider( array $settings = null ) {
		$this->lightbox_slide_index = 0;

		parent::print_slider( $settings );
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		if ( ! empty( $settings['thumbs_slider'] ) ) {
			$settings['video_play_icon'] = false;

			$this->add_render_attribute( $element_key . '-image', 'class', 'elementor-fit-aspect-ratio' );
		}

		$this->add_render_attribute( $element_key . '-image', [
			'class' => 'raven-carousel-image',
		] );

		$img_src = $this->get_slide_image_url( $slide, $settings );

		$img_attribute['style'] = 'background-image: url(' . $img_src . ')';

		if ( 'yes' === $settings['lazyload'] ) {
			$img_attribute['class']           = 'swiper-lazy';
			$img_attribute['data-background'] = $img_src;

			unset( $img_attribute['style'] );
		}

		$this->add_render_attribute( $element_key . '-image', $img_attribute );

		$image_link_to = $this->get_image_link( $slide );

		if ( $image_link_to && empty( $settings['thumbs_slider'] ) ) {
			if ( 'custom' === $slide['image_link_to_type'] ) {
				$this->add_link_attributes( $element_key . '_link', $slide['image_link_to'] );
			} else {
				$this->add_render_attribute( $element_key . '_link', 'href', $image_link_to );

				$this->add_lightbox_data_attributes( $element_key . '_link', $slide['image']['id'], 'yes', $this->get_id() );

				if ( Elementor::$instance->editor->is_edit_mode() ) {
					$this->add_render_attribute( $element_key . '_link', 'class', 'elementor-clickable' );
				}

				$this->lightbox_slide_index++;
			}

			if ( 'video' === $slide['type'] && $slide['video']['url'] ) {
				$embed_url_params = [
					'autoplay' => 1,
					'rel' => 0,
					'controls' => 0,
				];

				$this->add_render_attribute( $element_key . '_link', 'data-elementor-lightbox-video', Embed::get_embed_url( $slide['video']['url'], $embed_url_params ) );
			}

			echo '<a ' . $this->get_render_attribute_string( $element_key . '_link' ) . '>';
		}

		$this->print_slide_image( $slide, $element_key, $settings );

		if ( $image_link_to ) {
			echo '</a>';
		}
	}

	protected function print_slide_image( array $slide, $element_key, array $settings ) {
		?>
		<div <?php $this->print_render_attribute_string( $element_key . '-image' ); ?>>
			<?php if ( 'yes' === $settings['lazyload'] ) : ?>
				<div class="swiper-lazy-preloader"></div>
			<?php endif; ?>
			<?php if ( 'video' === $slide['type'] && $settings['video_play_icon'] ) : ?>
				<div class="elementor-custom-embed-play">
					<?php
						Icons_Manager::render_icon( [
							'library' => 'eicons',
							'value' => 'eicon-play',
						], [ 'aria-hidden' => 'true' ] );
					?>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Play', 'jupiterx-core' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $settings['overlay'] ) : ?>
			<div <?php $this->print_render_attribute_string( 'image-overlay' ); ?>>
				<?php
				if ( 'text' === $settings['overlay'] ) {
					echo wp_kses_post( $this->get_image_caption( $slide ) );
					echo '</div>';
					return;
				}

				$this->render_overlay_icon( $settings['icon'] );
				?>
			</div>
			<?php
		endif;
	}

	private function add_injections() {
		$this->start_injection( [
			'type' => 'section',
			'at' => 'start',
			'of' => 'section_slides',
		] );

		$this->add_control(
			'skin',
			[
				'label' => esc_html__( 'Skin', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'carousel' => esc_html__( 'Carousel', 'jupiterx-core' ),
					'slideshow' => esc_html__( 'Slideshow', 'jupiterx-core' ),
					'coverflow' => esc_html__( 'Coverflow', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-skin-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'image_size_custom_dimension',
		] );

		$this->add_responsive_control(
			'image_fit',
			[
				'label' => esc_html__( 'Image Fit', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Cover', 'jupiterx-core' ),
					'contain' => esc_html__( 'Contain', 'jupiterx-core' ),
					'auto' => esc_html__( 'Auto', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-main-swiper .raven-carousel-image' => 'background-size: {{VALUE}}',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'lazyload',
		] );

		$this->add_control(
			'overflow_visible',
			[
				'label' => esc_html__( 'Overflow Visible', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-main-swiper' => 'position:relative; width: 100vw; margin-left: -50vw; left: 49.2%;',
				],
				'separator' => 'before',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'slide_padding',
		] );

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'slide_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-main-swiper .swiper-slide',
				'condition' => [
					'skin' => [ 'carousel', 'coverflow' ],
					'effect!' => 'fade',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'pagination_color',
		] );

		$this->add_control(
			'play_icon_title',
			[
				'label' => esc_html__( 'Play Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-custom-embed-play svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'play_icon_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Shadow', 'Text Shadow Control', 'jupiterx-core' ),
					],
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'pause_on_interaction',
		] );

		$this->add_control(
			'overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'text' => esc_html__( 'Text', 'jupiterx-core' ),
					'icon' => esc_html__( 'Icon', 'jupiterx-core' ),
				],
				'condition' => [
					'skin!' => 'slideshow',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => esc_html__( 'Caption', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title' => esc_html__( 'Title', 'jupiterx-core' ),
					'caption' => esc_html__( 'Caption', 'jupiterx-core' ),
					'description' => esc_html__( 'Description', 'jupiterx-core' ),
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'search-plus',
				'options' => [
					'search-plus' => [
						'icon' => 'eicon-search-bold',
					],
					'plus-circle' => [
						'icon' => 'eicon-plus-circle',
					],
					'eye' => [
						'icon' => 'eicon-preview-medium',
					],
					'link' => [
						'icon' => 'eicon-link',
					],
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay' => 'icon',
				],
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => 'Fade',
					'slide-up' => 'Slide Up',
					'slide-down' => 'Slide Down',
					'slide-right' => 'Slide Right',
					'slide-left' => 'Slide Left',
					'zoom-in' => 'Zoom In',
				],
				'condition' => [
					'skin!' => 'slideshow',
					'overlay!' => '',
				],
			]
		);

		$this->end_injection();
	}

	private function add_navigation_injections() {
		$this->start_injection( [
			'type' => 'section',
			'of' => 'section_navigation',
		] );

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => esc_html__( 'Overlay', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'skin!' => 'slideshow',
					'overlay!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-carousel-image-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .raven-carousel-image-overlay' => '--raven-carousel-image-overlay-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .raven-carousel-image-overlay',
				'condition' => [
					'overlay' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .raven-carousel-image-overlay' => '--raven-carousel-image-overlay-icon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'overlay' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();

		$this->start_injection( [
			'of' => 'pagination_size',
		] );

		$this->add_responsive_control(
			'pagination_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'pagination!' => [ 'progressbar', '' ],
					'pagination_position' => 'outside',
					'skin!' => 'slideshow',
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->end_injection();
	}

	private function add_slideshow_injections() {

		$this->start_injection( [
			'of' => 'effect',
		] );

		$this->add_responsive_control(
			'slideshow_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'thumbs_title',
			[
				'label' => esc_html__( 'Thumbnails', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'slides_per_view',
		] );

		$this->add_control(
			'thumbs_ratio',
			[
				'label' => esc_html__( 'Ratio', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '219',
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'11' => '1:1',
				],
				'prefix_class' => 'elementor-aspect-ratio-',
				'condition' => [
					'skin' => 'slideshow',
				],
			]
		);

		$this->add_control(
			'centered_slides',
			[
				'label' => esc_html__( 'Centered Slides', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'skin' => 'slideshow',
				],
				'frontend_available' => true,
			]
		);

		$this->end_injection();

		$this->start_injection( [
			'of' => 'slides_per_view',
		] );

		$slides_per_view = range( 1, 10 );

		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slideshow_slides_per_view',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Slides Per View', 'jupiterx-core' ),
				'options' => [ '' => esc_html__( 'Default', 'jupiterx-core' ) ] + $slides_per_view,
				'condition' => [
					'skin' => 'slideshow',
				],
				'frontend_available' => true,
			]
		);

		$this->end_injection();
	}

	private function update_controls() {
		$carousel_controls = [
			'slides_to_scroll',
			'pagination',
			'heading_pagination',
			'pagination_size',
			'pagination_position',
			'pagination_color',
		];

		$carousel_responsive_controls = [
			'width',
			'height',
			'slides_per_view',
		];

		foreach ( $carousel_controls as $control_id ) {
			$this->update_control(
				$control_id,
				[
					'condition' => [
						'skin!' => 'slideshow',
					],
				],
				[ 'recursive' => true ]
			);
		}

		foreach ( $carousel_responsive_controls as $control_id ) {
			$this->update_responsive_control(
				$control_id,
				[
					'condition' => [
						'skin!' => 'slideshow',
					],
				],
				[ 'recursive' => true ]
			);
		}

		$this->update_responsive_control(
			'space_between',
			[
				'selectors' => [
					'{{WRAPPER}}.elementor-skin-slideshow .raven-main-swiper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'render_type' => 'ui',
			]
		);

		$this->update_control(
			'effect',
			[
				'condition' => [
					'skin!' => 'coverflow',
				],
			]
		);
	}

	private function render_overlay_icon( $icon_name ) {
		$icon_value = 'fas fa-' . $icon_name;

		$icon = [
			'library' => 'fa-solid',
			'value' => $icon_value,
		];

		Icons_Manager::render_icon( $icon );
	}
}
