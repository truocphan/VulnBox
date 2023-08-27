<?php
namespace JupiterX_Core\Raven\Modules\Carousel\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Reviews extends Base {

	public function get_name() {
		return 'raven-reviews';
	}

	public function get_title() {
		return esc_html__( 'Reviews', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-reviews';
	}

	public function get_inline_css_depends() {
		$slides = $this->get_settings_for_display( 'slides' );

		foreach ( $slides as $slide ) {
			if ( $slide['rating'] ) {
				return [
					[
						'name' => 'star-rating',
						'is_core_dependency' => true,
					],
				];
			}
		}

		return [];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'slide_padding',
			[
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__header' => 'padding-top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .raven-testimonial__content' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_navigation',
		] );

		$this->register_controls_section_content_style();
		$this->register_controls_section_image_style();
		$this->register_controls_section_icon_style();
		$this->register_controls_section_rating_style();

		$this->end_injection();

		$this->register_injections();

		$this->register_controls_inject_of_slide_padding();

		$this->update_responsive_control(
			'width',
			[
				'selectors' => [
					'{{WRAPPER}}.raven-arrows-yes .raven-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .raven-main-swiper' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->update_responsive_control(
			'slides_per_view',
			[
				'condition' => null,
			]
		);

		$this->update_control(
			'slides_to_scroll',
			[
				'condition' => null,
			]
		);

		$this->remove_control( 'effect' );
		$this->remove_responsive_control( 'height' );
		$this->remove_control( 'pagination_position' );
	}

	private function register_controls_inject_of_slide_padding() {
		$this->start_injection( [
			'of' => 'slide_padding',
		] );

		$this->add_control(
			'heading_header',
			[
				'label' => esc_html__( 'Header', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .raven-testimonial__content' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Separator', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'default' => 'has-separator',
				'return_value' => 'has-separator',
				'prefix_class' => 'raven-review--',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__header' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'show_separator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'show_separator!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__header' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_injection();
	}

	private function register_controls_section_content_style() {

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'name_title_style',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .raven-testimonial__name',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-testimonial__title',
			]
		);

		$this->add_control(
			'heading_review_style',
			[
				'label' => esc_html__( 'Review', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .raven-testimonial__text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_controls_section_image_style() {
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .raven-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
					'body.rtl {{WRAPPER}} .raven-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_controls_section_icon_style() {
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'icon_custom_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__icon:not(.raven-testimonial__rating)' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-testimonial__icon:not(.raven-testimonial__rating) svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-testimonial__icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-testimonial__icon svg' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_controls_section_rating_style() {
		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'star_style',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'star_fontawesome' => 'Font Awesome',
					'star_unicode' => 'Unicode',
				],
				'default' => 'star_fontawesome',
				'render_type' => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'unmarked_star_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'jupiterx-core' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'jupiterx-core' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'solid',
			]
		);

		$this->add_responsive_control(
			'star_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'star_space',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'stars_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stars_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'John Doe', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'default' => '@username',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'type' => 'number',
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'selected_social_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'social_icon',
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'recommended' => [
					'fa-solid' => [
						'rss',
						'shopping-cart',
						'thumbtack',
					],
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'digg',
						'dribbble',
						'envelope',
						'facebook',
						'flickr',
						'foursquare',
						'github',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'vimeo',
						'fa-vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'https://your-link.com',

			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Review', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
	}

	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return [
			[
				'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
				'name' => esc_html__( 'John Doe', 'jupiterx-core' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
				'name' => esc_html__( 'John Doe', 'jupiterx-core' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
			[
				'content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
				'name' => esc_html__( 'John Doe', 'jupiterx-core' ),
				'title' => '@username',
				'image' => [
					'url' => $placeholder_image_src,
				],
			],
		];
	}

	protected function register_injections() {
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
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->end_injection();
	}

	private function print_cite( $slide, $settings ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$html = '<cite class="raven-testimonial__cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<span class="raven-testimonial__name">' . $slide['name'] . '</span>';
		}

		if ( ! empty( $slide['rating'] ) ) {
			$html .= $this->render_stars( $slide, $settings );
		}

		if ( ! empty( $slide['title'] ) ) {
			$html .= '<span class="raven-testimonial__title">' . $slide['title'] . '</span>';
		}
		$html .= '</cite>';

		echo wp_kses_post( $html );
	}

	protected function stars_icon( $settings ) {
		$icon = '&#xE934;';

		if ( 'star_fontawesome' === $settings['star_style'] && 'outline' === $settings['unmarked_star_style'] ) {
			$icon = '&#xE933;';

			return $icon;
		}

		if ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		return $icon;
	}

	protected function render_stars( $slide, $settings ) {
		$icon           = $this->stars_icon( $settings );
		$rating         = (float) $slide['rating'] > 5 ? 5 : $slide['rating'];
		$floored_rating = (int) $rating;
		$stars_html     = '';

		for ( $stars = 1; $stars <= 5; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
			}
		}

		return '<div class="elementor-star-rating">' . $stars_html . '</div>';
	}

	private function get_social_icon( $slide, $is_new, $migrated ) {
		if ( ! empty( $slide['social_icon'] ) ) {
			$social = str_replace( 'fa fa-', '', $slide['social_icon'] );
		}

		if ( ( $is_new || $migrated ) && 'svg' !== $slide['selected_social_icon']['library'] ) {
			$social_temp = explode( ' ', $slide['selected_social_icon']['value'], 2 );
			$social      = '';

			if ( ! empty( $social_temp[1] ) ) {
				$social = str_replace( 'fa-', '', $social_temp[1] );
			}
		}

		if ( 'svg' === $slide['selected_social_icon']['library'] ) {
			$social = '';
		}

		return $social;
	}

	private function print_icon( $slide, $element_key ) {
		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( empty( $slide['social_icon'] ) && empty( $slide['selected_social_icon'] ) ) {
			return '';
		}

		if ( ! isset( $slide['social_icon'] ) && ! $migration_allowed ) {
			$slide['social_icon'] = 'fa fa-twitter';
		}

		$migrated = isset( $slide['__fa4_migrated']['selected_social_icon'] );
		$is_new   = empty( $slide['social_icon'] ) && $migration_allowed;
		$social   = '';

		if ( ! empty( $slide['social_icon'] ) ) {
			$icon = '<i class="' . esc_attr( $slide['social_icon'] ) . '" aria-hidden="true"></i>';
		}

		if ( $is_new || $migrated ) {
			ob_start();
			Icons_Manager::render_icon( $slide['selected_social_icon'], [ 'aria-hidden' => 'true' ] );

			$icon = ob_get_clean();
		}

		$social = $this->get_social_icon( $slide, $migration_allowed, $is_new, $migrated );

		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'raven-testimonial__icon elementor-icon' );

		$icon .= '<span class="elementor-screen-only">' . esc_html__( 'Read More', 'jupiterx-core' ) . '</span>';
		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'elementor-icon-' . $social );

		// Icon is escaped above, get_render_attribute_string() is safe
		echo '<div ' . $this->get_render_attribute_string( 'icon_wrapper_' . $element_key ) . '>' . $icon . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function print_slide( array $slide, array $settings, $element_key ) {
		$lazyload = 'yes' === $this->get_settings( 'lazyload' );

		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'raven-testimonial',
		] );

		$this->add_render_attribute( $element_key . '-testimonial', [
			'class' => 'raven-repeater-item-' . $slide['_id'],
		] );

		if ( ! empty( $slide['image']['url'] ) ) {
			$img_src              = $this->get_slide_image_url( $slide, $settings );
			$img_attribute['src'] = $img_src;

			if ( $lazyload ) {
				$img_attribute['class']    = 'swiper-lazy';
				$img_attribute['data-src'] = $img_src;

				unset( $img_attribute['src'] );
			}

			$img_attribute['alt'] = $this->get_slide_image_alt_attribute( $slide );

			$this->add_render_attribute( $element_key . '-image', $img_attribute );
		}

		?>
		<div <?php $this->print_render_attribute_string( $element_key . '-testimonial' ); ?>>
			<?php if ( $slide['image']['url'] || ! empty( $slide['name'] ) || ! empty( $slide['title'] ) ) :

				$link_url       = empty( $slide['link']['url'] ) ? false : $slide['link']['url'];
				$header_tag     = ! empty( $link_url ) ? 'a' : 'div';
				$header_element = 'header_' . $slide['_id'];

				$this->add_render_attribute( $header_element, 'class', 'raven-testimonial__header' );

				if ( ! empty( $link_url ) ) {
					$this->add_link_attributes( $header_element, $slide['link'] );
				}
				?>
				<<?php Utils::print_validated_html_tag( $header_tag ); ?> <?php $this->print_render_attribute_string( $header_element ); ?>>
					<?php if ( $slide['image']['url'] ) : ?>
						<div class="raven-testimonial__image">
							<img <?php $this->print_render_attribute_string( $element_key . '-image' ); ?>>
							<?php if ( $lazyload ) : ?>
								<div class="swiper-lazy-preloader"></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php $this->print_cite( $slide, $settings ); ?>
					<?php $this->print_icon( $slide, $element_key ); ?>
				</<?php Utils::print_validated_html_tag( $header_tag ); ?>>
			<?php endif; ?>
			<?php if ( $slide['content'] ) : ?>
				<div class="raven-testimonial__content">
					<div class="raven-testimonial__text">
						<?php
						// Main content allowed
						echo esc_html( $slide['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$this->print_slider();
	}
}
