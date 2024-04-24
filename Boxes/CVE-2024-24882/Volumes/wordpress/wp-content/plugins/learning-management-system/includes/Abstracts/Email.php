<?php
/**
 * Email class.
 *
 * @package Masteriyo\ABstracts
 *
 * @since 1.0.0
 */

namespace Masteriyo\Abstracts;

use Pelago\Emogrifier;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;

defined( 'ABSPATH' ) || exit;

/**
 * Email Class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Emails
 */
abstract class Email {

	/**
	 * Email method ID.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Email HTML template.
	 *
	 * @since 1.5.35
	 *
	 * @var string
	 */
	protected $html_template;

	/**
	 * Recipient.
	 *
	 * @since 1.0.0
	 *
	 * @var string[]
	 */
	private $recipients = array();

	/**
	 * Template data.
	 *
	 * @since 1.5.35
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Get email additional content.
	 *
	 * @since 1.0.0
	 * @since 1.5.35 Updated as abstract function.
	 *
	 * @return string
	 */
	abstract public function get_additional_content();

	/**
	 * Get email subject.
	 *
	 * @since 1.0.0
	 * @since 1.5.35 Updated as abstract function.
	 *
	 * @return string
	 */
	abstract public function get_subject();

	/**
	 * Check if this email is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	abstract public function is_enabled();

	/**
	 * Constructor
	 *
	 * @since 1.5.35
	 */
	public function __construct() {
		$this->data = $this->get_default_data();
	}

	/**
	 * Format email string. Like processing placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Text to format.
	 *
	 * @return string
	 */
	public function format_string( $string ) {
		$find    = array_keys( $this->get_placeholders() );
		$replace = array_values( $this->get_placeholders() );

		/**
		 * Filters formatted string in email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $str Formatted string.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( 'masteriyo_email_format_string', str_replace( $find, $replace, $string ), $this );
	}

	/**
	 * Set the locale to the site locale to make sure emails are in the site language.
	 *
	 * @since 1.0.0
	 */
	public function setup_locale() {
		/**
		 * Filters boolean: True if locale should be setup for email.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if locale should be setup for email.
		 */
		if ( apply_filters( 'masteriyo_email_setup_locale', true ) ) {
			masteriyo_switch_to_site_locale();
		}
	}

	/**
	 * Restore the locale to the default locale. Use after finished with setup_locale.
	 *
	 * @since 1.0.0
	 */
	public function restore_locale() {
		/**
		 * Filters boolean: True if the setup locale should be restored for email.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if the setup locale should be restored for email.
		 */
		if ( apply_filters( 'masteriyo_email_restore_locale', true ) ) {
			masteriyo_restore_locale();
		}
	}

	/**
	 * Send an email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $to Email to.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 * @param array  $attachments Email attachments.
	 *
	 * @return bool Result
	 */
	public function send( $to, $subject, $message, $headers, $attachments ) {
		$this->setup_locale();

		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		/**
		 * Filters email content.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content Email content.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$message = apply_filters( 'masteriyo_mail_content', $this->apply_styles( $message ), $this );

		/**
		 * Filters email sender function.
		 *
		 * @since 1.0.0
		 *
		 * @param \Callable $email_sender Email sender function.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$mail_callback = apply_filters( 'masteriyo_mail_callback', 'wp_mail', $this );

		/**
		 * Filters email sender function parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array $params Parameters for the email sender function.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$mail_callback_params = apply_filters( 'masteriyo_mail_callback_params', array( $to, $subject, $message, $headers, $attachments ), $this );

		$return = $mail_callback( ...$mail_callback_params );

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

		$this->restore_locale();

		return $return;
	}

	/**
	 * Get email headers.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_headers() {
		$headers = array( 'Content-Type: ' . $this->get_content_type() . "\r\n" );

		if ( $this->get_from_address() && $this->get_from_name() ) {
			$headers[] = 'Reply-to: ' . $this->get_from_name() . ' <' . $this->get_from_address() . ">\r\n";
		}

		/**
		 * Filters email headers.
		 *
		 * @since 1.0.0
		 *
		 * @since 1.5.35 Email headers can be array.
		 *
		 * @param string|string[] $headers Email headers.
		 * @param string $headers Email object id.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$headers = apply_filters( 'masteriyo_email_headers', $headers, $this->get_id(), $this );
		$headers = join( '', array_unique( $headers ) );

		return $headers;
	}

	/**
	 * Get email attachments.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_attachments() {
		/**
		 * Filters email attachments.
		 *
		 * @since 1.0.0
		 *
		 * @param array $attachments Absolute paths of attachments.
		 * @param string $headers Email object id.
		 * @param \Masteriyo\Emails\Email $email Email class object.
		 */
		return apply_filters( 'masteriyo_email_attachments', array(), $this->get_id(), $this );
	}

	/**
	 * Get email content type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content_type() {
		return 'text/html';
	}

	/**
	 * Apply styles to dynamic content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Content to apply styles on.
	 *
	 * @return string
	 */
	public function apply_styles( $content ) {
		/**
		 * Filters email styles.
		 *
		 * @since 1.0.0
		 *
		 * @param string $css Email style CSS.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$css = apply_filters( 'masteriyo_email_styles', masteriyo_get_template_html( 'emails/email-styles.php' ), $this );

		try {
			$emogrifier = new Emogrifier( $content, $css );

			/**
			 * Fires before applying CSS into an email HTML.
			 *
			 * @since 1.0.0
			 *
			 * @param \Pelago\Emogrifier $emogrifier Object that provides functions for converting CSS styles into inline style attributes in your HTML code.
			 * @param \Masteriyo\Emails\Email $email Email object.
			 */
			do_action( 'masteriyo_emogrifier', $emogrifier, $this );

			$content    = $emogrifier->emogrify();
			$html_prune = HtmlPruner::fromHtml( $content );
			$html_prune->removeElementsWithDisplayNone();
			$content = $html_prune->render();
		} catch ( \Exception $e ) {
			$content = '<style type="text/css">' . $css . '</style>' . $content;
		}

		return $content;
	}

