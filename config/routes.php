<?php

use Modules\Auth\Controller\AuthController;

$router->add('POST','/api/auth/login',[AuthController::class,'login']);
$router->add('GET','/api/auth/me',[AuthController::class,'me']);
