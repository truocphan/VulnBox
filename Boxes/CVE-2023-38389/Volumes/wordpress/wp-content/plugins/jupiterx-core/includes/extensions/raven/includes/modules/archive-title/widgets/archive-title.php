<?php

namespace JupiterX_Core\Raven\Modules\Archive_Title\Widgets;

use Elementor\Plugin;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Utils as Core_Utils;
use Elementor\Core\Base\Document;

defined( 'ABSPATH' ) || die();

class Archive_Title extends Base_Widget {

	public function get_name() {
		return 'raven-archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-archive-title';
	}

	public function register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'tab' => 'content',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type' => 'textarea',
				'dynamic' => [
					'active' => true,
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text( null, 'archive-title' ),
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
			'size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'small' => esc_html__( 'Small', 'jupiterx-core' ),
					'medium' => esc_html__( 'Medium', 'jupiterx-core' ),
					'large' => esc_html__( 'Large', 'jupiterx-core' ),
					'xl' => esc_html__( 'XL', 'jupiterx-core' ),
					'xxl' => esc_html__( 'XXL', 'jupiterx-core' ),
				],
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
					'{{WRAPPER}} .raven-archive-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-archive-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .raven-archive-title, {{WRAPPER}} .raven-archive-title a',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .raven-archive-title, {{WRAPPER}} .raven-archive-title a',
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .raven-archive-title',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-archive-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

	}

	public function show_in_panel() {
		$post = get_post();

		if ( empty( $post ) ) {
			return false;
		}

		$documents_manager = Plugin::instance()->documents;
		$doc_type          = get_post_meta( $post->ID, Document::TYPE_META_KEY, true );
		$doc_types         = $documents_manager->get_document_types();

		if ( empty( $doc_type ) || ! is_array( $doc_types ) || ! isset( $doc_types[ $doc_type ] ) ) {
			return false;
		}

		return 'archive' === $doc_type || 'product-archive' === $doc_type;
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['title'] ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'raven-archive-title' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'raven-size-' . $settings['size'] );
		}

		$this->add_inline_editing_attributes( 'title' );

		$title = $settings['title'];

		$query = apply_filters( 'jupiterx-raven-posts-query-arguments', false );

		if ( false !== $query ) {
			Plugin::$instance->db->switch_to_query( $query, $force_global_post = true );
			$title = get_the_archive_title();
			Plugin::$instance->db->restore_current_query();
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Core_Utils::validate_html_tag( $settings['header_size'] ), $this->get_render_attribute_string( 'title' ), $title );

		echo wp_kses_post( $title_html );
	}

	protected function content_template() {
		?>
		<#
		var title = settings.title;

		view.addRenderAttribute( 'title', 'class', 'raven-archive-title' );

		if ( '' !== settings.size ) {
			view.addRenderAttribute( 'title', 'class', 'raven-size-' + settings.size );
		}

		if ( '' !== settings.link.url ) {
			view.addRenderAttribute( 'url', settings.link );

			title = '<a ' + view.getRenderAttributeString( 'url' ) + '>' + title + '</a>';
		}

		view.addInlineEditingAttributes( 'title' );

		var headerSizeTag = elementor.helpers.validateHTMLTag( settings.header_size ),
			title_html = '<' + headerSizeTag  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + headerSizeTag + '>';

		print( title_html );
		#>
		<?php
	}
}
