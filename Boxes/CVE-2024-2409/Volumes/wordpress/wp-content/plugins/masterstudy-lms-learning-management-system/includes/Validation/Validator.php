<?php

namespace MasterStudy\Lms\Validation;

use DateTime;
use RuntimeException;
use stdClass;
use function esc_html__;

class Validator {

	/**
	 * String rule for
	 * Rules format is: rule_name,param_1;param_2;_dependent_field
	 * @var array
	 */
	protected array $rules = array();

	/**
	 * Instance attribute containing errors from last run
	 */
	protected array $errors;

	/**
	 * Contain readable field names that have been set manually
	 */
	protected static array $fields = array();

	/**
	 * Custom validation methods
	 */
	protected static array $validation_methods = array();

	/**
	 * Custom validation methods error messages and custom ones
	 */
	protected static array $validation_methods_errors = array();

	/**
	 * Data to be validated
	 * @var array
	 */
	protected array $data;

	protected array $numeric_rules = array(
		'float',
		'integer',
		'numeric',
	);

	protected array $implicit_rules = array(
		'accepted',
		'declined',
		'present',
		'required',
		'required_file',
		'required_if',
		'required_if_accepted',
		'required_if_declined',
		'required_with',
		'required_without',
	);

	public function __construct( array $data, array $rules ) {
		$this->data = $data;
		$this->set_rules( $rules );
	}

	public function fails(): bool {
		return ! $this->passes();
	}

	/**
	 * Adds a custom validation rule using a callback function.
	 *
	 * @param string $rule
	 * @param callable $callback
	 * @param string $error_message
	 *
	 * @return bool
	 *
	 * @throws RuntimeException
	 */
	public static function add_validator( $rule, $callback, $error_message = null ) {
		$method = 'validate_' . $rule;
		if ( method_exists( __CLASS__, $method ) || isset( self::$validation_methods[ $rule ] ) ) {
			throw new RuntimeException( "Validator rule '$rule' already exists." );
		}
		self::$validation_methods[ $rule ] = $callback;
		if ( $error_message ) {
			self::$validation_methods_errors[ $rule ] = $error_message;
		}

		return true;
	}

	/**
	 * Helper method to extract an element from an array safely
	 *
	 * @param mixed $key
	 * @param array $array
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	protected function field( $key, $default = null ) {
		return array_key_exists( $key, $this->data ) ? $this->data[ $key ] : $default;
	}

	/**
	 * Getter for the validation rules.
	 *
	 * @return array
	 */
	public function get_rules(): array {
		return $this->rules;
	}

	/**
	 * Setter for the validation rules.
	 *
	 * @param array $rules
	 */
	public function set_rules( array $rules ): void {
		$expanded = array();

		foreach ( $rules as $field => $rule ) {
			if ( $rule instanceof ConditionalRules ) {
				$rule = $rule->passes( $this->data ) ? $rule->get_rules() : array();
			}

			// only root asterisk supported for now
			// todo: add nested asterisk support
			if ( strpos( $field, '*' ) !== 0 ) {
				$expanded[ $field ] = $rule;
				continue;
			}
			$exploded = explode( '.', $field, 2 );

			foreach ( $this->data as $key => $val ) {
				$new_field              = $key . ( isset( $exploded[1] ) ? ".$exploded[1]" : '' );
				$expanded[ $new_field ] = $rule;
			}
		}

		$this->rules = array_filter( $expanded );
	}

	/**
	 * Get validated values.
	 *
	 * @return array
	 */
	public function get_validated(): array {
		if ( ! $this->errors ) {
			$this->passes();
		}

		$results = array();

		$missing_value = new stdClass();

		foreach ( $this->get_rules() as $field => $rules ) {
			$value = $this->field( $field, $missing_value );

			if ( $value !== $missing_value ) {
				$results[ $field ] = $this->sanitize_value( $field, $value );
			}
		}

		return $results;
	}

	/**
	 * Perform data validation
	 *
	 * @return bool
	 *
	 * @throws RuntimeException
	 */
	public function passes(): bool {
		$this->errors = array();
		foreach ( $this->rules as $field => $rules ) {
			$rules = array_filter( explode( '|', $rules ) );
			$value = $this->get_value( $field );

			foreach ( $rules as $rule ) {
				list( $rule, $param ) = $this->parse_rule( $rule );

				if ( ! $this->is_validatable( $rule, $field, $value ) ) {
					continue;
				}

				$method = 'validate_' . $rule;

				if ( method_exists( $this, $method ) ) {
					$result = $this->$method( $field, $value, $param );

					if ( false === $result ) {
						$this->add_error( $field, $value, $rule, $param );
					}
				} elseif ( isset( self::$validation_methods[ $rule ] ) ) {
					$result = call_user_func( self::$validation_methods[ $rule ], $field, $value, $param );

					if ( false === $result ) {
						$this->add_error( $field, $value, $rule, $param );
					}
				} else {
					throw new RuntimeException( "Validator method '$method' does not exist." );
				}
			}
		}

		return count( $this->errors ) === 0;
	}

