<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Persistence\Elasticsearch;

use function Couchbase\defaultDecoder;
use Elasticsearch\Client;
use Pilulka\Lab\Elasticsearch\Model\SearchRepository;
use Pilulka\Lab\Elasticsearch\Model\SourceDataRepository;
use Pilulka\Lab\Elasticsearch\Web\Model\SearchTerm;

class ElasticSearchSearchRepository implements SearchRepository
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var SourceDataRepository
     */
    private $sourceDataRepository;

    public function __construct(
        Client $client,
        SourceDataRepository $sourceDataRepository
    )
    {
        $this->client = $client;
        $this->sourceDataRepository = $sourceDataRepository;
    }

    public function search(SearchTerm $term, string $index): iterable
    {
        $queries = [];
        foreach ($this->sourceDataRepository->listSourceConfigs() as $config) {
            $queries[$config['index']] = $config['query'];
        }
        $query = $queries[$index];
        $results = $this->client->search($query);

        return [
            'total' => $results['hits']['total'],
            'items' => array_map(
                function ($doc) {
                    $doc['_source']['eScore'] = $doc['_score'];
                    return $doc['_source'];
                },
                $results['hits']['hits']
            )
        ];
    }


}