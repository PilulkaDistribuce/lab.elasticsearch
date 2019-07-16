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
            'bool' => [
                'should' => [
                    [
                        'query_string' => [
                            'fields' => ['name.hunspell'],
                            'query' => searchQueryString(searchTerm()),
                            'analyzer' => 'app_hunspell_unique',
                        ]
                    ],
                    [
                        'match' => [
                            'longText' => [
                                'query' => searchTerm(),
                                'boost' => 0.1,
                            ]
                        ]
                    ],
                    [
                        'match' => [
                            'keywords' => [
                                'query' => searchTerm(),
                                'boost' => 10,
                            ]
                        ]
                    ],
                ],
            ]
        ],
        'sort' => $sort,
    ],
    'size' => 40
];