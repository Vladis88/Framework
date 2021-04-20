<?php

use Framework\Http\Application;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * @var \Framework\Container\Container $container
 * @var Application $app
 */

require "vendor/autoload.php";

$container = require 'config/container.php';
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

//Running
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

//Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
