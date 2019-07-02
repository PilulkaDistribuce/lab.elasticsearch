<?php
declare(strict_types=1);

use \Pilulka\Lab\Elasticsearch\Factory\Container\Provider;

return [
    Provider\PersistenceProvider::class,
    Provider\HttpProvider::class,
    Provider\WebTemplateProvider::class,
];