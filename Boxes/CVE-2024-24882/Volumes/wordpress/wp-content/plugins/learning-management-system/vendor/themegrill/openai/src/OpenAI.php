<?php
/**
 * OpenAI base class.
 *
 * @since 1.6.15
 *
 * @package Masteriyo\packages\OpenAI
 */

namespace ThemeGrill\OpenAI;

/**
 * OpenAI base class.
 *
 * @since 1.6.15
 */
class OpenAI {
	/**
	 * OpenAI API key.
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * OpenAI base URL.
	 *
	 * @var string
	 */
	protected $base_url = 'https://api.openai.com/v1/';

	/**
	 * Default data for API requests.
	 *
	 * @var array
	 */
	protected $default_data = array(
		'model'       => 'gpt-3.5-turbo',
		'messages'    => array(
			array(
				'role'    => 'system',
				'content' => 'You are an expert course content creator.',
			),
		),
		'max_tokens'  => 3000,  // Set a reasonable limit to accommodate comprehensive responses.
		'temperature' => 0.7,   // Lower temperature for more focused and coherent responses.
		'top_p'       => 0.85,  // Higher value for very diverse and creative responses.
	);

	/**
	 * Constructor.
	 *
	 * @param string $api_key OpenAI API key.
	 */
	protected function __construct( $api_key ) {
			$this->api_key = $api_key;
	}

	/**
	 * Get the API key.
	 *
	 * @return string
	 */
	public function get_api_key() {
		return $this->api_key;
	}

	/**
	 * Send request to the OpenAI API.
	 *
	 * @param string $endpoint API endpoint.
	 * @param string $method   Request method.
	 * @param array  $body     Request body.
	 * @param array  $headers  Request headers.
	 * @param array  $args     Request args.
	 *
	 * @return string Response.
	 */
	protected function request( $endpoint, $method = 'GET', $body = array(), $headers = array(), $args = array() ) {
		$headers = array_merge(
			$headers,
			array(
				'Authorization' => 'Bearer ' . $this->api_key,
			)
		);

		$url = $this->base_url . $endpoint;

		return Request::send( $url, $method, $body, $headers, $args );
	}

	/**
	 * Get the default data for API requests.
	 *
	 * @return array
	 */
	protected function get_default_data() {
		return $this->default_data;
	}

	/**
	 * Set default data for API requests.
	 *
	 * @param array $data Default data.
	 *
	 * @return void
	 */
	protected function set_default_data( $data ) {
		$this->default_data = array_merge( $this->default_data, $data );
	}
}
