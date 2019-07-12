<?php
declare(strict_types=1);


$sort['_script'] = [
    'type' => 'number',
    'script' => [
        'lang' => 'expression',
        'inline' => "max(1, doc['score'].value) * _score", // * 0.00001
    ],
    'order' => 'desc',
];

$sort['_score'] = ['order' => 'desc'];


return [
    'index' => 'pure-fulltext',
    'type' => 'pure-fulltext',
    'body' => [
        'query' => [
            'multi_match' => [
                'query' => searchTerm(),
                'fields' => [
                    "name.hunspell",
                    "name.shingle",
//                    "name.ngram^0.1",
                    "keywords^2",
                    "keywords.shingle",
                    "longText^0.5",
//                    "shortText^0.5"
                ],
                "type" => "most_fields",
                'fuzziness' => 2
            ]
        ],
        'sort' => $sort,
    ],
    'size' => 40
];