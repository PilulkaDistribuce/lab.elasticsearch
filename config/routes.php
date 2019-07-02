<?php
declare(strict_types=1);

use Pilulka\Lab\Elasticsearch\Web;

return [
    ['GET', '/', Web\Action\Search\ViewIndexActionInterface::class],
    ['GET', '/search', Web\Action\Search\ViewSearchResultActionInterface::class],
];
