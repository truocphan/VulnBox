<?php
/**
 * VkApiException class file.
 *
 * This source file is subject to the New BSD License
 * that is bundled with this package in the file license.txt.
 * 
 * @author Author of the original: Mordehai German <mordehai.german@gmail.com> 
 *			Modified by: Andrey Geonya <a.geonya@gmail.com>
 * @link https://github.com/AndreyGeonya/vkPhpSdk
 * @copyright Copyright (c) 2010 Mordehai German
 */

/**
 * Thrown when the API returns an error.
 *
 * @author Mordehai German <mordehai.german@gmail.com>
 */

if( !class_exists( 'VkApiException' ) ) { 
	
	class VkApiException extends Exception
	{
		/**
		 * The error information from the API server.
		 *
		 * @var array
		 */
		protected $_error;
	
		/**
		 * Constructor
		 *
		 * @param array $error The error information from the API server.
		 * @return void
		 */
		public function __construct($error)
		{
			$this->_error = $error;
	
			$code = isset($error['error_code']) ? $error['error_code'] : 1;
	
			if (isset($error['error_msg']))
				$msg = $error['error_msg'];
			else
				$msg = 'Unknown error occurred.';
	
			parent::__construct($msg, $code);
		}
	
		/**
		 * Get the error.
		 *
		 * @return array The error information from the API server.
		 */
		public function getError()
		{
			return $this->_error;
		}
	
		/**
		 * Get the type.
		 *
		 * @return string The error type.
		 */
		public function getType()
		{
			if (isset($this->_error['error_type']))
				return $this->_error['error_type'];
			return 'Exception';
		}
	
		/**
		 * Magic method __toString().
		 *
		 * @return string
		 */
		public function __toString()
		{
			$string = $this->getType() . ': ';
			if ($this->code != 0)
				$string .= $this->code . ': ';
			return $string . $this->message;
		}
	}
}