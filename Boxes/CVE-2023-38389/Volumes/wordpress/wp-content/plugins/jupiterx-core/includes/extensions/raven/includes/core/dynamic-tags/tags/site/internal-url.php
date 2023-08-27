<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Data_Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Internal_URL extends Data_Tag {

	// The following special category is set exclusively for Advanced Menu widget in order to limit available dynamic tags for user.
	const ONLY_INTERNAL_URL = 'only_internal_url';

	public function get_name() {
		return 'internal-url';
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			$this::ONLY_INTERNAL_URL,
		];
	}

	public function get_title() {
		return esc_html__( 'Internal URL', 'jupiterx-core' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					'post'     => esc_html__( 'Content', 'jupiterx-core' ),
					'taxonomy' => esc_html__( 'Taxonomy', 'jupiterx-core' ),
					'author'   => esc_html__( 'Author', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => esc_html__( 'Search & Select', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => true,
				'query'       => [
					'source'    => Query::QUERY_SOURCE_POST,
					'post_type' => 'any',
				],
				'condition'   => [
					'type' => 'post',
				],
			]
		);

		$this->add_control(
			'taxonomy_id',
			[
				'label'       => esc_html__( 'Search & Select', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => true,
				'query'       => [
					'source'    => Query::QUERY_SOURCE_TAX,
					'post_type' => 'any',
				],
				'condition'   => [
					'type' => 'taxonomy',
				],
			]
		);

		$this->add_control(
			'author_id',
			[
				'label'       => esc_html__( 'Search & Select', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => true,
				'query'       => [
					'source'    => Query::QUERY_SOURCE_AUTHOR,
					'post_type' => 'any',
				],
				'condition'   => [
					'type' => 'author',
				],
			]
		);

		// TODO: At this point, E Pro also adds query control for "Attachment", But JupiterX
		// query control does not yet support Attachment. It should be added in future.
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$settings = $this->get_settings();
		$type     = $settings['type'];
		$url      = '';

		if ( 'post' === $type && ! empty( $settings['post_id'] ) ) {
			$url = get_permalink( (int) $settings['post_id'] );
		}

		if ( 'taxonomy' === $type && ! empty( $settings['taxonomy_id'] ) ) {
			$url = get_term_link( (int) $settings['taxonomy_id'] );
		}

		if ( 'attachment' === $type && ! empty( $settings['attachment_id'] ) ) {
			$url = get_attachment_link( (int) $settings['attachment_id'] );
		}

		if ( 'author' === $type && ! empty( $settings['author_id'] ) ) {
			$url = get_author_posts_url( (int) $settings['author_id'] );
		}

		return ! is_wp_error( $url ) ? $url : '';
	}
}
