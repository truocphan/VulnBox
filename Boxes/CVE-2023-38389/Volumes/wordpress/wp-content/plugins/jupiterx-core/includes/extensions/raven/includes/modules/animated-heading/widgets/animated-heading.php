<?php
namespace JupiterX_Core\Raven\Modules\Animated_Heading\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Animated_Heading extends Base_Widget {

	public function get_name() {
		return 'raven-animated-heading';
	}

	public function get_title() {
		return esc_html__( 'Animated Heading', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-animated-heading';
	}

	protected function register_controls() {
		$this->register_section_heading();
		$this->register_section_style_shape();
		$this->register_section_style_heading();
	}

	private function register_section_heading() {
		$this->start_controls_section(
			'section_heading',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'heading_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'highlight',
				'options' => [
					'highlight' => esc_html__( 'Highlighted', 'jupiterx-core' ),
					'rotate' => esc_html__( 'Rotating', 'jupiterx-core' ),
				],
				'prefix_class' => 'raven-heading--style-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label' => esc_html__( 'Animation', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'typing' => 'Typing',
					'clip' => 'Clip',
					'flip' => 'Flip',
					'swirl' => 'Swirl',
					'blinds' => 'Blinds',
					'drop-in' => 'Drop-in',
					'wave' => 'Wave',
					'slide' => 'Slide',
					'slide-down' => 'Slide Down',
				],
				'default' => 'typing',
				'condition' => [
					'heading_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'marker',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'circle',
				'options' => [
					'circle' => esc_html_x( 'Circle', 'Shapes', 'jupiterx-core' ),
					'curly' => esc_html_x( 'Curly', 'Shapes', 'jupiterx-core' ),
					'underline' => esc_html_x( 'Underline', 'Shapes', 'jupiterx-core' ),
					'double' => esc_html_x( 'Double', 'Shapes', 'jupiterx-core' ),
					'double_underline' => esc_html_x( 'Double Underline', 'Shapes', 'jupiterx-core' ),
					'underline_zigzag' => esc_html_x( 'Underline Zigzag', 'Shapes', 'jupiterx-core' ),
					'diagonal' => esc_html_x( 'Diagonal', 'Shapes', 'jupiterx-core' ),
					'strikethrough' => esc_html_x( 'Strikethrough', 'Shapes', 'jupiterx-core' ),
					'x' => 'X',
				],
				'render_type' => 'template',
				'condition' => [
					'heading_style' => 'highlight',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'before_text',
			[
				'label' => esc_html__( 'Before Text', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'default' => esc_html__( 'This page is', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Enter your heading', 'jupiterx-core' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlighted_text',
			[
				'label' => esc_html__( 'Highlighted Text', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'default' => esc_html__( 'Amazing', 'jupiterx-core' ),
				'label_block' => true,
				'condition' => [
					'heading_style' => 'highlight',
				],
				'separator' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'rotating_text',
			[
				'label' => esc_html__( 'Rotating Text', 'jupiterx-core' ),
				'type' => 'textarea',
				'placeholder' => esc_html__( 'Enter each word in a separate line', 'jupiterx-core' ),
				'separator' => 'none',
				'default' => "Better\nBigger\nFaster",
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'condition' => [
					'heading_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'after_text',
			[
				'label' => esc_html__( 'After Text', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your heading', 'jupiterx-core' ),
				'label_block' => true,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--iteration-count: infinite',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlight_animation_duration',
			[
				'label' => esc_html__( 'Duration', 'jupiterx-core' ) . ' (ms)',
				'type' => 'number',
				'default' => 1200,
				'render_type' => 'template',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--animation-duration: {{VALUE}}ms',
				],
				'condition' => [
					'heading_style' => 'highlight',
				],
			]
		);

		$this->add_control(
			'highlight_iteration_delay',
			[
				'label' => esc_html__( 'Delay', 'jupiterx-core' ) . ' (ms)',
				'type' => 'number',
				'default' => 8000,
				'render_type' => 'template',
				'frontend_available' => true,
				'condition' => [
					'heading_style' => 'highlight',
					'loop' => 'yes',
				],
			]
		);

		$this->add_control(
			'rotate_iteration_delay',
			[
				'label' => esc_html__( 'Duration', 'jupiterx-core' ) . ' (ms)',
				'type' => 'number',
				'default' => 2500,
				'render_type' => 'template',
				'frontend_available' => true,
				'condition' => [
					'heading_style' => 'rotate',
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
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-heading' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tag',
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
				'default' => 'h3',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_style_shape() {
		$this->start_controls_section(
			'section_style_marker',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'heading_style' => 'highlight',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-heading-dynamic-wrapper path' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'stroke_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-heading-dynamic-wrapper path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'above_content',
			[
				'label' => esc_html__( 'Bring to Front', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-heading-dynamic-wrapper svg' => 'z-index: 2',
					'{{WRAPPER}} .raven-heading-dynamic-text' => 'z-index: auto',
				],
			]
		);

		$this->add_control(
			'rounded_edges',
			[
				'label' => esc_html__( 'Rounded Edges', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-heading-dynamic-wrapper path' => 'stroke-linecap: round; stroke-linejoin: round',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_style_heading() {
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-heading-plain-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .raven-heading',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'heading_text_stroke',
				'selector' => '{{WRAPPER}} .raven-heading-plain-text',
			]
		);

		$this->add_control(
			'heading_words_style',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Animated Text', 'jupiterx-core' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'words_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--dynamic-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'words_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .raven-heading-dynamic-text',
				'exclude' => [ 'font_size' ],
			]
		);

		$this->add_control(
			'typing_animation_highlight_colors',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Selected Text', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'heading_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'highlighted_text_background_color',
			[
				'label' => esc_html__( 'Selection Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-bg-color: {{VALUE}}',
				],
				'condition' => [
					'heading_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'highlighted_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}}' => '--typing-selected-color: {{VALUE}}',
				],
				'condition' => [
					'heading_style' => 'rotate',
					'animation_type' => 'typing',
				],
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'animated_text_stroke',
				'selector' => '{{WRAPPER}} .raven-heading-dynamic-text',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tag      = Utils::validate_html_tag( $settings['tag'] );

		$this->add_render_attribute( 'heading', 'class', 'raven-heading' );

		if ( 'rotate' === $settings['heading_style'] ) {
			$this->add_render_attribute( 'heading', 'class', 'raven-heading-animation-type-' . $settings['animation_type'] );

			$is_letter_animation = in_array( $settings['animation_type'], [ 'typing', 'swirl', 'blinds', 'wave' ], true );

			if ( $is_letter_animation ) {
				$this->add_render_attribute( 'heading', 'class', 'raven-heading-letters' );
			}
		}

		$this->render_heading( $tag, $settings );
	}

	protected function content_template() {
		?>
		<#
		var headingClasses = 'raven-heading',
		tag = elementor.helpers.validateHTMLTag( settings.tag );

		if ( 'rotate' === settings.heading_style ) {
			headingClasses += ' raven-heading-animation-type-' + settings.animation_type;

			var isLetterAnimation = -1 !== [ 'typing', 'swirl', 'blinds', 'wave' ].indexOf( settings.animation_type );

			if ( isLetterAnimation ) {
				headingClasses += ' raven-heading-letters';
			}
		}

		if ( settings.link.url ) { #>
			<a href="#">
		<# } #>
			<{{{ tag }}} class="{{{ headingClasses }}}">
				<# if ( settings.before_text ) { #>
					<span class="raven-heading-plain-text raven-heading-text-wrapper">{{{ settings.before_text }}}</span>
				<# } #>

				<# if ( settings.rotating_text ) { #>
					<span class="raven-heading-dynamic-wrapper raven-heading-text-wrapper">
						<# if ( 'rotate' === settings.heading_style && settings.rotating_text ) {
							var rotatingText = ( settings.rotating_text || '' ).split( '\n' );
							for ( var i = 0; i < rotatingText.length; i++ ) {
								var statusClass = 0 === i ? 'raven-heading-text-active' : ''; #>
								<span class="raven-heading-dynamic-text {{ statusClass }}">
									{{{ rotatingText[ i ].replace( ' ', '&nbsp;' ) }}}
								</span>
							<# }
						}

						else if ( 'highlight' === settings.heading_style && settings.highlighted_text ) { #>
							<span class="raven-heading-dynamic-text raven-heading-text-active">{{{ settings.highlighted_text }}}</span>
						<# } #>
					</span>
				<# } #>

				<# if ( settings.after_text ) { #>
					<span class="raven-heading-plain-text raven-heading-text-wrapper">{{{ settings.after_text }}}</span>
				<# } #>
			</{{{ tag }}}>
		<# if ( settings.link.url ) { #>
			</a>
		<# } #>
		<?php
	}

	protected function render_heading( $tag, $settings ) {
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			echo '<a ' . $this->get_render_attribute_string( 'url' ) . '>';
		}

		?>
		<<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
		<?php if ( ! empty( $settings['before_text'] ) ) : ?>
			<span class="raven-heading-plain-text raven-heading-text-wrapper"><?php echo $settings['before_text']; ?></span>
		<?php endif; ?>
		<span class="raven-heading-dynamic-wrapper raven-heading-text-wrapper">
		<?php if ( 'rotate' === $settings['heading_style'] && $settings['rotating_text'] ) :
			$rotating_text = explode( "\n", $settings['rotating_text'] );
			foreach ( $rotating_text as $key => $text ) :
				$status_class = 1 > $key ? 'raven-heading-text-active' : ''; ?>
				<span class="raven-heading-dynamic-text <?php echo $status_class; ?>">
					<?php echo str_replace( ' ', '&nbsp;', $text ); ?>
				</span>
			<?php endforeach; ?>
		<?php elseif ( 'highlight' === $settings['heading_style'] && ! empty( $settings['highlighted_text'] ) ) : ?>
			<span class="raven-heading-dynamic-text raven-heading-text-active"><?php echo $settings['highlighted_text']; ?></span>
		<?php endif ?>
		</span>
		<?php if ( ! empty( $settings['after_text'] ) ) : ?>
			<span class="raven-heading-plain-text raven-heading-text-wrapper"><?php echo $settings['after_text']; ?></span>
		<?php endif; ?>
		</<?php echo $tag; ?>>
		<?php

		if ( ! empty( $settings['link']['url'] ) ) {
			echo '</a>';
		}
	}
}
