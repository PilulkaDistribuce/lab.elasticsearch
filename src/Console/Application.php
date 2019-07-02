<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

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

    public function run(array $commands = []): void
    {
        $commandLoader = $this->getCommandLoader($commands);
        $application = new \Symfony\Component\Console\Application();
        $application->setCommandLoader($commandLoader);
        $application->run();
    }

    private function getCommandLoader(array $commands): FactoryCommandLoader
    {
        $factories = [];
        foreach ($commands as $commandClass) {
            $factories[$commandClass::getDefaultName()] = function() use ($commandClass) {
                return $this->container->get($commandClass);
            };
        }
        return new FactoryCommandLoader($factories);
    }

}