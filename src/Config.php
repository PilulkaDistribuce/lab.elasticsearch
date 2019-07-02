<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch;

class Config
{
    /**
     * @var array
     */
    private $parameters;


    /**
     * Config constructor.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function get(string $key)
    {
        if (!array_key_exists($key, $this->parameters)) {
            throw new \InvalidArgumentException('Invalid config key: ' . $key);
        }
        return $this->parameters[$key];
    }

}