<?php

use Framework\Container\Container;

$container = new Container();
$container->set('config', require 'config/parameters.php');
require 'config/dependencies.php';

return $container;