	/**
	 * Set a readable name for a specified field names.
	 *
	 * @param string $field
	 * @param string $readable_name
	 */
	public static function set_field_name( $field, $readable_name ) {
		self::$fields[ $field ] = $readable_name;
	}

	/**
	 * Set readable name for specified fields in an array.
	 *
	 * Usage:
	 *
	 * Validation::set_field_names(array(
	 *  "name" => "My Lovely Name",
	 *  "username" => "My Beloved Username",
	 * ));
	 *
	 * @param array $array
	 */
	public static function set_field_names( array $array ) {
		foreach ( $array as $field => $readable_name ) {
			self::set_field_name( $field, $readable_name );
		}
	}

	/**
	 * Set a custom error message for a validation rule.
	 *
	 * @param string $rule
	 * @param string $message
	 */
	public static function set_error_message( $rule, $message ) {
		self::$validation_methods_errors[ $rule ] = $message;
	}

	/**
	 * Set custom error messages for validation rules in an array.
	 *
	 * Usage:
	 *
	 * Validation::set_error_messages(array(
	 *  "validate_required"     => "{field} is required",
	 *  "validate_valid_email"  => "{field} must be a valid email",
	 * ));
	 *
	 * @param array $array
	 */
	public static function set_error_messages( array $array ) {
		foreach ( $array as $rule => $message ) {
			self::set_error_message( $rule, $message );
		}
	}

	/**
	 * Get error messages.
	 *
	 * @return array
	 */
	protected function get_messages(): array {
		$messages = $this->get_message_array();
		if ( self::$validation_methods_errors ) {
			$messages = array_merge( $messages, self::$validation_methods_errors );
		}

		return $messages;
	}

	/**
	 * Process the validation errors and return an array of errors with field names as keys.
	 *
	 * @return array<string, array<string>>
	 * @throws RuntimeException
	 */
	public function get_errors_array(): array {
		$resp     = array();
		$messages = $this->get_messages();

		if ( ! $this->errors ) {
			$this->passes();
		}
		foreach ( $this->errors as $error ) {
			$field = ucwords( str_replace( array( '_', '-' ), chr( 32 ), $error['field'] ) );
			$param = $error['param'];
			// Let's fetch explicitly if the field names exist
			if ( array_key_exists( $error['field'], self::$fields ) ) {
				$field = self::$fields[ $error['field'] ];
				// If param is a field (i.e. equals field validator)
				if ( array_key_exists( $param, self::$fields ) ) {
					$param = self::$fields[ $error['param'] ];
				}
			}
			// Messages
			if ( isset( $messages[ $error['rule'] ] ) ) {
				// Show first validation error and don't allow to be overwritten
				if ( ! isset( $resp[ $error['field'] ] ) ) {
					if ( is_array( $param ) ) {
						$param = implode( ', ', $param );
					}

					if ( is_array( $messages[ $error['rule'] ] ) ) {
						$type    = $this->get_field_type( $error['field'] );
						$message = $messages[ $error['rule'] ][ $type ];
					} else {
						$message = $messages[ $error['rule'] ];
					}
					$resp[ $error['field'] ][] = str_replace(
						array( '{field}', '{param}' ),
						array( $field, $param ),
						$message
					);
				}
			} else {
				throw new RuntimeException( 'Rule "' . $error['rule'] . '" does not have an error message' );
			}
		}

		return $resp;
	}

	/**
	 * Verify that a value is contained within the pre-defined value set.
	 *
	 * Usage: '<index>' => 'contains,value value value'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_contains( $field, $input, $param = null ): bool {
		$param = trim( strtolower( $param ) );
		$value = trim( strtolower( $input ) );
		if ( preg_match_all( '#\'(.+?)\'#', $param, $matches, PREG_PATTERN_ORDER ) ) {
			$param = $matches[1];
		} else {
			$param = explode( chr( 32 ), $param );
		}

		return in_array( $value, $param, true );
	}

	/**
	 * Verify that a value is contained within the pre-defined value set.
	 * OUTPUT: will NOT show the list of values.
	 *
	 * Usage: '<index>' => 'contains_list,value;value;value'
	 *
	 * @param string $field
	 * @param mixed $input
	 */
	protected function validate_contains_list( $field, $input, $param = null ): bool {
		$param = trim( strtolower( $param ) );
		$value = trim( strtolower( $input ) );
		$param = explode( ';', $param );

		// consider: check lower case values
		return in_array( $value, $param, true );
	}

	/**
	 * Verify that a value is NOT contained within the pre-defined value set.
	 * OUTPUT: will NOT show the list of values.
	 *
	 * Usage: '<index>' => 'doesnt_contain_list,value;value;value'
	 *
	 * @param string $field
	 * @param mixed $input
	 */
	protected function validate_doesnt_contain_list( $field, $input, $param = null ): bool {
		return ! $this->validate_contains_list( $field, $input );
	}

