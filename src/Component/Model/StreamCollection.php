<?php

namespace Ustal\StreamHub\Component\Model;

class StreamCollection implements \IteratorAggregate, \Countable
{
    /** @var Stream[] */
    private array $items = [];

    public function __construct(Stream ...$items)
    {
        $this->items = $items;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