	/**
	 * Get the from_name for outgoing emails.
	 *
	 * @since 1.0.0
	 *
	 * @param string $from_name Default wp_mail() name associated with the "from" email address.
	 *
	 * @return string
	 */
	public function get_from_name() {
		$from_name = masteriyo_get_setting( 'emails.general.from_name' );
		$from_name = empty( trim( $from_name ) ) ? $this->get_site_title() : $from_name;

		/**
		 * Filters "From" value of an email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $from_name From name for an email.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$from_name = apply_filters( 'masteriyo_email_from_name', $from_name, $this );

		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from_address for outgoing emails.
	 *
	 * @since 1.0.0p
	 *
	 * @param string $from_email Default wp_mail() email address to send from.
	 *
	 * @return string
	 */
	public function get_from_address( $from_email = '' ) {
		$from_email = masteriyo_get_setting( 'emails.general.from_email' );
		$from_email = empty( trim( $from_email ) ) ? get_bloginfo( 'admin_email' ) : $from_email;

		/**
		 * Filters from address of an email.
		 *
		 * @since 1.0.0
		 *
		 * @param string $from_email From email address.
		 * @param Masteriyo\Emails\Email $email Email class object.
		 */
		$from_email = apply_filters( 'masteriyo_email_from_address', $from_email, $this );

		return sanitize_email( $from_email );
	}

	/**
	 * Get email identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get email full ID.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_full_id() {
		return 'masteriyo/email/' . $this->get_id();
	}

	/**
	 * Get email identifier for Action Scheduler.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_schedule_handle() {
		return 'masteriyo/schedule/email/' . $this->get_id();
	}

	/**
	 * Get email HTML template.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_html_template() {
		return $this->html_template;
	}

	/**
	 * Get valid recipients.
	 *
	 * @since 1.0.0
	 *
	 * @return string|string[]
	 */
	public function get_recipients() {
		return array_filter( array_map( 'sanitize_email', $this->recipients ), 'is_email' );
	}

	/**
	 * Set recipients.
	 *
	 * @since 1.0.0
	 *
	 * @param string|string[] $recipients Email recipients.
	 */
	public function set_recipients( $recipients ) {
		$recipients = is_array( $recipients ) ? $recipients : (array) $recipients;

		$this->recipients = array_filter( array_map( 'sanitize_email', $recipients ), 'is_email' );
	}

	/**
	 * Get placeholders.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_placeholders() {
		return array(
			'{site_title}'   => $this->get_site_title(),
			'{site_address}' => $this->get_site_url(),
			'{site_url}'     => $this->get_site_url(),
			'{admin_email}'  => $this->get_site_admin_email(),
		);
	}

	/**
	 * Return all template data.
	 *
	 * @since 1.5.35
	 * @return array
	 */
	public function all() {
		return $this->data;
	}

	/**
	 * Return template data.
	 *
	 * @since 1.5.35
	 *
	 * @return array
	 */
	public function get( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
	}

	/**
	 * Add data.
	 *
	 * @since 1.5.35
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Delete data.
	 *
	 * @since 1.5.35
	 *
	 * @param string $key
	 */
	public function delete( $key ) {
		unset( $this->data[ $key ] );
	}

	/**
	 * Clear all the data.
	 *
	 * @since 1.5.35
	 */
	public function clear() {
		$this->data = array();
	}

	/**
	 * Get email content.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_content() {
		return masteriyo_get_template_html( $this->get_html_template(), $this->all() );
	}

	/**
	 * Return site title.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_site_title() {
		return get_bloginfo( 'name' );
	}

	/**
	 * Return site admin email.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_site_admin_email() {
		return get_bloginfo( 'admin_email' );
	}

	/**
	 * Return site url.
	 *
	 * @since 1.5.35
	 *
	 * @return string
	 */
	public function get_site_url() {
		return wp_parse_url( home_url(), PHP_URL_HOST );
	}

	/**
	 * Return template data.
	 *
	 * @since 1.5.35
	 *
	 * @return array
	 */
	public function get_default_data() {
		return array(
			'site_title'         => $this->get_site_title(),
			'site_address'       => $this->get_site_url(),
			'site_url'           => $this->get_site_url(),
			'admin_email'        => $this->get_site_admin_email(),
			'additional_content' => $this->get_additional_content(),
			'email'              => $this,
		);
	}
}
