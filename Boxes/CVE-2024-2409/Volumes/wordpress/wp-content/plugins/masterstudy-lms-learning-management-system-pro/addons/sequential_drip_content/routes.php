<?php

/** @var \MasterStudy\Lms\Routing\Router $router */

$router->get(
	'/courses/{course_id}/settings/drip-content',
	\MasterStudy\Lms\Pro\addons\sequential_drip_content\Http\Controllers\GetSettingsController::class,
	\MasterStudy\Lms\Pro\addons\sequential_drip_content\Routing\Swagger\GetSettings::class
);

$router->put(
	'/courses/{course_id}/settings/drip-content',
	\MasterStudy\Lms\Pro\addons\sequential_drip_content\Http\Controllers\UpdateSettingsController::class,
	\MasterStudy\Lms\Pro\addons\sequential_drip_content\Routing\Swagger\UpdateSettings::class
);
