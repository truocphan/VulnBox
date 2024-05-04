<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class piotnetforms_Text extends Base_Widget_Piotnetforms {

	public function get_type() {
		return 'text';
	}

	public function get_class_name() {
		return 'piotnetforms_Text';
	}

	public function get_title() {
		return 'Text';
	}

	public function get_icon() {
		return [
			'type' => 'image',
			'value' => plugin_dir_url( __FILE__ ) . '../../assets/icons/i-text.svg',
		];
	}

	public function get_categories() {
		return [ 'pafe-form-builder' ];
	}

	public function get_keywords() {
		return [ 'text' ];
	}

	private function add_setting_controls() {
		$this->add_control(
			'text_content',
			[
				'type'        => 'textarea',
				'label'       => 'Content',
				'value'       => 'Text',
				'label_block' => true,
				'placeholder' => '',
			]
		);
		$this->add_control(
			'text_html_tag',
			[
				'type'    => 'select',
				'label'   => 'Tag',
				'value'   => 'h2',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'type'         => 'select',
				'label'        => 'Alignment',
				'label_block'  => true,
				'options'      => [
					''        => __( 'Default', 'piotnetforms' ),
					'left'    => __( 'Left', 'piotnetforms' ),
					'center'  => __( 'Center', 'piotnetforms' ),
					'right'   => __( 'Right', 'piotnetforms' ),
					'justify' => __( 'Justified', 'piotnetforms' ),
				],
				'prefix_class' => 'piotnetforms%s-align-',
				'selectors'    => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);
	}

	private function add_style_controls() {
		$this->add_control(
			'text_color',
			[
				'type'      => 'color',
				'label'     => 'Text Color',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_text_typography_controls(
			'text_typography',
			[
				'selectors' => '{{WRAPPER}}',
			]
		);
	}

	public function register_controls() {
		$this->start_tab( 'settings', 'Settings' );

		$this->start_section( 'text_settings_section', 'Settings' );
		$this->add_setting_controls();

		$this->start_tab( 'style', 'Style' );
		$this->start_section( 'text_styles_section', 'Style' );
		$this->add_style_controls();

		$this->add_advanced_tab();

		return $this->structure;
	}

	public function render() {
		$settings = $this->settings;
		if ( ! empty( $settings['text_content'] ) ) {
			echo '<' . $settings['text_html_tag'] . ' ' . $this->get_render_attribute_string( 'wrapper' ) . '>' . $settings['text_content'] . '</' . $settings['text_html_tag'] . '>';
		}
	}

	public function live_preview() {
		?>
		<<%=data['widget_settings']['text_html_tag']%> <%= view.render_attributes('wrapper') %>><%= data['widget_settings']['text_content'] %></<%=data['widget_settings']['text_html_tag'] %>>
		<?php
	}
}
