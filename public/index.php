<?php

use Framework\Http\Application;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * @var Application $app
 */
require "vendor/autoload.php";

$container = require 'config/container.php';
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

//Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->handle($request);

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
