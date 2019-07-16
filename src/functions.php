<?php
declare(strict_types=1);

use Pilulka\Lab\Elasticsearch\Web\Model\SearchTerm;

if (!function_exists('searchTerm')) {
    function searchTerm(): string
    {
        return (string)(new SearchTerm());
    }
}

if (!function_exists('searchQueryString')) {
    function searchQueryString(string $string): string
    {
        $parts = explode(' ', $string);
        $partsCount = count($parts);

        $stopKey = null;
        $search = '';
        for ($i = $partsCount; $i > 0; $i--) {
            $index = $partsCount - $i;
            $value = $parts[$index];
            if($index == 0) {
                $search .= "{$value}^{$i}~2";
                continue;
            }
            if(is_numeric($value) || strlen($value) <= 2) {
                $search .= " AND {$value}";
                continue;
            }
            $search .= " OR {$value}^{$i}~2";
        }
        return $search;
    }
}