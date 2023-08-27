<?php
namespace JupiterX_Core\Raven\Modules\Post_Content\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Post_Content\Skins;
use Elementor\Plugin;
use Elementor\Core\Base\Document;

defined( 'ABSPATH' ) || die();

class Post_Content extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-post-content';
	}

	public function get_title() {
		return __( 'Post Content', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-content';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'text_alignment',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
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
						'title' => __( 'Justified', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}}',
				'scheme' => '3',
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
		$jx_layout         = get_post_meta( $post->ID, 'jx-layout-type', true );
		$doc_types         = $documents_manager->get_document_types();
		$layout_builder    = filter_input( INPUT_GET, 'layout-builder', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( $jx_layout ) || ! empty( $layout_builder ) ) {
			return true;
		}

		if ( empty( $doc_type ) || ! is_array( $doc_types ) || ! isset( $doc_types[ $doc_type ] ) ) {
			return false;
		}

		return 'single' === $doc_type;
	}

	/**
	 * * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function render() {
		static $did_posts = [];

		$post = get_post();

		// Avoid recursion
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );

			return;
		}

		if ( Plugin::instance()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::instance()->preview->builder_wrapper( '' );
		} else {
			$documents_manager = Plugin::instance()->documents;
			$document          = $documents_manager->get( $post->ID );

			if ( $document ) {
				$preview_type = $document->get_settings( 'preview_type' );
				$preview_id   = $document->get_settings( 'preview_id' );

				if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
					$post = get_post( $preview_id );

					if ( ! $post ) {
						return;
					}
				}
			}

			$editor = Plugin::instance()->editor;

			$is_edit_mode = $editor->is_edit_mode();

			if ( empty( $content ) && 'elementor_library' === get_post_type( get_the_ID() ) ) {
				esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' );
				return;
			}

			$editor->set_edit_mode( false );

			$content = Plugin::instance()->frontend->get_builder_content( $post->ID, true );

			$editor->set_edit_mode( $is_edit_mode );

			if ( empty( $content ) ) {
				Plugin::instance()->frontend->remove_content_filter();

				setup_postdata( $post );

				$content = apply_filters( 'the_content', get_the_content() );

				Plugin::instance()->frontend->add_content_filter();
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		}

		echo $content;
	}

	public function render_plain_content() {}
}
