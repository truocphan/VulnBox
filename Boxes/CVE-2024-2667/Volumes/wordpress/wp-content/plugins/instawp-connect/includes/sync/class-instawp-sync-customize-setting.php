<?php

/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 */

include_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';

final class InstaWP_Sync_Customize_Setting extends \WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @param mixed $value The option value.
	 * @return void
	 */
	public function import( $value ) {
		$this->update( $value );
	}
}