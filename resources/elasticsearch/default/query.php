<?php
declare(strict_types=1);

return [
    'index' => 'default',
    'type' => 'default',
    'body' => [
        'query' => [
            'bool' => [
                'must' => [
                    ['term' => ['name' => ['value' => searchTerm()]]]
                ]
            ]
        ]
    ]
];