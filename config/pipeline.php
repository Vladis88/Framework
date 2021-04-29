<?php

use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\CredentialsMiddleware;
use Framework\Http\Middleware\ErrorHandler\ErrorHandlerMiddleware;
use App\Http\Middleware\ProfilerMiddleware;
use Framework\Http\Application;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Http\Middleware\RouteMiddleware;

/**
 * @var Application $app
 */
$app->pipe(ErrorHandlerMiddleware::class);
$app->pipe(CredentialsMiddleware::class);
$app->pipe(ProfilerMiddleware::class);
$app->pipe(RouteMiddleware::class);
$app->pipe('cabinet', BasicAuthMiddleware::class);
$app->pipe(DispatchMiddleware::class);