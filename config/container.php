<?php

use Framework\Container\Container;

$container = new Container(require 'config/dependencies.php');

$container->set('config', require 'config/parameters.php');

return $container;