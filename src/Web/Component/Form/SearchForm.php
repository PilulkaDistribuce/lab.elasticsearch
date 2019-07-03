<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Component\Form;

use Pilulka\Lab\Elasticsearch\Model\SourceDataRepository;
use Pilulka\Lab\Elasticsearch\Web\Component\ComponentInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class SearchForm implements ComponentInterface
{

    /** @var Environment */
    private $twig;
    /**
     * @var SourceDataRepository
     */
    private $sourceDataRepository;
    /**
     * @var ServerRequestInterface
     */
    private $serverRequest;

    public function __construct(
        Environment $twig,
        SourceDataRepository $sourceDataRepository,
        ServerRequestInterface $serverRequest
    )
    {
        $this->twig = $twig;
        $this->sourceDataRepository = $sourceDataRepository;
        $this->serverRequest = $serverRequest;
    }

    public function render(array $args = []): string
    {
        return $this->twig->render(
            'components/form.search.twig',
            [
                'indexes' => $this->getIndexes(),
                'actualIndex' => $this->getActualIndex(),
                'actualTerm' => $this->getActualTerm(),
            ]
        );
    }

    private function getActualTerm(): string
    {
        return $this->serverRequest->getQueryParams()['term'] ?? '';
    }

    private function getActualIndex(): string
    {
        return $this->serverRequest->getQueryParams()['index'] ?? '';
    }

    /**
     * @return array
     */
    private function getIndexes(): array
    {
        return array_map(
            function ($config) {
                return $config['index'];
            },
            iterator_to_array($this->sourceDataRepository->listSourceConfigs())
        );
    }

}
