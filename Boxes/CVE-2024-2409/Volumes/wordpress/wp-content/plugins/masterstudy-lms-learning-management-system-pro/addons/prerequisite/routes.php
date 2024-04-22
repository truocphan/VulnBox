<?php

/** @var \MasterStudy\Lms\Routing\Router $router */
$router->put(
	'/courses/{course_id}/settings/prerequisites',
	\MasterStudy\Lms\Pro\addons\prerequisite\Http\Controllers\UpdateSettingsController::class,
	\MasterStudy\Lms\Pro\addons\prerequisite\Routing\Swagger\UpdateSettings::class
);
