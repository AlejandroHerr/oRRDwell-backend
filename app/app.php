<?php

use Stack\Builder;
use Symfony\Component\HttpFoundation\Request;

$app = require './app/orrdwell.php';

$cors = [
    'allowedHeaders' => ['x-allowed-header', 'x-other-allowed-header'],
    'allowedMethods' => ['GET'],
    'allowedOrigins' => ['*'],
    'exposedHeaders' => false,
    'maxAge' => false,
    'supportsCredentials' => false,
];

$stack = (new Builder())
    ->push('Asm89\Stack\Cors', $cors);

$app = $stack->resolve($app);

$request = Request::createFromGlobals();
$response = $app->handle($request)->send();
$app->terminate($request, $response);
