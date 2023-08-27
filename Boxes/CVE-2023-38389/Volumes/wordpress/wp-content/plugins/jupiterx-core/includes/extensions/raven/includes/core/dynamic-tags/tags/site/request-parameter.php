<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class Request_Parameter extends Tag {
	public function get_name() {
		return 'request-arg';
	}

	public function get_title() {
		return esc_html__( 'Request Parameter', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'request_type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type'  => 'select',
				'default' => 'get',
				'options' => [
					'get' => esc_html__( 'Get', 'jupiterx-core' ),
					'post' => esc_html__( 'Post', 'jupiterx-core' ),
					'query_var' => esc_html__( 'Query Var', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'param_name',
			[
				'label' => esc_html__( 'Parameter Name', 'jupiterx-core' ),
				'type'  => 'text',
			]
		);
	}

	public function render() {
		$settings     = $this->get_settings();
		$request_type = $settings['request_type'];
		$param_name   = $settings['param_name'];
		$value        = '';

		if ( empty( $request_type ) || empty( $param_name ) ) {
			return;
		}

		switch ( $request_type ) {
			case 'get':
				$value = filter_input( INPUT_GET, $param_name );
				break;
			case 'post':
				$value = filter_input( INPUT_POST, $param_name );
				break;
			case 'query_var':
				$value = get_query_var( $param_name );
				break;
		}

		echo htmlentities( wp_kses_post( $value ) );
	}
}
