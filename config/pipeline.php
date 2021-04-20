<?php

use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use App\Http\Middleware\ErrorHandlerMiddleware;
use App\Http\Middleware\ProfilerMiddleware;
use Framework\Http\Application;
use \Framework\Container\Container;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;


/**
 * @var Application $app
 * @var Container $container
 */
$app->pipe($container->get(ErrorHandlerMiddleware::class));
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe('cabinet', $container->get(BasicAuthMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));