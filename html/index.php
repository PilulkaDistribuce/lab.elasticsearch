<?php

use Pilulka\Lab\Elasticsearch\Factory\Container\ContainerFactory;
use Pilulka\Lab\Elasticsearch\Web\Application;

require __DIR__ . '/../vendor/autoload.php';

$parameters = require __DIR__ . '/../config/parameters.php';
$providerClasses = require __DIR__ . '/../config/providers.php';
$container = (new ContainerFactory($parameters))->create($providerClasses);

(new Application($container))
    ->run();
