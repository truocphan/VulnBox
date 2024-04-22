<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

class CodeGenerator {
	public static function generate(): string {
		return substr( bin2hex( openssl_random_pseudo_bytes( 16 ) ), 0, 6 );
	}
}
