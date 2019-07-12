<?php
declare(strict_types=1);


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
                    "keywords^2",
                ]
            ]
        ],
        'sort' => $sort,
    ],
    'size' => 40
];