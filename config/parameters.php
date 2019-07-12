<?php
declare(strict_types=1);
return [
    'app.debug' => false,
    'es.hosts' => [
        'elasticsearch:9200',
    ],
    'mysql.dsn' => 'mysql:host=mysql;dbname=ipilulka_sk;charset=utf8',
    'mysql.username' => 'root',
    'mysql.password' => 'root',
    'path.storage' => __DIR__ . '/../storage'
];
