<?php

use AlejandroHerr\RRD\RrdServiceProvider;
use AlejandroHerr\Silex\SlashActionCallbackResolver;
use AlejandroHerr\oRRDwell\ApiControllerProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app = new Application(['debug' => true]);

$app->register(new RrdServiceProvider());

$app['callback_resolver'] = new SlashActionCallbackResolver($app);
$app->register(new ServiceControllerServiceProvider());

$apiControllerProvider = new ApiControllerProvider();
$app->register($apiControllerProvider);
$app->mount('/', $apiControllerProvider);

$app->view(function ($controllerResult, Request $request) use ($app) {
    if (is_array($controllerResult)) {
        return new JsonResponse($controllerResult);
    }

    return $controllerResult;
});
$app->error(function (HttpException $e) {
    return new JsonResponse([error => $e->getMessage()], $e->getStatusCode());
});

return $app;
