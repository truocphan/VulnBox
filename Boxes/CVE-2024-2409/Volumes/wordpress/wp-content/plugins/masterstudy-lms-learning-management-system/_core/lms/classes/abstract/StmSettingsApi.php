<?php
namespace stmLms\Classes\AbstractClasses;

abstract class StmSettingsApi {

	/**
	 * @var string
	 */
	public $id            = '';

	/**
	 * @var array
	 */
	public $errors        = array();

	/**
	 * @var string
	 */
	public $plugin_id     = 'stm_lms_';

	/**
	 * @var array
	 */
	public $settings_data = array();

	/**
	 * Initialise Settings.
	 */
	public function init_settings_data() {
		$this->settings_data = get_option( $this->get_option_key(), null );
	}

	/**
	 * Return the name of the option
	 *
	 * @return string
	 */
	public function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}

	/**
	 * @param  $key
	 * @param  null $empty_value
	 * @return mixed
	 */
	public function get_option( $key, $empty_value = null ) {

		if ( empty( $this->settings_data ) ) {
			$this->init_settings_data();
		}

		if ( ! is_null( $empty_value ) && !isset($this->settings_data[ $key ]) ) {
			$this->settings_data[ $key ] = $empty_value;
		}

		return (isset($this->settings_data[ $key ])) ? $this->settings_data[ $key ] : null;
	}

	public function update_option( $key, $value = '' ) {
		if ( empty( $this->settings_data ) ) {
			$this->init_settings();
		}
		$this->settings_data[ $key ] = $value;
		return update_option( $this->get_option_key(), apply_filters( 'stm_lms_settings_api_sanitized_fields_' . $this->id, $this->settings_data ), 'yes' );
	}

	/**
	 * Install for gateway
	 */
	public function install() { }

	/**
	 * Uninstall for gateway
	 */
	public function uninstall() { }
}