<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Persistence\FileSystem;

use Pilulka\Lab\Elasticsearch\Model\SourceDataRepository;

class FileSystemSourceDataRepository implements SourceDataRepository
{
    /**
     * @var string
     */
    private $sourceDataFile;
    /**
     * @var string
     */
    private $configDir;

    /**
     * FileSystemSourceDataRepository constructor.
     */
    public function __construct(
        string $sourceDataFile,
        string $configDir
    )
    {
        $this->validateSourceFile($sourceDataFile);
        $this->sourceDataFile = $sourceDataFile;
        $this->configDir = $configDir;
    }

    public function listAll(): iterable
    {
        $f = fopen($this->sourceDataFile, 'r');
        while (!feof($f)) {
            if($str = fgets($f)) {
                $product = json_decode($str,true);
                yield $product['_source'];
            }
        }
        fclose($f);
    }

    public function listSourceConfigs(): iterable
    {
        $directories =  array_diff(scandir($this->configDir), ['..', '.']);
        foreach ($directories as $directory) {
            $mappingPath = $this->configDir . '/' . $directory . '/mapping.json';
            $queryPath = $this->configDir . '/' . $directory . '/query.php';
            yield [
                'index' => $directory,
                'mapping' => file_exists($mappingPath) ? json_decode($mappingPath, true) : null,
                'query' => require $queryPath,
            ];
        }
    }


    /**
     * @param string $sourceFile
     */
    private function validateSourceFile(string $sourceFile): void
    {
        if (!file_exists($sourceFile) || !is_file($sourceFile)) {
            throw new \InvalidArgumentException("Path `{$sourceFile}` is invalid");
        }
    }


}