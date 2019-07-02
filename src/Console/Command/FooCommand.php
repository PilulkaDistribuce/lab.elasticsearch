<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends Command
{

    protected static $defaultName = 'foo:command';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('You!');
    }

}
