<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch;

use PHPUnit\Framework\TestCase;
use Pilulka\Lab\Elasticsearch\Persistence\FileSystem\FileSystemSourceDataRepository;

class FileSystemSourceDataRepositoryTest extends TestCase
{

    public function testListAll()
    {
        $repository = $this->repository();
        $i = 0;
        foreach ($repository->listAll() as $item) {
            if($i++ > 10) break;
        }
        $this->assertGreaterThan(0, $i);
    }

    public function testListSourceDataConfig()
    {
        $repository = $this->repository();
        $i = 0;
        foreach ($repository->listSourceConfigs() as $listSourceConfig) {
            $this->assertArrayHasKey('index', $listSourceConfig);
            $this->assertArrayHasKey('mapping', $listSourceConfig);
            $this->assertArrayHasKey('query', $listSourceConfig);
            $i++;
        }
        $this->assertGreaterThan(0, $i);
    }

    public function test__constructWitInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        new FileSystemSourceDataRepository('___', '');
    }

    /**
     * @return FileSystemSourceDataRepository
     */
    private function repository(): FileSystemSourceDataRepository
    {
        $repository = new FileSystemSourceDataRepository(
            __DIR__ . '/../storage/elasticsearch/products.ld-json',
            __DIR__ . '/../resources/elasticsearch'
        );
        return $repository;
    }
}
