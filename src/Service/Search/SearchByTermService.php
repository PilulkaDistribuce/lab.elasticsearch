<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Service\Search;

use Pilulka\Lab\Elasticsearch\Model\SearchRepository;
use Pilulka\Lab\Elasticsearch\Web\Model\SearchTerm;
use Psr\Http\Message\ServerRequestInterface;

class SearchByTermService
{
    /**
     * @var SearchRepository
     */
    private $repository;
    /**
     * @var ServerRequestInterface
     */
    private $serverRequest;

    /**
     * SearchByTermService constructor.
     */
    public function __construct(SearchRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $term, string $index): iterable
    {
        return $this->repository->search(new SearchTerm($term), $index);
    }

}
