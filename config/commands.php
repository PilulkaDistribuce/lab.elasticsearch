<?php
declare(strict_types=1);

use Pilulka\Lab\Elasticsearch\Console\Command;

return [
    Command\CreateIndexesCommand::class,
    Command\BuildDatafileFromDatabaseCommand::class,
];
