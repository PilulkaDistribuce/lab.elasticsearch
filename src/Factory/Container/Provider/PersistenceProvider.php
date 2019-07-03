<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Factory\Container\Provider;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Config;
use Pilulka\Lab\Elasticsearch\Model\ModelStorage;
use Pilulka\Lab\Elasticsearch\Model\SearchRepository;
use Pilulka\Lab\Elasticsearch\Model\SourceDataRepository;
use Pilulka\Lab\Elasticsearch\Persistence\Elasticsearch\ElasticSearchModelStorage;
use Pilulka\Lab\Elasticsearch\Persistence\Elasticsearch\ElasticSearchSearchRepository;
use Pilulka\Lab\Elasticsearch\Persistence\FileSystem\FileSystemSourceDataRepository;

/**
 * Class PersistenceProvider
 * @package Factory\Container\Provider
 * @property Container $container
 */
class PersistenceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Client::class,
        ModelStorage::class,
        SourceDataRepository::class,
        SearchRepository::class,
    ];

    public function register()
    {
        $this->container->share(Client::class, function() {
            /** @var Config $config */
            $config = $this->container->get(Config::class);
            return ClientBuilder::create()
                ->setHosts($config->get('es.hosts'))
                ->build();
        });
        $this->container->share(ModelStorage::class, function() {
            return $this->container->get(ElasticSearchModelStorage::class);
        });
        $this->container->share(SourceDataRepository::class, function() {
            return new FileSystemSourceDataRepository(
                __DIR__ . '/../../../../storage/elasticsearch/products.ld-json',
                __DIR__ . '/../../../../resources/elasticsearch'
            );
        });
        $this->container->share(SearchRepository::class, function() {
            return $this->container->get(ElasticSearchSearchRepository::class);
        });
    }

}