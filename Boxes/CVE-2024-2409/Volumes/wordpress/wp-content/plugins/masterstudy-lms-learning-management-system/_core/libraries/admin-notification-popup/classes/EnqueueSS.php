<?php


namespace ANP;


class EnqueueSS {
	public static function init() {
		add_action( 'admin_enqueue_scripts', [ self::class, 'load_admin_ss' ] );
	}

	public static function load_admin_ss() {

		$anp_nonce = wp_create_nonce( 'anp_nonce' );

		wp_enqueue_script( 'anp-script', STM_ANP_URL . 'assets/js/anp-scripts.js', 'jQuery', null, true );
		wp_enqueue_style( 'anp-style', STM_ANP_URL . 'assets/css/anp-style.css', null, null, 'all' );

		wp_localize_script( 'anp-script', 'anp_script_object', array( 'anp_nonce' => $anp_nonce ) );
	}
}
