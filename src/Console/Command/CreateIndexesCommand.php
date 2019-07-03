<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Command;

use Pilulka\Lab\Elasticsearch\Model\ModelStorage;
use Pilulka\Lab\Elasticsearch\Model\SourceDataRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateIndexesCommand extends Command
{

    protected static $defaultName = 'es:create-indexes';
    /**
     * @var ModelStorage
     */
    private $storage;
    /**
     * @var SourceDataRepository
     */
    private $sourceRepository;

    public function __construct(
        ModelStorage $storage,
        SourceDataRepository $sourceRepository
    )
    {
        parent::__construct();
        $this->storage = $storage;
        $this->sourceRepository = $sourceRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->sourceRepository->listSourceConfigs() as $sourceConfig) {
            $output->writeln("Deleting index: {$sourceConfig['index']}.");
            $this->storage->deleteIndex($sourceConfig['index']);
            if(isset($sourceConfig['mapping'])) {
                $output->writeln("Creating mapping: {$sourceConfig['index']}.");
                $this->storage->createMapping($sourceConfig['mapping']);
            }
            $output->writeln("Adding items to index: {$sourceConfig['index']}.");
            $i = 1;
            $docs = [];
            foreach ($this->sourceRepository->listAll() as $modelData) {
                $output->writeln("{$i}. Added document with `id`: {$modelData['id']}.");
                $docs[] = $modelData;
                if($i % 1000 === 0) {
                    $this->storage->storeBulk($sourceConfig['index'], $docs);
                    $docs = [];
                }
                $i++;
            }
            if($docs) {
                $this->storage->storeBulk($sourceConfig['index'], $docs);
            }
        }
    }

}