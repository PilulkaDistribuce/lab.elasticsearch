<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

use Psr\Http\Message\ServerRequestInterface;

trait ServerRequestTrait
{

    /**
     * @param ServerRequestInterface $request
     * @return mixed|string
     */
    protected function loadRequestInput(ServerRequestInterface $request)
    {
        $input = '';
        $body = $request->getBody();
        while (!$body->eof()) {
            $input .= $body->read(4096);
        }
        $input = json_decode($input, true);
        return $input;
    }

}
