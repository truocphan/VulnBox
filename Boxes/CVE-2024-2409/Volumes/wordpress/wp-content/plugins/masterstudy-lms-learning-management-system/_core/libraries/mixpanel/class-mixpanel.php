<?php

namespace MasterStudy\Lms\Libraries;

class Mixpanel {
	private $token = 'ZDgwNjAzZjlhZWY5NTU0NDM3NzgyMDEzZjUwMzU0YzA=';

	private $url = 'https://api.mixpanel.com/engage';

	protected $classes = array();

	protected static $data = array();

	public function __construct( $classes = array() ) {
		$this->classes = $classes;
	}

	public static function get_custom_posts_ids( $post_type ) {
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
		);

		return get_posts( $args );
	}

	public static function add_data( $key, $data ) {
		self::$data[ $key ] = $data;
	}

	public function collect_data() {
		foreach ( $this->classes as $class ) {
			call_user_func( array( $class, 'register_data' ) );
		}
	}

	public function execute() {
		$this->collect_data();

		if ( ! empty( self::$data ) && true !== Mixpanel_General::is_dev( home_url() ) ) {
			$args = array(
				'method'  => 'POST',
				'headers' => array(
					'accept'       => 'text/plain',
					'content-type' => 'application/json',
				),
				'body'    => wp_json_encode(
					array(
						array(
							'$token'       => base64_decode( $this->token ), // phpcs:ignore
							'$distinct_id' => home_url(),
							'$set'         => self::$data,
						),
					),
				),
			);

			wp_remote_post( $this->url, $args );
		}
	}
}
