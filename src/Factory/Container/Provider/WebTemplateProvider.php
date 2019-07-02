<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Factory\Container\Provider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Config;
use Pilulka\Lab\Elasticsearch\Web\Component\ComponentInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class WebTemplateProvider
 * @package Pilulka\Lab\Elasticsearch\Factory\Container\Provider
 * @property Container $container
 */
class WebTemplateProvider extends AbstractServiceProvider
{

    protected $provides = [
        Environment::class,
    ];

    public function register()
    {
        $this->registerTwigEnvironment();
    }

    private function registerTwigEnvironment(): void
    {
        $this->container->share(Environment::class, function () {
            /** @var Config $config */
            $config = $this->container->get(Config::class);
            $loader = new FilesystemLoader(__DIR__ . '/../../../../resources/templates');
            $twig = new Environment(
                $loader,
                [
                    'cache' => __DIR__ . '/../../../../storage/cache/templates',
                    'debug' => $config->get('app.debug'),
                ]
            );
            $this->addComponentFunctionToTwig($twig);
            return $twig;
        });
    }

    private function addComponentFunctionToTwig(Environment &$twig): void
    {
        $twig->addFunction(new TwigFunction(
            'component',
            function (string $name, array $args = []) {
                static $components = null;
                if (!isset($components)) {
                    $components = require __DIR__ . '/../../../../config/components.php';
                }
                if (!array_key_exists($name, $components)) {
                    throw new \InvalidArgumentException("Invalid component name: `{$name}`.");
                }
                $component = $this->container->get($components[$name]);
                if (!$component instanceof ComponentInterface) {
                    throw new \LogicException('Component must be instance of ' . ComponentInterface::class);
                }
                echo $component->render($args);
            },
            [
                'is_safe' => ['html'],
            ]
        ));
    }


}