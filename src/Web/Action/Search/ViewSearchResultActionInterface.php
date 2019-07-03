<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Action\Search;

use Pilulka\Lab\Elasticsearch\Service\Search\SearchByTermService;
use Pilulka\Lab\Elasticsearch\Web\ActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Zend\Diactoros\CallbackStream;
use Zend\Diactoros\Response\HtmlResponse;

class ViewSearchResultActionInterface implements ActionInterface
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var SearchByTermService
     */
    private $searchService;

    public function __construct(
        Environment $twig,
        SearchByTermService $searchService
    )
    {
        $this->twig = $twig;
        $this->searchService = $searchService;
    }

    public function __invoke(ServerRequestInterface $request, array $args = []): ResponseInterface
    {
        return new HtmlResponse(new CallbackStream(function() use ($request) {
            return $this->twig->render(
                'search/search-result.twig',
                [
                    'result' => $this->searchService->execute(
                        $request->getQueryParams()['term'] ?? '',
                            $request->getQueryParams()['index'] ?? ''
                    )
                ]
            );
        }));
    }

}