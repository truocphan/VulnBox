<?php

namespace MasterStudy\Lms\Utility;

class Sanitizer {

	public static function html( string $value ) {
		$allowed_html = array(
			'a'          => array(
				'href'  => array(),
				'style' => array(),
			),
			'p'          => array( 'style' => array() ),
			'br'         => array(),
			'span'       => array( 'style' => array() ),
			'strong'     => array( 'style' => array() ),
			'h1'         => array(),
			'h2'         => array(),
			'h3'         => array(),
			'h4'         => array(),
			'h5'         => array(),
			'h6'         => array(),
			'ol'         => array( 'style' => array() ),
			'ul'         => array( 'style' => array() ),
			'li'         => array( 'style' => array() ),
			'blockquote' => array(),
		);

		return wp_kses( $value, $allowed_html );
	}
}
