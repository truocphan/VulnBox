<?php
/**
 * ChatGPT service class.
 *
 * @since 1.6.15
 *
 * @package Masteriyo\packages\OpenAI
 */

namespace ThemeGrill\OpenAI;

/**
 * ChatGPT service class.
 *
 * @since 1.6.15
 */
class ChatGPT extends OpenAI {
	/**
	 * ChatGPT API endpoint.
	 *
	 * @var string
	 */
	protected $endpoint = 'chat/completions';


	/**
	 * Singleton instance of the ChatGPT class.
	 *
	 * @var ChatGPT|null
	 */
	private static $instance = null;

	/**
	 * Private constructor for singleton pattern.
	 *
	 * @param string $api_key OpenAI API key.
	 */
	private function __construct( $api_key ) {
		parent::__construct( $api_key );
	}

	/**
	 * Get the singleton instance of the ChatGPT class.
	 *
	 * @param string $api_key The API key for OpenAI. Only used when creating the first instance.
	 * @return ChatGPT|null An instance of ChatGPT or null if no API key provided.
	 */
	public static function get_instance( $api_key = '' ) {
		if ( null === self::$instance ) {

			if ( empty( $api_key ) ) {

				return null;  // Return null if no API key is provided.
			}

			self::$instance = new self( $api_key );
		}

		return self::$instance;
	}

	/**
	 * Send a prompt to ChatGPT and receive a response.
	 *
	 * @param string $prompt The prompt for the request.
	 * @param array  $data   Additional data for modifying default settings (optional).
	 *
	 * @return string Response text.
	 */
	public function send_prompt( $prompt, $data = array() ) {
		$this->set_default_data( $data );

		$messages   = $this->get_default_data()['messages'];
		$messages[] = array(
			'role'    => 'user',
			'content' => $prompt,
		);

		$data             = $this->get_default_data();
		$data['messages'] = $messages;

		return $this->request( $this->endpoint, 'POST', $data );
	}
}

