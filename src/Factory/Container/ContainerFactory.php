<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Factory\Container;

use Pilulka\Lab\Elasticsearch\Config;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * ContainerFactory constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function create(array $providerClasses): ContainerInterface
    {
        $container = new Container();
        $container->share(ContainerInterface::class, $container);
        $container->delegate(new ReflectionContainer());
        $container->share(Config::class, new Config($this->parameters));
        foreach ($providerClasses as $providerClass) {
            $container->addServiceProvider($container->get($providerClass));
        }
        return $container;
    }

}