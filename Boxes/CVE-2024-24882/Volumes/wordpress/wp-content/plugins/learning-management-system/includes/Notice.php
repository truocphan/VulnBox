<?php
/**
 * Notice class.
 *
 * @since 1.0.0
 * @package Masteriyo
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Session\Session;

/**
 * Notice class.
 */
class Notice {

	/**
	 * Success notice type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SUCCESS = 'success';

	/**
	 * Error notice type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ERROR = 'error';

	/**
	 * Info notice type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const INFO = 'info';

	/**
	 * Warning notice type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const WARNING = 'warning';

	/**
	 * Session handler.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Session\Session
	 */
	private $session;

	public function __construct( Session $session ) {
		$this->session = $session;
	}
	/**
	 * Get the count of notices added, either for all notices (default) or for one.
	 * particular notice type specified by $type.
	 *
	 * @since  1.0.0
	 * @param  string $type Optional. The name of the notice type - either error, success or notice.
	 * @return int
	 */
	public function count( $type = '' ) {
		$count       = 0;
		$all_notices = $this->session->get( 'notices', array() );

		if ( empty( $type ) ) {
			$count = count( $all_notices );
		} else {
			$notices = array_filter(
				$all_notices,
				function( $notice ) use ( $type ) {
					return $type === $notice['type'];
				}
			);
			$count   = count( $notices );
		}

		return $count;
	}

	/**
	 * Check if a notice has already been added.
	 *
	 * @since  1.0.0
	 * @param  string $message The text to display in the notice.
	 * @param  string $type Optional. The name of the notice type - either error, success or notice.
	 * @return bool
	 */
	public function has( $message, $type = self::SUCCESS ) {
		$notices = $this->session->get( 'notices', array() );
		$notices = array_filter(
			$notices,
			function( $notice ) use ( $type ) {
				return $type === $notice['type'];
			}
		);

		return array_search( $message, wp_list_pluck( $notices, 'notice' ), true ) !== false;
	}

	/**
	 * Returns all queued notices, optionally filtered by a notice type.
	 *
	 * @since  1.0.0
	 * @param  string $type Optional. The singular name of the notice type - either error, success or notice.
	 * @return array[]
	 */
	public function get( $type = '' ) {
		$notices = $this->session->get( 'notices', array() );

		if ( ! empty( $type ) ) {
			$notices = array_filter(
				$notices,
				function( $notice ) use ( $type ) {
					return $type === $notice['type'];
				}
			);
		}

		return $notices;
	}

	/**
	 * Add and store a notice.
	 *
	 * @since 1.0.0
	 * @param string $message The text to display in the notice.
	 * @param string $type    Optional. The name of the notice type - either error, success or notice.
	 * @param array  $data    Optional notice data.
	 */
	public function add( $message, $type = self::SUCCESS, $data = array() ) {
		$notices = $this->session->get( 'notices', array() );

		/**
		 * Filters the notice message that will be added.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The notice message.
		 */
		$message = apply_filters( "masteriyo_add_notice_{$type}", $message );

		if ( ! empty( $message ) ) {
			$notices[] = array(
				'type'    => $type,
				'message' => $message,
				'data'    => $data,
			);
		}
		$this->session->put( 'notices', $notices );
	}

	/**
	 * Add notices for WP Errors.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $errors Errors.
	 */
	public function add_wp_error_notices( $errors ) {
		if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
			foreach ( $errors->get_error_messages() as $error ) {
				$this->add( $error, self::ERROR );
			}
		}
	}
	/**
	 * Unset all notices.
	 *
	 * @since 1.0.0
	 */
	public function clear() {
		$this->session->put( 'notices', array() );
	}

	/**
	 * Display a single notice immediately.
	 *
	 * @since 1.0.0
	 * @param string $message The text to display in the notice.
	 * @param string $type Optional. The singular name of the notice type - either error, success or notice.
	 * @param array  $data        Optional notice data.
	 */
	public function display( $message, $type = self::SUCCESS, $data = array() ) {
		/**
		 * Filters the notice message that will be displayed.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The notice message.
		 */
		$message = apply_filters( "masteriyo_add_notice_{$type}", $message );
		masteriyo_get_template(
			"notices/{$type}.php",
			array(
				'message' => $message,
				'type'    => $type,
				'data'    => $data,
			)
		);
	}

	/**
	 * Prints messages and errors which are stored in the session, then clears them.
	 *
	 * @since 1.0.0
	 * @param bool $return true to return rather than echo.
	 * @param string $type Notice type. Default: ''
	 * @return string|null
	 */
	public function display_all( $return = false, $type = '' ) {
		$notices = $this->session->get( 'notices', array() );

		if ( ! empty( $type ) ) {
			$notices = array_filter(
				$notices,
				function( $notice ) use ( $type ) {
					return $type === $notice['type'];
				}
			);
		}

		$notice_html = array_reduce(
			$notices,
			function( $notice_html, $notice ) {
				$html = masteriyo_get_template_html(
					"notices/{$notice['type']}.php",
					array(
						'message' => $notice['message'],
						'type'    => $notice['type'],
						'data'    => $notice['data'],
					)
				);
				return $notice_html . $html;
			},
			''
		);

		$this->clear();

		$notice_html = $this->kses( $notice_html );

		if ( $return ) {
			return $notice_html;
		}

		echo wp_kses_post( $notice_html );
	}

	/**
	 * Filters out the same tags as wp_kses_post, but allows tabindex for <a> element.
	 *
	 * @since 1.0.0
	 * @param string $message Content to filter through kses.
	 * @return string
	 */
	public function kses( $message ) {
		$allowed_tags = array_replace_recursive(
			wp_kses_allowed_html( 'post' ),
			array(
				'a' => array(
					'tabindex' => true,
				),
			)
		);

		/**
		 * Kses notice allowed tags.
		 *
		 * @since 1.0.0
		 * @param array[]|string $allowed_tags An array of allowed HTML elements and attributes, or a context name such as 'post'.
		 */
		return wp_kses( $message, apply_filters( 'masteriyo_kses_notice_allowed_tags', $allowed_tags ) );
	}

	/**
	 * Get notice by type.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_by_id( $id, $type = '' ) {
		$all_notices = $this->get( $type );
		$message     = '';

		foreach ( $all_notices as $notice ) {
			if ( ! isset( $notice['data']['id'] ) ) {
				continue;
			}

			if ( $id === $notice['data']['id'] ) {
				$message = $notice['message'];
				break;
			}
		}

		return $message;
	}

	/**
	 * Remove notices.
	 *
	 * @since 1.0.0
	 *
	 * @param [type] $type Notice type.
	 * @return void
	 */
	public function remove( $type ) {
		$all_notices = $this->get();

		$all_notices = array_filter(
			$all_notices,
			function( $notice ) use ( $type ) {
				return $type !== $notice['type'];
			}
		);

		$this->session->put( 'notices', $all_notices );
	}
}
