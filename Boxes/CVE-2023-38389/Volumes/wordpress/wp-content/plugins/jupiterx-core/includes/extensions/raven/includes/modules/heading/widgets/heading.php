<?php
namespace JupiterX_Core\Raven\Modules\Heading\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Heading extends Base_Widget {

	public function get_name() {
		return 'raven-heading';
	}

	public function get_title() {
		return __( 'Heading', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-heading';
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_heading();
		$this->register_section_ornaments();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => 'textarea',
				'placeholder' => __( 'Enter your title', 'jupiterx-core' ),
				'default' => __( 'Add your text heading here', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'default' => [
					'url' => '',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'h2',
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
			]
		);

		$this->add_control(
			'show_ornaments',
			[
				'label' => __( 'Ornaments', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'default' => 'no',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_heading() {
		$this->start_controls_section(
			'section_heading',
			[
				'label' => __( 'Heading', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'title_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-heading-title, {{WRAPPER}} .raven-heading-title-inner',
			]
		);

		/**
		 * Use HIDDEN control to hack style.
		 */
		$this->add_control(
			'title_color_styles',
			[
				'type' => 'hidden',
				'default' => 'styles',
				'selectors' => [
					'{{WRAPPER}} .raven-heading-title' => '-webkit-background-clip: text; background-clip: text; color: transparent;',
				],
				'condition' => [
					'title_color_background' => 'gradient',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'scheme' => '1',
				'selector' => '{{WRAPPER}} .raven-heading, {{WRAPPER}} .raven-heading a',
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'title_text_shadow_gradient',
				'selector' => '{{WRAPPER}} .raven-heading-title-inner::after',
				'condition' => [
					'title_color_background' => 'gradient',
				],
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .raven-heading',
				'condition' => [
					'title_color_background' => 'solid',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => Utils::get_direction( 'left' ),
				'prefix_class' => 'elementor%s-align-',
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
					'justify' => [
						'title' => __( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_ornaments() {
		$this->start_controls_section(
			'section_ornaments',
			[
				'label' => __( 'Ornaments', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'show_ornaments' => 'yes',
				],
			]
		);

		$this->add_control(
			'ornament_type',
			[
				'label' => __( 'Ornament Style', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'rovi-single',
				'options' => [
					'rovi-single' => __( 'Rovi Single', 'jupiterx-core' ),
					'rovi-double' => __( 'Rovi Double', 'jupiterx-core' ),
					'norman-single' => __( 'Norman Single', 'jupiterx-core' ),
					'norman-double' => __( 'Norman Double', 'jupiterx-core' ),
					'norman-short-single' => __( 'Norman Short Single', 'jupiterx-core' ),
					'norman-short-double' => __( 'Norman Short Double', 'jupiterx-core' ),
					'lemo-single' => __( 'Lemo Single', 'jupiterx-core' ),
					'lemo-double' => __( 'Lemo Double', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'ornament_thickness',
			[
				'label' => __( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [ '30px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 8,
					],
				],
				// @codingStandardsIgnoreStart
				'selectors' => [
					'{{WRAPPER}} .raven-heading-lemo-double .raven-heading-title:before' => 'width: 100%; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading-lemo-double .raven-heading-title:after' => 'width: 100%; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading:after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading-title' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading-title' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading-title:before' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-heading-title:after' => 'width: {{SIZE}}{{UNIT}};',
				],
				// @codingStandardsIgnoreEnd
			]
		);

		$this->add_control(
			'ornament_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				// @codingStandardsIgnoreStart
				'selectors' => [
					'{{WRAPPER}} .raven-heading:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-heading:after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-heading-title' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-heading-title' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-heading-title:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .raven-heading-title:after' => 'background-color: {{VALUE}};',
				],
				// @codingStandardsIgnoreEnd
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title'] ) ) {
			return;
		}

		$raven_animated_gradient = '';

		if ( array_key_exists( 'raven_animated_gradient_enable', $settings ) && 'yes' === $settings['raven_animated_gradient_enable'] ) {
			$raven_animated_gradient = 'raven-animated-gradient';

			$color_list = $settings['raven_animated_gradient_color_list'];
			$speed      = '';
			$direction  = '';

			if ( array_key_exists( 'raven_animated_gradient_direction', $settings ) ) {
				$direction = $settings['raven_animated_gradient_direction'];
			}

			if ( array_key_exists( 'raven_animated_gradient_speed', $settings ) ) {
				$speed = $settings['raven_animated_gradient_speed']['size'] . 's';
			}

			$animated_gradient_attributes = Utils::get_animated_gradient_attributes( $direction, $color_list );

			$data_background_size = $animated_gradient_attributes['data_background_size'];
			$data_animation_name  = $animated_gradient_attributes['data_animation_name'];
			$angle                = $animated_gradient_attributes['angle'];

			$color = [];
			$count = count( $color_list );

			for ( $i = 0; $i < $count; $i++ ) {
				$color[ $i ] = $color_list[ $i ]['raven_animated_gradient_color'];
			}

			array_push( $color, $color_list[0]['raven_animated_gradient_color'], $color_list[1]['raven_animated_gradient_color'] );

			$color   = implode( ',', $color );
			$element = 'title';

			if ( 'gradient' === $settings['title_color_background'] ) {
				$element = 'title-inner';
			}

			$this->add_render_attribute( $element, 'data-color', $color );
			$this->add_render_attribute( $element, 'data-angle', $angle );
			$this->add_render_attribute( $element, 'data-speed', $speed );
			$this->add_render_attribute( $element, 'data-animation-name', $data_animation_name );
			$this->add_render_attribute( $element, 'data-background-size', $data_background_size );
		}

		$this->add_render_attribute( 'heading', 'class', 'raven-heading raven-heading-' . $settings['html_tag'] );

		$this->add_render_attribute( 'title', 'class', 'raven-heading-title ' . $raven_animated_gradient );

		if ( 'yes' === $settings['show_ornaments'] ) {
			$this->add_render_attribute( 'heading', 'class', 'raven-heading-' . $settings['ornament_type'] );
		}

		$title_html = $settings['title'];

		if ( 'gradient' === $settings['title_color_background'] ) {
			$this->add_render_attribute( 'title-inner', 'class', 'raven-heading-title-inner elementor-inline-editing ' . $raven_animated_gradient );

			$this->add_render_attribute( 'title-inner', 'data-text', esc_html( $settings['title'] ) );

			$this->add_render_attribute( 'title-inner', 'data-elementor-setting-key', 'title' );

			$title_html = '<span ' . $this->get_render_attribute_string( 'title-inner' ) . '>' . $title_html . '</span>';
		} else {
			$this->add_inline_editing_attributes( 'title' );
		}

		$title_html = '<span ' . $this->get_render_attribute_string( 'title' ) . '>' . $title_html . '</span>';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

			$this->render_link_properties( $this, $settings['link'], 'url' );

			$title_html = sprintf(
				'<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( 'url' ),
				$title_html
			);
		}

		$heading_html = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			$settings['html_tag'],
			$this->get_render_attribute_string( 'heading' ),
			$title_html
		);
		?>
		<div class="raven-widget-wrapper"><?php echo $heading_html; ?></div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		raven_animated_gradient = '';

		if ( 'yes' === settings.raven_animated_gradient_enable ) {
			raven_animated_gradient = 'raven-animated-gradient';

			color_list = settings.raven_animated_gradient_color_list;
			angle = '';
			speed = '';

			if ( settings.raven_animated_gradient_direction ) {
				direction = settings.raven_animated_gradient_direction;
			}

			if ( settings.raven_animated_gradient_speed ) {
				speed = settings.raven_animated_gradient_speed.size + 's';
			}

			background_size_color_count = ( color_list.length + 1 ) * 100;

			if ( 'left' === direction ) {
				background = background_size_color_count + '%' + ' 100%';
				animation  = 'AnimatedGradientBgLeft';
				angle      = '90deg';
			} else if ( 'right' == direction ) {
				background = background_size_color_count + '%' + ' 100%';
				animation  = 'AnimatedGradientBgRight';
				angle      = '90deg';
			} else if ( 'up' == direction ) {
				background = '100% ' + background_size_color_count + '%';
				animation  = 'AnimatedGradientBgUp';
				angle      = '0deg';
			} else if ( 'down' == direction ) {
				background = '100% ' + background_size_color_count + '%';
				animation  = 'AnimatedGradientBgDown';
				angle      = '0deg';
			}

			var color = [];
			var i = 0;
			_.each(color_list , function(color_list){
					color[i] = color_list.raven_animated_gradient_color;
					i = i+1;
			});

			color.push( color_list[0].raven_animated_gradient_color, color_list[1].raven_animated_gradient_color );

			color   = color.join();
			element = 'title';

			if ( 'gradient' === settings.title_color_background ) {
				element = 'title-inner';
			}

			view.addRenderAttribute( element, 'data-color', color );
			view.addRenderAttribute( element, 'data-angle', angle );
			view.addRenderAttribute( element, 'data-speed', speed );
			view.addRenderAttribute( element, 'data-animation-name', animation );
			view.addRenderAttribute( element, 'data-background-size', background );
		}

		view.addRenderAttribute( 'heading', 'class', 'raven-heading raven-heading-' + settings.html_tag );

		view.addRenderAttribute( 'title', 'class', 'raven-heading-title ' + raven_animated_gradient );

		if ( 'yes' === settings.show_ornaments ) {
			view.addRenderAttribute( 'heading', 'class', 'raven-heading-' + settings.ornament_type );
		}

		var title_html = settings.title

		if ( 'gradient' === settings.title_color_background ) {
			view.addRenderAttribute( 'title-inner', 'class', 'raven-heading-title-inner elementor-inline-editing ' + raven_animated_gradient );

			view.addRenderAttribute( 'title-inner', 'data-text', settings.title );

			view.addRenderAttribute( 'title-inner', 'data-elementor-setting-key', 'title' );

			title_html = '<span ' + view.getRenderAttributeString( 'title-inner' ) + '>' + title_html + '</span>';
		} else {
			view.addInlineEditingAttributes( 'title' );
		}

		title_html = '<span ' + view.getRenderAttributeString( 'title' ) + '>' + title_html + '</span>';

		if ( '' !== settings.link.url ) {
			title_html = '<a href="' + settings.link.url + '">' + title_html + '</a>';
		}

		var heading_html = '<div class="raven-widget-wrap">' +
			'<' + settings.html_tag  + ' ' + view.getRenderAttributeString( 'heading' ) + '>' +
			title_html +
			'</' + settings.html_tag + '>' +
			'</div>';

		print( heading_html );
		#>
		<?php
	}
}
