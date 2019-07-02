<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Component\Form;

use Pilulka\Lab\Elasticsearch\Web\Component\ComponentInterface;
use Twig\Environment;

class SearchForm implements ComponentInterface
{

    /** @var Environment */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(array $args = []): string
    {
        return $this->twig->render('components/form.search.twig');
    }

}