	/**
	 * Check if the specified key is present and not empty.
	 *
	 * Usage: '<index>' => 'required'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_required( $field, $input, $param = null ): bool {
		if ( is_null( $input ) ) {
			return false;
		}

		if ( is_string( $input ) && trim( $input ) === '' ) {
			return false;
		}

		if ( is_countable( $input ) && count( $input ) < 1 ) {
			return false;
		}

		return true;
	}

	protected function validate_required_if_accepted( $field, $input, $param = null ): bool {
		if ( ! $this->has_data( $param ) ) {
			return true;
		}

		if ( $this->validate_accepted( $param, $this->get_value( $param ) ) ) {
			return $this->validate_required( $field, $input, $param );
		}

		return true;
	}

	protected function validate_required_if_declined( $field, $input, $param = null ): bool {
		if ( ! $this->has_data( $param ) ) {
			return true;
		}

		if ( $this->validate_declined( $param, $this->get_value( $param ) ) ) {
			return $this->validate_required( $field, $input, $param );
		}

		return true;
	}

	protected function validate_required_with( $field, $input, $param = null ): bool {
		if ( $this->validate_required( $param, $this->get_value( $param ) ) ) {
			return $this->validate_required( $field, $input );
		}

		return true;
	}

	protected function validate_required_without( $field, $input, $param = null ): bool {
		if ( ! $this->validate_required( $param, $this->get_value( $param ) ) ) {
			return $this->validate_required( $field, $input );
		}

		return true;
	}


	/**
	 * Check if the specified key is present and not empty.
	 *
	 * Usage: '<index>' => 'required_if:anotherfield,value'
	 *
	 * @param mixed $field
	 * @param mixed $input
	 * @param mixed $param
	 *
	 * @return bool
	 */
	protected function validate_required_if( $field, $input, $param ): bool {
		list( $other_field, $other_value ) = explode( ';', $param );

		if ( $this->get_value( $other_field ) === $other_value ) {
			return $this->validate_required( $field, $input );
		}

		return true;
	}

	protected function validate_accepted( $field, $input, $param = null ): bool {
		$acceptable = array( 'yes', 'on', '1', 1, true, 'true' );

		return $this->validate_required( $field, $input ) && in_array( $input, $acceptable, true );
	}

	protected function validate_declined( $field, $input, $param = null ): bool {
		$acceptable = array( 'no', 'off', '0', 0, false, 'false' );

		return $this->validate_required( $field, $input ) && in_array( $input, $acceptable, true );
	}

