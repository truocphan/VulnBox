<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return esc_html__( 'User Info', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Field', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					''             => esc_html__( 'Choose', 'jupiterx-core' ),
					'id'           => esc_html__( 'ID', 'jupiterx-core' ),
					'display_name' => esc_html__( 'Display Name', 'jupiterx-core' ),
					'login'        => esc_html__( 'Username', 'jupiterx-core' ),
					'first_name'   => esc_html__( 'First Name', 'jupiterx-core' ),
					'last_name'    => esc_html__( 'Last Name', 'jupiterx-core' ),
					'description'  => esc_html__( 'Bio', 'jupiterx-core' ),
					'email'        => esc_html__( 'Email', 'jupiterx-core' ),
					'url'          => esc_html__( 'Website', 'jupiterx-core' ),
					'meta'         => esc_html__( 'User Meta', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label'     => esc_html__( 'Meta Key', 'jupiterx-core' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();

		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		if ( in_array( $type, [ 'login', 'email', 'url', 'nicename' ], true ) ) {
			$field = 'user_' . $type;
			echo wp_kses_post( isset( $user->$field ) ? $user->$field : '' );
			return;
		}

		if ( 'id' === $type ) {
			echo wp_kses_post( $user->ID );
			return;
		}

		if ( in_array( $type, [ 'description', 'first_name', 'last_name', 'display_name' ], true ) ) {
			echo wp_kses_post( isset( $user->$type ) ? $user->$type : '' );
			return;
		}

		if ( 'meta' === $type ) {
			$key = $this->get_settings( 'meta_key' );
			if ( ! empty( $key ) ) {
				echo wp_kses_post( get_user_meta( $user->ID, $key, true ) );
				return;
			}
		}

		echo '';
	}
}
