<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Pilulka\Lab\Elasticsearch\Web\Action\Exception\MethodNotAllowedActionInterface;
use Pilulka\Lab\Elasticsearch\Web\Action\Exception\NotFoundActionInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher
{
    /**
     * @var array
     */
    private $routes;
    /**
     * @var \FastRoute\Dispatcher
     */
    private $dispatcher;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->createDispatcher();
    }

    public function dispatch(ServerRequestInterface $request): array
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $routeInfo = $this->dispatcher->dispatch($method, $uri);
        $vars = [];
        $handler = null;
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $handler = NotFoundActionInterface::class;
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $handler = MethodNotAllowedActionInterface::class;
                $vars['allowedMethods'] = $routeInfo[1];
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                break;
        }
        return [$handler, $vars];
    }

    private function createDispatcher(): void
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $this->addRoutes($r);
        });
    }

    private function addRoutes(RouteCollector &$r)
    {
        foreach ($this->routes as list($method, $route, $handler)) {
            $r->addRoute($method, $route, $handler);
        }
    }

}
