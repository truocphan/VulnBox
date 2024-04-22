<?php
namespace stmLms\Classes\Models;

class StmCron {

	public static function init() {
		add_filter( 'cron_schedules', array(StmCron::class, 'stm_cron_interval'));
		//wp_clear_scheduled_hook('stm_listing_cron');
		if ( ! wp_next_scheduled( 'stm_listing_cron'))
			wp_schedule_event( time(), 'stm_cron_interval', 'stm_listing_cron' );
		add_action( "stm_listing_cron", array(StmCron::class, 'run_cron'));
	}

	public static function run_cron () {
		update_option('ulisting_listing_cron_time',date("H:i"));
		StmLmsPayout::generation_payout();
	}

	/**
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public static function stm_cron_interval( $schedules ) {
		$schedules['stm_cron_interval'] = array(
			'interval' => 86400, // 86400 seconds = 24 Hours
			'display' => 'Stm cron interval'
		);
		return $schedules;
	}
}