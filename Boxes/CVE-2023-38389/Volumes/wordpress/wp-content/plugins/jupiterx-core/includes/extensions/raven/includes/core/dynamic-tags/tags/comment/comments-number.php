<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Comment;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class Comments_Number extends Tag {

	public function get_name() {
		return 'comments-number';
	}

	public function get_title() {
		return esc_html__( 'Comments Number', 'jupiterx-core' );
	}

	public function get_group() {
		return 'comment';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'format_no_comments',
			[
				'label'   => esc_html__( 'No Comments Format', 'jupiterx-core' ),
				'default' => esc_html__( 'No Responses', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'format_one_comments',
			[
				'label'   => esc_html__( 'One Comment Format', 'jupiterx-core' ),
				'default' => esc_html__( 'One Response', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'format_many_comments',
			[
				'label'   => esc_html__( 'Many Comment Format', 'jupiterx-core' ),
				'default' => esc_html__( '{number} Responses', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => esc_html__( 'Link', 'jupiterx-core' ),
				'type'    => 'select',
				'default' => '',
				'options' => [
					''              => esc_html__( 'None', 'jupiterx-core' ),
					'comments_link' => esc_html__( 'Comments Link', 'jupiterx-core' ),
				],
			]
		);
	}

	public function render() {
		$settings        = $this->get_settings();
		$comments_number = intval( get_comments_number() );

		if ( ! $comments_number ) {
			$count = $settings['format_no_comments'];
		} elseif ( 1 === $comments_number ) {
			$count = $settings['format_one_comments'];
		} else {
			$count = strtr( $settings['format_many_comments'], [
				'{number}' => number_format_i18n( $comments_number ),
			] );
		}

		if ( 'comments_link' === $this->get_settings( 'link_to' ) ) {
			/* translators: %1$s: comments link, %2$s: comments number */
			$count = sprintf( '<a href="%1$s">%2$s</a>', get_comments_link(), $count );
		}

		echo wp_kses_post( $count );
	}
}
