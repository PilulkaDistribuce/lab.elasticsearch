<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Action\Exception;

use Pilulka\Lab\Elasticsearch\Web\ActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Zend\Diactoros\CallbackStream;
use Zend\Diactoros\Response\HtmlResponse;

class NotFoundActionInterface implements ActionInterface
{
    /** @var Environment */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, array $args = []): ResponseInterface
    {
        return new HtmlResponse(
            new CallbackStream(function() {
                return $this->twig->render('exception/404.twig');
            }),
            404
        );
    }

}