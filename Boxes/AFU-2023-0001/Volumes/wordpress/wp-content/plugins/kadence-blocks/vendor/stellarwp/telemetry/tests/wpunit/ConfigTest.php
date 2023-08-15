<?php

use Codeception\TestCase\WPTestCase;
use StellarWP\Telemetry\Config;

class ConfigTest extends WPTestCase {

	public function tearDown(): void {
		parent::tearDown();
		Config::reset();
	}

	public function test_it_should_set_prefix() {
		Config::set_hook_prefix( 'some-prefix' );

		$this->assertEquals( 'some-prefix/', Config::get_hook_prefix() );
	}

	public function test_it_should_set_server_url() {
		Config::set_server_url( 'https://www.example.com' );

		$this->assertEquals( 'https://www.example.com', Config::get_server_url() );
	}

	public function test_it_should_throw_exception_without_container() {
		$this->expectException( RuntimeException::class );

		Config::get_container();
	}

	public function test_it_should_set_stellar_slug() {
		Config::set_stellar_slug( 'unique_slug' );

		$this->assertEquals( 'unique_slug', Config::get_stellar_slug() );
	}
}
