<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;

class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->get(Dispatcher::class);
        $serverRequest = $this->container->get(ServerRequestInterface::class);
        list($handlerClass, $vars) = $dispatcher->dispatch($serverRequest);
        $handler = $this->container->get($handlerClass); // TODO: catch exception
        (new SapiStreamEmitter())
            ->emit($handler($serverRequest, $vars));
    }

}