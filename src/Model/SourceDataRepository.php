<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Model;

interface SourceDataRepository
{

    public function listAll(): iterable;

    public function listSourceConfigs(): iterable;

}