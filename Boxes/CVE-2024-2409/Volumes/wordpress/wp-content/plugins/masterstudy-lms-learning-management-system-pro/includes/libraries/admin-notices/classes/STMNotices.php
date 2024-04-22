<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly

class STMNotices {

	public static function init( $plugin_data ) {

		if ( ! isset( $plugin_data['notice_title'] ) || ! isset( $plugin_data['notice_logo'] ) ) {
			return;
		}

		add_filter( 'stm_admin_notices_data', function ( $notices ) use ( $plugin_data ) {
			$notices[] = $plugin_data;

			return $notices;
		} );

		add_action( 'admin_notices', array( self::class, 'stm_admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'admin_enqueue' ), 100 );

		add_action( 'add_admin_notice', array( self::class, 'build_notice') );
	}

	public static function stm_admin_notices() {

		$notice_data = apply_filters( 'stm_admin_notices_data', [] );

		foreach ( $notice_data as $data ) {
			self::build_notice( $data );
		}
	}

	public static function admin_enqueue() {
		wp_enqueue_style( 'stm_admin_notice', STM_ADMIN_NOTICES_URL . 'assets/css/admin.css', false );
		wp_enqueue_script( 'stm_admin_notice', STM_ADMIN_NOTICES_URL . 'assets/js/an-scripts.js', array( 'jquery' ), '1.0', true );
	}

	public static function build_notice( $plugin_data ) {

		$btnOneClass = (!empty($plugin_data['notice_btn_one_class'])) ? ' ' . $plugin_data['notice_btn_one_class'] : '';
		$btnTwoClass = (!empty($plugin_data['notice_btn_two_class'])) ? ' ' . $plugin_data['notice_btn_two_class'] : '';

		$html = '<div class="notice is-dismissible stm-notice stm-notice-' . esc_attr( $plugin_data['notice_type'] ) . '">';
		$html .= '<div class="img"><img src="' . STM_ADMIN_NOTICES_URL . 'assets/img/' . esc_attr( $plugin_data['notice_logo'] ) . '" /></div>';
		$html .= '<div class="text-wrap">';
		$html .= '<h4>' . $plugin_data['notice_title'] . '</h4>';
		$html .= ( ! empty( $plugin_data['notice_desc'] ) ) ? '<h5>' . $plugin_data['notice_desc'] . '</h5>' : '';
		$html .= '</div>';
		$html .= ( ! empty( $plugin_data['notice_btn_one'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_one'] ) . '" class="button btn-first' . $btnOneClass . '">' . $plugin_data['notice_btn_one_title'] . '</a>' : '';
		$html .= ( ! empty( $plugin_data['notice_btn_two'] ) ) ? '<a href="' . esc_url( $plugin_data['notice_btn_two'] ) . '" class="button btn-second' . $btnTwoClass . '">' . $plugin_data['notice_btn_two_title'] . '</a>' : '';
		$html .= '</div>';

		echo $html;
	}
}