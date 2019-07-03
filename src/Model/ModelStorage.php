<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Model;

interface ModelStorage
{

    public function store(string $index, array $model): void;

    public function storeBulk(string $index, array $modelData): void;

    public function createMapping(array $params): void;

    public function deleteIndex(string $index): void;

}
