<?php

namespace MasterStudy\Lms\Pro\addons\email_manager;

class EmailManagerSettings {
	public static function get_all(): array {
		return (array) get_option( 'stm_lms_email_manager_settings', array() );
	}
}
