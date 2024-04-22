<?php

namespace MasterStudy\Lms\Pro\addons\assignments;

use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;

final class Assignments implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return Addons::ASSIGNMENTS;
	}

	/**
	 *
	 * @param \MasterStudy\Lms\Plugin $plugin
	 */
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		$plugin->get_router()->load_routes( __DIR__ . '/routes.php' );
	}

	public static function statuses(): array {
		return array(
			'pending'    => array(
				'icon'  => '<span class="dashicons dashicons-clock"></span>',
				'title' => __( 'Pending', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'passed'     => array(
				'icon'  => '<span class="dashicons dashicons-yes-alt passed"></span>',
				'title' => __( 'Passed', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'not_passed' => array(
				'icon'  => '<span class="dashicons dashicons-dismiss not-passed"></span>',
				'title' => __( 'Non Passed', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'draft'      => array(
				'icon'  => '<span class="dashicons dashicons-hourglass draft"></span>',
				'title' => __( 'In process', 'masterstudy-lms-learning-management-system-pro' ),
			),
		);
	}

	public static function pending_viewed_transient_name( int $assignment_id ) {
		return "stm_lms_pending_assignments_seen_{$assignment_id}";
	}
}
