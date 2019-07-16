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
        $first = null;
        $or = [];
        for ($i = $partsCount; $i > 0; $i--) {
            $index = $partsCount - $i;
            if ($index == 0) {
                $first = $parts[$index] . "^{$i}~2";
            } else {
                $or[] = $parts[$index] . "^{$i}~2";
            }
        }
        $search = $first;
        if($or) $search .= ' OR (' . implode(' AND ', $or) . ')';
//        var_dump($search);die;
        return $search;
    }
}