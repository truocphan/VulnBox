<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Post;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Post_Time extends Tag {
	public function get_name() {
		return 'post-time';
	}

	public function get_title() {
		return esc_html__( 'Post Time', 'jupiterx-core' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'post_date_gmt' => esc_html__( 'Post Published', 'jupiterx-core' ),
					'post_modified_gmt' => esc_html__( 'Post Modified', 'jupiterx-core' ),
				],
				'default' => 'post_date_gmt',
			]
		);

		$this->add_control(
			'format',
			[
				'label' => esc_html__( 'Format', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'g:i a' => date( 'g:i a' ),
					'g:i A' => date( 'g:i A' ),
					'H:i' => date( 'H:i' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => esc_html__( 'Custom Format', 'jupiterx-core' ),
				'default' => '',
				'description' => sprintf(
					'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>',
					esc_html__( 'Documentation on date and time formatting', 'jupiterx-core' )
				),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$time_type = $this->get_settings( 'type' );
		$format    = $this->get_settings( 'format' );

		switch ( $format ) {
			case 'default':
				$date_format = '';
				break;
			case 'custom':
				$date_format = $this->get_settings( 'custom_format' );
				break;
			default:
				$date_format = $format;
				break;
		}

		if ( 'post_date_gmt' === $time_type ) {
			$value = get_the_time( $date_format );
		} else {
			$value = get_the_modified_time( $date_format );
		}

		echo wp_kses_post( $value );
	}
}
