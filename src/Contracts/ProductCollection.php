<?php

namespace Amplify\Widget\Contracts;

use Traversable;

class ProductCollection implements \Countable, \IteratorAggregate
{
    public readonly ?string $seopath;

    public function __construct() {}

    /**
     * @var Product[]
     */
    private $items = [];

    public function setSeoPath(?string $value = null)
    {
        $this->seopath = $value;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        yield from $this->items;
    }
}
