<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ActionInterface
{

    public function __invoke(ServerRequestInterface $request, array $args = []): ResponseInterface;

}
