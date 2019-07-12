<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Command;

use NotORM;
use Pilulka\Lab\Elasticsearch\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildDatafileFromDatabaseCommand extends Command
{

    public static $defaultName = 'db:build-datafile';
    /** @var NotORM */
    private $notORM;
    /** @var Config */
    private $config;

    public function __construct(
        NotORM $notORM,
        Config $config
    )
    {
        parent::__construct();
        $this->notORM = $notORM;
        $this->config = $config;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->config->get('path.storage') . '/elasticsearch/products.ld-json';
        if (!($file = fopen($path, 'w'))) throw new \LogicException("Path: `{$path}` is not writable.");
        $stub = json_decode('{"_index":"pilulka_cz","_type":"product","_id":"28205","_score":1,"_source": {}}', true);
        $i = 0;
        foreach ($this->getPayloads() as $payload) {
            $stub['_id'] = $payload['id'];
            $stub['_source'] = $payload;
            fputs($file, json_encode($stub) . "\n");
            $output->writeln(sprintf("%d. Added product: %s", $i++, $payload['name']));
        }
        if ($file) fclose($file);
    }

    /**
     * @return mixed
     */
    protected function getProducts()
    {
        $limit = 500;
        $offset = 0;
        do {
            $products = $this->notORM->pr_product(['status' => 'in_edit'])->limit($limit, $offset);
            foreach ($products as $product) yield $product;
            $offset += $limit;
        } while ($products->count());
    }

    /**
     * @param $product
     * @return mixed
     */
    protected function getImage($product)
    {
        if (!$product['image']) return null;
        return parse_url($product['image'], PHP_URL_HOST)
            ? $product['image']
            : 'https://www.pilulka.sk/' . ltrim($product['image'], '/');
    }

    protected function getPayloads(): iterable
    {
        foreach ($this->getProducts() as $product) {
            $payload = [
                'id' => (int)$product['id'],
                'name' => $product['selling_name'] ?: $product['name'],
                'shortText' => strip_tags($product->pr_additional_content['cover'] ?: ''),
                'longText' => strip_tags($product->pr_additional_content['selling'] ?: ''),
                'image' => $this->getImage($product),
                'keywords' => [],
                'score' => (int)$product['category_bestseller_score'],
            ];
            if ($brand = $product->pr_brand['name']) $payload['keywords'][] = $brand;
            if ($productLine = $product->pr_product_line['name']) $payload['keywords'][] = $productLine;
            if ($manufacturer = $product->pr_manufacturer['name']) $payload['keywords'][] = $manufacturer;
            foreach ($product->pr_product_category(['pr_category.is_active' => true]) as $ppc) {
                $payload['keywords'][] = $ppc->pr_category['name'];
            }
            foreach ($product->pr_keyword() as $keyword) {
                $payload['keywords'][] = $keyword['value'];
            }
            yield $payload;
        }
    }

}