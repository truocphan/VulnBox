<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Site;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class Current_Date_Time extends Tag {
	public function get_name() {
		return 'current-date-time';
	}

	public function get_title() {
		return esc_html__( 'Current Date Time', 'jupiterx-core' );
	}

	public function get_group() {
		return 'site';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'date_format',
			[
				'label'   => esc_html__( 'Date Format', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					''        => esc_html__( 'None', 'jupiterx-core' ),
					'F j, Y'  => gmdate( 'F j, Y' ),
					'Y-m-d'   => gmdate( 'Y-m-d' ),
					'm/d/Y'   => gmdate( 'm/d/Y' ),
					'd/m/Y'   => gmdate( 'd/m/Y' ),
					'custom'  => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'time_format',
			[
				'label'     => esc_html__( 'Time Format', 'jupiterx-core' ),
				'type'      => 'select',
				'options'   => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					''        => esc_html__( 'None', 'jupiterx-core' ),
					'g:i a'   => gmdate( 'g:i a' ),
					'g:i A'   => gmdate( 'g:i A' ),
					'H:i'     => gmdate( 'H:i' ),
				],
				'default'   => 'default',
				'condition' => [
					'date_format!' => 'custom',
				],
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label'       => esc_html__( 'Custom Format', 'jupiterx-core' ),
				'default'     => get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
				'description' => sprintf(
					'<a href="https://go.elementor.com/wordpress-date-time/" target="_blank">%s</a>',
					esc_html__( 'Documentation on date and time formatting', 'jupiterx-core' )
				),
				'condition'   => [
					'date_format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		if ( 'custom' === $settings['date_format'] ) {
			$format = $settings['custom_format'];

			echo wp_kses_post( date_i18n( $format ) );
			return;
		}

		$date_format = $settings['date_format'];
		$time_format = $settings['time_format'];
		$format      = '';

		if ( 'default' === $date_format ) {
			$date_format = get_option( 'date_format' );
		}

		if ( 'default' === $time_format ) {
			$time_format = get_option( 'time_format' );
		}

		$has_date = false;

		if ( $date_format ) {
			$format   = $date_format;
			$has_date = true;
		}

		if ( $time_format ) {
			$format .= $has_date ? ' ' . $time_format : $time_format;
		}

		echo wp_kses_post( date_i18n( $format ) );
	}
}
