<?php

/** @var \MasterStudy\Lms\Routing\Router $router */

$router->post(
	'/assignments',
	\MasterStudy\Lms\Pro\addons\assignments\Http\Controllers\CreateController::class,
	\MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger\Create::class,
);

$router->get(
	'/assignments/{assignment_id}',
	\MasterStudy\Lms\Pro\addons\assignments\Http\Controllers\GetController::class,
	\MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger\Get::class,
);

$router->put(
	'/assignments/{assignment_id}',
	\MasterStudy\Lms\Pro\addons\assignments\Http\Controllers\UpdateController::class,
	\MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger\Update::class,
);

$router->delete(
	'/assignments/{assignment_id}',
	\MasterStudy\Lms\Pro\addons\assignments\Http\Controllers\DeleteController::class,
	\MasterStudy\Lms\Pro\addons\assignments\Routing\Swagger\Delete::class,
);
