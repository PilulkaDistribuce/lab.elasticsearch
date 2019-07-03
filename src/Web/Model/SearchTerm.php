<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Model;

class SearchTerm
{

    private static $term;

    public function __construct(string $term = null)
    {
        if(isset($term)) {
            self::setTerm($term);
        }
    }


    public static function setTerm(string $term)
    {
        self::$term = $term;
    }

    public function __toString()
    {
        return self::$term ?: ($_GET['term'] ?? '');
    }

}