	/**
	 * Determine if the provided email is valid.
	 *
	 * Usage: '<index>' => 'valid_email'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_email( $field, $input, $param = null ): bool {
		if ( ! filter_var( $input, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the provided value length is less or equal to a specific value.
	 *
	 * Usage: '<index>' => 'max,240'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_max( $field, $input, $param = null ): bool {
		return $this->get_size( $field, $input ) <= $param;
	}

	/**
	 * Determine if the provided value length is more or equal to a specific value.
	 *
	 * Usage: '<index>' => 'min,4'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_min( $field, $input, $param = null ): bool {
		return $this->get_size( $field, $input ) >= $param;
	}

	/**
	 * Determine if the provided value length matches a specific value.
	 *
	 * Usage: '<index>' => 'size,5'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_size( $field, $input, $param = null ): bool {
		return $this->get_size( $field, $input ) === $param;
	}

	/**
	 * Determine if the provided value contains only alpha characters.
	 *
	 * Usage: '<index>' => 'alpha'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_alpha( $field, $input, $param = null ): bool {
		return preg_match( '/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input ) !== false;
	}

	/**
	 * Determine if the provided value contains only alpha-numeric characters.
	 *
	 * Usage: '<index>' => 'alpha_numeric'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_alpha_numeric( $field, $input, $param = null ): bool {
		return preg_match( '/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input ) !== false;
	}

	/**
	 * Determine if the provided value contains only alpha characters with dashed and underscores.
	 *
	 * Usage: '<index>' => 'alpha_dash'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_alpha_dash( $field, $input, $param = null ): bool {
		return preg_match( '/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i', $input ) !== false;
	}

	/**
	 * Determine if the provided value contains only alpha numeric characters with spaces.
	 *
	 * Usage: '<index>' => 'alpha_numeric_space'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_alpha_numeric_space( $field, $input, $param = null ): bool {
		return preg_match( '/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i', $input ) !== false;
	}

	/**
	 * Determine if the provided value contains only alpha numeric characters with spaces.
	 *
	 * Usage: '<index>' => 'alpha_space'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_alpha_space( $field, $input, $param = null ): bool {
		preg_match( '/^([0-9a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i', $input ) !== false;
	}

	/**
	 * Determine if the provided value is a valid number or numeric string.
	 *
	 * Usage: '<index>' => 'numeric'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_numeric( $field, $input, $param = null ): bool {
		return is_numeric( $input );
	}

	/**
	 * Determine if the provided value is a valid integer.
	 *
	 * Usage: '<index>' => 'integer'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 */
	protected function validate_integer( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_INT ) !== false;
	}

	/**
	 * Determine if the provided value is a PHP accepted boolean.
	 *
	 * Usage: '<index>' => 'boolean'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 *
	 * @return mixed
	 */
	protected function validate_boolean( $field, $input, $param = null ): bool {
		$booleans = array( '1', 'true', true, 1, '0', 'false', false, 0, 'yes', 'no', 'on', 'off' );

		return in_array( $input, $booleans, true );
	}

	/**
	 * Determine if the provided value is a valid float.
	 *
	 * Usage: '<index>' => 'float'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 *
	 * @return mixed
	 */
	protected function validate_float( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_FLOAT ) !== false;
	}

	/**
	 * Determine if the provided value is a valid URL.
	 *
	 * Usage: '<index>' => 'valid_url'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 *
	 * @return mixed
	 */
	protected function validate_url( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_URL ) !== false;
	}

	/**
	 * Determine if a URL exists & is accessible.
	 *
	 * Usage: '<index>' => 'url_exists'
	 *
	 * @param string $field
	 * @param mixed $input
	 * @param null $param
	 *
	 * @return mixed
	 */
	protected function validate_url_exists( $field, $input, $param = null ): bool {
		$url = parse_url( strtolower( $input ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
		if ( isset( $url['host'] ) ) {
			$url = $url['host'];
		}
		if ( function_exists( 'checkdnsrr' ) && function_exists( 'idn_to_ascii' ) ) {
			if ( checkdnsrr( idn_to_ascii( $url ), 'A' ) === false ) {
				return false;
			}
		} elseif ( gethostbyname( $url ) === $url ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the provided value is a valid IP address.
	 *
	 * Usage: '<index>' => 'valid_ip'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_ip( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_IP ) !== false;
	}

	/**
	 * Determine if the provided value is a valid IPv4 address.
	 *
	 * Usage: '<index>' => 'ipv4'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 *
	 * @see http://pastebin.com/UvUPPYK0
	 *
	 * What about private networks? http://en.wikipedia.org/wiki/Private_network
	 * What about loop-back address? 127.0.0.1
	 */
	protected function validate_ipv4( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) !== false;
	}

	/**
	 * Determine if the provided value is a valid IPv6 address.
	 *
	 * Usage: '<index>' => 'ipv6'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_ipv6( $field, $input, $param = null ): bool {
		return filter_var( $input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) !== false;
	}

	/**
	 * Determine if the input is a valid credit card number.
	 *
	 * See: http://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
	 * Usage: '<index>' => 'cc'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_cc( $field, $input, $param = null ): bool {
		$number = preg_replace( '/\D/', '', $input );
		if ( function_exists( 'mb_strlen' ) ) {
			$number_length = mb_strlen( $number );
		} else {
			$number_length = strlen( $number );
		}
		/**
		 * Bail out if $number_length is 0.
		 * This can be the case if a user has entered only alphabets
		 *
		 * @since 1.5
		 */
		if ( 0 === $number_length ) {
			return false;
		}
		$parity = $number_length % 2;
		$total  = 0;
		for ( $i = 0; $i < $number_length; ++ $i ) {
			$digit = $number[ $i ];
			if ( $i % 2 === $parity ) {
				$digit *= 2;
				if ( $digit > 9 ) {
					$digit -= 9;
				}
			}
			$total += $digit;
		}

		return 0 === ( $total % 10 );
	}

	/**
	 * Usage: '<index>' => 'name'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_name( $field, $input, $param = null ): bool {
		return preg_match( "/^([a-z \p{L} '-])+$/i", $input ) !== false;
	}

	/**
	 * Determine if the provided input is likely to be a street address using weak detection.
	 *
	 * Usage: '<index>' => 'street_address'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_street_address( $field, $input, $param = null ): bool {
		// Theory: 1 number, 1 or more spaces, 1 or more words
		$has_letter = preg_match( '/[a-zA-Z]/', $input );
		$has_digit  = preg_match( '/\d/', $input );
		$has_space  = preg_match( '/\s/', $input );

		return $has_letter && $has_digit && $has_space;
	}

	/**
	 * Determine if the provided value is a valid IBAN.
	 *
	 * Usage: '<index>' => 'iban'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_iban( $field, $input, $param = null ): bool {
		static $character = array(
			'A' => 10,
			'C' => 12,
			'D' => 13,
			'E' => 14,
			'F' => 15,
			'G' => 16,
			'H' => 17,
			'I' => 18,
			'J' => 19,
			'K' => 20,
			'L' => 21,
			'M' => 22,
			'N' => 23,
			'O' => 24,
			'P' => 25,
			'Q' => 26,
			'R' => 27,
			'S' => 28,
			'T' => 29,
			'U' => 30,
			'V' => 31,
			'W' => 32,
			'X' => 33,
			'Y' => 34,
			'Z' => 35,
			'B' => 11,
		);
		if ( ! preg_match( '/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/', $input ) ) {
			return false;
		}
		$iban = str_replace( ' ', '', $input );
		$iban = substr( $iban, 4 ) . substr( $iban, 0, 4 );
		$iban = strtr( $iban, $character );
		if ( bcmod( $iban, 97 ) !== '1' ) {
			return false;
		}
	}

	/**
	 * Determine if the provided input is a valid date (ISO 8601)
	 * or specify a custom format.
	 *
	 * Usage: '<index>' => 'date'
	 *
	 * @param string $field
	 * @param string $input date ('Y-m-d') or datetime ('Y-m-d H:i:s')
	 * @param string $param Custom date format
	 *
	 * @return bool
	 */
	protected function validate_date( $field, $input, $param = null ): bool {
		// Default
		// phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date
		if ( ! $param ) {
			$cdate1 = date( 'Y-m-d', strtotime( $input ) );
			$cdate2 = date( 'Y-m-d H:i:s', strtotime( $input ) );
			if ( $cdate1 !== $input && $cdate2 !== $input ) {
				return false;
			}
		} else {
			$date = \DateTime::createFromFormat( $param, $input[ $field ] );
			if ( false === $date || $input[ $field ] !== date( $param, $date->getTimestamp() ) ) { // phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
				return false;
			}
		}
		// phpcs:enable

		return true;
	}

	/**
	 * Determine if the provided input is a valid time
	 *
	 * Usage: '<index>' => 'time'
	 *
	 * @param string $field
	 * @param string $input time ('HH:mm')
	 *
	 * @return bool
	 */
	protected function validate_time( $field, $input ): bool {
		return preg_match( '/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $input );
	}

	/**
	 * Determine if the provided input meets age requirement (ISO 8601).
	 *
	 * Usage: '<index>' => 'min_age,13'
	 *
	 * @param string $field
	 * @param string $input date ('Y-m-d') or datetime ('Y-m-d H:i:s')
	 * @param string $param int
	 *
	 * @throws \Exception
	 */
	protected function validate_min_age( $field, $input, $param = null ): bool {
		// phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date
		$cdate1   = new DateTime( date( 'Y-m-d', strtotime( $input ) ) );
		$today    = new DateTime( date( 'd-m-Y' ) );
		$interval = $cdate1->diff( $today );
		$age      = $interval->y;
		// phpcs:enable

		return $age >= $param;
	}

	/**
	 * Determine if the provided value starts with param.
	 *
	 * Usage: '<index>' => 'starts,Z'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_starts( $field, $input, $param = null ): bool {
		return strpos( $input, $param ) === 0;
	}

	/**
	 * Checks if a file was uploaded.
	 *
	 * Usage: '<index>' => 'required_file'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_required_file( $field, $input, $param = null ): bool {
		return is_array( $input ) && 4 !== $input['error'];
	}

	/**
	 * Check the uploaded file for extension for now
	 * checks only the ext should add mime type check.
	 *
	 * Usage: '<index>' => 'extension,png;jpg;gif
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_extension( $field, $input, $param = null ): bool {
		if ( is_array( $input ) && 4 !== $input['error'] ) {
			$param              = trim( strtolower( $param ) );
			$allowed_extensions = explode( ';', $param );
			$path_info          = pathinfo( $input['name'] );
			$extension          = isset( $path_info['extension'] ) ? $path_info['extension'] : false;

			if ( $extension && in_array( strtolower( $extension ), $allowed_extensions, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if the provided field value equals current field value.
	 *
	 *
	 * Usage: '<index>' => 'equalsfield,Z'
	 *
	 * @param string $field
	 * @param string $input
	 * @param string $param field to compare with
	 *
	 * @return mixed
	 */
	protected function validate_equalsfield( $field, $input, $param = null ): bool {
		if ( isset( $this->data[ $param ] ) && $input === $this->data[ $param ] ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine if the provided field value is a valid GUID (v4)
	 *
	 * Usage: '<index>' => 'guidv4'
	 *
	 * @param string $field
	 * @param string $input
	 * @param string $param field to compare with
	 *
	 * @return mixed
	 */
	protected function validate_guidv4( $field, $input, $param = null ): bool {
		if ( preg_match( '/\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $input ) ) {
			return true;
		}

		return false;
	}

	protected function add_error( $field, $value, $rule, $param ): void {
		$this->errors[] = array(
			'field' => $field,
			'value' => $value,
			'rule'  => $rule,
			'param' => $param,
		);
	}

	/**
	 * Determine if the provided value is a valid phone number.
	 *
	 * Usage: '<index>' => 'phone_number'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 *
	 * Examples:
	 *
	 *  555-555-5555: valid
	 *  5555425555: valid
	 *  555 555 5555: valid
	 *  1(519) 555-4444: valid
	 *  1 (519) 555-4422: valid
	 *  1-555-555-5555: valid
	 *  1-(555)-555-5555: valid
	 */
	protected function validate_phone_number( $field, $input, $param = null ): bool {
		$regex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';
		if ( ! preg_match( $regex, $input ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Custom regex validator.
	 *
	 * Usage: '<index>' => 'regex,/your-regex-expression/'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_regex( $field, $input, $param = null ): bool {
		$regex = $param;
		if ( ! preg_match( $regex, $input ) ) {
			return false;
		}

		return true;
	}

	/**
	 * JSON validator.
	 *
	 * Usage: '<index>' => 'valid_json'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_json( $field, $input, $param = null ): bool {
		if ( ! is_string( $input ) ) {
			return false;
		}

		try {
			return json_decode( $input, false, 512, JSON_THROW_ON_ERROR ) !== null;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Determine if the input is a valid person name in Persian/Dari or Arabic mainly in Afghanistan and Iran.
	 *
	 * Usage: '<index>' => 'persian_name'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_persian_name( $field, $input, $param = null ): bool {
		if ( ! preg_match( "/^([ا آ أ إ ب پ ت ث ج چ ح خ د ذ ر ز ژ س ش ص ض ط ظ ع غ ف ق ک ك گ ل م ن و ؤ ه ة ی ي ئ ء ّ َ ِ ُ ً ٍ ٌ ْ\x{200B}-\x{200D}])+$/u", $input ) !== false ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the input is a valid person name in English, Persian/Dari/Pashtu or Arabic mainly in Afghanistan and Iran.
	 *
	 * Usage: '<index>' => 'eng_per_pas_name'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_eng_per_pas_name( $field, $input, $param = null ): bool {
		if ( ! preg_match( "/^([A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ'\- ا آ أ إ ب پ ت ټ ث څ ج چ ح ځ خ د ډ ذ ر ړ ز ږ ژ س ش ښ ص ض ط ظ ع غ ف ق ک ګ ك گ ل م ن ڼ و ؤ ه ة ی ي ې ۍ ئ ؋ ء ّ َ ِ ُ ً ٍ ٌ ْ \x{200B}-\x{200D} \s])+$/u", $input ) !== false ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the input is valid digits in Persian/Dari, Pashtu or Arabic format.
	 *
	 * Usage: '<index>' => 'persian_digit'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_persian_digit( $field, $input, $param = null ): bool {
		if ( ! preg_match( '/^([۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩])+$/u', $input ) !== false ) {
			return false;
		}

		return true;
	}


	/**
	 * Determine if the input is a valid text in Persian/Dari or Arabic mainly in Afghanistan and Iran.
	 *
	 * Usage: '<index>' => 'persian_text'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_persian_text( $field, $input, $param = null ): bool {
		if ( ! preg_match( "/^([ا آ أ إ ب پ ت ث ج چ ح خ د ذ ر ز ژ س ش ص ض ط ظ ع غ ف ق ک ك گ ل م ن و ؤ ه ة ی ي ئ ء ّ َ ِ ُ ً ٍ ٌ \. \/ \\ = \- \| \{ \} \[ \] ؛ : « » ؟ > < \+ \( \) \* ، × ٪ ٫ ٬ ! ۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩\x{200B}-\x{200D} \x{FEFF} \x{22} \x{27} \x{60} \x{B4} \x{2018} \x{2019} \x{201C} \x{201D} \s])+$/u", $input ) !== false ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the input is a valid text in Pashtu mainly in Afghanistan.
	 *
	 * Usage: '<index>' => 'pashtu_text'
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return mixed
	 */
	protected function validate_pashtu_text( $field, $input, $param = null ): bool {
		if ( ! preg_match( "/^([ا آ أ ب پ ت ټ ث څ ج چ ح ځ خ د ډ ذ ر ړ ز ږ ژ س ش ښ ص ض ط ظ ع غ ف ق ک ګ ل م ن ڼ و ؤ ه ة ی ې ۍ ي ئ ء ْ ٌ ٍ ً ُ ِ َ ّ ؋ \. \/ \\ = \- \| \{ \} \[ \] ؛ : « » ؟ > < \+ \( \) \* ، × ٪ ٫ ٬ ! ۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩ \x{200B}-\x{200D} \x{FEFF} \x{22} \x{27} \x{60} \x{B4} \x{2018} \x{2019} \x{201C} \x{201D} \s])+$/u", $input ) !== false ) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if the provided value is a valid twitter handle.
	 *
	 * @access protected
	 *
	 * @param string $field
	 * @param mixed $input
	 *
	 * @return bool
	 *
	 * todo add handler for network errors, api error response
	 */
	protected function validate_twitter( $field, $input, $param = null ) {
		$json_twitter = file_get_contents( 'http://twitter.com/users/username_available?username=' . $input ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$twitter_response = json_decode( $json_twitter );
		if ( 'taken' !== $twitter_response->reason ) {
			return false;
		}
	}

	/**
	 * Determine if the provided value is a string.
	 *
	 * @param string $field
	 * @param mixed $input
	 */
	protected function validate_string( $field, $input, $param = null ): bool {
		return is_string( $input );
	}

	/**
	 * Determine if the provided value is an array.
	 *
	 * @param string $field
	 * @param mixed $input
	 */
	protected function validate_array( $field, $input, $param = null ): bool {
		return is_array( $input );
	}

	/**
	 * Always pass
	 *
	 * @param string $field
	 * @param mixed $input
	 */
	protected function validate_nullable( $field, $input, $param = null ): bool {
		return true;
	}

	/**
	 * @param string $field
	 * @param mixed $input
	 * @param $param
	 *
	 * @return bool
	 */
	protected function validate_present( $field, $input, $param = null ): bool {
		return $this->has_data( $field );
	}

	/**
	 * @param $rule
	 *
	 * @return array
	 */
	protected function parse_rule( $rule ): array {
		$rule  = explode( ',', $rule );
		$param = $rule[1] ?? null;

		if ( null !== $param && preg_match( '/(?:(?:^|;)_([a-z_]+))/', $param, $matches ) && isset( $this->data[ $matches[1] ] ) ) {
			$param = str_replace( '_' . $matches[1], $this->data[ $matches[1] ], $param );
		}

		return array( $rule[0], $param );
	}

	protected function get_size( string $field, $value ) {

		$has_numeric = $this->has_rule( $field, $this->numeric_rules );

		if ( $has_numeric && is_numeric( $value ) ) {
			return $value;
		}

		if ( is_array( $value ) ) {
			return count( $value );
		}

		if ( function_exists( 'mb_strlen' ) ) {
			return mb_strlen( $value ?? '' );
		}

		return strlen( $value ?? '' );
	}

	/**
	 * @param string|array $rules
	 */
	protected function has_rule( string $field, $rules ): bool {
		if ( ! array_key_exists( $field, $this->rules ) ) {
			return false;
		}

		$rules = (array) $rules;

		$field_rules = explode( '|', $this->rules[ $field ] );
		foreach ( $field_rules as $rule ) {
			list( $rule ) = $this->parse_rule( $rule );

			if ( in_array( $rule, $rules, true ) ) {
				return true;
			}
		}

		return false;
	}

	protected function get_field_type( $field ): string {
		if ( $this->has_rule( $field, $this->numeric_rules ) ) {
			return 'numeric';
		}

		if ( $this->has_rule( $field, array( 'array' ) ) ) {
			return 'array';
		}

		if ( $this->has_rule( $field, array( 'boolean' ) ) ) {
			return 'boolean';
		}

		return 'string';
	}

	/**
	 * todo: inject translator to constructor and remove WP function dependency
	 */
	public function get_message_array(): array {
		return array(
			'alpha'                => esc_html__( 'The {field} field may only contain letters', 'masterstudy-lms-learning-management-system' ),
			'alpha_dash'           => esc_html__( 'The {field} field may only contain letters and dashes', 'masterstudy-lms-learning-management-system' ),
			'alpha_numeric'        => esc_html__( 'The {field} field may only contain letters and numbers', 'masterstudy-lms-learning-management-system' ),
			'alpha_numeric_space'  => esc_html__( 'The {field} field may only contain letters, numbers and spaces', 'masterstudy-lms-learning-management-system' ),
			'alpha_space'          => esc_html__( 'The {field} field may only contain letters and spaces', 'masterstudy-lms-learning-management-system' ),
			'array'                => esc_html__( 'The {field} field must be an array', 'masterstudy-lms-learning-management-system' ),
			'boolean'              => esc_html__( 'The {field} field has to be either true or false', 'masterstudy-lms-learning-management-system' ),
			'cc'                   => esc_html__( 'The {field} is not a valid credit card number', 'masterstudy-lms-learning-management-system' ),
			'contains'             => esc_html__( 'The {field} can only be one of the following: {param}', 'masterstudy-lms-learning-management-system' ),
			'contains_list'        => esc_html__( 'The {field} is not a valid option', 'masterstudy-lms-learning-management-system' ),
			'date'                 => esc_html__( 'The {field} must be a valid date', 'masterstudy-lms-learning-management-system' ),
			'doesnt_contain_list'  => esc_html__( 'The {field} field contains a value that is not accepted', 'masterstudy-lms-learning-management-system' ),
			'email'                => esc_html__( 'The {field} field must be a valid email address', 'masterstudy-lms-learning-management-system' ),
			'eng_per_pas_name'     => esc_html__( 'The {field} should be a valid English, Persian/Dari/Pashtu or Arabic name', 'masterstudy-lms-learning-management-system' ),
			'equalsfield'          => esc_html__( 'The {field} field does not equal {param} field', 'masterstudy-lms-learning-management-system' ),
			'extension'            => esc_html__( 'The {field} field can only have one of the following extensions: {param}', 'masterstudy-lms-learning-management-system' ),
			'float'                => esc_html__( 'The {field} field must be a number with a decimal point (float)', 'masterstudy-lms-learning-management-system' ),
			'guidv4'               => esc_html__( 'The {field} field needs to contain a valid GUID', 'masterstudy-lms-learning-management-system' ),
			'iban'                 => esc_html__( 'The {field} field needs to contain a valid IBAN', 'masterstudy-lms-learning-management-system' ),
			'integer'              => esc_html__( 'The {field} field must be a number without a decimal', 'masterstudy-lms-learning-management-system' ),
			'invalid'              => esc_html__( 'The {field} field is invalid', 'masterstudy-lms-learning-management-system' ),
			'ip'                   => esc_html__( 'The {field} field needs to be a valid IP address', 'masterstudy-lms-learning-management-system' ),
			'ipv4'                 => esc_html__( 'The {field} field needs to contain a valid IPv4 address', 'masterstudy-lms-learning-management-system' ),
			'ipv6'                 => esc_html__( 'The {field} field needs to contain a valid IPv6 address', 'masterstudy-lms-learning-management-system' ),
			'json'                 => esc_html__( 'The {field} field needs to contain a valid JSON format string', 'masterstudy-lms-learning-management-system' ),
			'max'                  => array(
				'array'   => esc_html__( 'The {field} fields needs to be an array with a size, equal to, or higher than {param}', 'masterstudy-lms-learning-management-system' ),
				'numeric' => esc_html__( 'The {field} field needs to be a numeric value, equal to, or lower than {param}', 'masterstudy-lms-learning-management-system' ),
				'string'  => esc_html__( 'The {field} field needs to be {param} characters or less', 'masterstudy-lms-learning-management-system' ),
			),
			'min'                  => array(
				'array'   => esc_html__( 'The {field} fields needs to be an array with a size, equal to, or lower than {param}', 'masterstudy-lms-learning-management-system' ),
				'numeric' => esc_html__( 'The {field} field needs to be a numeric value, equal to, or higher than {param}', 'masterstudy-lms-learning-management-system' ),
				'string'  => esc_html__( 'The {field} field needs to be at least {param} characters', 'masterstudy-lms-learning-management-system' ),
			),
			'min_age'              => esc_html__( 'The {field} field needs to have an age greater than or equal to {param}', 'masterstudy-lms-learning-management-system' ),
			'name'                 => esc_html__( 'The {field} should be a full name', 'masterstudy-lms-learning-management-system' ),
			'numeric'              => esc_html__( 'The {field} field must be a number', 'masterstudy-lms-learning-management-system' ),
			'pashtu_text'          => esc_html__( 'The {field} should be a valid text in Pashtu format', 'masterstudy-lms-learning-management-system' ),
			'persian_digit'        => esc_html__( 'The {field} should be a valid digit in Persian/Dari or Arabic format', 'masterstudy-lms-learning-management-system' ),
			'persian_name'         => esc_html__( 'The {field} should be a valid Persian/Dari or Arabic name', 'masterstudy-lms-learning-management-system' ),
			'persian_text'         => esc_html__( 'The {field} should be a valid text in Persian/Dari or Arabic format', 'masterstudy-lms-learning-management-system' ),
			'phone_number'         => esc_html__( 'The {field} field needs to be a valid Phone Number', 'masterstudy-lms-learning-management-system' ),
			'present'              => esc_html__( 'The {field} field must present', 'masterstudy-lms-learning-management-system' ),
			'regex'                => esc_html__( 'The {field} field needs to contain a value with valid format', 'masterstudy-lms-learning-management-system' ),
			'required'             => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_file'        => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_if'          => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_if_accepted' => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_if_declined' => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_with'        => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'required_without'     => esc_html__( 'The {field} field is required', 'masterstudy-lms-learning-management-system' ),
			'size'                 => array(
				'array'   => esc_html__( 'The {field} fields needs to be an array with a size equal to {param}', 'masterstudy-lms-learning-management-system' ),
				'numeric' => esc_html__( 'The {field} field needs to be a numeric value, equal to {param}', 'masterstudy-lms-learning-management-system' ),
				'string'  => esc_html__( 'The {field} field needs to be exactly {param} characters', 'masterstudy-lms-learning-management-system' ),
			),
			'starts'               => esc_html__( 'The {field} field needs to start with {param}', 'masterstudy-lms-learning-management-system' ),
			'street_address'       => esc_html__( 'The {field} field needs to be a valid street address', 'masterstudy-lms-learning-management-system' ),
			'string'               => esc_html__( 'The {field} field must be a string', 'masterstudy-lms-learning-management-system' ),
			'time'                 => esc_html__( 'The {field} must be a valid time', 'masterstudy-lms-learning-management-system' ),
			'twitter'              => esc_html__( 'The {field} is not a valid twitter handle', 'masterstudy-lms-learning-management-system' ),
			'url'                  => esc_html__( 'The {field} field has to be a URL', 'masterstudy-lms-learning-management-system' ),
			'url_exists'           => esc_html__( 'The {field} URL does not exist', 'masterstudy-lms-learning-management-system' ),
		);
	}

	private function has_data( string $field ): bool {
		$sub_array = $this->data;

		foreach ( explode( '.', $field ) as $part ) {
			if ( is_array( $sub_array ) && array_key_exists( $part, $sub_array ) ) {
				$sub_array = $sub_array[ $part ];
			} else {
				return false;
			}
		}

		return true;
	}

	private function get_value( string $field ) {
		$value = $this->data;
		foreach ( explode( '.', $field ) as $part ) {
			if ( is_array( $value ) && array_key_exists( $part, $value ) ) {
				$value = $value[ $part ];
			} else {
				return null;
			}
		}

		return $value;
	}

	private function sanitize_value( $field, $value ) {
		if ( null === $value ) {
			return null;
		}

		switch ( $this->get_field_type( $field ) ) {
			case 'array':
			case 'boolean':
				return $value;
			case 'numeric':
				return (float) $value;
			default:
				return wp_kses( $value, stm_lms_allowed_html() );
		}
	}

	private function is_validatable( $rule, $field, $value ) {
		$is_implicit = in_array( $rule, $this->implicit_rules, true );

		if ( $is_implicit ) {
			return true;
		}

		if ( is_string( $value ) && '' === trim( $value ) ) {
			return false;
		}

		$has_data = $this->has_data( $field );
		if ( $has_data && null === $value && $this->has_rule( $field, array( 'nullable' ) ) ) {
			return false;
		}

		return $has_data;
	}
}
