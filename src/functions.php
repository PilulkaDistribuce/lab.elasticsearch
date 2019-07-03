<?php
declare(strict_types=1);

use Pilulka\Lab\Elasticsearch\Web\Model\SearchTerm;

if(!function_exists('searchTerm')) {
    function searchTerm(): string {
        return (string)(new SearchTerm());
    }
}