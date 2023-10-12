<?php

/**
 * Class FMViewVerify_email
 */
class FMViewVerify_email {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  /**
   * Display message.
   *
   * @param string $message
   */
	public function display( $message = '' ) {
		echo WDW_FM_Library(self::PLUGIN)->message($message, 'fm-notice-success');
	}
}
