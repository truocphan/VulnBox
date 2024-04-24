<?php
/**
 * Masteriyo REST Exception Class
 *
 * Extends Exception to provide additional data.
 *
 * @package Masteriyo\RestApi
 * @since  1.0.0
 */

namespace Masteriyo\Exceptions;

defined( 'ABSPATH' ) || exit;

use Masteriyo\ModelException;

/**
 * RestException class.
 */
class RestException extends ModelException {}
