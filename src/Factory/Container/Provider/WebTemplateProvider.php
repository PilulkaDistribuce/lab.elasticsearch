<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Factory\Container\Provider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Config;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
        $this->container->share(Environment::class, function() {
            /** @var Config $config */
            $config = $this->container->get(Config::class);
            $loader = new FilesystemLoader(__DIR__ . '/../../../../resources/templates');
            return new Environment(
                $loader,
                [
                    'cache' => __DIR__ . '/../../../../storage/cache/templates',
                    'debug' => $config->get('app.debug'),
                ]
            );
        });
    }


}