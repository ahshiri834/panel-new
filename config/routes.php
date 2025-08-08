<?php

use Modules\Auth\Controller\AuthController;
use Modules\Customer\Controller\CustomerController;

$router->add('POST','/api/auth/login',[AuthController::class,'login']);
$router->add('GET','/api/auth/me',[AuthController::class,'me']);

$router->add('GET',    '/api/customers',               [CustomerController::class,'index']);
$router->add('GET',    '/api/customers/{id:\d+}',      [CustomerController::class,'show']);
$router->add('POST',   '/api/customers',               [CustomerController::class,'store']);
$router->add('PUT',    '/api/customers/{id:\d+}',      [CustomerController::class,'update']);
$router->add('POST',   '/api/customers/transfer',      [CustomerController::class,'transfer']);
