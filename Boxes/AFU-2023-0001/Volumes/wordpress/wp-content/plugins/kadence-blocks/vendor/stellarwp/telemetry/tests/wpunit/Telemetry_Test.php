<?php

use Codeception\TestCase\WPTestCase;
use StellarWP\Telemetry\Data_Providers\Debug_Data;
use StellarWP\Telemetry\Opt_In\Status;
use StellarWP\Telemetry\Telemetry\Telemetry;

class Telemetry_Test extends WPTestCase {
	/** @var WpunitTester */
	protected $tester;

	// Tests
	public function test_we_can_instantiate_it() {
		$this->assertInstanceOf( Telemetry::class, new Telemetry( new Debug_Data(), new Status() ) );
	}

	public function test_we_can_register_site() {
		$telemetry = new Telemetry( new Debug_Data(), new Status() );
	}

	public function test_we_can_send_data() {
		$telemetry = new Telemetry( new Debug_Data(), new Status() );
	}
}
