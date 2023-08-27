<?php

namespace JupiterX_Core\Raven\Modules\Author_Box\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Utils;

/**
* @SuppressWarnings(PHPMD.NPathComplexity)
*/
class Author_Box extends Base_Widget {

	public function get_name() {
		return 'raven-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-author-box';
	}

	protected function register_controls() {
		$this->register_section_author_info();
		$this->register_section_author_style_image();
		$this->register_section_author_style_text();
		$this->register_section_author_style_button();
	}

	private function register_section_author_info() {

		$this->start_controls_section(
			'section_author_info',
			[
				'label' => esc_html__( 'Author Info', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Author Avatar', 'jupiterx-core' ),
				'type' => 'switcher',
				'prefix_class' => 'raven-author-box-avatar-',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => esc_html__( 'Display Name', 'jupiterx-core' ),
				'type' => 'switcher',
				'prefix_class' => 'raven-author-box-name-',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_tag',
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
				],
				'default' => 'h4',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'website' => esc_html__( 'Website', 'jupiterx-core' ),
					'posts_archive' => esc_html__( 'Posts Archive', 'jupiterx-core' ),
				],
				'description' => esc_html__( 'Link for the Author Name and Image', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'show_biography',
			[
				'label' => esc_html__( 'Biography', 'jupiterx-core' ),
				'type' => 'switcher',
				'prefix_class' => 'raven-author-box-biography-',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_link',
			[
				'label' => esc_html__( 'Archive Button', 'jupiterx-core' ),
				'type' => 'switcher',
				'prefix_class' => 'raven-author-box-link-',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'default' => 'no',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => esc_html__( 'Archive Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'All Posts', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'above' => [
						'title' => esc_html__( 'Above', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'separator' => 'before',
				'prefix_class' => 'raven-author-box-layout-image-',
			]
		);

		$this->add_control(
			'alignment',
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
				],
				'prefix_class' => 'raven-author-box-align-',
			]
		);

		$this->end_controls_section();

	}

	private function register_section_author_style_image() {

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'image_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
				],
				'condition' => [
					'layout!' => 'above',
				],
				'selectors_dictionary' => [
					'top' => '-ms-flex-item-align: start;align-self: flex-start;',
					'middle' => '-ms-flex-item-align: center;align-self: center;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
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
					'body.rtl {{WRAPPER}}.raven-author-box-layout-image-left .raven-author-box-avatar,
					 body:not(.rtl) {{WRAPPER}}:not(.raven-author-box-layout-image-above) .raven-author-box-avatar' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
					'body:not(.rtl) {{WRAPPER}}.raven-author-box-layout-image-right .raven-author-box-avatar,
					 body.rtl {{WRAPPER}}:not(.raven-author-box-layout-image-above) .raven-author-box-avatar' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right:0;',
					'{{WRAPPER}}.raven-author-box-layout-image-above .raven-author-box-avatar' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'image_border',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar img' => 'border-style: solid',
				],
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar img' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-avatar img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .raven-author-box-avatar img',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_section();

	}

	private function register_section_author_style_text() {

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'heading_name_style',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} a .raven-author-box-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .raven-author-box-name',
			]
		);

		$this->add_responsive_control(
			'name_gap',
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
					'{{WRAPPER}} .raven-author-box-name' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_bio_style',
			[
				'label' => esc_html__( 'Biography', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-bio' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'bio_typography',
				'selector' => '{{WRAPPER}} .raven-author-box-bio',
			]
		);

		$this->add_responsive_control(
			'bio_gap',
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
					'{{WRAPPER}} .raven-author-box-bio' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

	}

	private function register_section_author_style_button() {
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => 'Button',
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .raven-author-box-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button:hover' => 'border-color: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'link_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition' => [
					'link_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .raven-author-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$user_id = get_the_author_meta( 'ID' );

		// Integrate with layout builder.
		if ( empty( $user_id ) ) {
			global $post;
			$user_id = $post->post_author;
		}

		$settings               = $this->get_active_settings();
		$author                 = [];
		$link_tag               = 'div';
		$link_url               = '';
		$link_target            = '';
		$author_name_tag        = Utils::validate_html_tag( $settings['author_name_tag'] );
		$avatar_args['size']    = 300;
		$author['avatar']       = get_avatar_url( $user_id, $avatar_args );
		$author['display_name'] = get_the_author_meta( 'display_name', $user_id );
		$author['website']      = get_the_author_meta( 'user_url', $user_id );
		$author['bio']          = get_the_author_meta( 'description', $user_id );
		$author['posts_url']    = get_author_posts_url( $user_id );
		$print_avatar           = ( 'yes' === $settings['show_avatar'] );
		$print_name             = ( 'yes' === $settings['show_name'] );
		$print_bio              = ( 'yes' === $settings['show_biography'] );
		$print_link             = ( ( 'yes' === $settings['show_link'] ) && ! empty( $settings['link_text'] ) );

		if ( ! empty( $settings['link_to'] ) ) {
			if ( ( 'website' === $settings['link_to'] ) && ! empty( $author['website'] ) ) {
				$link_tag    = 'a';
				$link_url    = $author['website'];
				$link_target = '_blank';
			} elseif ( 'posts_archive' === $settings['link_to'] && ! empty( $author['posts_url'] ) ) {
				$link_tag = 'a';
				$link_url = $author['posts_url'];
			}

			if ( ! empty( $link_url ) ) {
				$this->add_render_attribute( 'author_link', 'href', $link_url );

				if ( ! empty( $link_target ) ) {
					$this->add_render_attribute( 'author_link', 'target', $link_target );
				}
			}
		}

		$this->add_render_attribute(
			'button',
			'class',
			[
				'raven-author-box-button',
				'elementor-button',
				'elementor-size-xs',
			]
		);

		if ( $print_link ) {
			$this->add_render_attribute( 'button', 'href', $author['posts_url'] );
		}

		if ( $print_avatar ) {
			$this->add_render_attribute( 'avatar', 'src', $author['avatar'] );

			if ( ! empty( $author['display_name'] ) ) {
				$this->add_render_attribute( 'avatar', 'alt', $author['display_name'] );
			}
		}

		?>
		<div class= "raven-author-box">
			<?php if ( $print_avatar ) { ?>
				<<?php Utils::print_validated_html_tag( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?> class="raven-author-box-avatar">
					<img <?php $this->print_render_attribute_string( 'avatar' ); ?>>
				</<?php Utils::print_validated_html_tag( $link_tag ); ?>>
			<?php } ?>

			<div class= "raven-author-box-text">
				<?php if ( $print_name ) : ?>
					<<?php Utils::print_validated_html_tag( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?>>
						<<?php Utils::print_validated_html_tag( $author_name_tag ); ?> class="raven-author-box-name">
							<?php Utils::print_unescaped_internal_string( $author['display_name'] ); ?>
						</<?php Utils::print_validated_html_tag( $author_name_tag ); ?>>
					</<?php Utils::print_validated_html_tag( $link_tag ); ?>>
				<?php endif; ?>

				<?php if ( $print_bio ) : ?>
					<div class= "raven-author-box-bio">
						<?php Utils::print_unescaped_internal_string( $author['bio'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $print_link ) : ?>
					<a <?php $this->print_render_attribute_string( 'button' ); ?>>
						<?php $this->print_unescaped_setting( 'link_text' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
