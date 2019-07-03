<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Model;

use Pilulka\Lab\Elasticsearch\Web\Model\SearchTerm;

interface SearchRepository
{

    public function search(SearchTerm $term, string $index): iterable;

}