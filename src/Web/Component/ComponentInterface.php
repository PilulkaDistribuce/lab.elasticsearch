<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Component;

interface ComponentInterface
{

    public function render(array $args = []): string;

}