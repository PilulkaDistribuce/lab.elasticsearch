<?php
declare(strict_types=1);
return [
    'index' =>'current',
    'type' => 'current',
    'body' => [
        'query' => [
            'bool' => array(
                'must' => array(
                    array(
                        'terms' => array(
                            'status' => array(1),
                        ),
                    ),
                    array(
                        'bool' => array(
                            'should' => array(
                                array(
                                    'multi_match' => array(
                                        'query' => searchTerm(),
                                        'fields' => array(
                                            0 => 'attributes.ean^30',
                                            1 => 'sellingName.lowercase^10',
                                            2 => 'sellingName.hunspell^1',
                                            3 => 'name.lowercase^5',
                                            4 => 'name.hunspell^0.5',
                                            5 => 'keywords.lowercase^1',
                                            6 => 'keywords.hunspell^1',
                                            7 => 'content.short^0.1',
                                            8 => 'content.long^0.1',
                                        ),
                                        'type' => 'most_fields',
                                    ),
                                ),
                                array(
                                    'multi_match' => array(
                                        'query' => searchTerm(),
                                        'fields' => array(
                                            0 => 'attributes.ean^30',
                                            1 => 'sellingName.lowercase^10',
                                            2 => 'sellingName.hunspell^1',
                                            3 => 'name.lowercase^5',
                                            4 => 'name.hunspell^0.5',
                                            5 => 'keywords.lowercase^1',
                                            6 => 'keywords.hunspell^1',
                                            7 => 'content.short^0.1',
                                            8 => 'content.long^0.1',
                                        ),
                                        'type' => 'phrase_prefix',
                                        'slop' => 10,
                                        'boost' => 10,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            )
        ]
    ]
];