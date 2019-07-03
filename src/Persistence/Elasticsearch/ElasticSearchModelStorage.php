<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Persistence\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Pilulka\Lab\Elasticsearch\Model\ModelStorage;

class ElasticSearchModelStorage implements ModelStorage
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(string $index, array $data): void
    {
        $params = [
            'index' => $index,
            'type' => $index,
            'body' => $data
        ];
        $this->client->index($params);
    }

    public function storeBulk(string $index, array $modelData): void
    {
        $params = ['body' => []];

        foreach ($modelData as $body) {
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $index,
                    '_id'    => $body['id'],
                ]
            ];
            $params['body'][] = $body;
        }
        $this->client->bulk($params);
    }


    public function createMapping(array $params): void
    {
        $this->client->indices()->create($params);
    }

    public function deleteIndex(string $index): void
    {
        $params = ['index' => $index];
        try {
            $this->client->indices()->delete($params);
        } catch (Missing404Exception $exception) {
            // pass
        }
    }


}