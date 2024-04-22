<?php

namespace MasterStudy\Lms\Pro\Compatibility;

final class PluginVersion {
	public string $major;

	public string $minor;

	public string $patch;

	public static function from_string( string $version ) {
		$version = explode( '.', trim( $version ) );
		$version = array_slice( $version, 0, 3 );
		$version = array_pad( $version, 3, '0' );
		return new self( $version[0], $version[1], $version[2] );
	}

	public function __construct( string $major, string $minor, string $patch ) {
		$this->major = $major;
		$this->minor = $minor;
		$this->patch = $patch;
	}

	public function __toString(): string {
		return implode( '.', array( $this->major, $this->minor, $this->patch ) );
	}
}
