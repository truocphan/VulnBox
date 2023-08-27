<?php

namespace JupiterX_Core\Raven\Modules\Archive_Description\Widgets;

use Elementor\Core\Base\Document;
use Elementor\Plugin;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Archive_Description extends Base_Widget {

	public function get_name() {
		return 'raven-archive-description';
	}

	public function get_title() {
		return esc_html__( 'Archive Description', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-archive-description';
	}

	public function register_controls() {
		$this->start_controls_section(
			'section_archive_description_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				'selectors' => [
					'{{WRAPPER}} .raven-archive-description' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-archive-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-archive-description',
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

		if (
			empty( $doc_type ) ||
			! is_array( $doc_types ) ||
			! isset( $doc_types[ $doc_type ] )
		) {
			return false;
		}

		return 'archive' === $doc_type || 'product-archive' === $doc_type;
	}

	protected function render() {
		$description = wp_kses_post( get_the_archive_description() );
		$query       = apply_filters( 'jupiterx-raven-posts-query-arguments', false );

		if ( false !== $query ) {
			Plugin::$instance->db->switch_to_query( $query, $force_global_post = true );

			$description = wp_kses_post( get_the_archive_description() );

			Plugin::$instance->db->restore_current_query();
		}

		?>
		<div class="raven-archive-description">
			<?php echo $description; ?>
		</div>
		<?php
	}
}
