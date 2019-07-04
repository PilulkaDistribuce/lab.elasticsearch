<?php
declare(strict_types=1);

$sort['_script'] = [
    'type' => 'number',
    'script' => [
        'lang' => 'expression',
        'inline' => "_score * min(1, (pow(doc['categoryBestsellerScore'].value, 2)))", // * 0.00001
    ],
    'order' => 'desc',
];
$sort['_score'] = ['order' => 'desc'];

$fields = [
    'attributes.ean^30',
    'sellingName.lowercase^1000',
    'sellingName.hunspell^1',
    'name.lowercase^5000',
    'name.hunspell^0.5',
    'keywords.lowercase^1',
    'keywords.hunspell^1',
    'content.short^0.1',
    'content.long^0.1',
];

return [
    'index' => 'marek',
    'type' => 'marek',
    'body' => [
        'query' => [
            'bool' => [
                'must' => [
                    [
                        'bool' => [
                            'should' => [
                                [
                                    'multi_match' => [
                                        'query' => searchTerm(),
                                        'fields' => $fields,
                                        'type' => 'most_fields',
                                    ],
                                ],
                                [
                                    'multi_match' => [
                                        'query' => searchTerm(),
                                        'fields' => $fields,
                                        'type' => 'phrase_prefix',
                                        'slop' => 10,
                                        'boost' => 10,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'should' => [
                    [
                        'multi_match' => [
                            'query' => searchTerm(),
                            'fields' => $fields,
                            'type' => 'phrase',
                            'slop' => 10,
                            'boost' => 1000,
                        ],
                    ],
                ],
            ]
        ],
        'sort' => $sort,
    ],
];