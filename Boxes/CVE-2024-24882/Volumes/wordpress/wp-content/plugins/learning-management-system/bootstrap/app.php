<?php
/**
 * Create the application.
 *
 * @since 1.0.0
 */

$masteriyo = new League\Container\Container();

/**
 * Enable the auto wiring.
 */
$masteriyo->delegate(
	new League\Container\ReflectionContainer()
);

$masteriyo_service_providers = require_once dirname( dirname( __FILE__ ) ) . '/config/app.php';

foreach ( $masteriyo_service_providers as $p ) {
	$masteriyo->addServiceProvider( $p );
}

return $masteriyo;
