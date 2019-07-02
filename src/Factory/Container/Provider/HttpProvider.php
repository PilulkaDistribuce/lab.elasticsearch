<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Factory\Container\Provider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Web\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class HttpProvider
 * @package Pilulka\Lab\Elasticsearch\Factory\Container\Provider
 * @property Container $container
 */
class HttpProvider extends AbstractServiceProvider
{

    protected $provides = [
        ServerRequestInterface::class,
        Dispatcher::class,
    ];

    public function register()
    {
        $this->registerDispatcher();
        $this->registerServerRequest();
    }

    private function registerDispatcher(): void
    {
        $this->container->share(Dispatcher::class, function () {
            $routes = require __DIR__ . '/../../../../config/routes.php';
            return new Dispatcher($routes);
        });
    }

    private function registerServerRequest(): void
    {
        $this->container->share(ServerRequestInterface::class, function () {
            return ServerRequestFactory::fromGlobals();
        });
    }

}