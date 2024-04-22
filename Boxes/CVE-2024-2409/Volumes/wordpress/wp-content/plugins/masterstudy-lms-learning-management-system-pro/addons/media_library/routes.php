<?php

use MasterStudy\Lms\Pro\addons\media_library\Http\Controllers;
use MasterStudy\Lms\Pro\addons\media_library\Routing\Swagger;

/** @var \MasterStudy\Lms\Routing\Router $router */

$router->get( '/media-file-manager', Controllers\GetAllController::class, Swagger\GetAll::class );
$router->post( '/media-file-manager', Controllers\UploadController::class, Swagger\Upload::class );
$router->get( '/media-file-manager/{id}', Controllers\GetByIdController::class, Swagger\GetById::class );
$router->delete( '/media-file-manager/{id}', Controllers\DeleteController::class, Swagger\Delete::class );
