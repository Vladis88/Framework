<?php

use Zend\ServiceManager\ServiceManager;

$config = require 'config.php';

$container = new ServiceManager($config['dependencies']);

$container->setService('config', $config);

return $container;