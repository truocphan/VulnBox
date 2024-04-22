<?php

use MasterStudy\Lms\Plugin;
use MasterStudy\Lms\Routing\RouteCollection;
use MasterStudy\Lms\Routing\Router;

$router = new Router(
	'masterstudy-lms/v2',
	new RouteCollection()
);
$router->load_routes( __DIR__ . '/routes.php' );

$lms = new Plugin(
	$router
);

$lms->load_file( __DIR__ . '/actions.php' );
$lms->load_file( __DIR__ . '/filters.php' );
$lms->load_file( __DIR__ . '/enqueue.php' );
