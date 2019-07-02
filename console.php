<?php
declare(strict_types=1);

use Pilulka\Lab\Elasticsearch\Console\Application;
use Pilulka\Lab\Elasticsearch\Factory\Container\ContainerFactory;

require __DIR__ . '/vendor/autoload.php';

$parameters = require __DIR__ . '/config/parameters.php';
$providerClasses = require __DIR__ . '/config/providers.php';
$commands = require __DIR__ . '/config/commands.php';

$container = (new ContainerFactory($parameters))->create($providerClasses);

(new Application($container))
    ->run($commands);