<?php


/** @var \MasterStudy\Lms\Routing\Router $router */

$router->post(
	'/courses/{course_id}/scorm',
	\MasterStudy\Lms\Pro\addons\scorm\Http\Controllers\UploadController::class,
	\MasterStudy\Lms\Pro\addons\scorm\Routing\Swagger\Upload::class,
);

$router->delete(
	'/courses/{course_id}/scorm',
	\MasterStudy\Lms\Pro\addons\scorm\Http\Controllers\DeleteController::class,
	\MasterStudy\Lms\Pro\addons\scorm\Routing\Swagger\Delete::class,
);
